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

