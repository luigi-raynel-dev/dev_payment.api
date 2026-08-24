# Dev Payment API

Microsserviço de processamento de pagamentos desenvolvido com **HyperF**, seguindo princípios de **Clean Architecture**, **DDD** e boas práticas de engenharia para sistemas financeiros.

O objetivo deste projeto é servir como um portfólio técnico de um microsserviço de produção, com foco em qualidade de código, separação de responsabilidades, infraestrutura reproduzível, testes automatizados e evolução incremental.

> **Status atual: Sprint 4 em andamento.** A Sprint 3 consolidou o domínio de `Payment`, o caso de uso de criação, persistência em MySQL, repository e o endpoint `POST /payments`. A Sprint 4 inicia a evolução para eventos, mensageria e processamento assíncrono com SQS e Workers.

---

# Tecnologias

- PHP 8.4
- HyperF
- Swoole
- MySQL 8.4
- Redis 7
- Docker
- Docker Compose
- Make
- PHPUnit / testes automatizados
- AWS SQS *(Sprint 4)*
- MongoDB *(Sprint 5)*
- Prometheus / Grafana *(Sprint 6)*
- AWS / EC2 *(Sprint 7)*

---

# Objetivo do projeto

Construir uma base sólida para um microsserviço de pagamentos, evoluindo de forma incremental:

- infraestrutura profissional em Docker;
- aplicação executando com HyperF;
- Clean Architecture e DDD como fundamentos;
- domínio financeiro modelado com regras explícitas;
- persistência desacoplada por contratos;
- testes automatizados nas principais camadas;
- processamento assíncrono orientado a eventos;
- auditoria e rastreabilidade;
- observabilidade;
- infraestrutura e deploy em AWS.

---

# Sprint 3 — Payment Domain + Persistence

A Sprint 3 foi concluída com a implementação do primeiro fluxo funcional do domínio financeiro e da persistência dos pagamentos.

### Entregas concluídas

- Entidade `Payment` com regras e invariantes de domínio;
- `PaymentStatus` com estados e transições válidas;
- caso de uso `CreatePayment`;
- DTOs de entrada e saída;
- `PaymentRepositoryInterface`;
- geração de identificadores UUID através de contrato próprio;
- migration MySQL para pagamentos;
- `PaymentRepository` como adaptador de infraestrutura;
- modelo de persistência `Payment`;
- endpoint `POST /payments`;
- configuração de injeção de dependências;
- testes de domínio;
- testes do caso de uso;
- testes de integração do repository;
- testes HTTP do fluxo de criação;
- ADR-003 documentando a abordagem domain-first da sprint.

### Fluxo arquitetural validado

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

O fluxo foi implementado mantendo as regras de negócio no domínio e evitando acoplamento direto entre aplicação, controller e infraestrutura.

---

# Sprint 4 — Events and Asynchronous Processing

A Sprint 4 está em andamento e tem como objetivo introduzir o primeiro fluxo de processamento assíncrono do microsserviço.

A evolução parte do fluxo síncrono consolidado na Sprint 3:

```text
HTTP
  ↓
CreatePayment
  ↓
Payment Repository
  ↓
MySQL
```

E prepara a arquitetura para:

```text
HTTP
  ↓
CreatePayment
  ↓
Payment Repository
  ↓
MySQL
  ↓
Domain Event
  ↓
Event Publisher
  ↓
SQS
  ↓
Worker
  ↓
Event Handler
  ↓
Asynchronous Processing
```

### Escopo da Sprint

- [ ] Domain Events;
- [ ] evento `PaymentCreated`;
- [ ] contrato de publicação de eventos;
- [ ] integração com AWS SQS;
- [ ] Worker para consumo das mensagens;
- [ ] Event Handlers;
- [ ] processamento assíncrono;
- [ ] estratégia de idempotência;
- [ ] retry de mensagens com falha;
- [ ] testes do fluxo de publicação e consumo;
- [ ] documentação das decisões arquiteturais.

### Diretrizes arquiteturais

A Sprint 4 mantém o domínio e a aplicação desacoplados do mecanismo de mensageria.

O domínio não deve conhecer SQS, AWS SDK, filas, polling ou detalhes de infraestrutura. A comunicação deve ocorrer através de contratos, permitindo que a infraestrutura implemente a publicação e o consumo dos eventos.

```text
Application
    ↓
Event Publisher Interface
    ↓
Infrastructure Adapter
    ↓
AWS SQS
```

O Worker também deve permanecer separado do transporte, recebendo mensagens e encaminhando eventos para handlers responsáveis pelo processamento.

### Confiabilidade

Como o SQS utiliza entrega **at-least-once**, a Sprint 4 considera idempotência como requisito do processamento assíncrono. O identificador único do evento deverá permitir detectar e evitar processamento duplicado.

A mensagem deverá ser removida da fila somente após o processamento bem-sucedido. Em caso de falha, deverá permanecer disponível para retry conforme a configuração da fila e do Worker.

O **Outbox Pattern** não faz parte da implementação inicial desta Sprint. Ele será considerado como uma evolução futura para tratar a consistência entre a persistência transacional no MySQL e a publicação de eventos.

---

# Arquitetura

A aplicação segue uma estrutura inspirada em **Clean Architecture** e **DDD**:

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

A separação das camadas permite que as regras de negócio permaneçam independentes de HTTP, banco de dados e detalhes de infraestrutura.

Com a Sprint 4, a arquitetura passa a contemplar também eventos e processamento assíncrono:

```text
                         ┌─────────────────┐
                         │   HTTP Request  │
                         └────────┬────────┘
                                  │
                                  ▼
                         ┌─────────────────┐
                         │    Controller   │
                         └────────┬────────┘
                                  │
                                  ▼
                         ┌─────────────────┐
                         │   CreatePayment │
                         └────────┬────────┘
                                  │
                    ┌─────────────┴─────────────┐
                    │                           │
                    ▼                           ▼
             ┌─────────────┐             ┌──────────────┐
             │ Repository  │             │ Domain Event │
             └──────┬──────┘             └──────┬───────┘
                    │                           │
                    ▼                           ▼
                 MySQL                  Event Publisher
                                                │
                                                ▼
                                         ┌─────────────┐
                                         │     SQS     │
                                         └──────┬──────┘
                                                │
                                                ▼
                                         ┌─────────────┐
                                         │   Worker    │
                                         └──────┬──────┘
                                                │
                                                ▼
                                         Event Handler
```

---

# Requisitos

Para desenvolver neste projeto é recomendado utilizar:

- Linux ou macOS

ou

- Windows 11 + WSL2 + Ubuntu

Também é necessário possuir:

- Docker Desktop
- Docker Compose
- Git
- Make

---

# Estrutura do Projeto

```text
dev-payment-api
├── docker/
├── docs/
│   ├── adr/
│   └── planning/
├── app/
│   ├── Application/
│   ├── Domain/
│   ├── Infrastructure/
│   ├── Interfaces/
│   ├── Shared/
│   └── Config/
├── migrations/
├── test/
├── docker-compose.yml
├── Makefile
├── AGENTS.md
├── changelog.md
├── readme.md
└── .env.example
```

---

# Primeiros Passos

Clone o repositório:

```bash
git clone <url-do-repositorio>
cd dev-payment-api
```

## Opção rápida: setup completo

```bash
make setup
```

## Alternativa passo a passo

```bash
make build
make up
make doctor
```

### Iniciar a aplicação

Com hot reload:

```bash
make app-watch
```

Ou sem hot reload:

```bash
make app-start
```

## Executar os testes

```bash
make app-test
```

## Verificando a aplicação

Health check:

```bash
curl http://localhost:9501/health
```

Criação de pagamento:

```bash
curl -X POST http://localhost:9501/payments \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 100.00,
    "currency": "BRL",
    "description": "Pagamento de teste"
  }'
```

## Acessando o container

```bash
make shell
```

## Derrubando os containers

```bash
make down
```

---

# Comandos úteis

| Comando | Descrição |
|----------|-----------|
| `make` | Exibe ajuda com os comandos disponíveis |
| `make setup` | Configura o ambiente completo de forma rápida |
| `make build` | Constrói a imagem Docker |
| `make up` | Sobe os containers |
| `make down` | Derruba os containers |
| `make restart` | Reinicia o ambiente |
| `make doctor` | Verifica o estado do Docker, PHP, Composer e HyperF |
| `make shell` | Entra no container da aplicação |
| `make logs` | Exibe os logs |
| `make composer-install` | Instala as dependências do Composer |
| `make composer-update` | Atualiza as dependências do Composer |
| `make composer-dump` | Gera o autoload do Composer |
| `make composer-require PACKAGE=nome/pacote` | Adiciona uma dependência do Composer |
| `make composer-remove PACKAGE=nome/pacote` | Remove uma dependência do Composer |
| `make app-start` | Inicia a aplicação HyperF |
| `make app-watch` | Inicia a aplicação HyperF em modo watch |
| `make app-test` | Executa os testes da aplicação HyperF |

---

# Documentação

Toda a documentação do projeto está em `docs/`.

- ADRs → `docs/adr`
- Planejamento → `docs/planning`
- Arquitetura base → `docs/adr/ADR-002-base-architecture.md`
- Payment Domain → `docs/adr/ADR-003-payment-domain-first.md`
- HyperF → `docs/hyperf`

---

# Roadmap

- [x] Sprint 1: infraestrutura e ambiente base
- [x] Sprint 2: HyperF + bootstrap da aplicação + health check
- [x] Sprint 3: Payment Domain + CreatePayment + persistência + repository + `POST /payments`
- [ ] **Sprint 4: Events + SQS + Worker + processamento assíncrono** ← atual
- [ ] Sprint 5: MongoDB + auditoria + event tracking
- [ ] Sprint 6: observabilidade + Prometheus + Grafana
- [ ] Sprint 7: deploy + infraestrutura AWS

> A Sprint 3 estabeleceu a primeira etapa funcional do domínio financeiro e consolidou a persistência. A Sprint 4 evolui essa base para processamento assíncrono orientado a eventos, preparando o projeto para auditoria, observabilidade e deploy nas próximas etapas.

---

# Próximas etapas

Após a conclusão da Sprint 4, o projeto deverá possuir uma base de mensageria capaz de publicar e consumir eventos de forma desacoplada.

As próximas entregas previstas são:

1. conclusão do fluxo de Events + SQS + Worker;
2. MongoDB para auditoria e rastreamento de eventos;
3. observabilidade com Prometheus e Grafana;
4. deploy e infraestrutura na AWS.

---

# Licença

MIT
