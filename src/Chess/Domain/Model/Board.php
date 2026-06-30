<?php

declare(strict_types=1);

namespace Chess\Domain\Model;

use Chess\Domain\Value\Color;
use Chess\Domain\Value\PieceType;
use Chess\Domain\Value\Square;

final class Board
{
    /** @var array<string, Piece> */
    private array $squares = [];

    public static function withStandardSetup(): self
    {
        $board = new self();
        $board->placeStandardPieces();

        return $board;
    }

    public function placePiece(Square $square, Piece $piece): void
    {
        $this->squares[$square->toString()] = $piece;
    }

    public function pieceAt(Square $square): ?Piece
    {
        return $this->squares[$square->toString()] ?? null;
    }

    public function isEmpty(Square $square): bool
    {
        return null === $this->pieceAt($square);
    }

    public function movePiece(Square $from, Square $to): void
    {
        $piece = $this->pieceAt($from);

        if (null === $piece) {
            throw new \DomainException(\sprintf('No piece found on square "%s".', $from->toString()));
        }

        unset($this->squares[$from->toString()]);
        $this->squares[$to->toString()] = $piece;
    }

    private function placeStandardPieces(): void
    {
        $backRank = [
            PieceType::Rook,
            PieceType::Knight,
            PieceType::Bishop,
            PieceType::Queen,
            PieceType::King,
            PieceType::Bishop,
            PieceType::Knight,
            PieceType::Rook,
        ];

        $files = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

        foreach ($files as $index => $file) {
            $this->placePiece(new Square($file, 1), new Piece($backRank[$index], Color::White));
            $this->placePiece(new Square($file, 2), new Piece(PieceType::Pawn, Color::White));

            $this->placePiece(new Square($file, 7), new Piece(PieceType::Pawn, Color::Black));
            $this->placePiece(new Square($file, 8), new Piece($backRank[$index], Color::Black));
        }
    }
}
