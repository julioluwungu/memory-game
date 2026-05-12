const levels = [
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
    "🌽",
    "🍋",
    "🍈",
    "🥔",
    "🍅",
    "🥭",
    "🍐",
    "🧄",
    "🥑"
];

let currentLevel = 0;

let cards = [];

let firstCard = null;
let secondCard = null;

let lockBoard = false;

let moves = 0;

let matched = 0;

let timer = 0;

let interval = null;

const board =
    document.getElementById("game-board");

const movesText =
    document.getElementById("moves");

const timerText =
    document.getElementById("timer");

function startLevel() {

    resetGameVariables();

    generateCards();

    createBoard();

    startTimer();

    updateButtons();
}

function generateCards() {

    const totalCards =
        levels[currentLevel];

    const pairs =
        totalCards / 2;

    cards = emojis
        .slice(0, pairs);

    cards = [...cards, ...cards];

    cards.sort(() => Math.random() - 0.5);
}

function createBoard() {

    board.innerHTML = "";

    const totalCards =
        levels[currentLevel];

    let columns = 2;

    if (totalCards >= 6) {
        columns = 3;
    }

    if (totalCards >= 12) {
        columns = 4;
    }

    if (totalCards >= 20) {
        columns = 5;
    }

    board.style.gridTemplateColumns =
        `repeat(${columns}, 100px)`;

    cards.forEach((emoji, index) => {

        const card =
            document.createElement("div");

        card.classList.add("card");

        card.dataset.emoji = emoji;

        card.dataset.index = index;

        card.innerHTML = "?";

        card.addEventListener(
            "click",
            flipCard
        );

        board.appendChild(card);
    });
}

function flipCard() {

    if (lockBoard) return;

    if (this === firstCard) return;

    this.innerHTML =
        this.dataset.emoji;

    this.classList.add("flipped");

    if (!firstCard) {

        firstCard = this;

        return;
    }

    secondCard = this;

    moves++;

    movesText.innerText = moves;

    checkMatch();
}

function checkMatch() {

    if (
        firstCard.dataset.emoji ===
        secondCard.dataset.emoji
    ) {

        matched += 2;

        resetTurn();

        if (matched === cards.length) {

            clearInterval(interval);

            setTimeout(() => {

                alert(
                    `Nível concluído!\nMovimentos: ${moves}\nTempo: ${timer}s`
                );

            }, 300);
        }

    } else {

        lockBoard = true;

        setTimeout(() => {

            firstCard.innerHTML = "?";

            secondCard.innerHTML = "?";

            firstCard.classList.remove("flipped");

            secondCard.classList.remove("flipped");

            resetTurn();

        }, 1000);
    }
}

function resetTurn() {

    firstCard = null;

    secondCard = null;

    lockBoard = false;
}

function resetGameVariables() {

    clearInterval(interval);

    firstCard = null;

    secondCard = null;

    lockBoard = false;

    moves = 0;

    matched = 0;

    timer = 0;

    movesText.innerText = moves;

    timerText.innerText = timer;
}

function startTimer() {

    interval = setInterval(() => {

        timer++;

        timerText.innerText = timer;

    }, 1000);
}

function restartGame() {

    startLevel();
}

function nextLevel() {

    if (
        currentLevel <
        levels.length - 1
    ) {

        currentLevel++;

        startLevel();
    }
}

function previousLevel() {

    if (currentLevel > 0) {

        currentLevel--;

        startLevel();
    }
}

function updateButtons() {

    const prevBtn =
        document.getElementById("prev-btn");

    const nextBtn =
        document.getElementById("next-btn");

    prevBtn.disabled =
        currentLevel === 0;

    nextBtn.disabled =
        currentLevel ===
        levels.length - 1;
}

startLevel();