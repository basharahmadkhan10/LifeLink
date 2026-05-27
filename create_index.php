<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Illuminate\Support\Facades\Schema::connection('mongodb')->table('users', function(\MongoDB\Laravel\Schema\Blueprint $collection) {
    $collection->geospatial('location', '2dsphere');
});

echo "2dsphere index created successfully.\n";
