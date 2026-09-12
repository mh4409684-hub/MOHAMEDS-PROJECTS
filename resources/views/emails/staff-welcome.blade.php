<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Karibu CBE Portal - Taarifa za Akaunti Yako</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f1f5f9; padding: 25px; margin: 0;">
    <div style="max-width: 580px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e3a8a, #1e40af); padding: 30px 20px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">COLLEGE OF BUSINESS EDUCATION (CBE)</h1>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #bfdbfe;">Integrated Field Practical Training & Academic Portal</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 30px 25px; color: #334155; line-height: 1.6;">
            <p style="font-size: 15px; margin-top: 0;">Habari, <strong>{{ $user->name }}</strong>,</p>

            <p style="font-size: 14px;">
                Hongera! Umetengenezewa rasmi akaunti ya 
                <strong>
                    @if(in_array($staffType, ['supervisor', 'field_supervisor']))
                        Msimamizi wa Mafunzo ya Vitendo (Field Supervisor)
                    @elseif($staffType === 'lecturer')
                        Mkufunzi (Lecturer)
                    @elseif(in_array($staffType, ['coordinator', 'field_coordinator']))
                        Mratibu wa Mafunzo (Field Coordinator)
                    @else
                        Mtumishi wa Chuo (CBE Staff)
                    @endif
                </strong> 
                kwenye mfumo mkuu wa <strong>College of Business Education (CBE)</strong>.
            </p>

            <!-- Credentials Card -->
            <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 18px 20px; margin: 22px 0;">
                <h3 style="margin: 0 0 12px 0; font-size: 13px; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 800; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                    Taarifa Zako za Kuingilia Kwenye Mfumo (Login Credentials)
                </h3>
                <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 5px 0; color: #64748b; width: 140px; font-weight: bold;">Anuani ya Barua Pepe:</td>
                        <td style="padding: 5px 0; font-family: monospace; font-weight: bold; color: #0f172a;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #64748b; font-weight: bold;">Jina la Kuingilia (Username):</td>
                        <td style="padding: 5px 0; font-family: monospace; font-weight: bold; color: #0f172a;">{{ $user->username ?? $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #64748b; font-weight: bold;">Nenosiri la Muda (Password):</td>
                        <td style="padding: 5px 0;">
                            <span style="background-color: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 6px; font-family: monospace; font-weight: 800; border: 1px solid #fde68a;">
                                {{ $temporaryPassword }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Login Action Button -->
            <div style="text-align: center; margin: 28px 0;">
                <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 14px; padding: 14px 32px; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);">
                    Ingia Kwenye Mfumo Sasa (Log In) &rarr;
                </a>
            </div>

            <p style="font-size: 13px; color: #64748b; margin-bottom: 0;">
                <em>Kumbuka: Unashauriwa kubadilisha nenosiri hili baada ya kuingia kwa mara ya kwanza kwa usalama wa akaunti yako.</em>
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0;">
            College of Business Education &bull; Makao Makuu Dar es Salaam, Tanzania<br>
            Huu ni ujumbe wa kiotomatiki kutoka kwenye mfumo wa CBE. Usijibu barua pepe hii.
        </div>
    </div>
</body>
</html>
