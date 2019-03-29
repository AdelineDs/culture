<?php
namespace App\Controllers;

use \App\Models\EventsManager;
use Respect\Validation\Validator as v;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class  EventsController extends Controller {
    
    private $events;
    
    public function validateParams(RequestInterface $request){
        $errors = [];
        
        v::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer un nom d\'évènement';
        v::notEmpty()->validate($request->getParam('organizer')) || $errors['organizer'] = 'Veuillez entrer le nom de l\'organisateur';
        v::date()->validate($request->getParam('date')) || $errors['date'] = 'Veuillez entrer un format de date correct';
        v::notEmpty()->validate($request->getParam('hour')) || $errors['hour'] = 'Veuillez entrer une heure';
        v::notEmpty()->validate($request->getParam('place')) || $errors['place'] = 'Veuillez entrer un lieu';
        v::floatVal()->validate($request->getParam('price')) || $errors['price'] = 'Veuillez entrer un format de tarif valable (nombre entier ou à virgule)';
        v::notEmpty()->validate($request->getParam('description')) || $errors['description'] = 'Veuillez entrer une description';
        v::date()->validate($request->getParam('start_view_date')) || $errors['start_view_date'] = 'Veuillez entrer un format de date correct';
        v::date()->validate($request->getParam('end_view_date')) || $errors['end_view_date'] = 'Veuillez entrer un format de date correct';
        v::intVal()->validate($request->getParam('status')) || $errors['end_view_hour'] = 'Erreur avec le statut';
        
        return $errors;
    }
    
    public function adminEvent(RequestInterface $request, ResponseInterface $response, $idEvent = []) {
        if(isset($_SESSION['id']) && isset($_SESSION['user'])) {
            if($idEvent !== []){
                $this->events = new EventsManager();
                $event = $this->events->getEvent($idEvent["id"]);
                return $this->render($response, 'pages/admin.html.twig', ['session' => $_SESSION, 'event' => $event]);
            }
            return $this->render($response, 'pages/admin.html.twig', ['session' => $_SESSION]);
        }
       return $this->render($response, 'pages/admin.html.twig');
    }
    
    public function postEvent(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $errors = $this->validateParams($request);

        if (empty($errors)){
            $name = strip_tags($request->getParam('name'));
            $organizer = strip_tags($request->getParam('organizer'));
            $date = strip_tags ($request->getParam('date'));
            $hour = strip_tags($request->getParam('hour'));
            $place = strip_tags($request->getParam('place'));
            $price = strip_tags($request->getParam('price'));
            $description = strip_tags($request->getParam('description'));
            $startViewDate = $request->getParam('start_view_date');
            $endViewDate = $request->getParam('end_view_date');
            $status = $request->getParam('status');
            $this->events->addEvent($name, $organizer, $date, $hour, $place, $price, $description, $startViewDate, $endViewDate, $status);
            $this->flash('Le nouvel évènement a été enregistré avec succès');
            return $this->redirect($response, 'admin');
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement' , 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'admin', 301);
        }
    }
    
    public function updateEvent(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        
        $errors = $this->validateParams($request);
        v::intVal()->validate($request->getParam('id')) || $errors['id'] = 'Erreur avec l\'identifiant';

        if (empty($errors)){
            $id = $request->getParam('id');
            $name = strip_tags($request->getParam('name'));
            $organizer = strip_tags($request->getParam('organizer'));
            $date = strip_tags ($request->getParam('date'));
            $hour = strip_tags($request->getParam('hour'));
            $place = strip_tags($request->getParam('place'));
            $price = strip_tags($request->getParam('price'));
            $description = strip_tags($request->getParam('description'));
            $startViewDate = $request->getParam('start_view_date');
            $endViewDate = $request->getParam('end_view_date');
            $status = $request->getParam('status');
            $this->events->updateEvent($id, $name, $organizer, $date, $hour, $place, $price, $description, $startViewDate, $endViewDate, $status);
            $this->flash('L\'évènement a été mis à jour avec succès');
            return $this->redirect($response, 'admin');
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement' , 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'admin', 301);
        }
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

    public function deleteEvent(RequestInterface $request, ResponseInterface $response) {
        $this->events = new EventsManager();
        $errors = [];

        v::intVal()->validate($request->getParam('id')) || $errors['id'] = 'Erreur avec l\'identifiant';

        if (empty($errors)){
            $id = $request->getParam('id');
            $this->events->deleteEvent($id);
            $this->flash('L\'évènement a été supprimer avec succès');
            return $this->redirect($response, 'admin');
        } else {
            $this->flash('Une erreur s\'est produite avec l\'identifiant' , 'error');
            return $this->redirect($response, 'admin', 301);
        }
    }
     
      public function getAdmin(RequestInterface $request, ResponseInterface $response) {
          if(isset($_SESSION['id']) && isset($_SESSION['user'])) {
              return $this->render($response, 'pages/connectAdmin.html.twig', ['session' => $_SESSION]);
          }
          return $this->render($response, 'pages/connectAdmin.html.twig');
      }
    
   
}
