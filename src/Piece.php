<?php

class Piece {
    private $id;

    private $x;

    private $y;

    private $turn;

    public function __construct(int $x, int $y) {
        // Set properties.
        $this->x = $x;
        $this->y = $y;
        $this->id = $x . $y;
        $this->turn = false;
    }

    /** Get the sets of the piece. */
    public function getSets() {
        return [$this->x, $this->y];
    }

    public function getId() {
        return $this->id;
    }

    /** Gets the first set of the piece, placed on the left. */
    public function getX() {
        // Depending on turn value.
        return $this->turn ? $this->y : $this->x;
    }

    /** Gets the second set of the piece, placed on the right. */
    public function getY() {
        return $this->turn ? $this->x : $this->y;
    }

    /** Returns a "rendered" piece. */
    public function getPieceName() {
        return '| ' . $this->getX() . ' -- ' . $this->getY() . ' |';
    }

    /** Check if the piece is double. */
    public function isDouble() {
        return $this->x == $this->y;
    }

    /** Turn the piece. */
    public function turn() {
        $this->turn = true;
    }
}
