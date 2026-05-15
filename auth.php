<?php

session_start();

require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['id_usuario'])) {
    header("Location: niveis.php");
    exit;
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'];
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (empty($usuario) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } else {
        if ($acao == "registrar") {
            $usuario = trim($_POST['usuario']);
            $senha = trim($_POST['senha']);
            $confirmarSenha = trim($_POST['confirmar-senha']);

            if (empty($usuario) || empty($senha) || empty($confirmarSenha)) {
                $erro = "Preencha todos os campos.";
            } elseif ($senha !== $confirmarSenha) {
                $erro = "As senhas não coincidem.";
            } else {
                $sql = "
                    SELECT id
                    FROM usuarios
                    WHERE usuario = ?
                ";

                $stmt = $conn->prepare($sql);
                $stmt->execute([$usuario]);
                $user = $stmt->fetch();

                if ($user) {
                    $erro = "Usuário já existe.";
                } else {
                    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                    $sql = "
                        INSERT INTO usuarios
                        (
                            usuario,
                            senha,
                            niveis_desbloqueados
                        )
                        VALUES (?, ?, 1)
                    ";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$usuario, $senhaHash]);
                    $_SESSION['id_usuario'] = $conn->lastInsertId();
                    $_SESSION['usuario'] = $usuario;
                    header("Location: niveis.php");
                    exit;
                }
            }
        }
        if ($acao == "login") {
            $sql = "
                SELECT *
                FROM usuarios
                WHERE usuario = ?
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$usuario]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($senha, $user['senha'])) {
                $_SESSION['id_usuario'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                header("Location: niveis.php");
                exit;
            } else {
                $erro = "Usuário ou senha incorretos.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .abas {
            display: flex;
            margin-bottom: 20px;
        }
        .abas button {
            flex: 1;
            padding: 10px;
            border: none;
            cursor: pointer;
            background-color: #111827;
            font-weight: bold;
            color: white;
        }
        .abas button.active {
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
        .erro {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Jogo da Memória</h1>
    <?php if ($erro): ?>
        <div class="erro"><?= $erro ?></div>
    <?php endif; ?>

    <div class="abas">
        <button id="abaLogin" class="active" onclick="mostrarFormulario('login')">Login</button>
        <button id="abaRegistro" onclick="mostrarFormulario('registro')">Registrar</button>
    </div>

    <form id="formularioLogin" class="active" method="POST">
        <input type="hidden" name="acao" value="login">
        <input type="text" name="usuario" placeholder="Usuário">
        <input type="password" name="senha" placeholder="Senha">
        <button type="submit" class="submit">Entrar</button>
    </form>
    <form id="formularioRegistro" method="POST">
        <input type="hidden" name="acao" value="registrar">
        <input type="text" name="usuario" placeholder="Usuário">
        <input type="password" name="senha" placeholder="Senha">
        <input type="password" name="confirmar-senha" placeholder="Confirme a senha">
        <button type="submit" class="submit">Registrar</button>
    </form>
</div>

<script>
function mostrarFormulario(tipo) {
    const formularioLogin = document.getElementById("formularioLogin");
    const formularioRegistro = document.getElementById("formularioRegistro");
    const abaLogin = document.getElementById("abaLogin");
    const abaRegistro = document.getElementById("abaRegistro");

    formularioLogin.classList.remove("active");
    formularioRegistro.classList.remove("active");
    abaLogin.classList.remove("active");
    abaRegistro.classList.remove("active");

    if (tipo === "login") {
        formularioLogin.classList.add("active");
        abaLogin.classList.add("active");
    } else {
        formularioRegistro.classList.add("active");
        abaRegistro.classList.add("active");
    }
}
</script>
</body>
</html>