<?php

declare(strict_types=1);

namespace Chess\Tests\Application\Command\StartGame;

use Chess\Application\Command\StartGame\StartGameCommand;
use Chess\Application\Command\StartGame\StartGameHandler;
use Chess\Domain\Model\Game;
use Chess\Domain\Repository\GameRepositoryInterface;
use Chess\Domain\Value\GameId;
use PHPUnit\Framework\TestCase;

final class StartGameHandlerTest extends TestCase
{
    public function testItCreatesAndSavesAGame(): void
    {
        $repository = new class implements GameRepositoryInterface {
            public ?Game $savedGame = null;

            public function save(Game $game): void
            {
                $this->savedGame = $game;
            }

            public function findById(GameId $id): ?Game
            {
                return null;
            }
        };

        $handler = new StartGameHandler($repository);
        $gameId = $handler(new StartGameCommand());

        self::assertInstanceOf(GameId::class, $gameId);
        self::assertNotNull($repository->savedGame);
        self::assertSame($gameId->toString(), $repository->savedGame->id()->toString());
    }
}
