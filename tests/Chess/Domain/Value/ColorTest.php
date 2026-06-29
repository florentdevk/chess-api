<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Value;

use Chess\Domain\Value\Color;
use PHPUnit\Framework\TestCase;

final class ColorTest extends TestCase
{
    public function testWhiteOppositeIsBlack(): void
    {
        self::assertSame(Color::Black, Color::White->opposite());
    }

    public function testBlackOppositeIsWhite(): void
    {
        self::assertSame(Color::White, Color::Black->opposite());
    }

    public function testOppositeIsSymmetric(): void
    {
        self::assertSame(Color::White, Color::White->opposite()->opposite());
    }
}
