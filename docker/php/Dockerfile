FROM php:8.4-cli-bookworm

LABEL maintainer="Luigi Raynel"

# -----------------------------------------------------------------------------
# Argumentos
# -----------------------------------------------------------------------------
ARG APP_USER=app
ARG APP_UID=1000

# -----------------------------------------------------------------------------
# Diretório da aplicação
# -----------------------------------------------------------------------------
WORKDIR /opt/www

# -----------------------------------------------------------------------------
# Dependências do sistema
# -----------------------------------------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
  \
  # Ferramentas básicas
  git \
  curl \
  unzip \
  zip \
  procps \
  ca-certificates \
  \
  # Ferramentas para compilação de extensões PECL
  autoconf \
  make \
  g++ \
  pkg-config \
  \
  # Bibliotecas de desenvolvimento
  libzip-dev \
  libicu-dev \
  libssl-dev \
  libcurl4-openssl-dev \
  libxml2-dev \
  libonig-dev \
  libsqlite3-dev \
  && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------------------------------
# Extensões nativas do PHP
# -----------------------------------------------------------------------------
RUN docker-php-ext-install -j"$(nproc)" \
  bcmath \
  intl \
  mbstring \
  opcache \
  pcntl \
  pdo_mysql \
  sockets \
  zip

# -----------------------------------------------------------------------------
# Extensões PECL
# -----------------------------------------------------------------------------
RUN pecl install redis swoole \
  && docker-php-ext-enable redis swoole \
  && pecl clear-cache

# -----------------------------------------------------------------------------
# Composer
# -----------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/home/${APP_USER}/.composer

# -----------------------------------------------------------------------------
# Usuário da aplicação
# -----------------------------------------------------------------------------
RUN groupadd --gid ${APP_UID} ${APP_USER} \
  && useradd --uid ${APP_UID} --gid ${APP_UID} --create-home ${APP_USER} \
  && mkdir -p /opt/www \
  && chown -R ${APP_USER}:${APP_USER} /opt/www

USER ${APP_USER}

# -----------------------------------------------------------------------------
# Porta padrão do HyperF
# -----------------------------------------------------------------------------
EXPOSE 9501

# -----------------------------------------------------------------------------
# Comando padrão (temporário durante a Sprint 1)
# -----------------------------------------------------------------------------
CMD ["tail", "-f", "/dev/null"]