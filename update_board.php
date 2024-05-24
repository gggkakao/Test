<?php
include 'db.php';

$room_id = $_GET['room_id'];

$stmt = $conn->prepare("SELECT game_state, current_player FROM rooms WHERE room_id = ?");
$stmt->bind_param("s", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

echo json_encode($room);
?>
