<?php

$host = "localhost";
$user = "root";
$pass = "";

$dbname = "memory_game";

try {

    $conn = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $pass
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $conn->exec("
        CREATE DATABASE IF NOT EXISTS $dbname
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci
    ");

    $conn->exec("
        USE $dbname
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS users (

            id INT AUTO_INCREMENT PRIMARY KEY,

            username VARCHAR(50)
            NOT NULL UNIQUE,

            password VARCHAR(255)
            NOT NULL
        )
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS scores (

            id INT AUTO_INCREMENT PRIMARY KEY,

            user_id INT,

            moves INT,

            time_seconds INT,

            created_at TIMESTAMP
            DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE CASCADE
        )
    ");

} catch (PDOException $e) {

    die(
        "Erro na conexão: " .
        $e->getMessage()
    );
}

?>