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
$app->add(new \App\Middlewares\TwigCsrfMiddleware($container->view->getEnvironment(), $container->csrf));//permet d'injecter les formulaire csrf pour twig 
$app->add($container->csrf);//verifie la présence d'un token csrf 

//Routes 
$app->get('/', \App\Controllers\PagesController::class . ':home')->setName('root');
$app->get('/admin', \App\Controllers\PagesController::class . ':admin')->setName('admin');
$app->post('/admin', \App\Controllers\PagesController::class . ':postEvent');
$app->get('/search', \App\Controllers\PagesController::class . ':searchPage')->setName('search');
$app->get('/event/{id:[0-9]+}', \App\Controllers\PagesController::class . ':getEvent')->setName('event');
$app->get('/notFound', \App\Controllers\PagesController::class . ':notfound')->setName('notFound');
$app->get('/contact', \App\Controllers\PagesController::class . ':getContact')->setName('contact');
$app->post('/contact', \App\Controllers\PagesController::class . ':postContact');

$app->run();