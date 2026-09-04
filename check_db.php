<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user_count = \App\Models\User::count();
echo "Total users: $user_count\n";

$super_admin = \App\Models\User::where('email', 'superadmin@cbe.ac.tz')->first();
if ($super_admin) {
    echo "✓ Super Admin found: " . $super_admin->email . "\n";
    echo "  Password hash: " . substr($super_admin->password, 0, 20) . "...\n";
    echo "  Has super_admin role: " . ($super_admin->hasRole('super_admin') ? 'YES' : 'NO') . "\n";
} else {
    echo "✗ Super Admin NOT found\n";
}

$users = \App\Models\User::limit(5)->get();
echo "\nFirst 5 users:\n";
foreach ($users as $u) {
    echo "- " . $u->email . " (ID: " . $u->id . ")\n";
}
