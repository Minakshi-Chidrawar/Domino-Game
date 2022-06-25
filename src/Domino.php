<?php

require_once "Player.php";
require_once "Piece.php";

class Domino {
    private $numberOfPlayers;
    private $players;
    private $pieces;
    private $board = [];
    private $end = false;
    private $currentPlayer;
    private $winner;

    public function __construct(int $numberOfPlayers) {
        $this->numberOfPlayers = $numberOfPlayers;
    }

    /** Creates the game by initialising the pieces, gives them to players and launches the game. */
    public function createGame() {
        for ($id = 0; $id < $this->numberOfPlayers; $id++) {
            // Create the player objects
            $this->players[$id] = new Player($id);
        }

        // Create the pieces.
        $this->createPieces();
        // Allocate pieces to each player.
        $this->pickPieces($this->numberOfPlayers);
        // play the game.
        $this->play();
    }

    /** Play the game. */
    private function play() {

        echo "Welcome to Dominos! \n The game is ready... \n\n";
        echo "Number of players: " . $this->numberOfPlayers . "\n\n";

        echo "Players hand: ";

        // Show initial players pieces.
        foreach ($this->players as $player) {
            echo ("\n - " . $player->getUserName() . ": \n");
            foreach ($player->getPieces() as $piece) {
                echo " " . $piece->getPieceName() . " \n";
            }
        }

        // Show pieces left on the table.
        echo "\n - Pieces left on the table: \n";
        foreach ($this->pieces as $piece) {
            echo " " . $piece->getPieceName() . " \n";
        }

        $this->firstMove();
        $this->log();
        $this->nextPlayer();

        while (!$this->end) {
            $this->move();
        }

        if (!$this->winner) {
            echo "\n No more pieces left on the table BUT all players still have non matching pieces in hand...";
            echo "\n The winner is the one with the least total dots...";

            $sum = [];
            foreach ($this->players as $id => $player) {
                // get total number of dots keyed by player id.
                $sum[$id] = $player->getTotalDots();
            }

            $this->winner = $this->players[(array_keys($sum, min($sum)))[0]]->getUsername();
        }

        echo "\n \nGAME OVER. " . $this->winner . " won the game!\n";
    }

    /** Runs the first move of the game. */
    private function firstMove() {
        // get the player with the bigger double.
        $this->currentPlayer = $this->getFirstPlayer();
        // play the player's first piece.
        $this->board[] = $this->currentPlayer->playFirstPiece();

        echo "\n - " . $this->currentPlayer->getUserName() . " plays first. \n";
    }

    /** Sets the next player as current. */
    private function nextPlayer() {
        if ($id = $this->currentPlayer->getId() < ($this->numberOfPlayers - 1)) {
            $this->currentPlayer = $this->players[$id];
        } else {
            $this->currentPlayer = $this->players[0];
        }
    }

    /** Implements a move in the game. */
    private function move() {
        // Wherever the player has played a piece on the board.
        $played = false;

        foreach ($this->currentPlayer->getPieces() as $piece) {
            $result = $this->matches($piece);
            if ($result) {

                if ($result === 'right') {
                    array_push($this->board, $piece);
                } else {
                    array_unshift($this->board, $piece);
                }

                echo "\n - " . $this->currentPlayer->getUserName() . " plays " . $piece->getPieceName() . " at the " . $result . " board. \n";
                $this->currentPlayer->playPiece($piece);
                $played = true;
                $this->log();

                if (empty($this->currentPlayer->getPieces())) {
                    // We have a winner, no more pieces left for the current player.
                    $this->end = true;
                    $this->winner = $this->currentPlayer->getUserName();
                    break;
                } else {
                    // Go to next player.
                    $this->nextPlayer();
                    break;
                }
            }
        }

        if (!$played) {
            if (empty($this->pieces)) {
                // No more pieces left on the table. Game Over.
                $this->end = true;
            } else {
                // If player didn't play, draws a piece.
                $draw = $this->pieces[0];
                $this->currentPlayer->drawPiece($draw);

                array_splice($this->pieces, 0, 1);
                echo "\n - " . $this->currentPlayer->getUserName() . " draws " . $draw->getPieceName() . "\n";
            }
        }
    }

    public function matches(Piece $piece) {
        if ($piece->getX() === $this->boardLimits()[1]) {
            return 'right';
        } else if ($piece->getY() === $this->boardLimits()[0]) {
            return 'left';
        } else if ($piece->getX() === $this->boardLimits()[0]) {
            $piece->turn();
            return 'left';
        } else if ($piece->getY() === $this->boardLimits()[1]) {
            $piece->turn();
            return 'right';
        } else {
            return false;
        }
    }

    /** Creates and sets the pieces. */
    public function createPieces() {
        for ($x = 0; $x <= 6; $x++) {
            for ($y = $x; $y <= 6; $y++) {
                $piece = new Piece($x, $y);
                $this->pieces[] = $piece;
            }
        }

        // Shuffle the pieces.
        shuffle($this->pieces);
    }

    /** Allocates the pieces to each player. */
    private function pickPieces($numberOfPlayers) {
        $length = $this->numberOfPlayers === 2 ? 7 : 5;
        foreach ($this->players as $player) {
            $player->setPieces(
                array_splice($this->pieces, 0, $length)
            );
        }
    }

    /** Determines which player will play first. */
    private function getFirstPlayer() {
        $doubles = (array_map(
            function ($p) {
                return $p->getBiggerDouble();
            },
            $this->players
        ));

        $max = max($biggerDoubleValues = array_map(
            function ($d) {
                return (empty($d)) ? 0 : $d->getX();
            }
            , $doubles
        ));

        return $this->players[array_keys($biggerDoubleValues, $max)[0]];
    }

    /** Determines the board limits. */
    private function boardLimits() {
        return [reset($this->board)->getX(), end($this->board)->getY()];
    }

    private function log() {
        echo " - The board is now:";
        foreach ($this->board as $piece) {
            echo $piece->getPieceName();
        }
        echo "\n";
    }
}
