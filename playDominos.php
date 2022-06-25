<?php

require "src/Domino.php";

// Played by a minimum of 2 players and a max of 4.
if (isset($argv[1])) {
    if ($argv[1] >= 2 && $argv[1] <= 4) {
        $domino = new Domino($argv[1]);
    } else {
        echo "Sorry, the game is played by a minimum of 2 players and a max of 4. Try again...\n";
        exit();
    }
} else {
    $domino = new Domino(2);
}

$domino->createGame();
