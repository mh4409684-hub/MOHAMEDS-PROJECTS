<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CBE Official Report' }}</title>
    <link rel="icon" type="image/png" href="/mohamedtechpro-logo.png">
    <style>
        @page {
            size: A4;
            margin: 15mm 15mm 20mm 15mm;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .action-bar {
            background: #0f172a;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .action-bar a, .action-bar button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }
        .btn-print { background: #2563eb; color: #fff; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-excel { background: #16a34a; color: #fff; }
        .btn-excel:hover { background: #15803d; }
        .btn-back { background: #334155; color: #f1f5f9; }
        .btn-back:hover { background: #475569; }

        .report-sheet {
            background: #ffffff;
            max-width: 800px;
            margin: 24px auto;
            padding: 36px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header-logo {
            width: 50px;
            height: 50px;
            margin: 0 auto 8px;
            display: block;
            border-radius: 50%;
        }
        .header h1 {
            font-size: 17px;
            font-weight: 900;
            color: #0f172a;
            margin: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin: 4px 0 0;
        }
        .header-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 12px;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #64748b;
            margin-top: 12px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            border-left: 4px solid #2563eb;
            padding-left: 8px;
            margin: 24px 0 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 11px;
        }
        table th, table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        table th {
            background-color: #f1f5f9;
            font-weight: 700;
            color: #334155;
            width: 35%;
        }
        .data-table th {
            width: auto;
            text-transform: uppercase;
            font-size: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }
        .stat-box .num {
            font-size: 20px;
            font-weight: 900;
            color: #1e3a8a;
            margin-top: 4px;
        }
        .stat-box .lbl {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 36px;
            padding-top: 16px;
            border-top: 1px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #64748b;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            opacity: 0.04;
            pointer-events: none;
            width: 350px;
        }

        @media print {
            body { background: #fff; }
            .action-bar { display: none !important; }
            .report-sheet {
                box-shadow: none;
                border: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Action Toolbar (Hidden during Print) -->
    <div class="action-bar">
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="javascript:history.back()" class="btn-back">&larr; Rudi Nyuma</a>
            <span style="font-size:12px; font-weight:600; color:#94a3b8;">{{ $title ?? 'CBE Report' }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="{{ request()->fullUrlWithQuery(['format' => 'excel']) }}" class="btn-excel">
                <span>&#128196; Pakua Excel (CSV)</span>
            </a>
            <button onclick="window.print()" class="btn-print">
                <span>&#128424; Chapisha / Hifadhi kama PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Sheet -->
    <div class="report-sheet">
        <img src="/mohamedtechpro-logo.png" alt="Watermark" class="watermark">

        <div class="header">
            <img src="/mohamedtechpro-logo.png" alt="CBE" class="header-logo">
            <h1>College of Business Education (CBE)</h1>
            <h2>Field Practical Training & Academic Management System</h2>
            <div class="header-badge">{{ $title ?? $report['title'] ?? 'Official Report' }}</div>
            <div class="meta-info">
                <span>Tarehe ya Kutolewa: <strong>{{ $report['generated_at'] ?? now()->format('d M Y, H:i') }}</strong></span>
                <span>Mfumo: <strong>CBE E-Logbook Portal</strong></span>
            </div>
        </div>

        @php
            $student = $report['student'] ?? $report['student_information'] ?? null;
            $placement = $report['field_information'] ?? $report['placement_information'] ?? null;
            $supervisor = $report['supervisor_information'] ?? null;
            $attendanceSummary = $report['attendance_summary'] ?? $report['field_attendance'] ?? null;
            $logbookSummary = $report['logbook_summary'] ?? null;
        @endphp

        <!-- Student Info -->
        @if($student)
            <div class="section-title">Taarifa za Mwanafunzi</div>
            <table>
                <tr>
                    <th>Jina Kamili</th>
                    <td><strong>{{ $student['name'] ?? '-' }}</strong></td>
                    <th>Registration No</th>
                    <td><strong>{{ $student['registration_number'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <th>Kozi / Programme</th>
                    <td>{{ $student['programme'] ?? '-' }}</td>
                    <th>Campus</th>
                    <td>{{ $student['campus'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Barua Pepe (Email)</th>
                    <td>{{ $student['email'] ?? '-' }}</td>
                    <th>Namba ya Simu</th>
                    <td>{{ $student['phone'] ?? '-' }}</td>
                </tr>
            </table>
        @endif

        <!-- Placement Info -->
        @if($placement)
            <div class="section-title">Taarifa za Field Placement</div>
            <table>
                <tr>
                    <th>Taasisi / Shirika</th>
                    <td colspan="3"><strong>{{ $placement['host_organization'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <th>Anwani ya Ofisi</th>
                    <td colspan="3">{{ $placement['address'] ?? $placement['organization_address'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kipindi cha Kazi</th>
                    <td>{{ $placement['start_date'] ?? '-' }} hadi {{ $placement['end_date'] ?? '-' }}</td>
                    <th>Maendeleo ya Siku</th>
                    <td><strong>{{ $placement['days_completed'] ?? 0 }} / {{ $placement['total_days'] ?? 0 }} siku ({{ $placement['progress'] ?? $placement['field_progress'] ?? '0%' }})</strong></td>
                </tr>
            </table>
        @endif

        <!-- Supervisor Info -->
        @if($supervisor)
            <div class="section-title">Msimamizi wa Chuo (Supervisor)</div>
            <table>
                <tr>
                    <th>Jina la Msimamizi</th>
                    <td><strong>{{ $supervisor['name'] ?? '-' }}</strong></td>
                    <th>Wadhifa</th>
                    <td>{{ $supervisor['designation'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Simu</th>
                    <td>{{ $supervisor['phone'] ?? '-' }}</td>
                    <th>Barua Pepe</th>
                    <td>{{ $supervisor['email'] ?? '-' }}</td>
                </tr>
            </table>
        @endif

        <!-- Stats Summaries -->
        @if($attendanceSummary || $logbookSummary)
            <div class="section-title">Muhtasari wa Takwimu</div>
            <div class="stats-grid">
                @if($attendanceSummary)
                    <div class="stat-box">
                        <div class="lbl">Mahudhurio (%)</div>
                        <div class="num">{{ $attendanceSummary['percentage'] ?? 0 }}%</div>
                    </div>
                    <div class="stat-box">
                        <div class="lbl">Siku Zilizofika</div>
                        <div class="num">{{ $attendanceSummary['present'] ?? $attendanceSummary['present_days'] ?? 0 }}</div>
                    </div>
                @endif
                @if($logbookSummary)
                    <div class="stat-box">
                        <div class="lbl">Logbook Entries</div>
                        <div class="num">{{ $logbookSummary['total_entries'] ?? 0 }}</div>
                    </div>
                    <div class="stat-box">
                        <div class="lbl">Masaa ya Kazi</div>
                        <div class="num">{{ $logbookSummary['total_hours'] ?? 0 }} hrs</div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Logbook Entries Table -->
        @if(!empty($report['logbook_entries']))
            <div class="section-title">Muhtasari wa Shughuli za Logbook</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Tarehe</th>
                        <th>Maelezo ya Kazi</th>
                        <th style="width: 12%;">Masaa</th>
                        <th style="width: 15%;">Hali</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['logbook_entries'] as $entry)
                        <tr>
                            <td>{{ $entry['date'] ?? '-' }}</td>
                            <td>{{ $entry['activity'] ?? '-' }}</td>
                            <td style="text-align: center;">{{ $entry['hours'] ?? 0 }} hrs</td>
                            <td style="text-transform: capitalize; font-weight: bold;">{{ $entry['status'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Class Attendance Table -->
        @if(!empty($report['student_attendance']))
            <div class="section-title">Orodha ya Mahudhurio ya Wanafunzi ({{ $report['course'] ?? '' }} - {{ $report['section'] ?? '' }})</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>Jina la Mwanafunzi</th>
                        <th>Registration No</th>
                        <th style="text-align: center;">Vipindi</th>
                        <th style="text-align: center;">Asilimia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['student_attendance'] as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td><strong>{{ $st['name'] ?? '-' }}</strong></td>
                            <td>{{ $st['registration_number'] ?? '-' }}</td>
                            <td style="text-align: center;">{{ $st['total_present'] ?? 0 }} / {{ $st['total_sessions'] ?? 0 }}</td>
                            <td style="text-align: center; font-weight: bold;">{{ $st['percentage'] ?? 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- System Statistics Grid -->
        @if(isset($report['students']) && isset($report['field_placements']))
            <div class="section-title">Takwimu za Wanafunzi na Field</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="lbl">Jumla ya Wanafunzi</div>
                    <div class="num">{{ $report['students']['total'] ?? 0 }}</div>
                </div>
                <div class="stat-box">
                    <div class="lbl">Wanafunzi Hai</div>
                    <div class="num">{{ $report['students']['active'] ?? 0 }}</div>
                </div>
                <div class="stat-box">
                    <div class="lbl">Field Zilizopo</div>
                    <div class="num">{{ $report['field_placements']['total'] ?? 0 }}</div>
                </div>
                <div class="stat-box">
                    <div class="lbl">Watumishi (Staff)</div>
                    <div class="num">{{ $report['staff']['total'] ?? 0 }}</div>
                </div>
            </div>
        @endif

        <div class="footer">
            <span>Powered by <strong>MOHAMEDYTECH PRO</strong></span>
            <span>College of Business Education &bull; Dar es Salaam, Tanzania</span>
            <span>Ukurasa 1 wa 1</span>
        </div>
    </div>

    @if(!empty($autoPrint))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
    @endif
</body>
</html>
