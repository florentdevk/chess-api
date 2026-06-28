# Chess API

A REST API built with **PHP 8.4 / Symfony 8.1** implementing a chess game engine,
designed with **Domain-Driven Design (DDD)** principles and a strict layered architecture.

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

- [x] Phase 0 — Project setup
- [ ] Phase 1 — Domain modeling (Value Objects, Entities, Aggregate Root)
- [ ] Phase 2 — Application layer (CQRS, Symfony Messenger)
- [ ] Phase 3 — Infrastructure (Doctrine, REST API)
- [ ] Phase 4 — PHPStan level 8, OpenAPI docs