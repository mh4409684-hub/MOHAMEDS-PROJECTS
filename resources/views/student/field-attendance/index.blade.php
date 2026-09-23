<x-layouts.cbe title="GPS Field Attendance - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                        <i class="fa-solid fa-satellite text-[10px]"></i> GPS Geofence Anti-Cheat Protection
                    </span>
                    <span class="text-xs text-slate-500">College of Business Education</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 mt-1">Field Placement Attendance</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Taasisi Rasmi ya Field: <strong class="text-slate-800">{{ $placement->hostOrganization->name }}</strong> ({{ $placement->hostOrganization->city }})
                </p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('student.field-placement.apply') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-map-location-dot text-blue-600"></i> Badili GPS ya Taasisi
                </a>
                <a href="{{ route('student.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Dashboard
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-xs text-rose-800 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base mt-0.5 shrink-0"></i>
                <div>
                    <h4 class="font-bold text-rose-900 mb-1">Hitilafu ya Uthibitisho wa Mahudhurio:</h4>
                    <p class="leading-relaxed">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-xs text-emerald-800 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5 shrink-0"></i>
                <div>
                    <h4 class="font-bold text-emerald-900 mb-0.5">Mahudhurio Yamethibitishwa:</h4>
                    <p class="leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-xs text-blue-800 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-circle-info text-blue-600 text-base mt-0.5 shrink-0"></i>
                <div>
                    <p class="leading-relaxed font-semibold">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Today's Status Banner -->
        @if($todayAttendance)
            <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-700 rounded-3xl p-6 sm:p-7 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="h-14 w-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl font-bold shrink-0 shadow-inner">
                        <i class="fa-solid fa-circle-check text-emerald-300"></i>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-200 bg-white/10 px-2.5 py-0.5 rounded-full inline-block mb-1">
                            Uhakiki wa GPS Umekamilika
                        </span>
                        <h2 class="text-xl font-black">Mahudhurio Yamesharekodiwa Leo!</h2>
                        <p class="text-xs text-emerald-100 mt-1">
                            Muda wa Kuingia: <strong class="text-white">{{ $todayAttendance->check_in_time }}</strong> &bull; Hali: 
                            <span class="uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-white text-emerald-800 text-[10px]">{{ $todayAttendance->status }}</span>
                        </p>
                        @if($todayAttendance->latitude && $todayAttendance->longitude)
                            <p class="text-[11px] text-emerald-200 mt-1 font-mono">
                                <i class="fa-solid fa-location-dot"></i> GPS: {{ round($todayAttendance->latitude, 5) }}, {{ round($todayAttendance->longitude, 5) }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="shrink-0">
                    <span class="px-4 py-2 rounded-2xl bg-white text-emerald-900 font-black text-xs uppercase tracking-wider shadow-lg flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-check text-emerald-600"></i>
                        <span>100% Verified</span>
                    </span>
                </div>
            </div>
        @else
            <!-- GPS Check-In Interactive Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md p-6 sm:p-8 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-satellite-dish text-emerald-600"></i>
                            Piga Mahudhurio ya Leo (Live GPS Geofence)
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Setilaiti itathibitisha uwepo wako katika taasisi ya <strong class="text-slate-700">{{ $placement->hostOrganization->name }}</strong> ndani ya mita {{ $placement->hostOrganization->geofence_radius_meters ?? 200 }}.
                        </p>
                    </div>
                    <div class="text-left sm:text-right shrink-0">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Upeo wa Eneo:</span>
                        <span class="text-xs font-black text-blue-700 font-mono">Ndani ya mita {{ $placement->hostOrganization->geofence_radius_meters ?? 200 }}</span>
                    </div>
                </div>

                <!-- Live Geofence Map -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                    <div id="attendance-verification-map" class="w-full h-64 sm:h-80 bg-slate-100 z-10"></div>
                    <div id="map-overlay-badge" class="absolute top-2 left-2 z-20 bg-slate-900/90 backdrop-blur-md text-white text-[11px] font-semibold px-3 py-1.5 rounded-xl border border-slate-700 flex items-center gap-2 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
                        <span id="map-status-text">Inatafuta eneo lako la sasa...</span>
                    </div>
                </div>

                <!-- Real-time Verification Status Box -->
                <div id="verification-status-banner" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs transition duration-300">
                    <div class="flex items-center justify-between">
                        <span class="font-bold flex items-center gap-2" id="status-title">
                            <i id="gps-status-icon" class="fa-solid fa-spinner fa-spin text-blue-600"></i>
                            <span id="gps-status-label">Inapima umbali wako kutoka ofisini...</span>
                        </span>
                        <span id="live-distance-badge" class="font-mono font-bold text-xs px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-700">
                            Umbali: --
                        </span>
                    </div>
                    <div id="gps-details-row" class="hidden mt-2 pt-2 border-t border-slate-200/80 flex flex-wrap items-center justify-between text-[11px] font-mono text-slate-500 gap-2">
                        <span>GPS Yako: <span id="display-lat" class="font-bold text-slate-700">-</span>, <span id="display-lon" class="font-bold text-slate-700">-</span></span>
                        <span>Usahihi wa Satelaiti: &plusmn;<span id="display-acc" class="font-bold text-slate-700">-</span>m</span>
                        <span>Taasisi: {{ round($placement->hostOrganization->latitude, 4) }}, {{ round($placement->hostOrganization->longitude, 4) }}</span>
                    </div>
                </div>

                <!-- Check-in Form -->
                <form id="attendance-form" method="POST" action="{{ route('student.field-attendance.checkin') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="latitude" id="input-latitude">
                    <input type="hidden" name="longitude" id="input-longitude">
                    <input type="hidden" name="accuracy" id="input-accuracy">

                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 mb-1">
                            Maelezo ya Shughuli ya Siku (Activity Notes / Remark)
                        </label>
                        <input
                            type="text"
                            name="notes"
                            id="notes"
                            placeholder="mfano: Nimefika IT Department, kuanza kazi za database na networking..."
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <button
                            type="button"
                            id="btn-acquire-location"
                            onclick="acquireLiveLocation()"
                            class="py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider transition shadow flex items-center justify-center space-x-2 active:scale-95"
                        >
                            <i class="fa-solid fa-crosshairs"></i>
                            <span>1. Sasisha Eneo Langu la GPS</span>
                        </button>

                        <button
                            type="submit"
                            id="btn-submit-attendance"
                            disabled
                            class="py-3 px-4 rounded-xl bg-slate-200 text-slate-400 font-black text-xs uppercase tracking-wider transition shadow opacity-60 cursor-not-allowed flex items-center justify-center space-x-2"
                        >
                            <i class="fa-solid fa-check-double"></i>
                            <span>2. Thibitisha & Weka Mahudhurio</span>
                        </button>
                    </div>

                    <p class="text-[11px] text-slate-400 text-center flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-lock text-slate-400"></i>
                        <span>Ulinzi wa 100% wa Kupima Eneo: Kitufe cha kuthibitisha kinafunguka tu ukiwa ndani ya ofisi.</span>
                    </p>
                </form>
            </div>
        @endif

        <!-- Attendance History Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Historia ya Mahudhurio ya Field</h3>
                    <p class="text-xs text-slate-400">Kila siku iliyosajiliwa na alama zake za setilaiti</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                    {{ $summary['attendance_percentage'] ?? 0 }}% Kiwango cha Mahudhurio
                </span>
            </div>

            @if($attendances->isEmpty())
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl mb-2">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </div>
                    <p class="text-xs text-slate-500 font-semibold">Bado hujarekodi mahudhurio yoyote.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Tarehe</th>
                                <th class="px-4 py-3">Muda wa Kuingia</th>
                                <th class="px-4 py-3">Hali (Status)</th>
                                <th class="px-4 py-3">Uthibitisho wa GPS</th>
                                <th class="px-4 py-3">Maelezo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($attendances as $att)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $att->attendance_date ? $att->attendance_date->format('M d, Y') : '' }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-slate-600 font-bold">
                                        {{ $att->check_in_time ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($att->status === 'present')
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase inline-flex items-center gap-1">
                                                <i class="fa-solid fa-check text-[9px]"></i> Present
                                            </span>
                                        @elseif($att->status === 'late')
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] uppercase inline-flex items-center gap-1">
                                                <i class="fa-regular fa-clock text-[9px]"></i> Late
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">{{ $att->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-slate-500 text-[11px]">
                                        @if($att->latitude && $att->longitude)
                                            <span class="text-emerald-700 font-semibold inline-flex items-center gap-1">
                                                <i class="fa-solid fa-location-dot text-emerald-500"></i>
                                                {{ round($att->latitude, 4) }}, {{ round($att->longitude, 4) }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">Manual / N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 max-w-xs truncate">
                                        {{ $att->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        const ORG_LAT = {{ (float) ($placement->hostOrganization->latitude ?? -6.816064) }};
        const ORG_LON = {{ (float) ($placement->hostOrganization->longitude ?? 39.280358) }};
        const ALLOWED_RADIUS = {{ (int) ($placement->hostOrganization->geofence_radius_meters ?? 200) }};
        const ORG_NAME = "{{ addslashes($placement->hostOrganization->name) }}";

        let map = null;
        let orgMarker = null;
        let geofenceCircle = null;
        let studentMarker = null;
        let accuracyCircle = null;
        let connectLine = null;

        function initAttendanceMap() {
            const mapContainer = document.getElementById('attendance-verification-map');
            if (!mapContainer || typeof L === 'undefined') return;

            map = L.map('attendance-verification-map').setView([ORG_LAT, ORG_LON], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            // Host Organization Pin
            const officeIcon = L.divIcon({
                className: 'custom-office-pin',
                html: `<div class="w-9 h-9 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-base shadow-xl border-2 border-white ring-2 ring-indigo-500/50"><i class="fa-solid fa-building"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18]
            });

            orgMarker = L.marker([ORG_LAT, ORG_LON], { icon: officeIcon }).addTo(map);
            orgMarker.bindPopup(`<b>${ORG_NAME}</b><br>Eneo Rasmi la Kazi (Geofence: ${ALLOWED_RADIUS}m)`).openPopup();

            // Geofence perimeter circle
            geofenceCircle = L.circle([ORG_LAT, ORG_LON], {
                color: '#3b82f6',
                fillColor: '#60a5fa',
                fillOpacity: 0.15,
                radius: ALLOWED_RADIUS,
                weight: 2,
                dashArray: '4, 4'
            }).addTo(map);

            setTimeout(() => {
                map.invalidateSize();
            }, 300);
        }

        // Calculate Haversine distance in meters
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return Math.round(R * c);
        }

        function acquireLiveLocation() {
            const statusLabel = document.getElementById('gps-status-label');
            const statusIcon = document.getElementById('gps-status-icon');
            const mapStatusText = document.getElementById('map-status-text');
            const banner = document.getElementById('verification-status-banner');
            const distanceBadge = document.getElementById('live-distance-badge');
            const submitBtn = document.getElementById('btn-submit-attendance');
            const latInput = document.getElementById('input-latitude');
            const lonInput = document.getElementById('input-longitude');
            const accInput = document.getElementById('input-accuracy');
            const coordsRow = document.getElementById('gps-details-row');

            if (!navigator.geolocation) {
                statusLabel.innerText = "Kivinjari hiki hakiruhusu kutambua GPS.";
                statusIcon.className = "fa-solid fa-circle-xmark text-rose-600";
                return;
            }

            statusLabel.innerText = "Inanasa mawimbi ya setilaiti kwa usahihi...";
            statusIcon.className = "fa-solid fa-spinner fa-spin text-blue-600";
            if (mapStatusText) mapStatusText.innerText = "Inapima eneo lako...";

            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;
                    const acc = Math.round(pos.coords.accuracy);

                    latInput.value = lat;
                    lonInput.value = lon;
                    accInput.value = acc;

                    document.getElementById('display-lat').innerText = lat.toFixed(5);
                    document.getElementById('display-lon').innerText = lon.toFixed(5);
                    document.getElementById('display-acc').innerText = acc;
                    coordsRow.classList.remove('hidden');

                    const distance = calculateDistance(lat, lon, ORG_LAT, ORG_LON);
                    const formattedDist = distance >= 1000 ? (distance / 1000).toFixed(2) + ' km' : distance + ' m';

                    // Update Student Marker on Map
                    if (map) {
                        const studentIcon = L.divIcon({
                            className: 'custom-student-pin',
                            html: `<div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm shadow-xl border-2 border-white ring-4 ring-blue-400/40 animate-pulse"><i class="fa-solid fa-person-walking"></i></div>`,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16]
                        });

                        if (studentMarker) {
                            studentMarker.setLatLng([lat, lon]);
                        } else {
                            studentMarker = L.marker([lat, lon], { icon: studentIcon }).addTo(map);
                        }

                        if (accuracyCircle) {
                            accuracyCircle.setLatLng([lat, lon]).setRadius(acc);
                        } else {
                            accuracyCircle = L.circle([lat, lon], {
                                radius: acc,
                                color: '#38bdf8',
                                fillColor: '#38bdf8',
                                fillOpacity: 0.1,
                                weight: 1
                            }).addTo(map);
                        }

                        // Connecting line
                        if (connectLine) {
                            connectLine.setLatLngs([[ORG_LAT, ORG_LON], [lat, lon]]);
                        } else {
                            connectLine = L.polyline([[ORG_LAT, ORG_LON], [lat, lon]], {
                                color: distance <= ALLOWED_RADIUS ? '#10b981' : '#ef4444',
                                weight: 3,
                                dashArray: '6, 6'
                            }).addTo(map);
                        }

                        // Fit bounds to show both student and office
                        map.fitBounds(L.latLngBounds([[ORG_LAT, ORG_LON], [lat, lon]]), { padding: [50, 50] });
                    }

                    // Strict Geofence Verification
                    if (distance <= ALLOWED_RADIUS) {
                        // WITHIN GEOFENCE
                        banner.className = "p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-xs text-emerald-950 transition duration-300";
                        statusIcon.className = "fa-solid fa-circle-check text-emerald-600 text-base";
                        statusLabel.innerHTML = `<strong>✓ Upo Ndani ya Eneo la Taasisi!</strong> Umbali: <span class="font-bold text-emerald-800">${formattedDist}</span> (Upeo: ${ALLOWED_RADIUS}m).`;
                        distanceBadge.className = "font-mono font-black text-xs px-3 py-1 rounded-xl bg-emerald-600 text-white shadow-sm";
                        distanceBadge.innerText = `Umbali: ${formattedDist} (Ndani)`;
                        if (mapStatusText) mapStatusText.innerText = "✓ Upo Ndani ya Eneo la Kazi";

                        if (geofenceCircle) {
                            geofenceCircle.setStyle({ color: '#10b981', fillColor: '#34d399', fillOpacity: 0.25 });
                        }

                        submitBtn.removeAttribute('disabled');
                        submitBtn.className = "py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-xs uppercase tracking-wider transition shadow-lg shadow-emerald-600/30 flex items-center justify-center space-x-2 active:scale-95 cursor-pointer";
                    } else {
                        // OUTSIDE GEOFENCE (REJECT CHEATING)
                        banner.className = "p-4 rounded-2xl bg-rose-50 border border-rose-300 text-xs text-rose-950 transition duration-300";
                        statusIcon.className = "fa-solid fa-circle-xmark text-rose-600 text-base";
                        statusLabel.innerHTML = `<strong class="text-rose-900">⛔ Haupo Ndani ya Eneo la Kazi!</strong> Upo umbali wa <span class="font-bold text-rose-700">${formattedDist}</span> kutoka ofisini. Huwezi kupiga mahudhurio ukiwa mbali.`;
                        distanceBadge.className = "font-mono font-black text-xs px-3 py-1 rounded-xl bg-rose-600 text-white shadow-sm";
                        distanceBadge.innerText = `Umbali: ${formattedDist} (Nje)`;
                        if (mapStatusText) mapStatusText.innerText = "⛔ Nje ya Eneo la Kazi";

                        if (geofenceCircle) {
                            geofenceCircle.setStyle({ color: '#ef4444', fillColor: '#f87171', fillOpacity: 0.25 });
                        }

                        submitBtn.setAttribute('disabled', 'disabled');
                        submitBtn.className = "py-3 px-4 rounded-xl bg-slate-200 text-slate-400 font-black text-xs uppercase tracking-wider transition shadow opacity-60 cursor-not-allowed flex items-center justify-center space-x-2";
                    }
                },
                function(err) {
                    let msg = "Hitilafu ya GPS: ";
                    switch(err.code) {
                        case err.PERMISSION_DENIED:
                            msg += "Umeruhusu browser kusoma eneo lako (Location Permission). Washa GPS kisha bofya 'Allow'.";
                            break;
                        case err.POSITION_UNAVAILABLE:
                            msg += "Mawimbi ya GPS hayapatikani kwa sasa.";
                            break;
                        case err.TIMEOUT:
                            msg += "Muda wa kusoma GPS umekwisha. Jaribu tena.";
                            break;
                        default:
                            msg += err.message;
                    }
                    statusLabel.innerText = msg;
                    statusIcon.className = "fa-solid fa-circle-exclamation text-rose-600";
                    if (mapStatusText) mapStatusText.innerText = "Hitilafu ya GPS";
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        document.addEventListener('DOMContentLoaded', () => {
            initAttendanceMap();
            setTimeout(acquireLiveLocation, 800);
        });
    </script>
    @endpush
</x-layouts.cbe>
