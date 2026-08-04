# Dev Payment API

Microsserviço de processamento de pagamentos desenvolvido com **HyperF**, seguindo princípios de arquitetura limpa, DDD e microsserviços.

O objetivo deste projeto é demonstrar boas práticas de engenharia de software em um cenário inspirado em sistemas financeiros de alta disponibilidade.

> ⚠️ Projeto em desenvolvimento.

---

# Tecnologias

- PHP 8.4
- HyperF
- Swoole
- MySQL 8.4
- Redis 7
- Docker
- Docker Compose
- AWS SQS *(Sprint futura)*
- PHPUnit / Pest *(Sprint futura)*

---

# Arquitetura

Em construção.

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
├── src/
├── config/
├── migrations/
├── tests/
├── docker-compose.yml
├── Makefile
└── README.md
```

---

# Primeiros Passos

Clone o repositório:

```bash
git clone <url-do-repositorio>

cd dev-payment-api
```

---

## Construindo a imagem

```bash
make build
```

---

## Subindo o ambiente

```bash
make up
```

---

## Acessando o container

```bash
make shell
```

---

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

## Documentação

Toda a documentação do projeto encontra-se em `docs/`.

- ADRs → `docs/adr`
- Planning → `docs/planning`
- Arquitetura → `docs/architecture`

---

# Licença

MIT