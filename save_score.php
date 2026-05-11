<?php
session_start();

require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];

    $moves = $_POST['moves'];

    $sql = "
        INSERT INTO scores
        (user_id, moves, time_seconds)
        VALUES (?, ?, 0)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $user_id,
        $moves
    ]);
}