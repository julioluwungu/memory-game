<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth.php");
    exit;
}

if (!isset($_GET['nivel'])) {
    header("Location: niveis.php");
    exit;
}

$nivel = (int) $_GET['nivel'];

if ($nivel < 1 || $nivel > 10) {
    header("Location: niveis.php");
    exit;
}

$userId = $_SESSION['id_usuario'];

$sql = "
    SELECT niveis_desbloqueados
    FROM usuarios
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($nivel > $user['niveis_desbloqueados']) {
    header("Location: niveis.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Memória</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Jogo da Memória</h1>
    <h2>Nível <span id="numero-nivel"><?= $nivel ?></span></h2>

    <div id="tabuleiro"></div>
    <div class="info">
        <p>Movimentos: <span id="movimentos">0</span></p>
        <p>Tempo: <span id="timer">0</span></p>
    </div>

    <div class="botoes">
        <?php if ($nivel > 1): ?>
            <button id="anterior-btn" class="nivel-btn" onclick="nivelAnterior()">
                <img src="assets/images/previous.png" alt="Anterior">
            </button>

        <?php endif; ?>

        <button id="niveis-btn" class="niveis-btn" onclick="verNiveis()">
            <img src="assets/images/menu.png" alt="Menu">
        </button>

        <?php if ($nivel < 10): ?>
            <button id="proximo-btn" class="nivel-btn" onclick="proximoNivel()">
                <img src="assets/images/next.png" alt="Próximo">
            </button>

        <?php endif; ?>

        <button class="reiniciar-btn" onclick="reiniciarJogo()">
            <img src="assets/images/restart.png" alt="Reiniciar">
        </button>

        <a href="sair.php" class="sair-btn">
            <img src="assets/images/leave.png" alt="Sair">
        </a>
    </div>
</div>

<script>

const NIVEL_ATUAL = <?= $nivel ?>;
const NIVEL_DESBLOQUEADO = <?= $user['niveis_desbloqueados'] ?>;

</script>

<script src="assets/js/jogo.js"></script>

</body>
</html>