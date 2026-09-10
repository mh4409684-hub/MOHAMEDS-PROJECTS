<x-layouts.cbe title="GPS Field Attendance - CBE Portal">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        GPS Geofencing Verification
                    </span>
                    <span class="text-xs text-slate-500">College of Business Education</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 mt-1">Field Placement Attendance</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Assigned to <span class="font-semibold text-slate-700">{{ $placement->hostOrganization->name }}</span> ({{ $placement->hostOrganization->city }})
                </p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('student.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Today's Status Banner -->
        @if($todayAttendance)
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center text-2xl font-bold">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Attendance Recorded for Today!</h2>
                        <p class="text-xs text-emerald-100">
                            Check-in time: <span class="font-bold">{{ $todayAttendance->check_in_time }}</span> &bull; Status: <span class="uppercase font-bold tracking-wider">{{ $todayAttendance->status }}</span>
                        </p>
                        @if($todayAttendance->latitude && $todayAttendance->longitude)
                            <p class="text-[11px] text-emerald-200 mt-0.5 font-mono">
                                GPS: {{ round($todayAttendance->latitude, 5) }}, {{ round($todayAttendance->longitude, 5) }}
                            </p>
                        @endif
                    </div>
                </div>
                <span class="px-4 py-1.5 rounded-full bg-white text-emerald-800 font-extrabold text-xs uppercase tracking-wider shadow">
                    Verified
                </span>
            </div>
        @else
            <!-- GPS Check-In Interactive Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-md p-6 sm:p-8">
                <div class="text-center max-w-md mx-auto mb-6">
                    <div class="h-16 w-16 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-3 shadow-inner">
                        <i class="fa-solid fa-satellite-dish animate-pulse"></i>
                    </div>
                    <h2 class="text-xl font-black text-slate-900">Mark Today's Attendance</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Your browser will obtain your live GPS coordinates and ensure you are at {{ $placement->hostOrganization->name }} within {{ $placement->hostOrganization->geofence_radius_meters ?? 300 }} meters.
                    </p>
                </div>

                <!-- GPS Status Display Box -->
                <div id="gps-status-box" class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold flex items-center">
                            <i id="gps-icon" class="fa-solid fa-circle text-slate-400 mr-2 text-[10px]"></i>
                            GPS Status:
                        </span>
                        <span id="gps-text" class="font-mono text-slate-500">Waiting for location permission...</span>
                    </div>
                    <div id="gps-coords-row" class="hidden flex items-center justify-between border-t border-slate-200 pt-2 font-mono text-[11px]">
                        <span>Coordinates: <span id="display-lat">-</span>, <span id="display-lon">-</span></span>
                        <span>Accuracy: &plusmn;<span id="display-acc">-</span>m</span>
                    </div>
                </div>

                <!-- Check-in Form -->
                <form id="attendance-form" method="POST" action="{{ route('student.field-attendance.checkin') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="latitude" id="input-latitude">
                    <input type="hidden" name="longitude" id="input-longitude">

                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 mb-1">Activity Notes / Remark (Optional)</label>
                        <input type="text" name="notes" id="notes" placeholder="e.g. Arrived at IT Department, starting server maintenance" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <button type="button" id="btn-acquire-location" onclick="acquireLocation()" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider transition shadow flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-crosshairs"></i>
                        <span>1. Detect My GPS Location</span>
                    </button>

                    <button type="submit" id="btn-submit-attendance" disabled class="w-full py-3.5 px-4 rounded-xl bg-emerald-500 text-slate-950 font-black text-sm uppercase tracking-wider transition shadow-lg opacity-50 cursor-not-allowed flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-check-double"></i>
                        <span>2. Confirm & Mark Attendance</span>
                    </button>
                </form>
            </div>
        @endif

        <!-- Attendance History Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900">Attendance Log History</h3>
                <span class="text-xs font-semibold text-slate-500">
                    {{ $summary['attendance_percentage'] ?? 0 }}% Attendance Rate
                </span>
            </div>

            @if($attendances->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">No attendance recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-2.5">Date</th>
                                <th class="px-4 py-2.5">Check-In</th>
                                <th class="px-4 py-2.5">Status</th>
                                <th class="px-4 py-2.5">GPS Verification</th>
                                <th class="px-4 py-2.5">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($attendances as $att)
                                <tr>
                                    <td class="px-4 py-2.5 font-semibold text-slate-900">
                                        {{ $att->attendance_date ? $att->attendance_date->format('M d, Y') : '' }}
                                    </td>
                                    <td class="px-4 py-2.5 font-mono text-slate-600">
                                        {{ $att->check_in_time ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        @if($att->status === 'present')
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase">Present</span>
                                        @elseif($att->status === 'late')
                                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] uppercase">Late</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">{{ $att->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 font-mono text-slate-500 text-[11px]">
                                        @if($att->latitude && $att->longitude)
                                            <i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i>
                                            {{ round($att->latitude, 4) }}, {{ round($att->longitude, 4) }}
                                        @else
                                            <span class="text-slate-400">Manual / N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-500 max-w-xs truncate">
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
        function acquireLocation() {
            const statusText = document.getElementById('gps-text');
            const icon = document.getElementById('gps-icon');
            const coordsRow = document.getElementById('gps-coords-row');
            const submitBtn = document.getElementById('btn-submit-attendance');
            const latInput = document.getElementById('input-latitude');
            const lonInput = document.getElementById('input-longitude');
            const displayLat = document.getElementById('display-lat');
            const displayLon = document.getElementById('display-lon');
            const displayAcc = document.getElementById('display-acc');

            if (!navigator.geolocation) {
                statusText.innerText = "Geolocation is not supported by this browser.";
                icon.className = "fa-solid fa-circle text-rose-500 mr-2 text-[10px]";
                return;
            }

            statusText.innerText = "Acquiring accurate GPS fix...";
            icon.className = "fa-solid fa-spinner fa-spin text-blue-500 mr-2 text-[10px]";

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const acc = Math.round(position.coords.accuracy);

                    latInput.value = lat;
                    lonInput.value = lon;
                    displayLat.innerText = lat.toFixed(5);
                    displayLon.innerText = lon.toFixed(5);
                    displayAcc.innerText = acc;

                    coordsRow.classList.remove('hidden');
                    statusText.innerText = "GPS Position Locked! Ready to confirm.";
                    icon.className = "fa-solid fa-circle text-emerald-500 mr-2 text-[10px]";

                    submitBtn.removeAttribute('disabled');
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.add('hover:bg-emerald-400');
                },
                function(error) {
                    let msg = "Could not get location: ";
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            msg += "Location permission denied. Please allow location access in your browser.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            msg += "Position unavailable.";
                            break;
                        case error.TIMEOUT:
                            msg += "Request timed out.";
                            break;
                        default:
                            msg += error.message;
                    }
                    statusText.innerText = msg;
                    icon.className = "fa-solid fa-circle text-rose-500 mr-2 text-[10px]";
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        // Auto trigger detection if form is open
        document.addEventListener("DOMContentLoaded", function() {
            if (document.getElementById('btn-acquire-location')) {
                acquireLocation();
            }
        });
    </script>
    @endpush
</x-layouts.cbe>
