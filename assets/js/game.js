const emojis = [
    "🍎",
    "🍌",
    "🍇",
    "🍉",
    "🍓",
    "🍒",
    "🥝",
    "🍍"
];

let cards = [...emojis, ...emojis];

cards.sort(() => Math.random() - 0.5);

const board = document.getElementById("game-board");

let firstCard = null;
let secondCard = null;

let lockBoard = false;
let moves = 0;
let matched = 0;

function createBoard() {

    board.innerHTML = "";

    cards.forEach((emoji, index) => {

        const card = document.createElement("div");

        card.classList.add("card");

        card.dataset.emoji = emoji;
        card.dataset.index = index;

        card.innerHTML = "?";

        card.addEventListener("click", flipCard);

        board.appendChild(card);
    });
}

function flipCard() {

    if (lockBoard) return;

    if (this === firstCard) return;

    this.innerHTML = this.dataset.emoji;
    this.classList.add("flipped");

    if (!firstCard) {

        firstCard = this;
        return;
    }

    secondCard = this;

    moves++;

    document.getElementById("moves").innerText = moves;

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

            alert("Você venceu!");

            saveScore();
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

    [firstCard, secondCard] = [null, null];

    lockBoard = false;
}

function restartGame() {

    location.reload();
}

function saveScore() {

    fetch("save_score.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: `moves=${moves}`
    });
}

createBoard();