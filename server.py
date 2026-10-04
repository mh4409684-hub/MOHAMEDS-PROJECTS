import http.server
import socketserver
import os
import sys
import json
import urllib.parse
import time
import mimetypes

mimetypes.add_type('application/vnd.android.package-archive', '.apk')

sys.stdout.reconfigure(encoding='utf-8')

PORT = int(os.environ.get('PORT', 8081))
DIRECTORY = os.path.dirname(os.path.abspath(__file__))
DB_FILE = os.path.join(DIRECTORY, 'database.json')

# Initial Database Schema
DEFAULT_DB = {
    "config": {
        "appName": "SAFARIS",
        "rates": { "boda": 500, "bajaj": 750, "gari": 1100 },
        "commissionPercent": 15
    },
    "drivers": [
        {
            "id": "DRV-101",
            "name": "Hamisi Mwamba",
            "phone": "0754112233",
            "vehicle": "bajaj",
            "vehicleModel": "TVS King",
            "plate": "T 482 DXB",
            "rating": 4.9,
            "tripsCount": 1420,
            "status": "approved",
            "isOnline": True,
            "lat": -6.8228,
            "lng": 39.2785,
            "walletBalance": 124000
        },
        {
            "id": "DRV-102",
            "name": "Baraka John",
            "phone": "0714998877",
            "vehicle": "gari",
            "vehicleModel": "Toyota IST",
            "plate": "T 192 CSP",
            "rating": 4.8,
            "tripsCount": 890,
            "status": "approved",
            "isOnline": True,
            "lat": -6.8162,
            "lng": 39.2908,
            "walletBalance": 86000
        }
    ],
    "activeRides": {},
    "feedbacks": [
        {
            "id": 1,
            "riderName": "Halima Bakari",
            "driverName": "Hamisi Mwamba (Bajaj)",
            "route": "Kariakoo → Posta",
            "rating": 5,
            "review": "Dereva mzuri sana, alifika kwa wakati na bajaj yake ni safi kabisa!",
            "date": "Leo, 13:45"
        },
        {
            "id": 2,
            "riderName": "Peter Mwamba",
            "driverName": "Baraka John (Gari)",
            "route": "Mwenge → Sinza",
            "rating": 5,
            "review": "AC ilikuwa inafanya kazi vizuri na alitumia njia isiyo na foleni.",
            "date": "Leo, 11:20"
        }
    ],
    "financials": {
        "totalGrossFares": 1840000,
        "totalCommission": 276000
    }
}

def load_db():
    if not os.path.exists(DB_FILE):
        save_db(DEFAULT_DB)
        return DEFAULT_DB
    try:
        with open(DB_FILE, 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception:
        return DEFAULT_DB

def save_db(data):
    with open(DB_FILE, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

class SafarisHandler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=DIRECTORY, **kwargs)

    def end_headers(self):
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type')
        self.send_header('Cache-Control', 'no-cache, must-revalidate')
        super().end_headers()

    def do_OPTIONS(self):
        self.send_response(200)
        self.end_headers()

    def do_GET(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path
        query = urllib.parse.parse_qs(parsed.query)

        if path.startswith('/api/'):
            db = load_db()
            self.send_response(200)
            self.send_header('Content-Type', 'application/json')
            self.end_headers()

            # 1. Nearby Online Drivers
            if path == '/api/drivers/nearby':
                online = [d for d in db['drivers'] if d.get('isOnline') and d.get('status') == 'approved']
                self.wfile.write(json.dumps({"success": True, "drivers": online}).encode('utf-8'))
                return

            # 2. Driver polling for pending ride in their tier
            elif path == '/api/driver/pending-ride':
                driver_id = query.get('driverId', [''])[0]
                driver = next((d for d in db['drivers'] if d['id'] == driver_id), None)
                found_ride = None
                if driver and driver.get('isOnline') and driver.get('status') == 'approved':
                    for ride_id, r in db['activeRides'].items():
                        if r['status'] == 'searching':
                            if r['tier'] == driver.get('vehicle') or driver.get('vehicle') == 'all' or not driver.get('vehicle'):
                                found_ride = r
                                break
                self.wfile.write(json.dumps({"success": True, "ride": found_ride}).encode('utf-8'))
                return

            # 3. Rider polling for ride status
            elif path == '/api/ride/status':
                ride_id = query.get('rideId', [''])[0]
                ride = db['activeRides'].get(ride_id)
                self.wfile.write(json.dumps({"success": True, "ride": ride}).encode('utf-8'))
                return

            # 4. Admin Overview Data
            elif path == '/api/admin/data':
                self.wfile.write(json.dumps({
                    "success": True,
                    "config": db['config'],
                    "drivers": db['drivers'],
                    "feedbacks": db['feedbacks'],
                    "financials": db['financials'],
                    "activeRidesCount": len(db['activeRides'])
                }).encode('utf-8'))
                return

            self.wfile.write(json.dumps({"success": False, "error": "Unknown GET endpoint"}).encode('utf-8'))
            return

        # Serve static HTML/JS/CSS
        super().do_GET()

    def do_POST(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path

        if path.startswith('/api/'):
            content_length = int(self.headers.get('Content-Length', 0))
            body_bytes = self.rfile.read(content_length)
            body = {}
            if body_bytes:
                try:
                    body = json.loads(body_bytes.decode('utf-8'))
                except Exception:
                    body = {}

            db = load_db()
            self.send_response(200)
            self.send_header('Content-Type', 'application/json')
            self.end_headers()

            # 1. Driver Registration
            if path == '/api/driver/register':
                driver_id = f"DRV-{int(time.time() % 10000)}"
                new_driver = {
                    "id": driver_id,
                    "name": body.get("name", "Dereva Mpya"),
                    "phone": body.get("phone", "07XXXXXXXX"),
                    "vehicle": body.get("vehicle", "bajaj"),
                    "vehicleModel": body.get("vehicleModel", "TVS King"),
                    "plate": body.get("plate", "T 100 AAA"),
                    "rating": 5.0,
                    "tripsCount": 0,
                    "status": "approved", # Auto-approve for demo, admin can suspend
                    "isOnline": True,
                    "lat": -6.8228,
                    "lng": 39.2785,
                    "walletBalance": 10000
                }
                db['drivers'].append(new_driver)
                save_db(db)
                self.wfile.write(json.dumps({"success": True, "driver": new_driver}).encode('utf-8'))
                return

            # 2. Driver Online/Offline & GPS Position
            elif path == '/api/driver/status':
                driver_id = body.get("driverId")
                is_online = body.get("isOnline", True)
                for d in db['drivers']:
                    if d['id'] == driver_id:
                        d['isOnline'] = is_online
                        if 'lat' in body: d['lat'] = body['lat']
                        if 'lng' in body: d['lng'] = body['lng']
                        break
                save_db(db)
                self.wfile.write(json.dumps({"success": True, "isOnline": is_online}).encode('utf-8'))
                return

            # 3. Rider requests a ride
            elif path == '/api/ride/request':
                ride_id = f"RIDE-{int(time.time() * 1000) % 1000000}"
                new_ride = {
                    "rideId": ride_id,
                    "riderName": body.get("riderName", "Mohamed Mteja"),
                    "pickup": body.get("pickup", "Kariakoo"),
                    "dropoff": body.get("dropoff", "Mlimani City"),
                    "pickupCoords": body.get("pickupCoords", [-6.8228, 39.2785]),
                    "dropCoords": body.get("dropCoords", [-6.7725, 39.2205]),
                    "distanceKm": body.get("distanceKm", 5.2),
                    "tier": body.get("tier", "bajaj"),
                    "fare": body.get("fare", 5000),
                    "status": "searching",
                    "driver": None,
                    "createdAt": time.time()
                }
                db['activeRides'][ride_id] = new_ride
                save_db(db)
                self.wfile.write(json.dumps({"success": True, "ride": new_ride}).encode('utf-8'))
                return

            # 4. Driver accepts a ride
            elif path == '/api/ride/accept':
                ride_id = body.get("rideId")
                driver_id = body.get("driverId")
                driver = next((d for d in db['drivers'] if d['id'] == driver_id), None)
                ride = db['activeRides'].get(ride_id)

                if ride and driver:
                    ride['status'] = 'accepted'
                    ride['driver'] = {
                        "id": driver['id'],
                        "name": driver['name'],
                        "phone": driver['phone'],
                        "plate": driver['plate'],
                        "vehicleModel": driver['vehicleModel'],
                        "rating": driver['rating'],
                        "lat": driver['lat'],
                        "lng": driver['lng']
                    }
                    save_db(db)
                    self.wfile.write(json.dumps({"success": True, "ride": ride}).encode('utf-8'))
                    return
                self.wfile.write(json.dumps({"success": False, "error": "Ride or Driver not found"}).encode('utf-8'))
                return

            # 4b. Driver updates trip step ('arrived', 'on_trip', 'completed')
            elif path == '/api/ride/update-status':
                ride_id = body.get("rideId")
                new_status = body.get("status") # 'arrived', 'on_trip', etc.
                ride = db['activeRides'].get(ride_id)
                if ride and new_status:
                    ride['status'] = new_status
                    save_db(db)
                    self.wfile.write(json.dumps({"success": True, "ride": ride}).encode('utf-8'))
                    return
                self.wfile.write(json.dumps({"success": False, "error": "Invalid rideId or status"}).encode('utf-8'))
                return

            # 5. Complete trip & calculate commission
            elif path == '/api/ride/complete':
                ride_id = body.get("rideId")
                ride = db['activeRides'].get(ride_id)
                if ride:
                    ride['status'] = 'completed'
                    fare = ride['fare']
                    comm = round(fare * (db['config']['commissionPercent'] / 100))
                    driver_earn = fare - comm

                    # Update driver wallet
                    if ride.get('driver'):
                        d_id = ride['driver']['id']
                        for d in db['drivers']:
                            if d['id'] == d_id:
                                d['walletBalance'] += driver_earn
                                d['tripsCount'] += 1
                                break

                    db['financials']['totalGrossFares'] += fare
                    db['financials']['totalCommission'] += comm
                    save_db(db)
                    self.wfile.write(json.dumps({"success": True, "fare": fare, "commission": comm, "driverEarnings": driver_earn}).encode('utf-8'))
                    return
                self.wfile.write(json.dumps({"success": False, "error": "Ride not found"}).encode('utf-8'))
                return

            # 6. Customer submits feedback about driver
            elif path == '/api/feedback/submit':
                fb_id = int(time.time() * 1000) % 100000
                new_fb = {
                    "id": fb_id,
                    "riderName": body.get("riderName", "Mteja"),
                    "driverName": body.get("driverName", "Hamisi Mwamba"),
                    "route": body.get("route", "Kariakoo → Posta"),
                    "rating": body.get("rating", 5),
                    "review": body.get("review", "Safari safi kabisa"),
                    "date": "Sasa hivi"
                }
                db['feedbacks'].insert(0, new_fb)
                save_db(db)
                self.wfile.write(json.dumps({"success": True, "feedback": new_fb}).encode('utf-8'))
                return

            # 7. Admin driver action (Approve / Suspend)
            elif path == '/api/admin/driver-action':
                driver_id = body.get("driverId")
                action = body.get("action") # 'approve' or 'suspend'
                for d in db['drivers']:
                    if d['id'] == driver_id:
                        d['status'] = 'approved' if action == 'approve' else 'suspended'
                        if action == 'suspend': d['isOnline'] = False
                        break
                save_db(db)
                self.wfile.write(json.dumps({"success": True}).encode('utf-8'))
                return

            # 8. Admin rebrand & rate changes
            elif path == '/api/admin/rebrand':
                if 'appName' in body: db['config']['appName'] = body['appName']
                if 'rates' in body: db['config']['rates'] = body['rates']
                if 'commissionPercent' in body: db['config']['commissionPercent'] = body['commissionPercent']
                save_db(db)
                self.wfile.write(json.dumps({"success": True, "config": db['config']}).encode('utf-8'))
                return

            self.wfile.write(json.dumps({"success": False, "error": "Unknown POST endpoint"}).encode('utf-8'))
            return

        super().do_POST()

if __name__ == '__main__':
    os.chdir(DIRECTORY)
    load_db()
    with socketserver.TCPServer(("", PORT), SafarisHandler) as httpd:
        print("=" * 65)
        print("[LIVE ONLINE] SAFARIS DISPATCH ENGINE & CLOUD REST API IS RUNNING!")
        print("=" * 65)
        print(f"👉 Mteja (Safaris Ride):     http://192.168.1.122:{PORT}/rider.html")
        print(f"👉 Dereva (Safaris Drivers): http://192.168.1.122:{PORT}/driver.html")
        print(f"👉 Mmiliki (MohamedTech):    http://192.168.1.122:{PORT}/admin.html")
        print("=" * 65)
        try:
            httpd.serve_forever()
        except KeyboardInterrupt:
            httpd.server_close()
