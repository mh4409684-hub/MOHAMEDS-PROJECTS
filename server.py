import http.server
import socketserver
import os
import sys
import json
import urllib.parse
import time
import mimetypes
import math

mimetypes.add_type('application/vnd.android.package-archive', '.apk')

sys.stdout.reconfigure(encoding='utf-8')

def haversine_km(lat1, lon1, lat2, lon2):
    try:
        R = 6371.0
        dlat = math.radians(float(lat2) - float(lat1))
        dlon = math.radians(float(lon2) - float(lon1))
        a = math.sin(dlat / 2.0)**2 + math.cos(math.radians(float(lat1))) * math.cos(math.radians(float(lat2))) * math.sin(dlon / 2.0)**2
        c = 2.0 * math.atan2(math.sqrt(a), math.sqrt(1.0 - a))
        return R * c
    except Exception:
        return 9999.0

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

            # 2. Driver polling for pending ride with SMART PROXIMITY DISPATCH (BOLT ALGORITHM)
            elif path == '/api/driver/pending-ride':
                driver_id = query.get('driverId', [''])[0]
                driver = next((d for d in db['drivers'] if d['id'] == driver_id), None)
                found_ride = None

                if driver and driver.get('isOnline') and driver.get('status') == 'approved':
                    d_vehicle = driver.get('vehicle', 'bajaj')
                    d_lat = float(driver.get('lat', -6.8228))
                    d_lng = float(driver.get('lng', 39.2785))

                    for ride_id, r in db['activeRides'].items():
                        if r.get('status') != 'searching':
                            continue

                        # If this driver already declined this ride, do not ask again
                        if driver_id in r.get('rejectedDrivers', []):
                            continue

                        # Vehicle tier check (boda, bajaj, gari)
                        if r.get('tier') != d_vehicle and d_vehicle != 'all' and r.get('tier') != 'all':
                            continue

                        # Pickup coordinates
                        p_coords = r.get('pickupCoords', [-6.8228, 39.2785])
                        p_lat, p_lng = float(p_coords[0]), float(p_coords[1])
                        d_dist = haversine_km(d_lat, d_lng, p_lat, p_lng)

                        # Rank all eligible online approved drivers by real distance to pickup
                        candidate_drivers = []
                        for other_d in db['drivers']:
                            if other_d.get('isOnline') and other_d.get('status') == 'approved':
                                if other_d['id'] in r.get('rejectedDrivers', []):
                                    continue
                                if other_d.get('vehicle') == r.get('tier') or other_d.get('vehicle') == 'all' or r.get('tier') == 'all':
                                    c_lat = float(other_d.get('lat', -6.8228))
                                    c_lng = float(other_d.get('lng', 39.2785))
                                    dist = haversine_km(c_lat, c_lng, p_lat, p_lng)
                                    candidate_drivers.append((dist, other_d['id']))

                        candidate_drivers.sort(key=lambda x: x[0])
                        elapsed = time.time() - r.get('createdAt', time.time())

                        # Proximity cascading dispatch:
                        # Stage 1 (0 to 6s): Only #1 closest driver (within 10km)
                        # Stage 2 (6 to 15s): Top 3 closest drivers (within 15km)
                        # Stage 3 (> 15s): Any matching driver within 25km
                        should_dispatch = False
                        if elapsed < 6.0:
                            if candidate_drivers and candidate_drivers[0][1] == driver_id and d_dist <= 10.0:
                                should_dispatch = True
                        elif elapsed < 15.0:
                            top_ids = [c[1] for c in candidate_drivers[:3]]
                            if driver_id in top_ids and d_dist <= 15.0:
                                should_dispatch = True
                        else:
                            if d_dist <= 25.0:
                                should_dispatch = True

                        if should_dispatch:
                            found_ride = dict(r)
                            found_ride['distanceToPickup'] = round(d_dist, 1)
                            found_ride['etaMinutes'] = max(1, round(d_dist * 2.2))
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

            # 1. Driver Registration with full details and real GPS coordinates
            if path == '/api/driver/register':
                driver_id = f"DRV-{int(time.time() * 1000) % 10000}"
                name = (body.get("name") or "Dereva Mpya").strip()
                phone = (body.get("phone") or "07XXXXXXXX").strip()
                plate = (body.get("plate") or "T 100 AAA").strip().upper()
                vehicle = body.get("vehicle", "bajaj")
                vehicle_model = (body.get("vehicleModel") or "TVS King").strip()
                license_num = (body.get("licenseNumber") or f"DL-{int(time.time() % 1000000)}").strip()
                city = (body.get("city") or "Dar es Salaam").strip()
                lat = float(body.get("lat", -6.8228))
                lng = float(body.get("lng", 39.2785))

                new_driver = {
                    "id": driver_id,
                    "name": name,
                    "phone": phone,
                    "vehicle": vehicle,
                    "vehicleModel": vehicle_model,
                    "plate": plate,
                    "licenseNumber": license_num,
                    "city": city,
                    "rating": 5.0,
                    "tripsCount": 0,
                    "status": "approved", # Immediately active to take nearby rides
                    "isOnline": True,
                    "lat": lat,
                    "lng": lng,
                    "walletBalance": 10000,
                    "registeredAt": time.strftime("%Y-%m-%d %H:%M:%S")
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
                        if 'lat' in body: d['lat'] = float(body['lat'])
                        if 'lng' in body: d['lng'] = float(body['lng'])
                        break
                save_db(db)
                self.wfile.write(json.dumps({"success": True, "isOnline": is_online}).encode('utf-8'))
                return

            # 3. Rider requests a ride
            elif path == '/api/ride/request':
                ride_id = f"RIDE-{int(time.time() * 1000) % 1000000}"
                new_ride = {
                    "rideId": ride_id,
                    "riderName": body.get("riderName", "Mteja"),
                    "pickup": body.get("pickup", "Kariakoo"),
                    "dropoff": body.get("dropoff", "Mlimani City"),
                    "pickupCoords": body.get("pickupCoords", [-6.8228, 39.2785]),
                    "dropCoords": body.get("dropCoords", [-6.7725, 39.2205]),
                    "distanceKm": body.get("distanceKm", 5.2),
                    "tier": body.get("tier", "bajaj"),
                    "fare": body.get("fare", 5000),
                    "status": "searching",
                    "driver": None,
                    "rejectedDrivers": [],
                    "createdAt": time.time()
                }
                db['activeRides'][ride_id] = new_ride
                save_db(db)
                self.wfile.write(json.dumps({"success": True, "ride": new_ride}).encode('utf-8'))
                return

            # 3b. Driver declines ride (Cascade to next closest driver)
            elif path == '/api/ride/reject':
                ride_id = body.get("rideId")
                driver_id = body.get("driverId")
                ride = db['activeRides'].get(ride_id)
                if ride and driver_id:
                    if 'rejectedDrivers' not in ride:
                        ride['rejectedDrivers'] = []
                    if driver_id not in ride['rejectedDrivers']:
                        ride['rejectedDrivers'].append(driver_id)
                    save_db(db)
                    self.wfile.write(json.dumps({"success": True, "message": "Ride rejected, cascaded to next driver"}).encode('utf-8'))
                    return
                self.wfile.write(json.dumps({"success": False, "error": "Ride or Driver missing"}).encode('utf-8'))
                return

            # 3c. Admin toggles driver status (Approve / Suspend)
            elif path == '/api/driver/toggle-status':
                driver_id = body.get("driverId")
                new_status = body.get("status", "approved")
                found_d = None
                for d in db['drivers']:
                    if d['id'] == driver_id:
                        d['status'] = new_status
                        found_d = d
                        break
                if found_d:
                    save_db(db)
                    self.wfile.write(json.dumps({"success": True, "driver": found_d}).encode('utf-8'))
                    return
                self.wfile.write(json.dumps({"success": False, "error": "Driver not found"}).encode('utf-8'))
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
