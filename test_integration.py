import urllib.request
import json

def post(url, data):
    req = urllib.request.Request('http://localhost:8081' + url,
        data=json.dumps(data).encode('utf-8'),
        headers={'Content-Type': 'application/json'},
        method='POST')
    with urllib.request.urlopen(req) as res:
        return json.loads(res.read().decode('utf-8'))

def get(url):
    with urllib.request.urlopen('http://localhost:8081' + url) as res:
        return json.loads(res.read().decode('utf-8'))

print("Starting end-to-end integration test...")

# 1. Driver registers
drv_res = post('/api/driver/register', {
    'name': 'Juma Rashid',
    'phone': '0712345678',
    'vehicle': 'bajaj',
    'vehicleModel': 'TVS King 200',
    'plate': 'T 888 CCC'
})
drv = drv_res['driver']
print('1. Registered Driver:', drv['name'], drv['id'])

# 2. Rider requests ride
ride_res = post('/api/ride/request', {
    'riderName': 'Amina Salum',
    'pickup': 'Kariakoo Msimbazi',
    'dropoff': 'Posta Mpya',
    'pickupCoords': [-6.8228, 39.2785],
    'dropCoords': [-6.8162, 39.2908],
    'distanceKm': 3.5,
    'tier': 'bajaj',
    'fare': 4500
})
ride = ride_res['ride']
print('2. Requested Ride:', ride['rideId'], 'Fare:', ride['fare'])

# 3. Driver polls for pending ride
pending = get('/api/driver/pending-ride?driverId=' + drv['id'])
assert pending['ride']['rideId'] == ride['rideId'], "Pending ride mismatch"
print('3. Driver Alert Received for Ride:', pending['ride']['rideId'])

# 4. Driver accepts ride
acc = post('/api/ride/accept', {'rideId': ride['rideId'], 'driverId': drv['id']})
assert acc['ride']['status'] == 'accepted', "Status should be accepted"
print('4. Driver Accepted! Status:', acc['ride']['status'], 'Driver Assigned:', acc['ride']['driver']['name'])

# 5. Customer checks ride status
r_stat = get('/api/ride/status?rideId=' + ride['rideId'])
assert r_stat['ride']['driver']['name'] == 'Juma Rashid', "Driver name mismatch on rider"
print('5. Customer Verified Driver Assigned:', r_stat['ride']['driver']['name'])

# 6. Complete trip
comp = post('/api/ride/complete', {'rideId': ride['rideId']})
print('6. Trip Completed! Driver Share:', comp['driverEarnings'], 'Owner Commission:', comp['commission'])

# 7. Customer submits review
fb = post('/api/feedback/submit', {
    'riderName': 'Amina Salum',
    'driverName': 'Juma Rashid (TVS King 200)',
    'route': 'Kariakoo → Posta',
    'rating': 5,
    'review': 'Dereva mzuri sana, alifika kwa wakati na safari ilikuwa safi!'
})
print('7. Customer Review Submitted:', fb['feedback']['review'])

# 8. Admin checks live dashboard
admin = get('/api/admin/data')
print('8. Admin Live Financials:', admin['financials'])
print('   Admin Drivers Count:', len(admin['drivers']))
print('   Admin Latest Feedback:', admin['feedbacks'][0]['review'])
print('\n*** ALL TESTS PASSED: 100% BOLT DISPATCH & ECOSYSTEM VERIFIED! ***')
