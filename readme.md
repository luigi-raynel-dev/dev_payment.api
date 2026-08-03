# Dev Payment API

Microsserviço responsável por receber solicitações de autorização de pagamentos.

## Stack

- PHP 8.4
- HyperF
- Swoole
- MySQL
- Redis
- AWS SQS
- Docker

## Arquitetura

Clean Architecture

DDD (simplificado)

Repository Pattern

SOLID

## Fluxo

Cliente

↓

Payment API

↓

MySQL

↓

SQS

↓

Worker