<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$perms = \DB::table('permissions')
    ->where('name', 'like', '%detail_permintaan%')
    ->orderBy('name')
    ->pluck('name');

echo "Detail Permintaan Permissions:\n";
echo "=============================\n";
if ($perms->count() > 0) {
    foreach ($perms as $p) {
        echo "  ✓ " . $p . "\n";
    }
    echo "\nTotal: " . $perms->count() . "\n";
} else {
    echo "  ✗ NO PERMISSIONS FOUND!\n";
}

// Check all permissions count
$allPerms = \DB::table('permissions')->count();
echo "\nTotal permissions in database: " . $allPerms . "\n";

// Check roles
echo "\n\nRoles:\n";
echo "======\n";
$roles = \DB::table('roles')->pluck('name');
foreach ($roles as $r) {
    echo "  - " . $r . "\n";
}
