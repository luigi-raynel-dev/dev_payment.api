# Dev Payment API

Microsserviço de processamento de pagamentos desenvolvido com **HyperF**, seguindo princípios de **Clean Architecture**, **DDD** e boas práticas de engenharia para sistemas financeiros.

O objetivo deste projeto é servir como um portfólio técnico de um microsserviço de produção, com foco em qualidade de código, separação de responsabilidades, infraestrutura reproduzível, testes automatizados e evolução incremental.

> **Status atual: Sprint 3 concluída.** O domínio de `Payment`, o caso de uso de criação, persistência em MySQL e o endpoint `POST /payments` estão implementados e cobertos por testes automatizados.

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
- AWS SQS *(sprint futura)*

---

# Objetivo do projeto

Construir uma base sólida para um microsserviço de pagamentos, evoluindo de forma incremental:

- infraestrutura profissional em Docker;
- aplicação executando com HyperF;
- Clean Architecture e DDD como fundamentos;
- domínio financeiro modelado com regras explícitas;
- persistência desacoplada por contratos;
- testes automatizados nas principais camadas;
- evolução futura para mensageria, auditoria, observabilidade e AWS.

---

# Sprint 3 — Payment Domain

A Sprint 3 foi concluída com a implementação do primeiro fluxo funcional do domínio financeiro.

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

### Validação

A Sprint 3 atende aos critérios definidos no planejamento:

- domínio de `Payment` modelado com invariantes;
- `CreatePayment` implementado como caso de uso;
- repository exposto por interface e implementado na infraestrutura;
- criação de pagamento disponível através de `POST /payments`;
- regras de domínio, aplicação, persistência e HTTP cobertas por testes;
- documentação e roadmap atualizados de acordo com a implementação real.

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
- [ ] Sprint 5: mensageria e workers com SQS
- [ ] Sprint 6: MongoDB e auditoria
- [ ] Sprint 7: observabilidade e monitoramento
- [ ] Sprint 8: deploy e infraestrutura AWS

> A Sprint 3 representa a primeira etapa funcional do domínio financeiro e estabelece a base para processamento assíncrono, auditoria, observabilidade e deploy nas próximas etapas.

---

# Próximas etapas

Após a conclusão da Sprint 3, o projeto pode evoluir para processamento assíncrono e integração orientada a eventos, mantendo o domínio desacoplado dos mecanismos de infraestrutura.

As próximas entregas previstas são:

1. SQS e workers;
2. publicação e consumo de eventos;
3. MongoDB para auditoria;
4. observabilidade com Prometheus e Grafana;
5. deploy na AWS.

---

# Licença

MIT
