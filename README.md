# Chess API

A REST API built with **PHP 8.4 / Symfony 8.1** implementing a chess game engine,
designed with **Domain-Driven Design (DDD)** principles and a strict layered architecture.

---

## Architecture

```
src/Chess/
├── Domain/         # Pure PHP business logic — zero framework dependency
├── Application/    # Command/Query handlers (CQRS via Symfony Messenger)
└── Infrastructure/ # Doctrine, Symfony controllers, API serialization
```

**Dependency rule:** dependencies always point inward toward the Domain.

---

## Domain model

| Class | DDD Pattern | Description |
|---|---|---|
| `Game` | Aggregate Root | Enforces all game invariants, orchestrates the game |
| `Board` | Entity | 8×8 grid managing piece positions |
| `GameId` | Value Object | Unique game identifier (UUID) |
| `Square` | Value Object | Algebraic notation (e2, e4…), immutable |
| `Move` | Value Object | A from → to square pair, immutable |
| `Color` | Enum | `White` / `Black` |
| `PieceType` | Enum | `King`, `Queen`, `Rook`, `Bishop`, `Knight`, `Pawn` |
| `Piece` | Entity | A piece with its color and type |

---

## Stack

- PHP 8.4 / Symfony 8.1
- PHPUnit 13 / PHPStan 2.2
- Docker / GitHub Actions

---

## Getting started

```bash
git clone https://github.com/florentdevk/chess-api.git
cd chess-api
composer install
php bin/phpunit
```

---

## Roadmap

- [x] Phase 0 — Project setup (Symfony skeleton, CI, PHPStan, CS Fixer)
- [x] Phase 1 — Domain modeling (Value Objects, Entities, Aggregate Root)
- [ ] Phase 2 — Application layer (CQRS, Symfony Messenger)
- [ ] Phase 3 — Infrastructure (Doctrine, REST API)
- [ ] Phase 4 — PHPStan level 8, OpenAPI docs