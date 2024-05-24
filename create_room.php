<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = uniqid();
    $player1 = $_POST['player1'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_id, player1) VALUES (?, ?)");
    $stmt->bind_param("ss", $room_id, $player1);
    $stmt->execute();

    session_start();
    $_SESSION['player_name'] = $player1;
    $_SESSION['player_number'] = 1;

    header("Location: game.php?room_id=" . $room_id);
    exit();
}
?>
