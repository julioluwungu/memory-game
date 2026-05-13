CREATE DATABASE IF NOT EXISTS memory_game
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE memory_game;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    niveis_desbloqueados INT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS estatisticas_niveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    numero_nivel INT NOT NULL,
    melhor_movimento INT DEFAULT 0,
    melhor_tempo INT DEFAULT 0,

    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);