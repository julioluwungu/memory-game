<?php
session_start();
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user) {

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: index.php");
            exit;
        }
    }

    echo "Usuário ou senha incorretos";
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Usuário">
    <input type="password" name="password" placeholder="Senha">
    <button type="submit">Entrar</button>
</form>