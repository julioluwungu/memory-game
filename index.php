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

<h2>
    Nível
    <span id="level">
        <?= 1 ?>
    </span>
</h2>

<div id="game-board"></div>

<div class="info">

    <p>
        Movimentos:
        <span id="moves">
            0
        </span>
    </p>

    <p>
        Tempo:
        <span id="timer">
            0
        </span>
        s
    </p>

</div>

<div class="buttons">

    <button
        id="prev-btn"
        class="level-btn"
        onclick="previousLevel()"
    >
        Anterior
    </button>

    <button
        id="next-btn"
        class="level-btn"
        onclick="nextLevel()"
    >
        Seguinte
    </button>

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