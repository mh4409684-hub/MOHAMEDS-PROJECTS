<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CBE Security Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f1f5f9; padding: 30px; margin: 0;">
    <div style="max-width: 550px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e3a8a, #312e81); padding: 25px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 22px; font-weight: bold; letter-spacing: -0.5px;">COLLEGE OF BUSINESS EDUCATION (CBE)</h1>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #bfdbfe;">Integrated E-Logbook & GPS Attendance Portal</p>
        </div>

        <!-- Body -->
        <div style="padding: 30px 25px; color: #334155; line-height: 1.6;">
            <p style="font-size: 15px; margin-top: 0;">Hello, <strong>{{ $user->name }}</strong>,</p>

            @if($actionType === '2fa')
                <p style="font-size: 14px;">An administrative sign-in was attempted for your account. To proceed, please enter the following single-use Two-Factor Authentication (OTP) security code:</p>
                <!-- Code Display Box -->
                <div style="text-align: center; margin: 25px 0;">
                    <div style="display: inline-block; padding: 14px 28px; background-color: #eff6ff; border: 2px dashed #3b82f6; border-radius: 12px;">
                        <span style="font-family: 'Courier New', monospace; font-size: 32px; font-weight: 800; letter-spacing: 6px; color: #1d4ed8;">
                            {{ $code }}
                        </span>
                    </div>
                    <p style="font-size: 12px; color: #64748b; margin-top: 8px;">(This code is valid for 10 minutes)</p>
                </div>
            @elseif($actionType === 'registration')
                <p style="font-size: 14px; color: #1e40af; background-color: #eff6ff; padding: 14px; border-radius: 10px; border: 1px solid #bfdbfe;">
                    📩 <strong>Usajili Wako Umepokelewa!</strong> Asante kwa kujiunga na mfumo wa mafunzo kwa vitendo wa <strong>College of Business Education (CBE)</strong>.
                </p>
                <p style="font-size: 14px; margin-top: 15px;">
                    Akaunti yako ya mwanafunzi (Reg No: <strong>{{ $user->registration_number }}</strong>, Username: <strong>{{ $user->username }}</strong>) imehifadhiwa salama kwenye mfumo.
                </p>
                <p style="font-size: 13px; color: #475569;">
                    Kwa sasa akaunti yako inasubiri uhakiki wa kiusalama kutoka kwa Mkuu wa Mfumo (Administrator). Mara tu itakapoidhinishwa, utatumiwa ujumbe rasmi na utaweza kuingia mara moja.
                </p>
            @elseif($actionType === 'approval')
                <p style="font-size: 14px; color: #065f46; background-color: #ecfdf5; padding: 14px; border-radius: 10px; border: 1px solid #a7f3d0;">
                    🎉 <strong>Hongera!</strong> Ombi lako la kujisajili kama mwanafunzi wa <strong>College of Business Education (CBE)</strong> limehakikiwa na <strong>limekubaliwa rasmi</strong> na Mkuu wa Mfumo (Admin).
                </p>
                <p style="font-size: 14px; margin-top: 15px;">
                    Akaunti yako sasa iko hai (Activated) na imethibitishwa. Unaweza kuingia kwenye mfumo na kuanza kurekodi E-Logbook, GPS Attendance, na kuwasiliana na Supervisor wako.
                </p>
                <div style="text-align: center; margin: 25px 0;">
                    <a href="{{ $actionUrl ?? url('/cbe/login') }}" style="display: inline-block; padding: 14px 28px; background-color: #2563eb; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 10px; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);">
                        Ingia Kwenye CBE Portal &rarr;
                    </a>
                </div>
            @else
                <p style="font-size: 14px;">A password reset request was initiated for your university account. Use the verification code below to reset your password:</p>
                <!-- Code Display Box -->
                <div style="text-align: center; margin: 25px 0;">
                    <div style="display: inline-block; padding: 14px 28px; background-color: #eff6ff; border: 2px dashed #3b82f6; border-radius: 12px;">
                        <span style="font-family: 'Courier New', monospace; font-size: 32px; font-weight: 800; letter-spacing: 6px; color: #1d4ed8;">
                            {{ $code }}
                        </span>
                    </div>
                    <p style="font-size: 12px; color: #64748b; margin-top: 8px;">(This code is valid for 10 minutes)</p>
                </div>
            @endif

            <p style="font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                If you did not request this action, please contact the CBE IT Systems Administrator immediately at <strong>ict-support@cbe.ac.tz</strong>.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 15px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0;">
            College of Business Education &bull; Dar es Salaam / Dodoma / Mwanza / Mbeya Campuses<br>
            All rights reserved &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
