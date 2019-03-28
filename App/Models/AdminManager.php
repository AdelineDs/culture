<?php
namespace App\Models;

class AdminManager extends Model{
    
    //get user connection 
    public function getAdmin($user) {
        $sql = 'SELECT id, user, password FROM users WHERE user= ? ';
        $result = $this->executeQuery($sql, array($user));
        return $result->fetch();
    }

}