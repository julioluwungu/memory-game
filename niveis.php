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

            background: linear-gradient(to bottom right, #050816, #14052E);

            display: flex;

            justify-content: center;

            align-items: center;

            min-height: 100vh;
        }

        .container {

            background-color: #090D1F;
            
            border: 1px solid #8A3FFC;

            width: 500px;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 0 30px rgba(138, 63, 252, 0.25);

            text-align: center;
        }

        h1 {

            color: white;
        
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

            background-color: #7B2FF7;

            transition: 0.3s;
        }

        .open:hover {

            background-color: #9B4DFF;

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