<?php
$container = $app->getContainer();

$container['debug'] = function () {
    return true;
};

$container['view'] = function ($container) {
    $dir = dirname(__DIR__);
    $view = new \Slim\Views\Twig($dir . '/app/views', [
        'cache' => $container->debug ?  false : $dir . '/tmp/cache',
        'debug' => $container->debug
    ]);
    
    if($container->debug) {
        $view->addExtension(new Twig_Extension_Debug());
    }
    
    // Instantiate ans add Slim specific extention 
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    
    return $view;
};

//Override the default Not Found Handler after App
unset($container['notFoundHandler']);
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response->withStatus(404), 'pages/errorPage.html.twig', []);
    };
};
