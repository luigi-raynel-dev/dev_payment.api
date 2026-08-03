# ADR-001 — Project Foundation

## Status

Accepted

---

## Context

O projeto **dev-payment-api** será desenvolvido como um microsserviço utilizando HyperF, com foco em demonstrar boas práticas de engenharia de software, arquitetura distribuída e desenvolvimento backend de alta performance.

Antes da implementação das regras de negócio, foi necessário definir uma base sólida de infraestrutura e desenvolvimento para garantir um ambiente reproduzível, consistente e de fácil manutenção.

---

## Decision

Foram adotadas as seguintes decisões para a fundação do projeto:

* Desenvolvimento totalmente utilizando Docker.
* PHP 8.4 como versão da linguagem.
* HyperF como framework principal.
* Swoole como servidor assíncrono.
* MySQL como banco de dados relacional.
* Redis para cache e comunicação assíncrona futura.
* Docker Compose para orquestração do ambiente local.
* Makefile para padronização dos comandos de desenvolvimento.
* Execução da aplicação utilizando um usuário não-root por questões de segurança.

---

## Consequences

### Positivas

* Ambiente de desenvolvimento reproduzível em qualquer máquina.
* Baixo acoplamento entre ambiente local e aplicação.
* Facilidade para onboarding de novos desenvolvedores.
* Base preparada para evolução em microsserviços.
* Estrutura compatível com futuras etapas de CI/CD e deploy em AWS.

### Negativas

* Tempo inicial maior para configuração da infraestrutura.
* Curva de aprendizado para desenvolvedores sem experiência com Docker e HyperF.

---

## Alternatives Considered

* Desenvolvimento local sem Docker.
* Utilização da imagem oficial do HyperF.
* Execução da aplicação como usuário root.

Essas alternativas foram descartadas para privilegiar reprodutibilidade, segurança e maior controle sobre o ambiente de desenvolvimento.
