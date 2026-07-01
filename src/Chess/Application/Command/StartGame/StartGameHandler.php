<?php

declare(strict_types=1);

namespace Chess\Application\Command\StartGame;

use Chess\Domain\Model\Game;
use Chess\Domain\Repository\GameRepositoryInterface;
use Chess\Domain\Value\GameId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final class StartGameHandler
{
    public function __construct(
        private readonly GameRepositoryInterface $repository,
    ) {
    }

    public function __invoke(StartGameCommand $command): GameId
    {
        $game = Game::start();
        $this->repository->save($game);

        return $game->id();
    }
}
