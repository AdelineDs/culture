<?php
namespace App\Controllers;

use \App\Models\EventsManager;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class  PagesController extends Controller {
    
    private $events;
    
    public function home(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $events = $this->events->getEvents();
        $this->render($response, 'pages/home.twig', ['events' => $events]);
    }
   
}