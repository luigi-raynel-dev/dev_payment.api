## Entregas

- Estrutura inicial do repositório
- Docker Compose
- Dockerfile
- PHP 8.4
- MySQL
- Redis
- Makefile
- Ambiente Linux utilizando WSL2
- ADR-001
- README inicial

## Critérios de aceite

- [x] Docker Build executa com sucesso.
- [x] Docker Compose sobe todos os containers.
- [x] MySQL disponível.
- [x] Redis disponível.
- [x] Ambiente pronto para receber o HyperF.

## Não faz parte desta Sprint

- HyperF
- API REST
- Banco configurado
- Entidades
- Casos de uso
- Testes
- AWS
- SQS

## Desafios encontrados

- Configuração do ambiente Linux com WSL2.
- Instalação do GNU Make.
- Configuração do Docker Desktop utilizando WSL.
- Compilação da extensão Swoole.
- Dependência libbrotlienc durante o build.

## Aprendizados

Durante a Sprint foi possível compreender melhor:

- funcionamento do WSL2;
- integração entre Docker Desktop e Linux;
- processo de compilação de extensões PECL;
- criação de imagens Docker;
- importância de um ambiente reproduzível.

## Resultado

Ao término da Sprint o projeto possui toda a infraestrutura necessária para iniciar o desenvolvimento da aplicação sem depender de configurações locais da máquina do desenvolvedor.