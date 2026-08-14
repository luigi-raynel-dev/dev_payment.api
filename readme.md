# Dev Payment API

Microsserviço de processamento de pagamentos desenvolvido com **HyperF**, seguindo princípios de arquitetura limpa, DDD e boas práticas de engenharia para sistemas financeiros.

O objetivo deste projeto é servir como um portfolio técnico de um microsserviço de produção, com foco em qualidade de código, organização de camadas, infraestrutura reproduzível e evolução gradual.

> Status atual: Sprint 3 em andamento; foco no domínio de Payment, regras de negócio e primeiro fluxo de criação de pagamento.

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
- PHPUnit / Pest *(sprint futura)*
- AWS SQS *(sprint futura)*

---

# Objetivo do projeto

Construir uma base sólida para um microsserviço de pagamentos, com:

- infraestrutura profissional em Docker;
- aplicação executando com HyperF;
- estrutura de arquitetura preparada para Clean Architecture e DDD;
- endpoints iniciais para observabilidade e saúde da aplicação;
- evolução controlada para regras de negócio e integrações.

---

# Sprint atual

## Sprint 3 — Payment Domain

A Sprint 3 tem como foco principal definir e implementar o domínio de pagamentos, iniciando pela entidade `Payment` e pelo primeiro fluxo de criação de pagamento.

### Objetivo

Construir a base do domínio financeiro do microsserviço respeitando a arquitetura definida na Sprint 2:

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

### Definition of Done

Ao final desta sprint, o projeto deve permitir:

- modelar a entidade `Payment` com regras de domínio;
- definir estados e transições válidas;
- criar o caso de uso `CreatePayment`;
- implementar o contrato do repository e persistência em MySQL;
- expor `POST /payments` sem lógica de negócio no controller;
- cobrir a funcionalidade com testes de domínio, aplicação e HTTP.

### Entregas esperadas

- `Payment` entity e invariantes do domínio;
- `PaymentRepositoryInterface` e contratos de aplicação;
- caso de uso `CreatePayment`;
- migration/tabela de pagamentos;
- endpoint HTTP para criação;
- testes e documentação atualizados.

---

# Arquitetura

A arquitetura base do microsserviço seguirá uma estrutura inspirada em Clean Architecture e DDD:

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

Essa organização foi definida antes do início do desenvolvimento de regras de negócio para evitar refatorações futuras.

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

Se você quiser iniciar o ambiente de forma automatizada, o comando abaixo já sobe os containers, instala as dependências do Composer e inicia a aplicação HyperF:

```bash
make setup
```

## Alternativa passo a passo

Se preferir rodar os comandos um por um:

```bash
make build
make up
make doctor
```

### Se quiser rodar com hot reload (reiniciando automaticamente ao salvar arquivos), utilize:

```bash
make app-watch
```
### Ou se quiser apenas iniciar a aplicação HyperF sem hot reload:

```bash
make app-start
```

### O que o comando `make doctor` faz?

O `make doctor` é útil para validar rapidamente o estado do ambiente. Ele verifica:

- status dos containers Docker;
- versão do PHP;
- versão do Composer;
- versão da aplicação HyperF.

## Verificando a aplicação

```bash
curl http://localhost:9501/health
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
- HyperF → `docs/hyperf`

---

# Roadmap

- [x] Sprint 1: infraestrutura e ambiente base concluída
- [x] Sprint 2: HyperF + bootstrap da aplicação + health check
- [ ] Sprint 3: domínio de pagamentos e casos de uso
- [ ] Sprint 4: persistência e repositories
- [ ] Sprint 5: mensageria e workers
- [ ] Sprint 6: MongoDB e auditoria
- [ ] Sprint 7: observabilidade e monitoramento
- [ ] Sprint 8: deploy e infraestrutura AWS

> A Sprint 3 é a primeira etapa funcional do domínio financeiro do projeto e deve servir como base para as próximas fases de persistência e integração.

---

# Licença

MIT