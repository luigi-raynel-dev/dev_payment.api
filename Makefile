# =============================================================================
# Dev Payment API - Makefile
# Maintainer: Luigi Raynel
# Github: luigi-raynel-dev
# =============================================================================

APP=dev-payment-api

.PHONY: help up down start stop restart build build-no-cache ps logs shell clean

help:
	@echo ""
	@echo "🚀 Dev Payment API by Luigi Raynel"
	@echo ""
	@echo "Comandos disponíveis:"
	@echo ""
	@echo "  make up              - Sobe todos os containers"
	@echo "  make down            - Derruba os containers"
	@echo "  make start           - Inicia containers parados"
	@echo "  make stop            - Para os containers"
	@echo "  make restart         - Reinicia os containers"
	@echo "  make build           - Reconstrói as imagens"
	@echo "  make build-no-cache  - Reconstrói as imagens sem cache"
	@echo "  make ps              - Lista os containers"
	@echo "  make logs            - Exibe os logs da aplicação"
	@echo "  make shell           - Acessa o container da aplicação"
	@echo "  make clean           - Remove containers, volumes e órfãos"
	@echo "  make doctor          - Verifica o status dos serviços"
	@echo "  make composer-install   - Instala dependências do Composer"
	@echo "  make composer-update    - Atualiza dependências do Composer"
	@echo "  make composer-dump      - Gera o autoload do Composer"
	@echo "  make composer-require   - Adiciona uma dependência do Composer"
	@echo "  make composer-remove    - Remove uma dependência do Composer"
	@echo "  make app-start       - Inicia a aplicação HyperF"
	@echo "  make app-start-watch - Inicia a aplicação HyperF com watch"
	@echo "  make app-stop        - Para a aplicação HyperF"
	@echo "  make app-reload      - Recarrega a aplicação HyperF"
	@echo "  make app-status      - Exibe o status da aplicação HyperF"
	@echo ""

# -----------------------------------------------------------------------------
# Docker Compose
# -----------------------------------------------------------------------------

up:
	docker compose up -d

down:
	docker compose down

start:
	docker compose start

stop:
	docker compose stop

restart:
	docker compose restart

build:
	docker compose build

build-no-cache:
	docker compose build --no-cache

ps:
	docker compose ps

logs:
	docker compose logs -f $(APP)

shell:
	docker compose exec $(APP) sh

clean:
	docker compose down -v --remove-orphans

doctor:
	@echo ""
	@echo "🐳 Docker"
	docker compose ps
	@echo ""
	@echo "🐘 PHP"
	docker compose exec $(APP) php -v
	@echo ""
	@echo "🎼 Composer"
	docker compose exec $(APP) composer --version
	@echo ""
	@echo "🚀 HyperF"
	docker compose exec $(APP) php bin/hyperf.php --version

# ------------------------------------------------------------------------------
# Composer
# -----------------------------------------------------------------------------
composer-install:
	docker compose exec $(APP) composer install

composer-update:
	docker compose exec $(APP) composer update

composer-dump:
	docker compose exec $(APP) composer dump-autoload

composer-require:
	docker compose exec $(APP) composer require $(PACKAGE)

composer-remove:
	docker compose exec $(APP) composer remove $(PACKAGE)

# -----------------------------------------------------------------------------
# HyperF
# -----------------------------------------------------------------------------

app-start:
	docker compose exec $(APP) php bin/hyperf.php start

app-start-watch:
	docker compose watch

app-stop:
	docker compose exec $(APP) php bin/hyperf.php stop

app-reload:
	docker compose exec $(APP) php bin/hyperf.php reload

app-status:
	docker compose exec $(APP) php bin/hyperf.php server:status

# -----------------------------------------------------------------------------
# Setup
# -----------------------------------------------------------------------------

setup:
	@echo ""
	@echo "🚀 Configurando o ambiente de desenvolvimento..."
	@echo ""
	@echo "🐳 Docker - subindo imagens..."
	docker compose up -d
	@echo ""
	@echo "🐘 PHP - Verificando versão..."
	docker compose exec $(APP) php -v
	@echo ""
	@echo "🎼 Composer - Instalando dependências do PHP..."
	docker compose exec $(APP) composer install
	@echo ""
	@echo "🚀 HyperF - Iniciando o servidor..."
	docker compose exec $(APP) php bin/hyperf.php start