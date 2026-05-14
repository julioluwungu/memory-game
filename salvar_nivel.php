<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['id_usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
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
$estatistica = $stmt->fetch(PDO::FETCH_ASSOC);

if ($estatistica) {
    $sql = "
        UPDATE estatisticas_niveis
        SET
            melhor_movimento = ?,
            melhor_tempo = ?
        WHERE id_usuario = ?
        AND numero_nivel = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$movimentos, $tempo, $idUsuario, $nivel]);

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

$sql = "
    SELECT niveis_desbloqueados
    FROM usuarios
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($proximoNivel > $usuario['niveis_desbloqueados'] && $proximoNivel <= 10) {

    $sql = "
        UPDATE usuarios
        SET niveis_desbloqueados = ?
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$proximoNivel, $idUsuario]);
}

echo "sucesso";