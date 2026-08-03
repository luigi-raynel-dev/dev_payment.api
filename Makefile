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
	@echo ""

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

