<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class Controller {
        
    private $container;
    
    public function __construct($container) {
        $this->container = $container;
    }
    
    public function render(ResponseInterface $response, $file, $params = []) {
        $this->container['view']->render($response, $file, $params);
    }
    
    public function redirect($response, $name, $status = 302) {
        return $response->withStatus($status)->withHeader('Location', $this->router->pathFor($name));
    }
    
    public function flash($message, $type = 'success') {
        if (!isset($_SESSION['flash'])){
            $_SESSION['flash'] = [];
        }
        return $_SESSION['flash'][$type] = $message;
    }
    
    //lorsque l'on accède une propriété "inconnue" on retourne la propriété qui se situe dans le container 
    public function __get($name) {
        return $this->container->get($name);
    }
}