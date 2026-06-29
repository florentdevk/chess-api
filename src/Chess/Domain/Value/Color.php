<?php

declare(strict_types=1);

namespace Chess\Domain\Value;

enum Color
{
    case White;
    case Black;

    public function opposite(): self
    {
        return match ($this) {
            Color::White => Color::Black,
            Color::Black => Color::White,
        };
    }
}
