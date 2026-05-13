<?php

$host = "localhost";
$dbname = "memory_game";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("
        CREATE DATABASE IF NOT EXISTS $dbname
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_general_ci
    ");

    $conn->exec("USE $dbname");

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
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
            ON DELETE CASCADE
        )
    ");

} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}