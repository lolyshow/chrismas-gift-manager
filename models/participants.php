<?php
class Participant {
    private $conn;
    private $table = 'participants';

    public $id;
    public $event_id;
    public $name;
    public $email;
    public $assigned_to;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a participant and add them to an event
    public function create() {
        $query = "INSERT INTO " . $this->table . " (event_id, name, email) VALUES (:event_id, :name, :email)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->event_id = htmlspecialchars(strip_tags($this->event_id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':event_id', $this->event_id);

        return $stmt->execute();
    }

    // Retrieve participants for a specific event
    public function getByEvent() {
        $query = "SELECT * FROM " . $this->table . " WHERE event_id = :event_id";
        $stmt = $this->conn->prepare($query);

        $this->event_id = htmlspecialchars(strip_tags($this->event_id));
        $stmt->bindParam(':event_id', $this->event_id);

        $stmt->execute();
        return $stmt;
    }

    // Assign a gift to a participant
    public function assignGift($assignedTo) {
        $query = "UPDATE " . $this->table . " SET assigned_to = :assigned_to WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->assigned_to = htmlspecialchars(strip_tags($assignedTo));
        $stmt->bindParam(':assigned_to', $this->assigned_to);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
?>
