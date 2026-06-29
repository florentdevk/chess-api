<?php

declare(strict_types=1);

namespace Chess\Domain\Value;

enum PieceType
{
    case King;
    case Queen;
    case Rook;
    case Bishop;
    case Knight;
    case Pawn;
}
