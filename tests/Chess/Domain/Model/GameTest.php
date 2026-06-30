<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Model;

use Chess\Domain\Model\Game;
use Chess\Domain\Value\Color;
use Chess\Domain\Value\Move;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    public function testGameStartsWithWhiteTurn(): void
    {
        $game = Game::start();

        self::assertSame(Color::White, $game->currentTurn());
    }

    public function testGameIsNotOverWhenStarted(): void
    {
        $game = Game::start();

        self::assertFalse($game->isOver());
    }

    public function testPlayingAMoveAlternatesTurn(): void
    {
        $game = Game::start();

        $game->play(Move::fromString('e2', 'e4'));

        self::assertSame(Color::Black, $game->currentTurn());
    }

    public function testBlackCanPlayAfterWhite(): void
    {
        $game = Game::start();

        $game->play(Move::fromString('e2', 'e4'));
        $game->play(Move::fromString('e7', 'e5'));

        self::assertSame(Color::White, $game->currentTurn());
    }

    public function testPlayingFromEmptySquareThrowsException(): void
    {
        $game = Game::start();

        $this->expectException(\DomainException::class);

        $game->play(Move::fromString('e4', 'e5'));
    }

    public function testPlayingWithWrongColorThrowsException(): void
    {
        $game = Game::start();

        $this->expectException(\DomainException::class);

        $game->play(Move::fromString('e7', 'e5'));
    }
}
