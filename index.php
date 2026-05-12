<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {

    header("Location: auth.php");

    exit;
}

if (!isset($_GET['level'])) {

    header("Location: levels.php");

    exit;
}

$level = (int) $_GET['level'];

if ($level < 1 || $level > 10) {

    header("Location: levels.php");

    exit;
}

$userId = $_SESSION['user_id'];

$sql = "
    SELECT unlocked_level
    FROM users
    WHERE id = ?
";

$stmt = $conn->prepare($sql);

$stmt->execute([$userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($level > $user['unlocked_level']) {

    header("Location: levels.php");

    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Jogo da Memória</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body>

<div class="container">

    <h1>Jogo da Memória</h1>

    <h2>

        Nível

        <span id="level-number">

            <?= $level ?>

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

            </span>s

        </p>

    </div>

    <div class="buttons">

        <?php if ($level > 1): ?>

            <button
                class="level-btn"
                onclick="previousLevel()"
            >
                Anterior
            </button>

        <?php endif; ?>

        <?php if ($level < 10): ?>

            <button
                class="level-btn"
                onclick="nextLevel()"
            >
                Seguinte
            </button>

        <?php endif; ?>

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

</div>

<script>

const CURRENT_LEVEL =
    <?= $level ?>;

</script>

<script src="assets/js/game.js"></script>

</body>
</html>