<?php

declare(strict_types=1);

namespace Chess\Tests\Application\Command\PlayMove;

use Chess\Application\Command\PlayMove\PlayMoveCommand;
use Chess\Application\Command\PlayMove\PlayMoveHandler;
use Chess\Domain\Model\Game;
use Chess\Domain\Repository\GameRepositoryInterface;
use Chess\Domain\Service\MoveValidator;
use Chess\Domain\Value\Color;
use Chess\Domain\Value\GameId;
use PHPUnit\Framework\TestCase;

final class PlayMoveHandlerTest extends TestCase
{
    public function testItPlaysAMoveOnAnExistingGame(): void
    {
        $game = Game::start();

        $repository = new class($game) implements GameRepositoryInterface {
            public ?Game $savedGame = null;

            public function __construct(private readonly Game $game)
            {
            }

            public function save(Game $game): void
            {
                $this->savedGame = $game;
            }

            public function findById(GameId $id): Game
            {
                return $this->game;
            }
        };

        $handler = new PlayMoveHandler($repository, new MoveValidator());
        $handler(new PlayMoveCommand($game->id()->toString(), 'e2', 'e4'));

        self::assertNotNull($repository->savedGame);
        self::assertSame(Color::Black, $repository->savedGame->currentTurn());
    }

    public function testItThrowsWhenGameNotFound(): void
    {
        $repository = new class implements GameRepositoryInterface {
            public function save(Game $game): void
            {
            }

            public function findById(GameId $id): ?Game
            {
                return null;
            }
        };

        $this->expectException(\DomainException::class);

        $handler = new PlayMoveHandler($repository, new MoveValidator());
        $handler(new PlayMoveCommand('unknown-id', 'e2', 'e4'));
    }
}
