# Sprint 03 — Payment Domain

## Objetivo da Sprint

Definir o domínio de pagamentos e implementar o primeiro fluxo de criação de um pagamento, respeitando a arquitetura definida na Sprint 2.

A prioridade desta Sprint é estabelecer as regras de negócio do `Payment` antes de qualquer implementação de infraestrutura ou endpoint HTTP. O objetivo não é apenas "criar um registro no banco", mas garantir que o domínio financeiro fique corretamente modelado, validado e testado.

---

## Definition of Done

Ao final da Sprint, o projeto deve ser capaz de:

- modelar a entidade `Payment` com regras de domínio e invariantes;
- definir estados e transições válidas do pagamento;
- criar o caso de uso `CreatePayment` sem lógica de infraestrutura no domínio;
- implementar o contrato de persistência do repository;
- expor a criação de pagamento via `POST /payments`;
- validar o fluxo com testes de domínio, aplicação e HTTP;
- manter a arquitetura no padrão: HTTP → Interface → Application → Domain → Repository Interface → Infrastructure.

---

## Backlog da Sprint

### 1. Domínio de Payment

- [x] Definir a entidade `Payment`;
- [x] definir campos e tipos;
- [x] validar valores mínimos e máximos;
- [x] mapear status possíveis;
- [x] definir transições permitidas;
- [x] separar regras do domínio das regras da aplicação.

### 2. Contratos e portas

- [x] criar `PaymentRepositoryInterface`;
- [x] definir DTOs de entrada e saída;
- [x] estabelecer interfaces para ports necessários ao caso de uso;
- [x] evitar acoplamento com banco e controllers.

### 3. Caso de uso de criação

- [x] implementar `CreatePayment`;
- [x] gerar `id` único;
- [x] validar `amount`, `currency`, `status`, `description`;
- [x] criar a entidade com timestamps corretos;
- [x] persistir apenas através do contrato do repositório.

### 4. Infraestrutura

- [x] criar migration MySQL para pagamentos;
- [x] implementar repository concreto;
- [x] configurar conexão e mapeamento do modelo;
- [x] manter a infra como adaptador, sem regras de negócio.

### 5. Interface HTTP

- [x] criar `POST /payments`;
- [x] definir request e response;
- [x] criar controller delegando para o caso de uso;
- [x] manter o controller fino e sem regra de negócio.

### 6. Testes

- [x] testes unitários do domínio;
- [x] testes do caso de uso;
- [ ] testes de integração do repository;
- [ ] testes do endpoint HTTP.

### 7. Documentação

- [ ] atualizar roadmap;
- [x] registrar ADR quando houver decisão arquitetural relevante;
- [ ] manter a documentação alinhada com a implementação real.

---

## Riscos e dependências

### Riscos

- definir status e transições do pagamento de forma inconsistente;
- misturar validação de aplicação com validação de domínio;
- começar pelo controller e criar acoplamento com a infraestrutura;
- permitir regras de negócio dentro da camada HTTP;
- modelar `Money` e `Currency` sem necessidade real, gerando complexidade desnecessária.

### Dependências

- arquitetura base da Sprint 2 concluída;
- estrutura Clean Architecture já estabelecida;
- conhecimento do padrão Health já validado no projeto;
- banco MySQL disponível para integração;
- ambiente Docker funcionando para validação do endpoint.

---

## Critérios de aceite

A Sprint 3 será considerada concluída quando:

1. `Payment` estiver modelado como entidade de domínio com invariantes;
2. `CreatePayment` estiver implementado como caso de uso da aplicação;
3. o repository estiver exposto por interface e implementado na infraestrutura;
4. a criação de pagamento funcionar via `POST /payments`;
5. todos os testes relevantes do domínio e HTTP estiverem verdes;
6. o fluxo da aplicação respeitar a camada arquitetural esperada;
7. a documentação do projeto refletir corretamente a nova entrega.

---

## Regras de implementação

- não criar lógica de negócio dentro do controller;
- não acessar banco diretamente na camada de aplicação;
- sempre criar interfaces antes das implementações concretas;
- sempre cobrir regras de negócio com testes;
- manter as decisões arquiteturais explícitas e documentadas.

---

## Fluxo esperado da implementação

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

Esse fluxo deve ser visível no código e na organização dos arquivos, demonstrando que o projeto segue Clean Architecture e DDD de forma prática.
