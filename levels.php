<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['user_id'])) {

    header("Location: auth.php");

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

$unlockedLevel = $user['unlocked_level'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Níveis</title>

    <style>

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;
        }

        body {

            font-family: Arial;

            background: #f2f2f2;

            display: flex;

            justify-content: center;

            align-items: center;

            min-height: 100vh;
        }

        .container {

            background: white;

            width: 500px;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 0 10px rgba(0,0,0,0.1);

            text-align: center;
        }

        h1 {

            margin-bottom: 25px;
        }

        .levels {

            display: grid;

            grid-template-columns: repeat(5, 1fr);

            gap: 15px;
        }

        .level {

            height: 70px;

            display: flex;

            justify-content: center;

            align-items: center;

            border-radius: 10px;

            font-size: 22px;

            font-weight: bold;

            text-decoration: none;

            color: white;
        }

        .open {

            background: #3498db;

            transition: 0.3s;
        }

        .open:hover {

            background: #2980b9;

            transform: scale(1.05);
        }

        .locked {

            background: #999;

            cursor: not-allowed;
        }

        .logout {

            display: inline-block;

            margin-top: 25px;

            padding: 12px 20px;

            background: #e74c3c;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            transition: 0.3s;
        }

        .logout:hover {

            background: #c0392b;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Escolha um Nível</h1>

    <div class="levels">

        <?php for ($i = 1; $i <= 10; $i++): ?>

            <?php if ($i <= $unlockedLevel): ?>

                <a
                    href="
                        index.php?level=<?= $i ?>
                    "
                    class="level open"
                >
                    <?= $i ?>
                </a>

            <?php else: ?>

                <div class="level locked">
                    🔒
                </div>

            <?php endif; ?>

        <?php endfor; ?>

    </div>

    <a
        href="logout.php"
        class="logout"
    >
        Sair
    </a>

</div>

</body>
</html>