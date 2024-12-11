<?php
require_once '../config/database.php';
require_once '../models/Event.php';

$database = new Database();
$db = $database->connect();

$event = new Event($db);
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new event
    if ($_GET['action'] === 'create') {
        $event->event_name = $data->event_name;
        $event->event_date = $data->event_date;
        $event->organizer_id = $data->organizer_id;

        if ($event->create()) {
            echo json_encode(["message" => "Event created successfully."]);
        } else {
            echo json_encode(["message" => "Failed to create event."]);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all events for a specific organizer
    if ($_GET['action'] === 'list') {
        $event->organizer_id = $_GET['organizer_id'];
        $stmt = $event->getByOrganizer();

        if ($stmt->rowCount() > 0) {
            $events_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $event_item = array(
                    "id" => $id,
                    "event_name" => $event_name,
                    "event_date" => $event_date,
                    "organizer_id" => $organizer_id
                );
                array_push($events_arr, $event_item);
            }
            echo json_encode($events_arr);
        } else {
            echo json_encode(["message" => "No events found."]);
        }
    }
}
?>
