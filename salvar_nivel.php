<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {

    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    exit;
}

$userId = $_SESSION['user_id'];

$level = (int) $_POST['level'];

$moves = (int) $_POST['moves'];

$time = (int) $_POST['time'];

$sql = "
    SELECT *
    FROM level_stats
    WHERE user_id = ?
    AND level_number = ?
";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $userId,
    $level
]);

$existing =
    $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {

    $bestMoves =
        min(
            $existing['best_moves'],
            $moves
        );

    $bestTime =
        min(
            $existing['best_time'],
            $time
        );

    $sql = "
        UPDATE level_stats
        SET
            best_moves = ?,
            best_time = ?
        WHERE user_id = ?
        AND level_number = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $bestMoves,
        $bestTime,
        $userId,
        $level
    ]);

} else {

    $sql = "
        INSERT INTO level_stats
        (
            user_id,
            level_number,
            best_moves,
            best_time
        )
        VALUES (?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $userId,
        $level,
        $moves,
        $time
    ]);
}

$nextLevel = $level + 1;

if ($nextLevel <= 10) {

    $sql = "
        UPDATE users
        SET unlocked_level = ?
        WHERE id = ?
        AND unlocked_level < ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $nextLevel,
        $userId,
        $nextLevel
    ]);
}

echo "success";