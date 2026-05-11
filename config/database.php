<?php

$host = "localhost";
$dbname = "memory_game";
$user = "root";
$pass = "";

$conn = new PDO(
    "mysql:host=$host;dbname=$dbname",
    $user,
    $pass
);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);