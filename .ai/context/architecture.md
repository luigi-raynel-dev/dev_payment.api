# Architecture:

Controller

↓

Application

↓

Domain

↓

Repository Interface

↓

Infrastructure

Never access Infrastructure directly from Controllers.

Business rules belong to Domain.

Use Cases belong to Application.

Repositories are abstractions.

Infrastructure contains implementations only.