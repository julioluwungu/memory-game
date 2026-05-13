<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit;
}

$idUsuario = $_SESSION['id_usuario'];
$nivel = (int) $_POST['nivel'];
$movimentos = (int) $_POST['movimentos'];
$tempo = (int) $_POST['tempo'];

$sql = "
    SELECT *
    FROM estatisticas_niveis
    WHERE id_usuario = ?
    AND numero_nivel = ?
";

$stmt = $conn->prepare($sql);
$stmt->execute([$idUsuario, $nivel]);
$existente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existente) {
    $melhorMovimento = min($existente['melhor_movimento'], $movimentos);
    $melhorTempo = min($existente['melhor_tempo'], $tempo);

    $sql = "
        UPDATE estatisticas_niveis
        SET
            melhor_movimento = ?,
            melhor_tempo = ?
        WHERE id_usuario = ?
        AND numero_nivel = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$melhorMovimento, $melhorTempo, $idUsuario, $nivel]);

} else {
    $sql = "
        INSERT INTO estatisticas_niveis
        (
            id_usuario,
            numero_nivel,
            melhor_movimento,
            melhor_tempo
        )
        VALUES (?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$idUsuario, $nivel, $movimentos, $tempo]);
}

$proximoNivel = $nivel + 1;

if ($proximoNivel <= 10) {
    $sql = "
        UPDATE usuarios
        SET niveis_desbloqueados = ?
        WHERE id = ?
        AND niveis_desbloqueados < ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$proximoNivel, $idUsuario, $proximoNivel]);
}

echo "success";