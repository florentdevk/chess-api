<?php

declare(strict_types=1);

namespace Chess\Domain\Value;

final class Move
{
    public function __construct(
        private readonly Square $from,
        private readonly Square $to,
    ) {
        if ($from->equals($to)) {
            throw new \InvalidArgumentException(\sprintf('A move must go to a different square than "%s".', $from->toString()));
        }
    }

    public static function fromString(string $from, string $to): self
    {
        return new self(
            Square::fromString($from),
            Square::fromString($to),
        );
    }

    public function from(): Square
    {
        return $this->from;
    }

    public function to(): Square
    {
        return $this->to;
    }

    public function equals(self $other): bool
    {
        return $this->from->equals($other->from)
            && $this->to->equals($other->to);
    }
}
