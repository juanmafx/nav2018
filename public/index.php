<?php

declare(strict_types=1);

use Nav2018\Application;
use Nav2018\Http\Request;
use Nav2018\Http\Response;
use Nav2018\Http\SecurityHeaders;

ini_set('display_errors', '0');

require dirname(__DIR__) . '/vendor/autoload.php';

try {
    $response = Application::create(dirname(__DIR__) . '/templates')->handle(Request::fromGlobals($_SERVER));
} catch (Throwable $exception) {
    error_log($exception::class . ': ' . $exception->getMessage());
    $response = new Response(500, SecurityHeaders::all() + ['Content-Type' => 'text/plain; charset=UTF-8'], 'Internal Server Error');
}

$response->send();
