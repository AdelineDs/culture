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
/*Pages routes*/
$app->get('/', \App\Controllers\PagesController::class . ':home')->setName('root');
$app->get('/search', \App\Controllers\PagesController::class . ':searchPage')->setName('search');
$app->get('/recherche', \App\Controllers\PagesController::class . ':search')->setName('recherche');
$app->get('/notFound', \App\Controllers\PagesController::class . ':notfound')->setName('notFound');
/*Events routes*/
$app->get('/event/{id:[0-9]+}', \App\Controllers\EventsController::class . ':getEvent')->setName('event');
$app->get('/admin', \App\Controllers\EventsController::class . ':adminEvent')->setName('admin');
$app->get('/admin/{id}', \App\Controllers\EventsController::class . ':adminEvent')->setName('update');
$app->post('/admin', \App\Controllers\EventsController::class . ':postEvent');
$app->put('/admin', \App\Controllers\EventsController::class . ':updateEvent');
$app->delete('/admin', \App\Controllers\EventsController::class . ':deleteEvent');
/*Contact routes*/
$app->get('/contact', \App\Controllers\ContactController::class . ':getContact')->setName('contact');
$app->post('/contact', \App\Controllers\ContactController::class . ':postContact');
/*Admin routes*/
$app->get('/connect', \App\Controllers\AdminController::class . ':getAdmin')->setName('connectAdmin');
$app->post('/connect', \App\Controllers\AdminController::class . ':connection');
$app->get('/disconnect', \App\Controllers\AdminController::class . ':disconnect')->setName('disconnect');

$app->run();