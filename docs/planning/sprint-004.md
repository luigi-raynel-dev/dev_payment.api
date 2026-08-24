# Sprint 04 — Eventos e Processamento Assíncrono

## Objetivo da Sprint

Introduzir processamento assíncrono no microsserviço de pagamentos através de eventos de domínio, AWS SQS e um Worker responsável pelo consumo e processamento das mensagens.

A prioridade desta Sprint é estabelecer uma arquitetura de mensageria desacoplada da implementação concreta do SQS, permitindo que o domínio e a camada de aplicação publiquem eventos sem conhecer detalhes da infraestrutura.

Ao final da Sprint, a criação de um pagamento deverá continuar funcionando de forma síncrona através do endpoint `POST /payments`, porém eventos relevantes do domínio deverão poder ser publicados para processamento assíncrono através de uma fila SQS.

---

## Definition of Done

Ao final da Sprint, o projeto deve ser capaz de:

- definir eventos de domínio relacionados ao `Payment`;
- implementar o primeiro evento `PaymentCreated`;
- definir um contrato para publicação de eventos;
- implementar um adaptador de publicação utilizando AWS SQS;
- configurar uma fila SQS para o processamento dos eventos;
- implementar um Worker responsável pelo consumo da fila;
- implementar handlers para os eventos recebidos;
- garantir que mensagens possam ser processadas de forma idempotente;
- tratar falhas de processamento sem perder silenciosamente a mensagem;
- testar o fluxo de publicação e consumo;
- manter o domínio desacoplado do AWS SDK e do SQS;
- documentar as decisões arquiteturais relacionadas à mensageria.

---

## Backlog da Sprint

### 1. Domain Events

- [ ] definir o conceito de Domain Event dentro do projeto;
- [ ] criar contrato/base para eventos de domínio;
- [ ] criar o evento `PaymentCreated`;
- [ ] definir os dados carregados pelo evento;
- [ ] gerar um identificador único para cada evento;
- [ ] registrar o momento em que o evento ocorreu;
- [ ] manter eventos imutáveis após sua criação.

### 2. Event Publisher

- [ ] criar `EventPublisherInterface`;
- [ ] definir o contrato de publicação;
- [ ] evitar qualquer dependência do domínio com SQS;
- [ ] permitir que diferentes adapters implementem o publisher;
- [ ] definir comportamento esperado em caso de falha na publicação.

Fluxo esperado:

```text
Application
    ↓
EventPublisherInterface
    ↓
SQS Event Publisher
```

A camada de aplicação deve depender do contrato, e não da implementação do SQS.

---

### 3. Integração com AWS SQS

- [ ] adicionar a dependência necessária para comunicação com AWS SQS;
- [ ] configurar credenciais e região através de variáveis de ambiente;
- [ ] configurar o nome da fila;
- [ ] criar adapter de infraestrutura para SQS;
- [ ] serializar eventos em formato JSON;
- [ ] definir um envelope padrão para mensagens;
- [ ] publicar `PaymentCreated` na fila;
- [ ] validar publicação através de testes de integração.

Envelope esperado:

```text
{
    "event_id": "...",
    "event_type": "PaymentCreated",
    "occurred_at": "...",
    "payload": {
        "payment_id": "...",
        "amount": "...",
        "currency": "..."
    }
}
```

O formato definitivo do envelope deverá ser validado durante a implementação.

---

### 4. Worker

- [ ] definir o processo responsável pelo consumo da fila;
- [ ] implementar leitura de mensagens do SQS;
- [ ] configurar long polling;
- [ ] processar uma mensagem por vez inicialmente;
- [ ] encaminhar a mensagem para o handler correspondente;
- [ ] remover a mensagem da fila somente após processamento bem-sucedido;
- [ ] manter a mensagem disponível para retry quando ocorrer uma falha;
- [ ] configurar comportamento adequado para mensagens inválidas;
- [ ] permitir execução do Worker de forma independente do servidor HTTP.

Fluxo esperado:

```text
SQS
 ↓
Worker
 ↓
Message Decoder
 ↓
Event Handler
 ↓
Application / Domain
```

---

### 5. Event Handler

- [ ] criar mecanismo para mapear `event_type` para seu handler;
- [ ] implementar handler para `PaymentCreated`;
- [ ] manter o handler independente do transporte SQS;
- [ ] evitar lógica específica do SQS dentro do handler;
- [ ] definir comportamento para eventos desconhecidos;
- [ ] registrar falhas de processamento.

Exemplo conceitual:

```text
PaymentCreated
      ↓
PaymentCreatedHandler
      ↓
Processamento assíncrono
```

---

### 6. Idempotência

Como o SQS trabalha com entrega `at-least-once`, o mesmo evento pode ser entregue mais de uma vez.

Portanto:

- [ ] identificar cada evento através de `event_id`;
- [ ] definir estratégia de identificação de eventos já processados;
- [ ] impedir processamento duplicado;
- [ ] testar entrega duplicada;
- [ ] documentar a estratégia de idempotência.

A idempotência deve ser considerada requisito arquitetural do Worker, e não uma responsabilidade do controller HTTP.

---

### 7. Integração com o fluxo de Payment

O fluxo de criação deverá evoluir de:

```text
HTTP
 ↓
CreatePayment
 ↓
Repository
 ↓
MySQL
```

para:

```text
HTTP
 ↓
CreatePayment
 ↓
Repository
 ↓
MySQL
 ↓
PaymentCreated
 ↓
EventPublisher
 ↓
SQS
```

A publicação do evento deve ocorrer após a criação bem-sucedida do pagamento.

O caso de uso não deve depender diretamente de classes concretas do AWS SDK.

---

### 8. Testes

#### Domain

- [ ] testar criação do `PaymentCreated`;
- [ ] testar geração do identificador do evento;
- [ ] testar payload do evento;
- [ ] testar imutabilidade/comportamento esperado do evento.

#### Application

- [ ] testar publicação do evento após criação do pagamento;
- [ ] testar que o publisher é chamado corretamente;
- [ ] testar comportamento quando a publicação falhar.

#### Infrastructure

- [ ] testar serialização do evento;
- [ ] testar publicação no SQS;
- [ ] testar leitura de mensagens;
- [ ] testar remoção da mensagem após sucesso;
- [ ] testar retry após falha.

#### Worker

- [ ] testar roteamento para o handler correto;
- [ ] testar evento desconhecido;
- [ ] testar falha no handler;
- [ ] testar processamento idempotente;
- [ ] testar processamento duplicado do mesmo evento.

#### HTTP

- [ ] manter os testes existentes do `POST /payments`;
- [ ] garantir que a introdução de eventos não altere o contrato HTTP existente.

---

## Arquitetura

A arquitetura esperada para a Sprint 4 é:

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
                         │   Use Case      │
                         │ CreatePayment   │
                         └───────┬─────────┘
                                 │
                    ┌────────────┴────────────┐
                    │                         │
                    ▼                         ▼
             ┌─────────────┐          ┌──────────────┐
             │ Repository  │          │ Domain Event │
             └──────┬──────┘          └──────┬───────┘
                    │                        │
                    ▼                        ▼
                 MySQL              EventPublisher
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

### Regra arquitetural

O fluxo deve respeitar:

```text
HTTP
  ↓
Interface
  ↓
Application
  ↓
Domain
  ↓
Ports / Interfaces
  ↓
Infrastructure
  ↓
AWS SQS
```

O domínio e a aplicação não devem conhecer:

- AWS SDK;
- SQS;
- nome da fila;
- credenciais AWS;
- detalhes de polling;
- `DeleteMessage`;
- `VisibilityTimeout`.

Essas responsabilidades pertencem à infraestrutura/Worker.

---

## Ambiente local

Para desenvolvimento local, a infraestrutura deverá permitir executar o SQS sem depender obrigatoriamente de uma fila AWS real.

A opção preferencial é utilizar um emulador AWS compatível com SQS, como LocalStack.

Fluxo esperado:

```text
Docker Compose
     │
     ├── API
     ├── MySQL
     ├── Redis
     └── LocalStack
             │
             └── SQS
```

As configurações devem ser controladas por variáveis de ambiente.

Exemplo conceitual:

```text
AWS_REGION
AWS_ACCESS_KEY_ID
AWS_SECRET_ACCESS_KEY
AWS_ENDPOINT
AWS_SQS_QUEUE
```

Os valores definitivos e a estratégia de inicialização da fila devem ser definidos durante a implementação.

---

## Processamento e confiabilidade

### Ack da mensagem

A mensagem somente deverá ser removida da fila após o processamento completo do evento.

```text
Receive
   ↓
Process
   ↓
Success?
 ┌─┴─┐
 │   │
YES  NO
 │   │
 ▼   ▼
Delete Retry
```

### Retry

Em caso de erro:

- a mensagem não deve ser removida imediatamente;
- o SQS deverá permitir uma nova tentativa;
- o Worker deverá registrar o erro;
- a estratégia de retry deverá respeitar o `VisibilityTimeout`.

### Dead Letter Queue

A utilização de DLQ deve ser considerada como parte da arquitetura de confiabilidade.

Para esta Sprint, a implementação pode ser mantida simples caso aumente excessivamente o escopo, mas a decisão deve ser registrada.

---

## Riscos

### 1. Acoplamento com AWS

Implementar o SQS diretamente dentro do caso de uso criaria dependência da infraestrutura.

**Mitigação:** utilizar `EventPublisherInterface`.

### 2. Processamento duplicado

O mesmo evento pode ser entregue mais de uma vez.

**Mitigação:** processamento idempotente através do `event_id`.

### 3. Perda do evento

O pagamento pode ser persistido no MySQL enquanto a publicação no SQS falha.

**Mitigação nesta Sprint:** documentar a limitação.

**Evolução futura:** avaliar Outbox Pattern.

### 4. Worker instável

Falhas no Worker podem impedir o processamento das mensagens.

**Mitigação:** retry através do mecanismo do SQS, logs e posteriormente observabilidade.

### 5. Complexidade excessiva

Introduzir SQS, eventos, Worker, retry, DLQ, idempotência e Outbox simultaneamente pode transformar a Sprint em uma grande mudança arquitetural.

**Mitigação:** implementar primeiro o fluxo mínimo funcional e deixar Outbox/observabilidade avançada para evoluções posteriores.

---

## Dependências

- Sprint 3 concluída;
- domínio `Payment` existente;
- `CreatePayment` existente;
- repository de Payment funcionando;
- Docker funcionando;
- MySQL disponível;
- infraestrutura preparada para receber o novo adapter;
- definição do ambiente local para SQS.

A Sprint 3 já estabeleceu o domínio, caso de uso, repository, persistência e endpoint `POST /payments`, servindo como base direta para esta Sprint.

---

## ADRs sugeridos

### ADR-004 — Introdução de Domain Events

Registrar:

- por que eventos são necessários;
- diferença entre evento de domínio e mensagem;
- responsabilidade pela criação dos eventos;
- formato inicial dos eventos.

### ADR-005 — AWS SQS como mecanismo de mensageria

Registrar:

- escolha do SQS;
- características de entrega;
- estratégia de retry;
- visibility timeout;
- possíveis DLQs;
- limitações da solução.

### ADR-006 — Processamento idempotente de eventos

Registrar:

- necessidade de idempotência;
- uso do `event_id`;
- comportamento diante de mensagens duplicadas.

### ADR futuro — Outbox Pattern

Não implementar obrigatoriamente nesta Sprint.

Registrar como possível evolução para resolver o problema de consistência entre:

```text
MySQL Transaction
        +
SQS Publication
```

---

## Critérios de aceite

A Sprint 4 será considerada concluída quando:

1. existir um evento `PaymentCreated`;
2. o evento possuir identificador único e timestamp;
3. a aplicação possuir um contrato de publicação de eventos;
4. existir uma implementação concreta utilizando SQS;
5. o `POST /payments` continuar funcionando sem alteração do contrato HTTP;
6. a criação de um pagamento gerar o evento esperado;
7. o evento puder ser recebido pelo Worker;
8. o Worker encaminhar o evento para o handler correto;
9. a mensagem somente for removida após processamento bem-sucedido;
10. falhas permitirem retry;
11. o processamento possuir estratégia de idempotência;
12. testes unitários e de integração cobrirem o fluxo;
13. o ambiente local permitir testar o fluxo sem depender obrigatoriamente da AWS;
14. a aplicação não possuir dependência direta do SQS dentro do domínio;
15. as decisões arquiteturais relevantes estiverem documentadas.

---

## Regras de implementação

- implementar código;
- garantir que testes unitários estejam passando;
- garantir que testes de integração estejam passando;
- garantir que testes HTTP existentes estejam passando;
- garantir que análise estática esteja passando;
- garantir que PHP-CS-Fixer esteja passando;
- garantir que Docker esteja funcionando;
- garantir que SQS local esteja funcionando;
- garantir que Worker esteja executando;
- manter documentação atualizada;
- documentar ADRs relevantes;
- atualizar roadmap;
- Manter README/changelog/Projet Status atualizados.

---

## Fluxo esperado da implementação

```text
1. Cliente envia POST /payments
            ↓
2. CreatePayment executa
            ↓
3. Payment é persistido
            ↓
4. PaymentCreated é criado
            ↓
5. EventPublisher publica no SQS
            ↓
6. Worker recebe a mensagem
            ↓
7. Worker identifica PaymentCreated
            ↓
8. PaymentCreatedHandler é executado
            ↓
9. Processamento é concluído
            ↓
10. Mensagem é removida da fila
```

O objetivo desta Sprint não é apenas adicionar uma fila ao projeto, mas estabelecer a primeira fronteira real de processamento assíncrono do microsserviço, mantendo o domínio independente da tecnologia de mensageria e preparando a arquitetura para futuras evoluções.
