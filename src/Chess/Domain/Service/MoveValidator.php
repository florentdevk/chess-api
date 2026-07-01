<?php

declare(strict_types=1);

namespace Chess\Domain\Service;

use Chess\Domain\Exception\InvalidMoveException;
use Chess\Domain\Model\Board;
use Chess\Domain\Value\Move;
use Chess\Domain\Value\PieceType;
use Chess\Domain\Value\Square;

final class MoveValidator
{
    public function validate(Move $move, Board $board): void
    {
        $piece = $board->pieceAt($move->from());

        if (null === $piece) {
            throw new InvalidMoveException(\sprintf('No piece on square "%s".', $move->from()->toString()));
        }

        $destination = $board->pieceAt($move->to());
        if (null !== $destination && $destination->isColor($piece->color())) {
            throw new InvalidMoveException('Cannot capture your own piece.');
        }

        match ($piece->type()) {
            PieceType::Knight => $this->validateKnightMove($move->from(), $move->to()),
            PieceType::Bishop => $this->validateBishopMove($move->from(), $move->to(), $board),
            PieceType::Rook => $this->validateRookMove($move->from(), $move->to(), $board),
            PieceType::Queen => $this->validateQueenMove($move->from(), $move->to(), $board),
            PieceType::King => $this->validateKingMove($move->from(), $move->to()),
            PieceType::Pawn => $this->validatePawnMove($move->from(), $move->to(), $board, $piece->color()),
        };
    }

    private function validateKnightMove(Square $from, Square $to): void
    {
        $fileDiff = abs(\ord($to->file()) - \ord($from->file()));
        $rankDiff = abs($to->rank() - $from->rank());

        $isLShape = (1 === $fileDiff && 2 === $rankDiff)
            || (2 === $fileDiff && 1 === $rankDiff);

        if (!$isLShape) {
            throw new InvalidMoveException('Knight must move in an L-shape.');
        }
    }

    private function validateBishopMove(Square $from, Square $to, Board $board): void
    {
        $fileDiff = abs(\ord($to->file()) - \ord($from->file()));
        $rankDiff = abs($to->rank() - $from->rank());

        if ($fileDiff !== $rankDiff) {
            throw new InvalidMoveException('Bishop must move diagonally.');
        }

        $this->assertDiagonalPathClear($from, $to, $board);
    }

    private function validateRookMove(Square $from, Square $to, Board $board): void
    {
        $fileDiff = \ord($to->file()) - \ord($from->file());
        $rankDiff = $to->rank() - $from->rank();

        if (0 !== $fileDiff && 0 !== $rankDiff) {
            throw new InvalidMoveException('Rook must move in a straight line.');
        }

        $this->assertStraightPathClear($from, $to, $board);
    }

    private function validateQueenMove(Square $from, Square $to, Board $board): void
    {
        $fileDiff = abs(\ord($to->file()) - \ord($from->file()));
        $rankDiff = abs($to->rank() - $from->rank());

        $isDiagonal = $fileDiff === $rankDiff;
        $isStraight = 0 === $fileDiff || 0 === $rankDiff;

        if (!$isDiagonal && !$isStraight) {
            throw new InvalidMoveException('Queen must move in a straight line or diagonally.');
        }

        if ($isDiagonal) {
            $this->assertDiagonalPathClear($from, $to, $board);
        } else {
            $this->assertStraightPathClear($from, $to, $board);
        }
    }

    private function validateKingMove(Square $from, Square $to): void
    {
        $fileDiff = abs(\ord($to->file()) - \ord($from->file()));
        $rankDiff = abs($to->rank() - $from->rank());

        if ($fileDiff > 1 || $rankDiff > 1) {
            throw new InvalidMoveException('King can only move one square in any direction.');
        }
    }

    private function validatePawnMove(Square $from, Square $to, Board $board, \Chess\Domain\Value\Color $color): void
    {
        $fileDiff = abs(\ord($to->file()) - \ord($from->file()));
        $rankDiff = $to->rank() - $from->rank();
        $direction = \Chess\Domain\Value\Color::White === $color ? 1 : -1;
        $startRank = \Chess\Domain\Value\Color::White === $color ? 2 : 7;

        if (0 === $fileDiff && $rankDiff === $direction) {
            if (!$board->isEmpty($to)) {
                throw new InvalidMoveException('Pawn cannot capture forward.');
            }

            return;
        }

        if (0 === $fileDiff && $rankDiff === 2 * $direction && $from->rank() === $startRank) {
            $intermediate = new Square($from->file(), $from->rank() + $direction);
            if (!$board->isEmpty($intermediate) || !$board->isEmpty($to)) {
                throw new InvalidMoveException('Pawn path is blocked.');
            }

            return;
        }

        if (1 === $fileDiff && $rankDiff === $direction) {
            if ($board->isEmpty($to)) {
                throw new InvalidMoveException('Pawn can only capture on a non-empty square.');
            }

            return;
        }

        throw new InvalidMoveException('Invalid pawn move.');
    }

    private function assertStraightPathClear(Square $from, Square $to, Board $board): void
    {
        $fileStep = $this->step(\ord($to->file()) - \ord($from->file()));
        $rankStep = $this->step($to->rank() - $from->rank());

        $file = \chr(\ord($from->file()) + $fileStep);
        $rank = $from->rank() + $rankStep;

        while ($file !== $to->file() || $rank !== $to->rank()) {
            if (!$board->isEmpty(new Square($file, $rank))) {
                throw new InvalidMoveException('Path is not clear.');
            }
            $file = \chr(\ord($file) + $fileStep);
            $rank += $rankStep;
        }
    }

    private function assertDiagonalPathClear(Square $from, Square $to, Board $board): void
    {
        $fileStep = $this->step(\ord($to->file()) - \ord($from->file()));
        $rankStep = $this->step($to->rank() - $from->rank());

        $file = \chr(\ord($from->file()) + $fileStep);
        $rank = $from->rank() + $rankStep;

        while ($file !== $to->file() || $rank !== $to->rank()) {
            if (!$board->isEmpty(new Square($file, $rank))) {
                throw new InvalidMoveException('Path is not clear.');
            }
            $file = \chr(\ord($file) + $fileStep);
            $rank += $rankStep;
        }
    }

    private function step(int $diff): int
    {
        if (0 === $diff) {
            return 0;
        }

        return $diff > 0 ? 1 : -1;
    }
}
