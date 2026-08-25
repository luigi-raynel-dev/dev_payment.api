# ADR-005 — SQS as Message Broker

- **Status:** Accepted
- **Sprint:** Sprint 04

---

## Context

Sprint 04 introduces asynchronous processing to the payment microsservice. Domain events need to be transported from the API process to an independent consumer responsible for processing them outside the synchronous HTTP request.

The project therefore needs a message broker/queue that provides reliable asynchronous delivery, supports independent producers and consumers, and can evolve toward the AWS deployment planned for later Sprints.

The existing architecture already separates infrastructure concerns from the application and domain layers. The selected messaging technology must therefore be implemented behind an infrastructure adapter rather than being exposed directly to the domain.

---

## Decision

The project will use **Amazon Simple Queue Service (AWS SQS)** as the message broker for asynchronous event processing.

For local development and automated integration tests, the project should use an AWS-compatible local environment rather than requiring a real AWS account for every developer or test execution.

The SQS integration will follow these principles:

- the domain will not depend directly on SQS;
- application code will depend on an event publishing abstraction;
- SQS-specific behavior will be implemented in the infrastructure layer;
- messages will use a JSON-based transport envelope;
- each message will carry the domain event identifier and event type;
- messages will only be deleted after successful processing by the consumer;
- failed processing will leave the message available for retry according to SQS visibility and redelivery behavior;
- long polling should be used by the Worker to reduce unnecessary polling requests;
- queue configuration must be provided through environment/configuration rather than hard-coded values.

The conceptual architecture is:

```text
Domain Event
     │
     ▼
EventPublisher Interface
     │
     ▼
SQS Infrastructure Adapter
     │
     ▼
    SQS
     │
     ▼
   Worker
     │
     ▼
 Event Handler
```

### Delivery Semantics

The system must be designed around **at-least-once delivery**. A message may be delivered more than once, therefore consumers must not assume that receiving a message implies that it is unique.

This constraint directly leads to the idempotency decision documented in ADR-006.

### Message Lifecycle

A message follows this lifecycle:

```text
Receive message
      │
      ▼
Process message
      │
   ┌──┴──┐
   │     │
Success Failure
   │     │
   ▼     ▼
Delete  Retry
```

The Worker must not delete a message before its processing has completed successfully.

---

## Alternatives Considered

### 1. RabbitMQ

#### Advantages

- mature messaging platform;
- rich routing capabilities;
- supports multiple messaging patterns;
- strong ecosystem.

#### Disadvantages

- introduces an additional broker infrastructure to operate;
- requires more operational configuration;
- less aligned with the project's future AWS deployment;
- provides more messaging features than required for the initial use case.

**Rejected for Sprint 04.**

### 2. Apache Kafka

#### Advantages

- high throughput;
- durable event streaming;
- strong ecosystem for event-driven architectures;
- useful for large-scale event streams.

#### Disadvantages

- significantly higher operational complexity;
- requires more infrastructure and configuration;
- unnecessary for the current workload and scope;
- introduces concepts such as partitions, offsets and consumer groups that are not required for the first asynchronous flow.

**Rejected for Sprint 04.**

### 3. Redis Streams

#### Advantages

- Redis is already part of the project's infrastructure;
- simple local setup;
- supports stream-based asynchronous processing.

#### Disadvantages

- would make Redis responsible for a role that is better represented by a dedicated managed queue in the target AWS architecture;
- requires additional operational decisions around durability and stream management;
- does not provide the same managed queue model as SQS.

**Rejected for Sprint 04.**

### 4. AWS SQS (Chosen)

#### Advantages

- fully managed queue service;
- native AWS integration;
- simple producer/consumer model;
- supports asynchronous decoupling;
- provides at-least-once delivery;
- supports visibility timeout and retry behavior;
- supports Dead Letter Queues;
- fits the AWS deployment planned for future Sprints;
- low operational overhead.

#### Disadvantages

- introduces AWS-specific infrastructure;
- local development requires an AWS-compatible emulator or test environment;
- at-least-once delivery requires explicit idempotency handling;
- more advanced routing patterns may require additional AWS services.

**Chosen.**

---

## Consequences

### Positive

- API and Worker processes become decoupled.
- Asynchronous processing can scale independently from HTTP traffic.
- The project gains a production-oriented messaging foundation.
- Future consumers can be introduced without changing the HTTP interface.
- The architecture is aligned with the planned AWS deployment.
- Retry and visibility semantics are provided by the queue infrastructure.

### Negative

- The project becomes dependent on an external messaging infrastructure.
- Local development requires an AWS-compatible SQS environment.
- Consumers must be designed for duplicate message delivery.
- Queue configuration, monitoring and failure handling become operational concerns.

---

## Notes

A Dead Letter Queue is considered part of the reliability strategy, but its exact configuration is intentionally left to the implementation and operational documentation of the Worker.

The Outbox Pattern is not adopted in Sprint 04. The potential consistency gap between MySQL persistence and SQS publication will be evaluated separately in a future ADR if the project requires stronger delivery guarantees.
