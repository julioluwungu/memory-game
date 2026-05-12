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
    "🌽"
];

let currentLevel =
    CURRENT_LEVEL - 1;

let cards = [];

let firstCard = null;

let secondCard = null;

let lockBoard = false;

let moves = 0;

let matched = 0;

let timer = 0;

let interval = null;

let levelCompleted = false;

const board =
    document.getElementById("game-board");

const movesText =
    document.getElementById("moves");

const timerText =
    document.getElementById("timer");

function startGame() {

    resetVariables();

    generateCards();

    createBoard();

    startTimer();
}

function resetVariables() {

    clearInterval(interval);

    firstCard = null;

    secondCard = null;

    lockBoard = false;

    moves = 0;

    matched = 0;

    timer = 0;

    movesText.innerText = 0;

    timerText.innerText = 0;
}

function generateCards() {

    const totalCards =
        levels[currentLevel];

    const pairs =
        totalCards / 2;

    cards =
        emojis.slice(0, pairs);

    cards =
        [...cards, ...cards];

    cards.sort(
        () => Math.random() - 0.5
    );
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

    cards.forEach((emoji) => {

        const card =
            document.createElement("div");

        card.classList.add("card");

        card.dataset.emoji = emoji;

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

            levelCompleted = true;

            saveLevel();

            setTimeout(() => {

                alert(
                    `Nível concluído!\n\nTempo: ${timer}s\nMovimentos: ${moves}`
                );

                location.href =
                    "levels.php";

            }, 500);
        }

    } else {

        lockBoard = true;

        setTimeout(() => {

            firstCard.innerHTML = "?";

            secondCard.innerHTML = "?";

            firstCard.classList.remove(
                "flipped"
            );

            secondCard.classList.remove(
                "flipped"
            );

            resetTurn();

        }, 1000);
    }
}

function resetTurn() {

    firstCard = null;

    secondCard = null;

    lockBoard = false;
}

function startTimer() {

    interval = setInterval(() => {

        timer++;

        timerText.innerText = timer;

    }, 1000);
}

function restartGame() {

    startGame();
}

function nextLevel() {

    if (!levelCompleted) {

        alert(
            "Complete o nível primeiro!"
        );

        return;
    }

    if (CURRENT_LEVEL < 10) {

        location.href =
            `index.php?level=${CURRENT_LEVEL + 1}`;
    }
}

function previousLevel() {

    if (CURRENT_LEVEL > 1) {

        location.href =
            `index.php?level=${CURRENT_LEVEL - 1}`;
    }
}

function goToLevels() {

    location.href = "levels.php";
}

function saveLevel() {

    fetch("save_level.php", {

        method: "POST",

        headers: {
            "Content-Type":
                "application/x-www-form-urlencoded"
        },

        body:
            `level=${CURRENT_LEVEL}` +
            `&moves=${moves}` +
            `&time=${timer}`
    });
}

startGame();