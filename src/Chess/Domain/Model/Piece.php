<?php

declare(strict_types=1);

namespace Chess\Domain\Model;

use Chess\Domain\Value\Color;
use Chess\Domain\Value\PieceType;

final class Piece
{
    public function __construct(
        private readonly PieceType $type,
        private readonly Color $color,
    ) {
    }

    public function type(): PieceType
    {
        return $this->type;
    }

    public function color(): Color
    {
        return $this->color;
    }

    public function isColor(Color $color): bool
    {
        return $this->color === $color;
    }
}
