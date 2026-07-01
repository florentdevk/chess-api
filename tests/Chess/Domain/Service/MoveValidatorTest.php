<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Service;

use Chess\Domain\Exception\InvalidMoveException;
use Chess\Domain\Model\Board;
use Chess\Domain\Model\Piece;
use Chess\Domain\Service\MoveValidator;
use Chess\Domain\Value\Color;
use Chess\Domain\Value\Move;
use Chess\Domain\Value\PieceType;
use Chess\Domain\Value\Square;
use PHPUnit\Framework\TestCase;

final class MoveValidatorTest extends TestCase
{
    private MoveValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new MoveValidator();
    }

    public function testKnightCanMoveInLShape(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Knight, Color::White));

        $this->validator->validate(Move::fromString('e4', 'f6'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testKnightCannotMoveInStraightLine(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Knight, Color::White));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'e6'), $board);
    }

    public function testBishopCanMoveDiagonally(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Bishop, Color::White));

        $this->validator->validate(Move::fromString('e4', 'h7'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testBishopCannotMoveStraight(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Bishop, Color::White));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'e6'), $board);
    }

    public function testBishopCannotJumpOverPieces(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Bishop, Color::White));
        $board->placePiece(Square::fromString('f5'), new Piece(PieceType::Pawn, Color::Black));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'g6'), $board);
    }

    public function testRookCanMoveStraight(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Rook, Color::White));

        $this->validator->validate(Move::fromString('e4', 'e8'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testRookCannotMoveDiagonally(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Rook, Color::White));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'f5'), $board);
    }

    public function testRookCannotJumpOverPieces(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Rook, Color::White));
        $board->placePiece(Square::fromString('e6'), new Piece(PieceType::Pawn, Color::Black));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'e8'), $board);
    }

    public function testQueenCanMoveStraight(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Queen, Color::White));

        $this->validator->validate(Move::fromString('e4', 'e8'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testQueenCanMoveDiagonally(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Queen, Color::White));

        $this->validator->validate(Move::fromString('e4', 'h7'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testQueenCannotMoveInLShape(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Queen, Color::White));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'f6'), $board);
    }

    public function testKingCanMoveOneSquareInAnyDirection(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::King, Color::White));

        $this->validator->validate(Move::fromString('e4', 'e5'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testKingCannotMoveTwoSquares(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::King, Color::White));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'e6'), $board);
    }

    public function testWhitePawnCanMoveOneSquareForward(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Pawn, Color::White));

        $this->validator->validate(Move::fromString('e4', 'e5'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testWhitePawnCanMoveTwoSquaresFromStartingRank(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e2'), new Piece(PieceType::Pawn, Color::White));

        $this->validator->validate(Move::fromString('e2', 'e4'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testWhitePawnCannotMoveTwoSquaresFromNonStartingRank(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e3'), new Piece(PieceType::Pawn, Color::White));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e3', 'e5'), $board);
    }

    public function testWhitePawnCanCaptureOnDiagonal(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Pawn, Color::White));
        $board->placePiece(Square::fromString('f5'), new Piece(PieceType::Pawn, Color::Black));

        $this->validator->validate(Move::fromString('e4', 'f5'), $board);
        $this->expectNotToPerformAssertions();
    }

    public function testWhitePawnCannotCaptureForward(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Pawn, Color::White));
        $board->placePiece(Square::fromString('e5'), new Piece(PieceType::Pawn, Color::Black));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'e5'), $board);
    }

    public function testCannotCaptureOwnPiece(): void
    {
        $board = new Board();
        $board->placePiece(Square::fromString('e4'), new Piece(PieceType::Rook, Color::White));
        $board->placePiece(Square::fromString('e6'), new Piece(PieceType::Pawn, Color::White));

        $this->expectException(InvalidMoveException::class);
        $this->validator->validate(Move::fromString('e4', 'e6'), $board);
    }
}
