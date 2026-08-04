# ADR-002 — Base Architecture

- **Status:** Accepted
- **Sprint:** Sprint 02

---

# Context

Após concluir a infraestrutura do projeto na Sprint 01, foi necessário definir a arquitetura que servirá de base para todo o microsserviço.

O HyperF oferece uma estrutura padrão para aplicações, porém ela tende a centralizar responsabilidades e não incentiva explicitamente a separação entre domínio, aplicação e infraestrutura.

Como este projeto tem como objetivo simular um ambiente de produção inspirado em empresas como grandes fintechs, optou-se por definir uma arquitetura desde o início, evitando grandes refatorações conforme o sistema evoluir.

---

# Decision

Adotar uma arquitetura inspirada em **Clean Architecture** e **Domain-Driven Design (DDD)**.

A estrutura da aplicação será organizada em camadas com responsabilidades bem definidas, priorizando baixo acoplamento, alta coesão e facilidade de manutenção.

Estrutura proposta:

```text
app/
├── Application/
├── Domain/
├── Infrastructure/
├── Interfaces/
│   └── Http/
├── Shared/
└── Config/
```

Cada camada terá responsabilidades específicas:

- **Application**: Casos de uso e orquestração da aplicação.
- **Domain**: Entidades, regras de negócio, Value Objects, Services e contratos.
- **Infrastructure**: Implementações técnicas (banco de dados, Redis, SQS, APIs externas, etc.).
- **Interfaces**: Controllers, Requests, Responses e demais pontos de entrada da aplicação.
- **Shared**: Componentes compartilhados entre módulos.
- **Config**: Configurações específicas da aplicação.

---

# Alternatives Considered

## Utilizar a estrutura padrão do HyperF

### Vantagens

- Menor curva de aprendizado.
- Desenvolvimento inicial mais rápido.
- Menor quantidade de arquivos.

### Desvantagens

- Maior acoplamento entre camadas.
- Mistura entre regras de negócio e infraestrutura.
- Maior esforço de refatoração conforme o projeto cresce.

---

## Adotar Clean Architecture desde o início (Escolhida)

### Vantagens

- Separação clara de responsabilidades.
- Maior facilidade para testes automatizados.
- Código mais desacoplado.
- Evolução gradual sem necessidade de grandes refatorações.
- Arquitetura semelhante à utilizada em microsserviços de produção.

### Desvantagens

- Maior quantidade de arquivos inicialmente.
- Curva de aprendizado mais elevada.
- Maior esforço nas primeiras Sprints.

---

# Consequences

## Positivas

- Código mais organizado.
- Maior facilidade para manutenção.
- Melhor testabilidade.
- Facilita a implementação de novas funcionalidades.
- Redução do acoplamento.
- Estrutura preparada para crescimento.
- Facilita futuras integrações com Redis, SQS, MongoDB e AWS.

## Negativas

- Desenvolvimento inicial um pouco mais lento.
- Maior quantidade de abstrações.
- Mais arquivos para funcionalidades simples.

---

# Notes

Esta decisão está alinhada com os objetivos do projeto de servir como portfólio técnico, demonstrando práticas modernas de arquitetura de software utilizadas em sistemas distribuídos e microsserviços de alta disponibilidade.

A adoção desta arquitetura também facilita a aplicação de princípios SOLID, Design Patterns e boas práticas de engenharia ao longo das próximas Sprints.