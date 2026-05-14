<?php

$isLocal = $_SERVER['SERVER_NAME'] === 'localhost';

$localConfig = [
    "host" => "localhost",
    "dbname" => "memory_game",
    "user" => "root",
    "pass" => ""
];

$prodConfig = [
    "host" => "sqlXXX.infinityfree.com",
    "dbname" => "if0_00000000_memory_game",
    "user" => "if0_00000000",
    "pass" => "SUA_SENHA"
];

$config = $isLocal ? $localConfig : $prodConfig;

$host = $config['host'];
$dbname = $config['dbname'];
$user = $config['user'];
$pass = $config['pass'];

try {
    if ($isLocal) {
        $conn = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conn->exec("
            CREATE DATABASE IF NOT EXISTS $dbname
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_general_ci
        ");

        $conn->exec("USE $dbname");

    } else {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    $conn->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(100) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            niveis_desbloqueados INT DEFAULT 1
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS estatisticas_niveis (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_usuario INT NOT NULL,
            numero_nivel INT NOT NULL,
            melhor_movimento INT DEFAULT 0,
            melhor_tempo INT DEFAULT 0,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
        )
    ");

} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}