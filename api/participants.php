<?php
require_once '../config/database.php';
require_once '../models/Participant.php';

$database = new Database();
$db = $database->connect();

$participant = new Participant($db);
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add participant to an event
    if ($_GET['action'] === 'add') {
        $participant->event_id = $data->event_id;
        $participant->name = $data->name;
        $participant->email = $data->email;

        if ($participant->create()) {
            echo json_encode(["message" => "Participant added successfully."]);
        } else {
            echo json_encode(["message" => "Failed to add participant."]);
        }
    }

    // Assign a gift to a participant
    if ($_GET['action'] === 'assign') {
        $participant->id = $data->id;
        $participant->assigned_to = $data->assigned_to;

        if ($participant->assignGift($data->assigned_to)) {
            echo json_encode(["message" => "Gift assignment successful."]);
        } else {
            echo json_encode(["message" => "Failed to assign gift."]);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get participants for a specific event
    if ($_GET['action'] === 'list') {
        $participant->event_id = $_GET['event_id'];
        $stmt = $participant->getByEvent();
        
        if ($stmt->rowCount() > 0) {
            $participants_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $participant_item = array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "assigned_to" => $assigned_to
                );
                array_push($participants_arr, $participant_item);
            }
            echo json_encode($participants_arr);
        } else {
            echo json_encode(["message" => "No participants found."]);
        }
    }
}
?>
