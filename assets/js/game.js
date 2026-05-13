const niveis = [
    4,
    6,
    8,
    10,
    12,
    14,
    16,
    18,
    20,
    24
];

const emojis = [
    "🍎",
    "🍌",
    "🍇",
    "🍉",
    "🍓",
    "🍒",
    "🥝",
    "🍍",
    "🥥",
    "🍑",
    "🥕",
    "🌽"
];

let nivelAtual =
    NIVEL_ATUAL - 1;

let cards = [];

let primeiroCard = null;

let segundoCard = null;

let bloquearQuadro = false;

let movimentos = 0;

let combinados = 0;

let timer = 0;

let intervalo = null;

let nivelCompleto = false;

const quadro =
    document.getElementById("game-quadro");

const movimentosTxt =
    document.getElementById("movimentos");

const timerTxt =
    document.getElementById("timer");

function iniciarJogo() {

    resetarVariaveis();

    gerarCards();

    criarQuadro();

    iniciarTimer();
}

function resetarVariaveis() {

    clearInterval(intervalo);

    primeiroCard = null;

    segundoCard = null;

    bloquearQuadro = false;

    movimentos = 0;

    combinados = 0;

    timer = 0;

    movimentosTxt.innerText = 0;

    timerTxt.innerText = 0;
}

function gerarCards() {

    const totalCards =
        niveis[nivelAtual];

    const pares =
        totalCards / 2;

    cards =
        emojis.slice(0, pares);

    cards =
        [...cards, ...cards];

    cards.sort(
        () => Math.random() - 0.5
    );
}

function criarQuadro() {

    quadro.innerHTML = "";

    const totalCards =
        niveis[nivelAtual];

    let colunas = 2;

    if (totalCards >= 6) {
        colunas = 3;
    }

    if (totalCards >= 12) {
        colunas = 4;
    }

    if (totalCards >= 20) {
        colunas = 5;
    }

    quadro.style.gridTemplateColumns =
        `repeat(${colunas}, 100px)`;

    cards.forEach((emoji) => {

        const card =
            document.createElement("div");

        card.classList.add("card");

        card.dataset.emoji = emoji;

        card.innerHTML = "?";

        card.addEventListener(
            "click",
            virarCard
        );

        quadro.appendChild(card);
    });
}

function virarCard() {

    if (bloquearQuadro) return;

    if (this === primeiroCard) return;

    this.innerHTML =
        this.dataset.emoji;

    this.classList.add("flipped");

    if (!primeiroCard) {

        primeiroCard = this;

        return;
    }

    segundoCard = this;

    movimentos++;

    movimentosTxt.innerText = movimentos;

    verificarCorrespondencia();
}

function verificarCorrespondencia() {

    if (
        primeiroCard.dataset.emoji ===
        segundoCard.dataset.emoji
    ) {

        combinados += 2;

        resetarRodada();

        if (combinados === cards.length) {

            clearInterval(intervalo);

            nivelCompleto = true;

            salvarNivel();

            setTimeout(() => {

                alert(
                    `Nível concluído!\n\nTempo: ${timer}s\nMovimentos: ${movimentos}`
                );

                location.href =
                    "niveis.php";

            }, 500);
        }

    } else {

        bloquearQuadro = true;

        setTimeout(() => {

            primeiroCard.innerHTML = "?";

            segundoCard.innerHTML = "?";

            primeiroCard.classList.remove(
                "flipped"
            );

            segundoCard.classList.remove(
                "flipped"
            );

            resetarRodada();

        }, 1000);
    }
}

function resetarRodada() {

    primeiroCard = null;

    segundoCard = null;

    bloquearQuadro = false;
}

function iniciarTimer() {

    intervalo = setInterval(() => {

        timer++;

        timerTxt.innerText = timer;

    }, 1000);
}

function reiniciarJogo() {

    iniciarJogo();
}

function proximoNivel() {

    const proximo =
        NIVEL_ATUAL + 1;

    if (
        proximo <= NIVEL_DESBLOQUEADO &&
        NIVEL_ATUAL < 10
    ) {

        location.href =
            `index.php?level=${proximo}`;

    } else {

        alert(
            "Complete este nível primeiro!"
        );
    }
}

function nivelAnterior() {

    if (NIVEL_ATUAL > 1) {

        location.href =
            `index.php?level=${NIVEL_ATUAL - 1}`;
    }
}

function verNiveis() {

    location.href = "niveis.php";
}

function salvarNivel() {

    fetch("save_level.php", {

        method: "POST",

        headers: {
            "Content-Type":
                "application/x-www-form-urlencoded"
        },

        body:
            `level=${NIVEL_ATUAL}` +
            `&movimentos=${movimentos}` +
            `&time=${timer}`
    });
}

iniciarJogo();