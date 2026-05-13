<?php

$host = "localhost";
$dbname = "memory_game";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    try {
        $tempConn = new PDO("mysql:host=$host", $user, $pass);

        $tempConn->exec("
            CREATE DATABASE IF NOT EXISTS memory_game
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_general_ci
        ");

        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e2) {
        die("Erro ao conectar ao banco de dados.");
    }
}