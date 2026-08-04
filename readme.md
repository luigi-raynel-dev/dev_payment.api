# Dev Payment API

Microsserviço de processamento de pagamentos desenvolvido com **HyperF**, seguindo princípios de arquitetura limpa, DDD e boas práticas de engenharia para sistemas financeiros.

O objetivo deste projeto é servir como um portfolio técnico de um microsserviço de produção, com foco em qualidade de código, organização de camadas, infraestrutura reproduzível e evolução gradual.

> Status atual: preparando a Sprint 2 para bootstrap do HyperF e definição da arquitetura base.

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

## Sprint 2 — Bootstrap HyperF

A Sprint 2 tem como foco inicializar a aplicação HyperF e garantir que o microsserviço execute corretamente em Docker, pronto para receber regras de negócio.

### Definition of Done

Ao final da sprint, o projeto deve permitir:

```bash
make up
```

E expor:

```http
GET /health
```

Com resposta esperada:

```json
{
  "status": "ok",
  "service": "dev-payment-api",
  "version": "0.2.0"
}
```

Isso confirma que:

- HyperF está funcionando;
- Swoole está funcionando;
- roteamento está funcionando;
- Docker está funcionando;
- a aplicação está pronta para evoluir.

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

## Construindo a imagem

```bash
make build
```

## Subindo o ambiente

```bash
make up
```

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
| `make build` | Constrói a imagem Docker |
| `make up` | Sobe os containers |
| `make down` | Derruba os containers |
| `make restart` | Reinicia o ambiente |
| `make shell` | Entra no container da aplicação |
| `make logs` | Exibe os logs |
| `make test` | Executa os testes |
| `make composer` | Executa comandos do Composer |

---

# Documentação

Toda a documentação do projeto está em `docs/`.

- ADRs → `docs/adr`
- Planejamento → `docs/planning`
- Arquitetura base → `docs/adr/ADR-002-base-architecture.md`

---

# Roadmap

- Sprint 1: infraestrutura e ambiente base concluída
- Sprint 2: HyperF + bootstrap da aplicação + health check
- Sprint 3: domínio de pagamentos e casos de uso
- Sprint 4: persistência e repositories
- Sprint 5: mensageria e workers
- Sprint 6: MongoDB e auditoria
- Sprint 7: observabilidade e monitoramento
- Sprint 8: deploy e infraestrutura AWS

---

# Licença

MIT