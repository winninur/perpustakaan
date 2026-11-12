<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode check
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle the incoming request through the kernel
$app->handleRequest(Request::capture());
