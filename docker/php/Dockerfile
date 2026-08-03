FROM php:8.4-cli-bookworm

LABEL maintainer="Luigi Raynel"

WORKDIR /opt/www

# -----------------------------------------------------------------------------
# Dependências do sistema
# -----------------------------------------------------------------------------
RUN apt-get update && apt-get upgrade -y && apt-get install -y --no-install-recommends \
  git \
  curl \
  unzip \
  zip \
  procps \
  libzip-dev \
  libicu-dev \
  libssl-dev \
  libcurl4-openssl-dev \
  libxml2-dev \
  libonig-dev \
  libpq-dev \
  libsqlite3-dev \
  pkg-config \
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
# Redis
# -----------------------------------------------------------------------------
RUN pecl install redis \
  && docker-php-ext-enable redis

# -----------------------------------------------------------------------------
# OpenSwoole
# -----------------------------------------------------------------------------
RUN pecl install openswoole \
  && docker-php-ext-enable openswoole

# -----------------------------------------------------------------------------
# Composer
# -----------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/tmp/composer

# -----------------------------------------------------------------------------
# Usuário da aplicação
# -----------------------------------------------------------------------------
RUN groupadd -g 1000 app \
  && useradd -u 1000 -g app -m app \
  && chown -R app:app /opt/www

USER app

EXPOSE 9501

CMD ["php", "-a"]