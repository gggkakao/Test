document.addEventListener('DOMContentLoaded', () => {
    const board = document.getElementById('game-board');
    const currentPlayerDisplay = document.querySelector('p:nth-of-type(3)');

    function updateBoard() {
        fetch('update_board.php?room_id=' + roomId)
            .then(response => response.json())
            .then(data => {
                for (let i = 0; i < 9; i++) {
                    const cell = board.children[i];
                    cell.textContent = data.game_state[i];
                    cell.removeEventListener('click', onCellClick);
                    if (data.game_state[i] === '-' && data.current_player == playerNumber) {
                        cell.addEventListener('click', onCellClick);
                    }
                }
                currentPlayerDisplay.textContent = "Current Player: " + (data.current_player == 1 ? player1 : player2);
            });
    }

    function onCellClick(e) {
        const cell = e.target;
        const index = cell.dataset.index;
        fetch('make_move.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ room_id: roomId, index: index })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateBoard();
            } else {
                alert(data.message);
            }
        });
    }

    setInterval(updateBoard, 2000);
    updateBoard();
});
