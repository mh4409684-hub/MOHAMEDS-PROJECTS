<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test:mail {to?}', function ($to = 'mh4409684@gmail.com') {
    $this->info("Sending test email to: {$to}");
    try {
        \Illuminate\Support\Facades\Mail::raw("CBE Portal live email test.\nKama unaiona hii barua pepe, basi mfumo wako wa CBE umeanza rasmi kutuma taarifa moja kwa moja kwenye inboxes za mtumiaji!", function ($m) use ($to) {
            $m->to($to)->subject('CBE Portal - Live SMTP Email Verification Test');
        });
        $this->info("SUCCESS: Email sent successfully!");
    } catch (\Throwable $e) {
        $this->error("ERROR: " . $e->getMessage());
    }
});

Artisan::command('generate:icons', function () {
    $pwaDir = public_path('pwa');
    if (!file_exists($pwaDir)) {
        mkdir($pwaDir, 0755, true);
    }
    
    foreach ([192, 512] as $size) {
        $im = imagecreatetruecolor($size, $size);
        $blue = imagecolorallocate($im, 30, 58, 138);
        $white = imagecolorallocate($im, 255, 255, 255);
        $gold = imagecolorallocate($im, 251, 191, 36);

        imagefilledrectangle($im, 0, 0, $size, $size, $blue);
        imagefilledellipse($im, (int)($size/2), (int)($size/2), (int)($size*0.85), (int)($size*0.85), $gold);
        imagefilledellipse($im, (int)($size/2), (int)($size/2), (int)($size*0.80), (int)($size*0.80), $blue);

        $font = 5;
        $text = 'CBE';
        $charW = imagefontwidth($font);
        $charH = imagefontheight($font);
        $len = strlen($text);
        
        $x = (int)(($size - ($len * $charW * 4)) / 2);
        $y = (int)(($size - ($charH * 4)) / 2);
        
        // Scale text
        $temp = imagecreatetruecolor($len * $charW, $charH);
        imagefill($temp, 0, 0, $blue);
        imagestring($temp, $font, 0, 0, $text, $white);
        imagecopyresized($im, $temp, $x, $y, 0, 0, $len * $charW * 4, $charH * 4, $len * $charW, $charH);
        imagedestroy($temp);

        imagepng($im, "{$pwaDir}/icon-{$size}.png");
        imagedestroy($im);
    }
    $this->info('Icons generated successfully.');
});

Artisan::command('make:desktop-files', function () {
    $desktop = 'C:/Users/MOHAMEDY/OneDrive/Desktop';
    $folder = "{$desktop}/CBE_PORTAL_APP_AND_LINKS";

    if (!file_exists($folder)) {
        mkdir($folder, 0755, true);
    }

    $urlContent = "[InternetShortcut]\r\nURL=https://mohanedy-project.onrender.com/cbe/login\r\nIconIndex=0\r\n";
    file_put_contents("{$desktop}/CBE_Portal_App.url", $urlContent);
    file_put_contents("{$folder}/CBE_Portal_App.url", $urlContent);

    $info = <<<TEXT
================================================================================
COLLEGE OF BUSINESS EDUCATION (CBE) - INTEGRATED E-LOGBOOK & ATTENDANCE SYSTEM
Designed & Exclusively Owned by: MOHAMEDY HAMADI MOHAMED
================================================================================

1. PRIMARY 24/7 LIVE SERVER LINK (Render Cloud - Masaa 24 Hata Ukizima PC):
   -----------------------------------------------------------------------
   Login: https://mohanedy-project.onrender.com/cbe/login
   Student Self-Register: https://mohanedy-project.onrender.com/cbe/register

2. BACKUP LIVE TUNNEL LINK (Laptop Hii Ikiwa Imewashwa):
   -----------------------------------------------------
   Login: https://cave-trials-yorkshire-literary.trycloudflare.com/cbe/login
   Student Register: https://cave-trials-yorkshire-literary.trycloudflare.com/cbe/register

3. LOCALHOST URL (Kwenye Kompyuta Hii Tu):
   ----------------------------------------
   URL: http://127.0.0.1:8080/cbe/login

================================================================================
LOGIN CREDENTIALS (AKAUNTI ZA KUINGILIA):
================================================================================
A. SUPER ADMIN / SYSTEM OWNER:
   - Jina: MOHAMEDY HAMADI MOHAMED
   - Email: mh4409684@gmail.com
   - Password: mobili2004
   - Reg No: 03.5845.01.02.2025
   * Ujumbe wa 2FA unatumwa moja kwa moja kwenye Gmail ya mh4409684@gmail.com!
   * Ana ukurasa maalum wa "Owner Console" wa kuzima na kuwasha mfumo (/admin/owner-control).

B. DEMO STUDENT (MWANAFUNZI WA MAJARIBIO):
   - Email: student@cbe.ac.tz
   - Password: Student@2025

C. FIELD SUPERVISOR:
   - Wasimamizi wote huundwa na kusajiliwa na Admin kutoka Admin Dashboard.

================================================================================
JINSI YA KUTUMIA KAMA APP YA SIMU (ANDROID APK / PWA):
================================================================================
Mtumie mtu au mwanafunzi link hii kwenye WhatsApp au SMS:
👉 https://mohanedy-project.onrender.com/cbe/login

Akifungua kwenye Google Chrome ya simu yake:
1. Atakuta kitufe cha bluu kilichoandikwa "Pakua App" / "Tumia Kama App Ya Simu".
2. Au akibonyeza vitone 3 vya juu kulia vya Chrome (⋮), achague "Install App" / "Weka Kwenye Skrini".
3. Hapo hapo simu yake itatengeneza Icon rasmi ya CBE Portal kwenye skrini ya simu yake na itafunguka kama App halisi ya simu bila browser bar na itatumia GPS ya simu yake!

Kupakua APK Package (.apk file):
Weka link hiyo kwenye tovuti ya bure ya Microsoft: https://www.pwabuilder.com kisha bonyeza "Package for Android" ili kupakua faili la .apk.
================================================================================
TEXT;

    file_put_contents("{$desktop}/CBE_PORTAL_LINKS.txt", $info);
    file_put_contents("{$folder}/MAWASILIANO_NA_LINKS_ZA_MFUMO.txt", $info);

    $this->info("Desktop files created successfully!");
});


