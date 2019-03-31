<?php
namespace App\Controllers;

use \App\Models\AdminManager;
use Respect\Validation\Validator as v;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class  AdminController extends Controller {
    
    public function getAdmin(RequestInterface $request, ResponseInterface $response) {
          if(isset($_SESSION['id']) && isset($_SESSION['user'])) {
              return $this->render($response, 'connectAdmin.html.twig', ['session' => $_SESSION]);
          }
          return $this->render($response, 'connectAdmin.html.twig');
      }
    
     public function connection(RequestInterface $request, ResponseInterface $response) {
         $this->admin = new AdminManager();
         $errors = [];
        
        v::notEmpty()->validate($request->getParam('user')) || $errors['user'] = 'Veuillez entrer votre nom d\'utilisateur';
        v::notEmpty()->validate($request->getParam('pass')) || $errors['pass'] = 'Veuillez entrer votre mot de passe';
        
        if (empty($errors)){
            $user = strip_tags($request->getParam('user'));
            $pass = $request->getParam('pass');
            $adminUser = $this->admin->getAdmin($user);
            $isPassCorrect = password_verify($pass, $adminUser['password']);
            if (!$isPassCorrect){
                $this->flash('Certains champs n\'ont pas été rempli correctement' , 'error');
                return $this->redirect($response, 'connectAdmin', 301);
            }
            else{
                $idUser = $adminUser['id'];
                $_SESSION['id'] = $idUser;
                $_SESSION['user'] = $user;
                $this->render($response, 'admin.html.twig', [ 'session' => $_SESSION]);
            }
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement' , 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'connectAdmin', 301);
        }
         
    }
    
    public function disconnect(RequestInterface $request, ResponseInterface $response) {
        if(isset($_SESSION['id']) && isset($_SESSION['user'])) {
            session_unset();
            session_destroy();
            return $this->render($response, 'admin.html.twig');
        }
       return $this->render($response, 'admin.html.twig');
    }
   
}