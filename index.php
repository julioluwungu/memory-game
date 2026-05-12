<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Jogo da Memória</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h1>Jogo da Memória</h1>

<p>
    Jogador:
    <?= $_SESSION['username'] ?>
</p>

<div id="game-board"></div>

<p>Movimentos: <span id="moves">0</span></p>

<div class="buttons">

    <button
        class="restart-btn"
        onclick="restartGame()"
    >
        Reiniciar
    </button>

    <a
        href="logout.php"
        class="logout-btn"
    >
        Sair
    </a>

</div>

<script src="assets/js/game.js"></script>

</body>
</html>