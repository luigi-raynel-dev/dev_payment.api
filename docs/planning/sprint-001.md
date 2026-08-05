# Sprint 01 — Preparar infraestrutura base

## Goal

Estabelecer a infraestrutura inicial do microsserviço para permitir que a equipe avance para a implementação do HyperF sem depender de configurações locais do ambiente do desenvolvedor. Nesta Sprint, o foco foi criar uma base operacional estável, reproduzível e pronta para receber a aplicação.

---

## Backlog

- [x] Estruturar o repositório inicial
- [x] Configurar Docker Compose
- [x] Criar Dockerfile para PHP 8.4
- [x] Preparar a infraestrutura com MySQL e Redis
- [x] Configurar Makefile para automação do ambiente
- [x] Ajustar o ambiente Linux com WSL2
- [x] Registrar ADR-001
- [x] Documentar o README inicial

### Não faz parte desta Sprint

- HyperF
- API REST
- Banco configurado para a aplicação
- Entidades
- Casos de uso
- Testes automatizados
- AWS
- SQS

---

## Acceptance Criteria

- [x] O Docker Build executa com sucesso.
- [x] O Docker Compose sobe todos os containers.
- [x] O MySQL fica disponível para uso local.
- [x] O Redis fica disponível para uso local.
- [x] O ambiente está pronto para receber o HyperF.

---

## Deliverables

- [x] Estrutura inicial do repositório
- [x] Arquivos de containerização do projeto
- [x] Ambiente PHP 8.4 em Docker
- [x] Serviços de banco e cache configurados
- [x] Documentação inicial da aplicação
- [x] Registro do ADR-001

---

## Risks

- Configuração do ambiente Linux com WSL2 e Docker Desktop.
- Instalação e uso do GNU Make em ambientes Windows/WSL.
- Compilação da extensão Swoole durante a criação da imagem.
- Dependência de bibliotecas externas, como libbrotlienc, no build do PHP.
- Risco de ambiente não reproduzível caso a infraestrutura não seja documentada corretamente.

---

## Definition of Done

- [x] A infraestrutura mínima do projeto foi criada.
- [x] O ambiente de desenvolvimento está funcionando em contêineres.
- [x] O stack base do projeto está pronto para receber a aplicação HyperF.
- [x] A documentação inicial foi atualizada com a base do projeto.
- [x] A decisão técnica da arquitetura inicial foi registrada via ADR.

---

## Retrospective

Nesta Sprint, o principal aprendizado foi a importância de estabilizar o ambiente antes de iniciar o desenvolvimento funcional. A jornada exigiu ajustes no WSL2, na integração com Docker Desktop, na criação de imagens PHP e na solução de dependências de compilação, especialmente relacionadas ao Swoole e à biblioteca libbrotlienc.

Além disso, a equipe conseguiu estabelecer uma base operacional consistente, com repositório estruturado, serviços de infraestrutura disponíveis e documentação inicial suficientes para dar continuidade ao projeto com menor risco de retrabalho. Ao final da Sprint, o projeto estava pronto para a etapa seguinte: a inicialização do HyperF e a estrutura base da aplicação.