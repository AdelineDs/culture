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
        $this->render($response, 'pages/home.twig', ['events' => $events]);
    }
    
    public function admin(RequestInterface $request, ResponseInterface $response) {
        $this->render($response, 'pages/admin.twig');
    }
    
    public function postEvent(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $errors = [];
        
        v::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer un nom d\'évènement';
        v::notEmpty()->validate($request->getParam('organizer')) || $errors['organizer'] = 'Veuillez entrer le nom de l\'organisateur';
        v::date()->validate($request->getParam('date')) || $errors['date'] = 'Veuillez entrer un format de date correct';
        v::notEmpty()->validate($request->getParam('hour')) || $errors['hour'] = 'Veuillez entrer une heure';
        v::notEmpty()->validate($request->getParam('place')) || $errors['place'] = 'Veuillez entrer un lieu';
        v::floatVal()->validate($request->getParam('price')) || $errors['price'] = 'Veuillez entrer un tarif';
        v::notEmpty()->validate($request->getParam('description')) || $errors['description'] = 'Veuillez entrer une description';
        v::date()->validate($request->getParam('start_view_date')) || $errors['start_view_date'] = 'Veuillez entrer un format de date correct';
        v::notEmpty()->validate($request->getParam('start_view_hour')) || $errors['start_view_hour'] = 'Veuillez entrer une heure';
        v::date()->validate($request->getParam('end_view_date')) || $errors['end_view_date'] = 'Veuillez entrer un format de date correct';
        v::notEmpty()->validate($request->getParam('end_view_hour')) || $errors['end_view_hour'] = 'Veuillez entrer une heure';
        v::intVal()->validate($request->getParam('status')) || $errors['end_view_hour'] = 'Erreur avec le statut';

        if (empty($errors)){
            $name = $request->getParam('name');
            $organizer = $request->getParam('organizer');
            $date = $request->getParam('date');
            $hour = $request->getParam('hour');
            $place = $request->getParam('place');
            $price = $request->getParam('price');
            $description = $request->getParam('description');
            $startViewDate = $request->getParam('start_view_date');
            $startViewHour = $request->getParam('start_view_hour');
            $endViewDate = $request->getParam('end_view_date');
            $endViewHour = $request->getParam('end_view_hour');
            $status = $request->getParam('status');
            $this->events->addEvent($name, $organizer, $date, $hour, $place, $price, $description, $startViewDate, $startViewHour, $endViewDate, $endViewHour, $status);
            $this->flash('Le nouvel évènement a été enregistré avec succès');
            return $this->redirect($response, 'admin');
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement' , 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'admin', 400);
        }
    }
    
    public function searchPage(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $keyWords = $request->getParam('search');
        $events = $this->events->searchEvents($keyWords);
        $this->render($response, 'pages/searchPage.twig', ['events' => $events]);
    }
   
}