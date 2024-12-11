<?php
class Event {
    private $conn;
    private $table = 'events';

    public $id;
    public $event_name;
    public $event_date;
    public $organizer_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try{

            $query = "INSERT INTO " . $this->table . " (event_name, event_date, organizer_id) VALUES (:event_name, :event_date, :organizer_id)";
            $stmt = $this->conn->prepare($query);
    
            $this->event_name = htmlspecialchars(strip_tags($this->event_name));
            $this->event_date = htmlspecialchars(strip_tags($this->event_date));
            $this->organizer_id = htmlspecialchars(strip_tags($this->organizer_id));
    
            $stmt->bindParam(':event_name', $this->event_name);
            $stmt->bindParam(':event_date', $this->event_date);
            $stmt->bindParam(':organizer_id', $this->organizer_id);
    
            return $stmt->execute();
        }
        catch(PDOException $e) {
            echo'erroreee'. $e->getMessage();
            return 0;
        }
    }

    public function getByOrganizer() {
        $query = "SELECT * FROM " . $this->table . " WHERE organizer_id = :organizer_id";
        $stmt = $this->conn->prepare($query);

        $this->organizer_id = htmlspecialchars(strip_tags($this->organizer_id));
        $stmt->bindParam(':organizer_id', $this->organizer_id);

        $stmt->execute();
        return $stmt;
    }
}
?>
