<?php

session_start();

require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (
        empty($username) ||
        empty($password)
    ) {

        $error = "Preencha todos os campos.";

    } else {

        $sql = "
            SELECT id
            FROM users
            WHERE username = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([$username]);

        $user = $stmt->fetch();

        if ($user) {

            $error = "Esse usuário já existe.";

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "
                INSERT INTO users
                (username, password)
                VALUES (?, ?)
            ";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                $username,
                $hashedPassword
            ]);

            $_SESSION['user_id'] = $conn->lastInsertId();

            $_SESSION['username'] = $username;

            header("Location: index.php");

            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="UTF-8">

    <title>Cadastro</title>

    <style>

        body {
            font-family: Arial;
            background: #f2f2f2;

            display: flex;
            justify-content: center;
            align-items: center;

            height: 100vh;
        }

        .container {

            background: white;

            padding: 30px;

            border-radius: 10px;

            width: 300px;

            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
        }

        input {

            width: 100%;

            padding: 10px;

            margin-bottom: 15px;

            border: 1px solid #ccc;

            border-radius: 5px;

            box-sizing: border-box;
        }

        button {

            width: 100%;

            padding: 10px;

            border: none;

            background: #3498db;

            color: white;

            border-radius: 5px;

            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }

        .error {

            color: red;

            margin-bottom: 15px;

            text-align: center;
        }

        .link {

            text-align: center;

            margin-top: 15px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Cadastrar</h1>

    <?php if (isset($error)): ?>

        <div class="error">
            <?= $error ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <input
            type="text"
            name="username"
            placeholder="Usuário"
        >

        <input
            type="password"
            name="password"
            placeholder="Senha"
        >

        <button type="submit">
            Criar Conta
        </button>

    </form>

    <div class="link">

        <a href="login.php">
            Já tenho conta
        </a>

    </div>

</div>

</body>
</html>