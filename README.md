# memory-game
# Grupo 3 de TLP

# Integrantes do Grupo:

- João Louvath Tomás — Nº 08
- Júlio Paulo Altino Luwungu — Nº 12
- Kilder Salvador — Nº 14
- Nelson Bernardo Gouveia Saco — Nº 21

## Tema do Projeto
# Jogo da Memória (Memory Game)

Este projeto consiste em um jogo da memória desenvolvido com:

- HTML
- CSS
- JavaScript
- PHP
- MySQL

O sistema permite que os usuários criem contas, façam login e joguem diferentes níveis do jogo da memória. O progresso do jogador é salvo automaticamente no banco de dados, incluindo:

- níveis desbloqueados;
- quantidade de movimentos;
- melhor tempo por nível.

---

# Funcionalidades do Projeto

- Sistema de registro e login
- Salvamento de sessão do usuário
- 10 níveis progressivos
- Desbloqueio de níveis
- Contador de movimentos
- Temporizador
- Salvamento de estatísticas
- Interface responsiva
- Navegação entre níveis
- Reinício do jogo
- Sistema de logout

---

# Tecnologias Utilizadas

## Front-End
- HTML5
- CSS3
- JavaScript

## Back-End
- PHP

## Banco de Dados
- MySQL

---

# Estrutura do Projeto

```txt
memory-game/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── config/
│   └── database.php
│
├── auth.php
├── index.php
├── niveis.php
├── salvar_nivel.php
├── sair.php
└── README.md
