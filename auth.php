<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['user_id'])) {

    header("Location: levels.php");

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

                header("Location: levels.php");

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

                header("Location: levels.php");

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

            transition: all 0.8s;
        }

        body {

            font-family: Arial;

            background: linear-gradient(to bottom right, #050816, #14052E);
            
            color: #F5F5F5;

            display: flex;

            justify-content: center;

            align-items: center;

            height: 100vh;
        }

        .container {

            background-color: #090D1F;
            
            border: 1px solid #8A3FFC;

            width: 350px;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 0 30px rgba(138, 63, 252, 0.25);
        }

        h1 {

            color: white;

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

            background-color: #111827;

            font-weight: bold;

            color: white;
        }

        .tabs button.active {

            background-color: #7B2FF7;
    
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

            border-radius: 5px;

            border: 1px solid #2A2F45;
            
            background-color: #111827;
    
            color: white;

            outline: none;
        }

        input:focus {
            border-color: #8A3FFC;
            
            box-shadow: 0 0 10px rgba(138, 63, 252, 0.4);
        }

        input::placeholder {

            color: #6B7280;

        }

        button.submit {

            padding: 10px;

            border: none;

            background-color: #7B2FF7;
    
            color: white;

            border-radius: 5px;

            cursor: pointer;
        }

        button.submit:hover {

            background-color: #9B4DFF;

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