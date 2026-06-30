<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Model;

use Chess\Domain\Model\Board;
use Chess\Domain\Model\Piece;
use Chess\Domain\Value\Color;
use Chess\Domain\Value\PieceType;
use Chess\Domain\Value\Square;
use PHPUnit\Framework\TestCase;

final class BoardTest extends TestCase
{
    public function testEmptyBoardHasNoPieceOnAnySquare(): void
    {
        $board = new Board();

        self::assertTrue($board->isEmpty(Square::fromString('e4')));
        self::assertNull($board->pieceAt(Square::fromString('e4')));
    }

    public function testCanPlacePieceOnSquare(): void
    {
        $board = new Board();
        $piece = new Piece(PieceType::Queen, Color::White);

        $board->placePiece(Square::fromString('d1'), $piece);

        self::assertFalse($board->isEmpty(Square::fromString('d1')));
        self::assertSame($piece, $board->pieceAt(Square::fromString('d1')));
    }

    public function testMovePieceFromOneSquareToAnother(): void
    {
        $board = new Board();
        $piece = new Piece(PieceType::Pawn, Color::White);
        $board->placePiece(Square::fromString('e2'), $piece);

        $board->movePiece(Square::fromString('e2'), Square::fromString('e4'));

        self::assertTrue($board->isEmpty(Square::fromString('e2')));
        self::assertSame($piece, $board->pieceAt(Square::fromString('e4')));
    }

    public function testMovingFromEmptySquareThrowsException(): void
    {
        $board = new Board();

        $this->expectException(\DomainException::class);

        $board->movePiece(Square::fromString('e2'), Square::fromString('e4'));
    }

    public function testStandardSetupPlacesThirtyTwoPieces(): void
    {
        $board = Board::withStandardSetup();

        $count = 0;
        foreach (range(1, 8) as $rank) {
            foreach (['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'] as $file) {
                if (!$board->isEmpty(new Square($file, $rank))) {
                    ++$count;
                }
            }
        }

        self::assertSame(32, $count);
    }

    public function testStandardSetupPlacesWhiteKingOnE1(): void
    {
        $board = Board::withStandardSetup();

        $piece = $board->pieceAt(Square::fromString('e1'));

        self::assertNotNull($piece);
        self::assertSame(PieceType::King, $piece->type());
        self::assertSame(Color::White, $piece->color());
    }

    public function testStandardSetupPlacesBlackKingOnE8(): void
    {
        $board = Board::withStandardSetup();

        $piece = $board->pieceAt(Square::fromString('e8'));

        self::assertNotNull($piece);
        self::assertSame(PieceType::King, $piece->type());
        self::assertSame(Color::Black, $piece->color());
    }

    public function testStandardSetupPlacesPawnsOnSecondAndSeventhRank(): void
    {
        $board = Board::withStandardSetup();

        $whitePawn = $board->pieceAt(Square::fromString('a2'));
        $blackPawn = $board->pieceAt(Square::fromString('a7'));

        self::assertNotNull($whitePawn);
        self::assertSame(PieceType::Pawn, $whitePawn->type());

        self::assertNotNull($blackPawn);
        self::assertSame(PieceType::Pawn, $blackPawn->type());
    }
}
