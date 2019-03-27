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
}
