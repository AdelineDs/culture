<?php
namespace App\Controllers;

use Respect\Validation\Validator as v;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class  ContactController extends Controller {
    
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