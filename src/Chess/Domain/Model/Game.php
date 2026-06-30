<?php

declare(strict_types=1);

namespace Chess\Domain\Model;

use Chess\Domain\Value\Color;
use Chess\Domain\Value\GameId;
use Chess\Domain\Value\Move;

final class Game
{
    private Color $currentTurn;
    private bool $isOver = false;

    public function __construct(
        private readonly GameId $id,
        private readonly Board $board,
    ) {
        $this->currentTurn = Color::White;
    }

    public static function start(): self
    {
        return new self(
            GameId::generate(),
            Board::withStandardSetup(),
        );
    }

    public function play(Move $move): void
    {
        if ($this->isOver) {
            throw new \DomainException('Cannot play a move on a finished game.');
        }

        $piece = $this->board->pieceAt($move->from());

        if (null === $piece) {
            throw new \DomainException(\sprintf('No piece on square "%s".', $move->from()->toString()));
        }

        if (!$piece->isColor($this->currentTurn)) {
            throw new \DomainException(\sprintf('It is %s\'s turn.', $this->currentTurn->name));
        }

        $this->board->movePiece($move->from(), $move->to());
        $this->currentTurn = $this->currentTurn->opposite();
    }

    public function currentTurn(): Color
    {
        return $this->currentTurn;
    }

    public function isOver(): bool
    {
        return $this->isOver;
    }

    public function id(): GameId
    {
        return $this->id;
    }

    public function board(): Board
    {
        return $this->board;
    }
}
