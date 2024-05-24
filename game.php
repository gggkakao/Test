<?php
include 'db.php';
session_start();

$room_id = $_GET['room_id'];

$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param("s", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    die("Room not found!");
}

$player1 = $room['player1'];
$player2 = $room['player2'];
$game_state = $room['game_state'];
$current_player = $room['current_player'];

if (!isset($_SESSION['player_name'])) {
    if (isset($_GET['player_name'])) {
        $_SESSION['player_name'] = $_GET['player_name'];
    } else {
        die("Player name not set!");
    }
}

if (!isset($_SESSION['player_number'])) {
    if ($_SESSION['player_name'] == $player1) {
        $_SESSION['player_number'] = 1;
    } elseif ($_SESSION['player_name'] == $player2) {
        $_SESSION['player_number'] = 2;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tic-Tac-Toe</title>
</head>
<body>
    <h1>Tic-Tac-Toe: Room <?php echo htmlspecialchars($room_id); ?></h1>
    <p>Player 1: <?php echo htmlspecialchars($player1); ?> (X)</p>
    <p>Player 2: <?php echo htmlspecialchars($player2); ?> (O)</p>
    <p>Current Player: <?php echo $current_player == 1 ? htmlspecialchars($player1) : htmlspecialchars($player2); ?></p>
    <div id="game-board">
        <?php
        for ($i = 0; $i < 9; $i++) {
            $symbol = $game_state[$i];
            echo "<div data-index='$i'>$symbol</div>";
        }
        ?>
    </div>
    <script>
        const roomId = "<?php echo $room_id; ?>";
        const playerNumber = <?php echo $_SESSION['player_number']; ?>;
        const player1 = "<?php echo $player1; ?>";
        const player2 = "<?php echo $player2; ?>";
    </script>
    <script src="game.js"></script>
</body>
</html>
