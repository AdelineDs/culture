<?php
namespace App\Models;

class EventsManager extends Model{

    // return all events 
    public function getEvents($page) {
        $start = ($page-1)*10;
        $sql = 'SELECT id, name, organizer, dateEvent, hour, place, price, description, start_view_date, end_view_date, status  FROM '
            . 'events  ORDER BY dateEvent LIMIT 10 OFFSET '.$start. '';
        $events = $this->executeQuery($sql)->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }
    
    //count the number of events
    public function getNbPages(){
        $sql = 'SELECT COUNT(*) AS nbEvents FROM events';
        $data = $this->executeQuery($sql);
        $nbEvent = $data->fetchColumn();
        $nbPages = ceil($nbEvent/10);
        return $nbPages;
    }
    
    //return an event 
    public function getEvent($idEvent) {
        $sql = 'SELECT id, name, organizer, dateEvent, hour, place, price, description, image, start_view_date, end_view_date, status '
                . 'FROM events WHERE id= ? ';
        $event= $this->executeQuery($sql, array($idEvent));
        if ($event->rowCount() == 1) {
            return $event->fetch(); //access to the first line of results
        }
        else {
            $message = "error";
            return $message;
            //throw new \Exception("Aucun évènement ne correspond à l'identifiant '$idEvent'");
        }
    }
    
    // insert new event in bdd 
    public function addEvent($name, $organizer, $date, $hour, $place, $price, $description, $image, $startViewDate, $endViewDate, $status){
        $sql = 'INSERT INTO events(name, organizer, dateEvent, hour, place, price, description, image, start_view_date,  end_view_date, status) '
                . 'VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $this->executeQuery($sql, array($name, $organizer, $date, $hour, $place, $price, $description, $image, $startViewDate, $endViewDate,  $status));
    }
    
    // return events according to key words
    public function searchEvents($keyWords) {
        $words = explode(' ', $keyWords); //separating words from search to each space through explode
        $nbWords = count($words); //counting number of words
        $queryValue = '';
        for($nbWordsLoop = 0; $nbWordsLoop < $nbWords; $nbWordsLoop++){
            $queryValue .= 'OR CONCAT(name, organizer, dateEvent, description, place) LIKE \'%' . $words[$nbWordsLoop] . '%\'';
        }
        $queryValue = ltrim($queryValue,'OR champ  LIKE'); //suppression de OR au début de la boucle
        $sql = 'SELECT id, name, organizer, dateEvent, hour, place, price, description, start_view_date, end_view_date,  status  FROM '
            . 'events WHERE ' . $queryValue . 'ORDER BY dateEvent ';
        $events = $this->executeQuery($sql)->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }
    
    // return events according to key words
    public function recherche($name, $organizer, $date, $place) {
        if ($name == ''){
            $nameQuery = 'AND name IS NOT NULL';
        }else{
            $nameQuery = 'AND name LIKE \'%' . $name . '%\'';
        }
        if ($organizer == ''){
            $organizerQuery = 'AND organizer IS NOT NULL';
        }else{
            $organizerQuery = 'AND organizer LIKE \'%' . $organizer . '%\'';
        }
        if ($date == ''){
            $dateQuery = 'dateEvent IS NOT NULL';
        }else{
            $dateQuery= 'DATEDIFF(dateEvent, \''. $date .'\')=0';
        }
         if ($place == ''){
            $placeQuery = 'AND place IS NOT NULL';
        }else{
            $placeQuery= 'AND place LIKE \'%' . $place . '%\'';
        }
        $sql = 'SELECT id, name, organizer, dateEvent, hour, place, price, description, start_view_date, end_view_date,  status  FROM '
            . 'events WHERE ' . $dateQuery . ' ' . $nameQuery . ' ' . $organizerQuery . ' ' . $placeQuery .  ' ORDER BY dateEvent ';
        $events = $this->executeQuery($sql)->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }
    
     //update an event
    public function updateEvent($id, $name, $organizer, $date, $hour, $place, $price, $description, $image, $startViewDate, $endViewDate, $status){
        $sql = 'UPDATE events SET name= ?, organizer=?, dateEvent=?, hour=?, place=?, price=?, description=?, image=?, start_view_date=?, end_view_date=?, status=? WHERE id=?';
        $this->executeQuery($sql, array($name, $organizer, $date, $hour, $place, $price, $description, $image, $startViewDate, $endViewDate, $status, $id));
    }
    
    //delete an event in database
    public function deleteEvent($id) {
        $sql = 'DELETE FROM events WHERE id= ?';
        $this->executeQuery($sql, array($id));
    }
}