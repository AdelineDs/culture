<?php
namespace App\Controllers;

use \App\Models\EventsManager;
use \App\Models\AdminManager;
use Respect\Validation\Validator as v;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class  PagesController extends Controller {
    
    private $events;

    public function home(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $events = $this->events->getEvents();
        if(isset($_SESSION['id']) && isset($_SESSION['user'])) {
            return $this->render($response, 'pages/home.html.twig', ['events' => $events, 'session' => $_SESSION]);
        }
        return $this->render($response, 'pages/home.html.twig', ['events' => $events]);
    }
    
    public function searchPage(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $keyWords = $request->getParam('search');
        $events = $this->events->searchEvents($keyWords);
        $this->render($response, 'pages/searchPage.html.twig', ['events' => $events]);
    }
    
    public function notFound(RequestInterface $request, ResponseInterface $response) {
        $this->render($response, 'pages/404.html.twig');
    }
     
}