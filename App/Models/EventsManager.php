<?php
namespace App\Models;

class EventsManager extends Model{

    // return all events 
    public function getEvents() {
        $sql = 'SELECT id, name, organizer, date, place, price, description, start_view_date, end_view_date, status  FROM '
            . 'events  ORDER BY date DESC';
        $events = $this->executeQuery($sql)->fetchAll(\PDO::FETCH_OBJ);
        return $events;
    }
}
