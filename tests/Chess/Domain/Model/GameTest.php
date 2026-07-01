<?php

declare(strict_types=1);

namespace Chess\Tests\Domain\Model;

use Chess\Domain\Model\Game;
use Chess\Domain\Service\MoveValidator;
use Chess\Domain\Value\Color;
use Chess\Domain\Value\Move;
use PHPUnit\Framework\TestCase;

final class GameTest extends TestCase
{
    private MoveValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new MoveValidator();
    }

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

        $game->play(Move::fromString('e2', 'e4'), $this->validator);

        self::assertSame(Color::Black, $game->currentTurn());
    }

    public function testBlackCanPlayAfterWhite(): void
    {
        $game = Game::start();

        $game->play(Move::fromString('e2', 'e4'), $this->validator);
        $game->play(Move::fromString('e7', 'e5'), $this->validator);

        self::assertSame(Color::White, $game->currentTurn());
    }

    public function testPlayingFromEmptySquareThrowsException(): void
    {
        $game = Game::start();

        $this->expectException(\DomainException::class);

        $game->play(Move::fromString('e4', 'e5'), $this->validator);
    }

    public function testPlayingWithWrongColorThrowsException(): void
    {
        $game = Game::start();

        $this->expectException(\DomainException::class);

        $game->play(Move::fromString('e7', 'e5'), $this->validator);
    }
}
