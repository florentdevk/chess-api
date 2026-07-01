<?php

declare(strict_types=1);

namespace Chess\Domain\Repository;

use Chess\Domain\Model\Game;
use Chess\Domain\Value\GameId;

interface GameRepositoryInterface
{
    public function save(Game $game): void;

    public function findById(GameId $id): ?Game;
}
