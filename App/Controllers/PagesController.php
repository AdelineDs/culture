<?php
namespace App\Controllers;

use \App\Models\EventsManager;
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
    
    public function search(RequestInterface $request, ResponseInterface $response) {
        $errors = [];
        
        v::stringType()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer un nom d\'évènement valide';
        v::stringType()->validate($request->getParam('organizer')) || $errors['organizer'] = 'Veuillez entrer le nom de l\'organisateur valide';
        if($request->getParam('date')){
            v::date()->validate($request->getParam('date')) || $errors['date'] = 'Veuillez entrer un format de date correct';}
            v::stringType()->validate($request->getParam('place')) || $errors['place'] = 'Veuillez entrer un lieu valide';
        if(empty($errors)){
            $name = $request->getParam('name');
            $organizer = $request->getParam('organizer');
            $date = $request->getParam('date');
            $place = $request->getParam('place');
            if($name == "" && $organizer == "" & $date == "" & $place == ""){
                $this->flash('Veuillez remplir au moins un critère de recherche' , 'error');
                return $this->redirect($response, 'root', 301);
            } else {
                $this->events = new EventsManager();
                $events = $this->events->recherche($name, $organizer, $date, $place);
                $this->render($response, 'pages/searchPage.html.twig', ['events' => $events]);
            }
        }
        else {
            $this->flash('Certains champs n\'ont pas été rempli correctement' , 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'root', 301);
        }
//        $this->events = new EventsManager();
//        $keyWords = $request->getParam('search');
//        $events = $this->events->searchEvents($keyWords);
//        $this->render($response, 'pages/searchPage.html.twig', ['events' => $events]);
    }
    
    public function notFound(RequestInterface $request, ResponseInterface $response) {
        $this->render($response, 'pages/404.html.twig');
    }
     
}