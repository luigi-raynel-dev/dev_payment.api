# ADR-006 — Idempotent Event Processing

- **Status:** Accepted
- **Sprint:** Sprint 04

---

## Context

The adoption of SQS in Sprint 04 introduces an important delivery characteristic: messages must be treated as **at-least-once delivered**.

This means a Worker may receive the same event more than once. A consumer that performs business operations without detecting duplicate events could execute the same operation multiple times.

For a payment system, duplicate processing can lead to incorrect state transitions, duplicated side effects or inconsistent downstream data.

The architecture therefore needs an explicit strategy to make event processing idempotent.

---

## Decision

Event consumers will implement **idempotent processing based on a unique `event_id`**.

Every domain event must contain a globally unique `event_id`. The Worker/handler processing pipeline will use this identifier to determine whether an event has already been successfully processed.

The system will maintain a durable record of successfully processed event identifiers. For the current architecture, this record will be persisted in **MySQL**, which is already the system's primary durable relational store.

Conceptually:

```text
SQS Message
     │
     ▼
 event_id
     │
     ▼
Check processed event
     │
 ┌───┴────┐
 │        │
Exists   New
 │        │
 ▼        ▼
Skip    Process
          │
          ▼
   Mark as processed
```

The processed-event record must have a uniqueness constraint on `event_id` so that concurrent or duplicated attempts cannot create multiple records for the same event.

A message should only be considered successfully processed after the business operation and the corresponding idempotency record have been persisted successfully.

When possible, the business state change and the processed-event record should be committed within the same database transaction. This prevents a successful business operation from being recorded without its corresponding idempotency state, or vice versa.

### Duplicate Message Behavior

If an event has already been successfully processed:

1. the Worker must not execute the business operation again;
2. the event should be treated as successfully handled;
3. the SQS message may then be acknowledged/deleted.

### Failed Processing

If processing fails before the successful completion of the transaction:

- the event must not be marked as successfully processed;
- the SQS message must remain available for retry;
- the next attempt must be able to process the event again.

---

## Alternatives Considered

### 1. No idempotency mechanism

#### Advantages

- simplest implementation;
- no additional persistence;
- lower initial development effort.

#### Disadvantages

- unsafe with at-least-once delivery;
- duplicate messages may cause duplicate business operations;
- unsuitable for a payment-oriented system;
- failures become difficult to recover safely.

**Rejected.**

### 2. Store processed event identifiers in Redis

#### Advantages

- fast lookups;
- Redis is already part of the project infrastructure;
- simple implementation.

#### Disadvantages

- requires TTL/retention decisions;
- an expired key could allow the same event to be processed again;
- idempotency state is operationally more critical than ordinary cache data;
- introduces additional consistency concerns between Redis and MySQL.

**Rejected as the primary strategy.**

### 3. Store processed event identifiers in MySQL (Chosen)

#### Advantages

- durable persistence;
- already part of the application's primary data infrastructure;
- supports unique constraints;
- can participate in the same transaction as business state changes;
- no additional infrastructure is required for the first implementation.

#### Disadvantages

- introduces an additional table and database writes;
- very high event volumes may require future optimization or partitioning;
- idempotency checks depend on database availability.

**Chosen.**

### 4. Use only the Payment identifier as the deduplication key

#### Advantages

- no additional event identifier storage;
- simple for payment-specific events.

#### Disadvantages

- the same payment may legitimately produce multiple different events;
- cannot distinguish two valid events associated with the same payment;
- couples idempotency to a specific domain entity instead of the message itself.

**Rejected.**

---

## Consequences

### Positive

- Duplicate SQS deliveries can be safely handled.
- Event consumers become resilient to at-least-once delivery.
- Business operations can be protected from duplicate execution.
- MySQL transactions can provide atomicity between business changes and idempotency state.
- The strategy can be reused by future event handlers.

### Negative

- Every asynchronous event requires a unique identifier.
- Processing requires an additional persistence operation.
- The system must retain processed-event records according to an appropriate lifecycle policy.
- The database becomes part of the reliability path for event consumption.

---

## Notes

The exact schema and lifecycle/retention policy for processed events belong to the implementation of Sprint 04 and should not be confused with the audit/event-tracking model planned for Sprint 05.

The idempotency record answers the operational question **"Has this event already been successfully processed?"**. It is not intended to become the project's business audit log.

The Outbox Pattern remains outside the scope of this ADR. Outbox addresses reliable publication between database state and message delivery, while this ADR addresses safe consumption of messages that have already reached the queue.
