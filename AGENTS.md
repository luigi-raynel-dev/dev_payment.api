# Dev Payment API

## Project Goal

Production-grade payment microservice.

## Tech Stack

- HyperF
- PHP 8.4
- MySQL
- Redis
- Swoole

## Architecture

Clean Architecture

DDD

SOLID

## Rules

Never generate business logic inside controllers.

Never access infrastructure directly.

Always create interfaces before implementations.

Always create tests when creating new business rules.

Always explain architectural decisions.

Always follow PSR-12.

For Sprint 3, implement the Payment domain first and only then move outward through the application, interface, and infrastructure layers.

The expected flow is: HTTP -> Interface -> Application -> Domain -> Repository Interface -> Infrastructure -> MySQL.

Do not start the sprint with controllers, database access, or model classes without first defining the domain behavior.