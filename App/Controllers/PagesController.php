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
        $this->render($response, 'pages/home.html.twig', ['events' => $events]);
    }
    
    public function admin(RequestInterface $request, ResponseInterface $response) {
        $this->render($response, 'pages/admin.html.twig');
    }
    
    public function postEvent(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $errors = [];
        
        v::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer un nom d\'évènement';
        v::notEmpty()->validate($request->getParam('organizer')) || $errors['organizer'] = 'Veuillez entrer le nom de l\'organisateur';
        v::date()->validate($request->getParam('date')) || $errors['date'] = 'Veuillez entrer un format de date correct';
        v::notEmpty()->validate($request->getParam('hour')) || $errors['hour'] = 'Veuillez entrer une heure';
        v::notEmpty()->validate($request->getParam('place')) || $errors['place'] = 'Veuillez entrer un lieu';
        v::floatVal()->validate($request->getParam('price')) || $errors['price'] = 'Veuillez entrer unformat de tarif valable (nombre entier ou à virgule)';
        v::notEmpty()->validate($request->getParam('description')) || $errors['description'] = 'Veuillez entrer une description';
        v::date()->validate($request->getParam('start_view_date')) || $errors['start_view_date'] = 'Veuillez entrer un format de date correct';
        v::notEmpty()->validate($request->getParam('start_view_hour')) || $errors['start_view_hour'] = 'Veuillez entrer une heure';
        v::date()->validate($request->getParam('end_view_date')) || $errors['end_view_date'] = 'Veuillez entrer un format de date correct';
        v::notEmpty()->validate($request->getParam('end_view_hour')) || $errors['end_view_hour'] = 'Veuillez entrer une heure';
        v::intVal()->validate($request->getParam('status')) || $errors['end_view_hour'] = 'Erreur avec le statut';

        if (empty($errors)){
            $name = strip_tags($request->getParam('name'));
            $organizer = strip_tags($request->getParam('organizer'));
            $date = strip_tags ($request->getParam('date'));
            $hour = strip_tags($request->getParam('hour'));
            $place = strip_tags($request->getParam('place'));
            $price = strip_tags($request->getParam('price'));
            $description = strip_tags($request->getParam('description'));
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
            return $this->redirect($response, 'admin', 301);
        }
    }
    
    public function searchPage(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $keyWords = $request->getParam('search');
        $events = $this->events->searchEvents($keyWords);
        $this->render($response, 'pages/searchPage.html.twig', ['events' => $events]);
    }
    
    public function getEvent(RequestInterface $request, ResponseInterface $response, $idEvent) {
        $this->events = new EventsManager();
        $event = $this->events->getEvent($idEvent["id"]);
        if ($event == 'error'){
            $message = "Aucun évènement ne correspond à l'identifiant indiquée";
            $this->render($response, 'pages/errorPage.html.twig', ['error' => $message]);
        }
        else {
            $this->render($response, 'pages/eventDetail.html.twig', ['event' => $event]);
        }
    }
    
    public function notFound(RequestInterface $request, ResponseInterface $response) {
        $this->render($response, 'pages/404.html.twig');
    }
    
         public function getContact(RequestInterface $request, ResponseInterface $response) {
         return $this->render($response, 'pages/contact.html.twig');
    }
    
     public function postContact(RequestInterface $request, ResponseInterface $response) {
         $errors = [];
         
         v::email()->validate($request->getParam('email')) || $errors['email'] = 'Votre email n\'est pas valide';
         v::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer votre nom';
         v::notEmpty()->validate($request->getParam('content')) || $errors['content'] = 'Veuillez entrer un message';

         
         if (empty($errors)){
            $message = (new \Swift_Message('Message de contact'))
                ->setFrom([$request->getParam('email') => $request->getParam('name')])
                ->setTo('contact@monsite.fr')
                ->setBody("Un email vous a été envoyé : 
            {$request->getParam('content')}");
            $this->mailer->send($message);
            $this->flash('Votre message a été envoyé avec succès');
            return $this->redirect($response, 'contact');
         } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement' , 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'contact', 301);
         }
     }
   
}