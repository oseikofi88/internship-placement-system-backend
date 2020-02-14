<?php
date_default_timezone_set('Europe/London');
set_time_limit(0);

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token');

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Instantiate the app



$settings = require __DIR__ . '/app/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/app/dependencies.php';

// Register middleware
require __DIR__ . '/app/middleware.php';

// Register routes
require __DIR__ . '/app/routes.php';

// Run!
$app->run();
