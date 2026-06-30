<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Value;

use Chess\Domain\Value\Move;
use Chess\Domain\Value\Square;
use PHPUnit\Framework\TestCase;

final class MoveTest extends TestCase
{
    public function testCanBeCreatedFromTwoSquares(): void
    {
        $move = new Move(Square::fromString('e2'), Square::fromString('e4'));

        self::assertSame('e2', $move->from()->toString());
        self::assertSame('e4', $move->to()->toString());
    }

    public function testCanBeCreatedFromString(): void
    {
        $move = Move::fromString('e2', 'e4');

        self::assertSame('e2', $move->from()->toString());
        self::assertSame('e4', $move->to()->toString());
    }

    public function testTwoMovesWithSameSquaresAreEqual(): void
    {
        $a = Move::fromString('e2', 'e4');
        $b = Move::fromString('e2', 'e4');

        self::assertTrue($a->equals($b));
    }

    public function testTwoMovesWithDifferentSquaresAreNotEqual(): void
    {
        $a = Move::fromString('e2', 'e4');
        $b = Move::fromString('d2', 'd4');

        self::assertFalse($a->equals($b));
    }

    public function testThrowsExceptionWhenFromAndToAreTheSameSquare(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Move::fromString('e4', 'e4');
    }
}
