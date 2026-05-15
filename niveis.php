<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: auth.php");
    exit;
}

$idUsuario = $_SESSION['id_usuario'];

$sql = "
    SELECT niveis_desbloqueados
    FROM usuarios
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$niveisDesbloqueados = $usuario['niveis_desbloqueados'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .niveis {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .nivel {
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

        .aberto {
            background-color: #7B2FF7;
            transition: 0.3s;
        }

        .aberto:hover {
            background-color: #9B4DFF;
            transform: scale(1.05);
        }
        .bloqueado {
            background: #999;
            cursor: not-allowed;
        }
        .sair {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }
        .sair:hover {
            background: #c0392b;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .niveis {
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
            }

            .nivel {
                height: 60px;
                font-size: 18px;
            }
        }

    </style>
</head>
<body>
<div class="container">
    <h1>Escolha um Nível</h1>
    <div class="niveis">
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <?php if ($i <= $niveisDesbloqueados): ?>
                <a href="index.php?nivel=<?= $i ?>" class="nivel aberto"><?= $i ?></a>
            <?php else: ?>
                <div class="nivel bloqueado">🔒</div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <a href="sair.php" class="sair">Sair</a>
</div>
</body>
</html>