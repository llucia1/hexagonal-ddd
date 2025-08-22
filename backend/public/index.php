<?php

use Fynkus\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

// carga .env si no hay APP_ENV
if (!isset($_SERVER['APP_ENV'])) {
    (new Dotenv())->load(dirname(__DIR__).'/.env');
}

// calcula debug de forma segura
$debug = $_SERVER['APP_DEBUG'] ?? ($_SERVER['APP_ENV'] !== 'prod');
if ($debug) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);