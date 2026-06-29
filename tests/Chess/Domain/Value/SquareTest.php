<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Value;

use Chess\Domain\Value\Square;
use PHPUnit\Framework\TestCase;

final class SquareTest extends TestCase
{
    public function testCanBeCreatedFromValidNotation(): void
    {
        $square = Square::fromString('e4');

        self::assertSame('e', $square->file());
        self::assertSame(4, $square->rank());
    }

    public function testToStringReturnsAlgebraicNotation(): void
    {
        $square = Square::fromString('e4');

        self::assertSame('e4', $square->toString());
    }

    public function testTwoSquaresWithSameNotationAreEqual(): void
    {
        $a = Square::fromString('e4');
        $b = Square::fromString('e4');

        self::assertTrue($a->equals($b));
    }

    public function testTwoSquaresWithDifferentNotationAreNotEqual(): void
    {
        $a = Square::fromString('e4');
        $b = Square::fromString('d5');

        self::assertFalse($a->equals($b));
    }

    public function testThrowsExceptionOnInvalidFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Square::fromString('z4');
    }

    public function testThrowsExceptionOnInvalidRank(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Square::fromString('e9');
    }

    public function testThrowsExceptionOnInvalidNotationLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Square::fromString('e42');
    }
}
