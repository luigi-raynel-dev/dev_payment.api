# ADR-004 — Domain Events

- **Status:** Accepted
- **Sprint:** Sprint 04

---

## Context

The payment domain is currently capable of creating and persisting a `Payment`, but the next stage of the project requires processing actions outside the synchronous HTTP request.

The application needs a way to communicate that an important business event has occurred without coupling the domain to infrastructure technologies such as AWS SQS.

The main architectural question is how the project should represent and publish events produced by the domain while preserving the separation established by the existing Clean Architecture and DDD approach.

The distinction between a **domain event** and a **transport message** is important. A domain event represents something that happened in the business domain, while a message is an infrastructure representation used to transport that event between processes.

---

## Decision

The project will adopt **Domain Events** as the mechanism for representing relevant occurrences in the payment domain.

For Sprint 04, the first domain event will be `PaymentCreated`, representing the successful creation of a payment.

Domain events will:

- belong to the domain/application boundary rather than the infrastructure layer;
- represent facts that already happened in the domain;
- be immutable after creation;
- contain a unique `event_id`;
- contain an `occurred_at` timestamp;
- contain an explicit event type;
- carry only the data required by consumers of the event;
- remain independent of SQS, AWS SDKs and other transport technologies.

The expected conceptual flow is:

```text
Payment
   │
   │ payment successfully created
   ▼
PaymentCreated
   │
   ▼
Event Publisher Interface
   │
   ▼
Infrastructure Adapter
```

The domain event must not contain infrastructure concerns such as queue names, AWS credentials, SQS message attributes or visibility timeout configuration.

The infrastructure layer is responsible for converting the domain event into the transport-specific message format required by the selected message broker.

---

## Alternatives Considered

### 1. Publish SQS messages directly from the Payment domain

#### Advantages

- simple initial implementation;
- fewer abstractions;
- direct integration with the selected infrastructure.

#### Disadvantages

- couples the domain to AWS and SQS;
- makes domain tests dependent on infrastructure concerns;
- makes replacing the message broker harder;
- violates the architectural separation already established by the project.

**Rejected.**

### 2. Use generic application messages without domain events

#### Advantages

- simpler conceptual model;
- fewer domain abstractions.

#### Disadvantages

- weak representation of business facts;
- application concerns become responsible for understanding domain changes;
- makes event-driven evolution of the domain less explicit.

**Rejected.**

### 3. Adopt Domain Events (Chosen)

#### Advantages

- keeps business events explicit;
- preserves domain independence from infrastructure;
- supports asynchronous processing;
- improves testability;
- allows the transport mechanism to evolve independently;
- aligns with the existing DDD/Clean Architecture decisions.

#### Disadvantages

- introduces additional concepts and abstractions;
- requires clear distinction between domain events and transport messages;
- event contracts must be treated as stable interfaces for consumers.

**Chosen.**

---

## Consequences

### Positive

- The domain remains independent from SQS and AWS.
- Business events become explicit and discoverable.
- The application can publish events through an abstraction.
- Future consumers can be added without changing the meaning of the domain event.
- Testing domain behavior remains independent from messaging infrastructure.
- The architecture is prepared for future event-driven features such as audit logging and event tracking.

### Negative

- More abstractions are introduced compared with directly publishing messages.
- Event contracts require versioning and backward-compatibility discipline as the system evolves.
- Developers must understand the difference between domain events and infrastructure messages.

---

## Notes

`PaymentCreated` is the first event introduced by the project, but the decision applies to future relevant domain events as well.

This ADR intentionally does not define the delivery mechanism. The message broker and transport concerns are addressed separately by ADR-005.

The Outbox Pattern is not adopted as part of this decision. The consistency problem between database persistence and event publication is intentionally deferred to a future architectural decision.
