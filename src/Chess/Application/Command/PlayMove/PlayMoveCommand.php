<?php

declare(strict_types=1);

namespace Chess\Application\Command\PlayMove;

final class PlayMoveCommand
{
    public function __construct(
        public readonly string $gameId,
        public readonly string $from,
        public readonly string $to,
    ) {
    }
}
