<?php

declare(strict_types=1);

namespace Chess\Domain\Value;

final class Square
{
    private const FILES = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    private const RANKS = [1, 2, 3, 4, 5, 6, 7, 8];

    public function __construct(
        private readonly string $file,
        private readonly int $rank,
    ) {
        if (!in_array($file, self::FILES, strict: true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid file "%s". Must be a letter between a and h.', $file)
            );
        }

        if (!in_array($rank, self::RANKS, strict: true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid rank "%d". Must be a number between 1 and 8.', $rank)
            );
        }
    }

    public static function fromString(string $notation): self
    {
        if (strlen($notation) !== 2) {
            throw new \InvalidArgumentException(
                sprintf('Invalid square notation "%s". Expected format: e4.', $notation)
            );
        }

        return new self($notation[0], (int) $notation[1]);
    }

    public function file(): string
    {
        return $this->file;
    }

    public function rank(): int
    {
        return $this->rank;
    }

    public function equals(self $other): bool
    {
        return $this->file === $other->file
            && $this->rank === $other->rank;
    }

    public function toString(): string
    {
        return $this->file . $this->rank;
    }
}
