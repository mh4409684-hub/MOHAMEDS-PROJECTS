<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'CBE Report' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; line-height: 1.5; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 20px; }
        .logo { font-size: 18px; font-weight: bold; color: #1e3a8a; }
        .sublogo { font-size: 12px; color: #64748b; }
        .title { font-size: 16px; font-weight: bold; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 10px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">COLLEGE OF BUSINESS EDUCATION (CBE)</div>
        <div class="sublogo">Field Practical Training & Academic Management System</div>
        <div class="title">{{ $title ?? 'Official Report' }}</div>
        <div style="font-size: 10px; color: #64748b;">Generated on: {{ now()->format('d M Y, H:i') }}</div>
    </div>

    @if(isset($student))
        <h3>Student Information</h3>
        <table>
            <tr><th>Name</th><td>{{ $student['name'] ?? '-' }}</td></tr>
            <tr><th>Registration Number</th><td>{{ $student['registration_number'] ?? '-' }}</td></tr>
            <tr><th>Programme</th><td>{{ $student['programme'] ?? '-' }}</td></tr>
            <tr><th>Campus</th><td>{{ $student['campus'] ?? '-' }}</td></tr>
        </table>
    @endif

    @if(isset($summary))
        <h3>Summary Statistics</h3>
        <table>
            @foreach($summary as $key => $val)
                <tr>
                    <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                    <td>{{ is_array($val) ? json_encode($val) : $val }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <div class="footer">
        CBE System Automated Report &bull; Dar es Salaam, Tanzania
    </div>
</body>
</html>
