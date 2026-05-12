<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['user_id'])) {

    header("Location: index.php");

    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    $username = trim($_POST['username']);

    $password = trim($_POST['password']);

    if (
        empty($username) ||
        empty($password)
    ) {

        $error = "Preencha todos os campos.";

    } else {

        if ($action == "register") {

            $sql = "
                SELECT id
                FROM users
                WHERE username = ?
            ";

            $stmt = $conn->prepare($sql);

            $stmt->execute([$username]);

            $user = $stmt->fetch();

            if ($user) {

                $error = "Usuário já existe.";

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

                $_SESSION['user_id'] =
                    $conn->lastInsertId();

                $_SESSION['username'] =
                    $username;

                header("Location: index.php");

                exit;
            }
        }

        if ($action == "login") {

            $sql = "
                SELECT *
                FROM users
                WHERE username = ?
            ";

            $stmt = $conn->prepare($sql);

            $stmt->execute([$username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                $user &&
                password_verify(
                    $password,
                    $user['password']
                )
            ) {

                $_SESSION['user_id'] =
                    $user['id'];

                $_SESSION['username'] =
                    $user['username'];

                header("Location: index.php");

                exit;

            } else {

                $error =
                    "Usuário ou senha incorretos.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Jogo da Memória</title>

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

            height: 100vh;
        }

        .container {

            background: white;

            width: 350px;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {

            text-align: center;

            margin-bottom: 20px;
        }

        .tabs {

            display: flex;

            margin-bottom: 20px;
        }

        .tabs button {

            flex: 1;

            padding: 10px;

            border: none;

            cursor: pointer;

            background: #ddd;

            font-weight: bold;
        }

        .tabs button.active {

            background: #3498db;

            color: white;
        }

        form {

            display: none;

            flex-direction: column;
        }

        form.active {
            display: flex;
        }

        input {

            padding: 10px;

            margin-bottom: 15px;

            border: 1px solid #ccc;

            border-radius: 5px;
        }

        button.submit {

            padding: 10px;

            border: none;

            background: #3498db;

            color: white;

            border-radius: 5px;

            cursor: pointer;
        }

        button.submit:hover {

            background: #2980b9;
        }

        .error {

            color: red;

            text-align: center;

            margin-bottom: 15px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Jogo da Memória</h1>

    <?php if ($error): ?>

        <div class="error">
            <?= $error ?>
        </div>

    <?php endif; ?>

    <div class="tabs">

        <button
            id="loginTab"
            class="active"
            onclick="showForm('login')"
        >
            Login
        </button>

        <button
            id="registerTab"
            onclick="showForm('register')"
        >
            Registrar
        </button>

    </div>

    <form
        id="loginForm"
        class="active"
        method="POST"
    >

        <input
            type="hidden"
            name="action"
            value="login"
        >

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

        <button
            type="submit"
            class="submit"
        >
            Entrar
        </button>

    </form>

    <form
        id="registerForm"
        method="POST"
    >

        <input
            type="hidden"
            name="action"
            value="register"
        >

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

        <button
            type="submit"
            class="submit"
        >
            Registrar
        </button>

    </form>

</div>

<script>

function showForm(type) {

    const loginForm =
        document.getElementById("loginForm");

    const registerForm =
        document.getElementById("registerForm");

    const loginTab =
        document.getElementById("loginTab");

    const registerTab =
        document.getElementById("registerTab");

    loginForm.classList.remove("active");

    registerForm.classList.remove("active");

    loginTab.classList.remove("active");

    registerTab.classList.remove("active");

    if (type === "login") {

        loginForm.classList.add("active");

        loginTab.classList.add("active");

    } else {

        registerForm.classList.add("active");

        registerTab.classList.add("active");
    }
}

</script>

</body>
</html>