<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $player2 = $_POST['player2'];

    $stmt = $conn->prepare("UPDATE rooms SET player2 = ? WHERE room_id = ? AND player2 IS NULL");
    $stmt->bind_param("ss", $player2, $room_id);
    $stmt->execute();

    session_start();
    $_SESSION['player_name'] = $player2;
    $_SESSION['player_number'] = 2;

    header("Location: game.php?room_id=" . $room_id);
    exit();
}
?>
