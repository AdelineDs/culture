<?php
namespace App\Models;

class EventsManager extends Model{

    // return all events 
    public function getEvents() {
        $sql = 'SELECT id, name, organizer, DATE_FORMAT(date, "%d/%m/%Y") as date_fr, hour, place, price, description, start_view_date, start_view_hour, end_view_date, end_view_hour, status  FROM '
            . 'events  ORDER BY date DESC';
        $events = $this->executeQuery($sql)->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }
    
    //return an event 
    public function getEvent($idEvent) {
        $sql = 'SELECT id, name, organizer, DATE_FORMAT(date, "%d/%m/%Y") as date_fr, hour, place, price, description '
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
    public function addEvent($name, $organizer, $date, $hour, $place, $price, $description, $startViewDate, $startViewHour, $endViewDate, $endViewHour, $status){
        $sql = 'INSERT INTO events(name, organizer, date, hour, place, price, description, start_view_date, start_view_hour, end_view_date, end_view_hour, status) '
                . 'VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $this->executeQuery($sql, array($name, $organizer, $date, $hour, $place, $price, $description, $startViewDate, $startViewHour, $endViewDate, $endViewHour, $status));
    }
    
    // return events according to key words
    public function searchEvents($keyWords) {
        $words = explode(' ', $keyWords); //separating words from search to each space through explode
        $nbWords = count($words); //counting number of words
        $queryValue = '';
        for($nbWordsLoop = 0; $nbWordsLoop < $nbWords; $nbWordsLoop++){
            $queryValue .= 'OR CONCAT(name, organizer, date, description, place) LIKE \'%' . $words[$nbWordsLoop] . '%\'';
        }
        $queryValue = ltrim($queryValue,'OR champ  LIKE'); //suppression de OR au début de la boucle
        $sql = 'SELECT id, name, organizer, DATE_FORMAT(date, "%d/%m/%Y") as date_fr, hour, place, price, description, start_view_date, start_view_hour, end_view_date, end_view_hour, status  FROM '
            . 'events WHERE ' . $queryValue . 'ORDER BY date ';
        $events = $this->executeQuery($sql)->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }
}