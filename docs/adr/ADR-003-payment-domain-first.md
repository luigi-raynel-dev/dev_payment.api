# ADR-003 — Payment Domain First

- **Status:** Accepted
- **Sprint:** Sprint 03

---

## Context

A Sprint 2 consolidou a infraestrutura da aplicação e definiu uma base arquitetural inspirada em Clean Architecture e DDD. Agora, o próximo passo é iniciar o domínio financeiro do microsserviço.

A tentação, neste momento, é começar pela rota HTTP, criar o controller e partir para o banco. Esse caminho seria rápido, mas iria ocultar a arquitetura que foi definida e misturaria regras de negócio com infraestrutura.

Como o objetivo do projeto é demonstrar arquitetura profissional, é necessário que a primeira funcionalidade de pagamento reflita o fluxo correto de responsabilidade entre camadas.

---

## Decision

A Sprint 3 deve começar pela modelagem do domínio de `Payment`, seguido do caso de uso de criação e, somente depois, da implementação da infraestrutura e do endpoint HTTP.

A implementação deve seguir a ordem de dependência abaixo:

```text
HTTP
  ↓
Interface
  ↓
Application / Use Case
  ↓
Domain
  ↓
Repository Interface
  ↓
Infrastructure
  ↓
MySQL
```

Isso significa que:

- a entidade `Payment` será definida primeiro;
- as regras de negócio e invariantes serão estabelecidas no domínio;
- o caso de uso `CreatePayment` orquestrará a criação do pagamento;
- o repositório será exposto por interface;
- a infraestrutura implementará a persistência real;
- o controller será apenas um adaptador de entrada/saída.

---

## Alternatives Considered

### 1. Iniciar pelo controller

#### Vantagens

- desenvolvimento inicial mais rápido;
- menos abstrações no começo;
- implementação funcional imediata.

#### Desvantagens

- mistura de regras de negócio com tecnologia;
- acoplamento com banco e HTTP;
- baixa testabilidade do domínio;
- dificulta a demonstração da arquitetura desejada.

### 2. Iniciar pelo domínio (Escolhida)

#### Vantagens

- regras de negócio no lugar correto;
- maior clareza da modelagem de pagamentos;
- melhor coesão e menor acoplamento;
- facilita testes e evolução futura;
- mantém a arquitetura alinhada com Clean Architecture e DDD.

#### Desvantagens

- exige mais planejamento antes da implementação;
- demanda mais atenção à modelagem e invariantes do domínio;
- exige rigor ao definir contratos e responsabilidades.

---

## Consequences

### Positivas

- a implementação real demonstra a arquitetura acordada;
- regras de negócio ficam isoladas e mais fáceis de testar;
- a aplicação se torna mais previsível e sustentável;
- o código reflete melhor o objetivo do microsserviço financeiro.

### Negativas

- a primeira entrega funcional leva mais tempo;
- exige maior rigor de modelagem antes da codificação;
- pode parecer mais "teórico" para quem busca uma implementação rápida.

---

## Notes

Esta decisão é importante porque o projeto não deve mostrar apenas uma estrutura de pastas bonitinha. O objetivo é demonstrar como uma aplicação de pagamentos de verdade organiza responsabilidades em camadas, separando domínio, aplicação, interface e infraestrutura.

A Sprint 3 será a primeira oportunidade de validar esse princípio em funcionamento.
