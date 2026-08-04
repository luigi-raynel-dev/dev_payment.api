# Sprint 02 — Bootstrap HyperF

## Objetivo da Sprint

Inicializar a aplicação HyperF e entregar um microsserviço executando em Docker, pronto para receber regras de negócio.

> Importante: nesta Sprint não vamos implementar pagamentos.

O foco desta Sprint é construir uma base sólida para as próximas entregas.

---

## Definition of Done

Ao final da Sprint, queremos ser capazes de executar:

```bash
make up
```

E acessar:

```http
GET /health
```

Retornando algo como:

```json
{
  "status": "ok",
  "service": "dev-payment-api",
  "version": "0.2.0"
}
```

Isso significa que:

- HyperF está funcionando.
- O Swoole está funcionando.
- O roteamento está funcionando.
- O Docker está funcionando.
- A aplicação está pronta para evoluir.

---

## Backlog da Sprint

### Ambiente

- Instalar HyperF
- Configurar Composer
- Ajustar Docker para HyperF
- Configurar `.env`

### Aplicação

- Criar endpoint `/health`
- Configurar rotas
- Estrutura inicial da aplicação

### Engenharia

- Atualizar README
- Criar Sprint 02
- Atualizar Roadmap
- Atualizar CHANGELOG
- Revisar AGENTS.md, se necessário

---

## O que NÃO faz parte desta Sprint

Ainda não vamos criar:

- Payment
- Repository
- Entity
- Use Cases
- SQS
- Redis
- Banco
- Eventos
- Filas
- MongoDB

Essa limitação reduz o risco de misturar responsabilidades e ajuda a manter o escopo controlado.

---

## Decisão arquitetural

Nesta Sprint vamos aproveitar a oportunidade para definir a arquitetura antes de criar qualquer código de domínio.

A estrutura base proposta será:

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

Mesmo que algumas pastas fiquem vazias por enquanto, essa organização ajuda a evitar uma refatoração pesada nas próximas Sprints.

A decisão foi baseada na intenção de manter a arquitetura alinhada com Clean Architecture e DDD, sem depender da estrutura padrão do HyperF, que tende a centralizar responsabilidades.

---

## Novo ADR

Esta decisão merece registro formal:

- ADR-002 — Arquitetura Base do Microsserviço

O ADR deve justificar a escolha por uma arquitetura inspirada em Clean Architecture e DDD desde o início, em vez de começar com a estrutura padrão do HyperF e reorganizar tudo depois.

---

## Fluxo de decisão de arquitetura

A partir desta Sprint, sempre que surgir uma decisão importante, vamos seguir o fluxo abaixo:

1. Apresentar o problema e o contexto.
2. Definir alternativas.
3. Explicar trade-offs e custos.
4. Registrar um ADR quando a decisão for relevante.
5. Implementar apenas após a decisão estar documentada.

Esse processo aproxima o projeto da realidade de times de engenharia, onde decisões importantes são debatidas antes de virar código.

---

## Primeiro passo da Sprint 2

O projeto deve instalar o HyperF de forma correta, aproveitando toda a infraestrutura profissional montada na Sprint 1.

A diferença principal é que a instalação será orientada para evolução do serviço, com foco em:

- Clean Architecture;
- DDD;
- SQS;
- Redis;
- observabilidade;
- deploy em infraestrutura cloud.

A Sprint 2 será a última Sprint de infraestrutura antes do início real do domínio de pagamentos.

---

## Resultado esperado

Ao final desta Sprint, o serviço deve estar pronto para receber a primeira camada de lógica de negócio sem que a base arquitetural precise ser reestruturada novamente.
