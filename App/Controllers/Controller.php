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
    
    //lorsque l'on accède une propriété "inconnue" on retourne la propriété qui se situe dans le container 
    public function __get($name) {
        return $this->container->get($name);
    }
}