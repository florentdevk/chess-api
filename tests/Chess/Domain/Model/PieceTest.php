<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Model;

use Chess\Domain\Model\Piece;
use Chess\Domain\Value\Color;
use Chess\Domain\Value\PieceType;
use PHPUnit\Framework\TestCase;

final class PieceTest extends TestCase
{
    public function testPieceHasType(): void
    {
        $piece = new Piece(PieceType::King, Color::White);

        self::assertSame(PieceType::King, $piece->type());
    }

    public function testPieceHasColor(): void
    {
        $piece = new Piece(PieceType::Queen, Color::Black);

        self::assertSame(Color::Black, $piece->color());
    }

    public function testPieceIsColorReturnsTrueWhenColorMatches(): void
    {
        $piece = new Piece(PieceType::Rook, Color::White);

        self::assertTrue($piece->isColor(Color::White));
    }

    public function testPieceIsColorReturnsFalseWhenColorDoesNotMatch(): void
    {
        $piece = new Piece(PieceType::Rook, Color::White);

        self::assertFalse($piece->isColor(Color::Black));
    }
}
