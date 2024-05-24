<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$room_id = $data['room_id'];
$index = $data['index'];

$stmt = $conn->prepare("SELECT game_state, current_player FROM rooms WHERE room_id = ?");
$stmt->bind_param("s", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

$game_state = $room['game_state'];
$current_player = $room['current_player'];

session_start();
$player_number = $_SESSION['player_number'];

if ($player_number != $current_player) {
    echo json_encode(['success' => false, 'message' => 'Not your turn']);
    exit();
}

if ($game_state[$index] !== '-') {
    echo json_encode(['success' => false, 'message' => 'Cell is already taken']);
    exit();
}

$symbol = $current_player == 1 ? 'X' : 'O';
$game_state[$index] = $symbol;
$current_player = $current_player == 1 ? 2 : 1;

$stmt = $conn->prepare("UPDATE rooms SET game_state = ?, current_player = ? WHERE room_id = ?");
$stmt->bind_param("sis", $game_state, $current_player, $room_id);
$stmt->execute();

echo json_encode(['success' => true]);
?>
