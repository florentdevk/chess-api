<?php

declare(strict_types=1);

namespace Chess\Application\Command\PlayMove;

use Chess\Domain\Repository\GameRepositoryInterface;
use Chess\Domain\Service\MoveValidator;
use Chess\Domain\Value\GameId;
use Chess\Domain\Value\Move;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final class PlayMoveHandler
{
    public function __construct(
        private readonly GameRepositoryInterface $repository,
        private readonly MoveValidator $validator,
    ) {
    }

    public function __invoke(PlayMoveCommand $command): void
    {
        $game = $this->repository->findById(GameId::fromString($command->gameId));

        if (null === $game) {
            throw new \DomainException(\sprintf('Game "%s" not found.', $command->gameId));
        }

        $game->play(Move::fromString($command->from, $command->to), $this->validator);
        $this->repository->save($game);
    }
}
