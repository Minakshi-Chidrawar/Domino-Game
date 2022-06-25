<?php

const USERS = ["One", "Two", "Three", "Four"];

class Player {
    private $id;

    private $userName;

    private $pieces = [];

    /** The Player class constructor. */
    public function __construct(int $id) {
        $this->id = $id;
        $this->userName = USERS[$id];
    }

    public function getId() {
        return $this->id;
    }

    public function getUserName() {
        return 'Player ' . $this->userName;
    }

    public function getPieces() {
        return $this->pieces;
    }

    public function setPieces(array $pieces) {
        foreach ($pieces as $piece) {
            $this->pieces[$piece->getId()] = $piece;
        }
    }

    /** Draw the piece given. Removes it from player's array of pieces. */
    public function drawPiece(Piece $piece) {
        $this->pieces[$piece->getId()] = $piece;
    }

    /** Player the player's first piece */
    public function playFirstPiece() {
        $piece = reset($this->pieces);

        unset($this->pieces[$piece->getId()]);
        return $piece;
    }

    /** Plays the piece given. */
    public function playPiece(Piece $piece) {
        unset($this->pieces[$piece->getId()]);
    }

    /** Gets the players bigger double. */
    public function getBiggerDouble() {
        if (empty($this->pieces)) {
            return 0;
        }

        $doubles = array_filter($this->pieces,
            function ($p) {
                return $p->isDouble();
            }
        );

        if (empty($doubles)) {
            return [];
        }

        $maxDouble = reset($doubles);
        $max = $maxDouble->getX();

        foreach ($doubles as $doublePiece) {
            if ($doublePiece->getX() > $max) {
                $maxDouble = $doublePiece;
                $max = $doublePiece->getX();
            }
        }

        return $maxDouble;
    }

    /** Gets the total numbers of dots of the player's pieces. */
    public function getTotalDots() {
        $totalDots = 0;
        foreach ($this->pieces as $piece) {
            $totalDots += $piece->getX() + $piece->getY();
        }
        return $totalDots;
    }
}
