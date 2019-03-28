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

//Middleware 
$app->add(new \App\Middlewares\FlashMiddleware($container->view->getEnvironment()));
$app->add(new \App\Middlewares\OldMiddleware($container->view->getEnvironment()));

$app->get('/', \App\Controllers\PagesController::class . ':home')->setName('root');
$app->get('/admin', \App\Controllers\PagesController::class . ':admin')->setName('admin');
$app->post('/admin', \App\Controllers\PagesController::class . ':postEvent');
$app->get('/search', \App\Controllers\PagesController::class . ':searchPage')->setName('search');
$app->get('/event/{id:[0-9]+}', \App\Controllers\PagesController::class . ':getEvent')->setName('event');
$app->get('/notFound', \App\Controllers\PagesController::class . ':notfound')->setName('notFound');

$app->run();