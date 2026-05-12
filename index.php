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

<a href="logout.php">
    Sair
</a>

<div id="game-board"></div>

<p>Movimentos: <span id="moves">0</span></p>

<button onclick="restartGame()">
    Reiniciar
</button>

<script src="assets/js/game.js"></script>

</body>
</html>