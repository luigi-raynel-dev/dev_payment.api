# Changelog

All notable changes to this project will be documented in this file.

This project follows the principles of Keep a Changelog.

---

## [0.3.0] - 2026-08-21

### Added

- Sprint 3 planning for the Payment Domain and creation flow.
- `Payment` domain entity with business invariants and validation rules.
- `PaymentStatus` with explicit payment states and valid transitions.
- `CreatePayment` application use case.
- Input and output DTOs for payment creation.
- `PaymentRepositoryInterface` as the persistence port.
- `IdGeneratorInterface` and UUID-based ID generation.
- MySQL migration for the payments table.
- `PaymentRepository` infrastructure adapter for persistence.
- `Payment` persistence model and domain-to-infrastructure mapping.
- `POST /payments` endpoint for payment creation.
- Dependency injection configuration for the payment flow.
- Unit tests for the Payment domain.
- Application tests for the `CreatePayment` use case.
- Integration tests for the payment repository.
- HTTP tests for `POST /payments`.
- ADR-003 documenting the decision to prioritize the payment domain before external integrations.

### Changed

- README updated to reflect the completed Sprint 3 scope and the current project state.
- Roadmap updated to mark the Payment Domain, persistence and repository work as completed.
- Application architecture now demonstrates the expected flow from HTTP through Interface, Application, Domain, Repository Interface and Infrastructure.
- Payment persistence was implemented without moving business rules into controllers or infrastructure adapters.
- Composer dependencies and lockfile updated for the Sprint 3 implementation.

### Validated

- Payment domain rules are covered by automated tests.
- Payment creation use case is covered by automated tests.
- Repository persistence and retrieval are covered by integration tests.
- HTTP payment creation flow is covered by endpoint tests.
- Sprint 3 Definition of Done and acceptance criteria are satisfied.

---

## [0.2.0] - 2026-08-12

### Added

- Sprint 2 planning for HyperF bootstrap.
- Definition of Done for the health-check foundation.
- Architectural decision to adopt Clean Architecture and DDD from the start.
- README updated to reflect the Sprint 2 goals and application bootstrap scope.
- Roadmap updated with the next delivery stage.
- Project documentation aligned with the early architecture decision.
- Implemented `/health` endpoint with liveness response and versioned health contract.

### Changed

- README revised to describe the project foundation and Sprint 2 focus.
- Architecture narrative updated to emphasize the base layer before domain implementation.
- Planning documents aligned with the scope definition for the next sprint.

---

## [0.1.0] - 2026-08-04

### Added

- Initial project structure.
- Professional Docker environment.
- Docker Compose configuration.
- PHP 8.4 image.
- MySQL 8.4 service.
- Redis 7 service.
- Makefile with development commands.
- Initial README.
- ADR-001 documenting UUID as primary keys.
- AI context documentation.
- AGENTS.md for AI assistants.
- Sprint planning documentation.
