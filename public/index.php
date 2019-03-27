<?php
require '../vendor/autoload.php';

session_start();

$app = new \Slim\App([
    'setting' => [
        'displayErrorDetails' => TRUE
    ]
]);

require ('../app/container.php');

$container = $app->getContainer();

$app->get('/', \App\Controllers\PagesController::class . ':home')->setName('root');
$app->get('/admin', \App\Controllers\PagesController::class . ':admin')->setName('admin');

$app->run();