<?php
// set up errors show
error_reporting(E_ALL);
ini_set('display_errors', 1);

use vendor\core\Router;

// get composer autoload
require __DIR__ . '/../vendor/autoload.php';

// get router and run app
$router = new Router();
$router->run();
