# Use a imagem base do Ubuntu
FROM ubuntu:latest

# Adiciona um rótulo ao contêiner 
LABEL maintainer="test_liveecommerce"

# Instale dependências básicas e PHP
RUN apt-get update && apt-get install -y \
    software-properties-common \
    lsb-release \
    apt-transport-https \
    ca-certificates \
    wget \
    curl \
    php \
    php-cli \
    php-mbstring \
    php-xml \
    php-zip \
    php-sqlite3 \
    php-pcov \
    php-xdebug \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    php-json

# RUN useradd -rm -d /home/lucas -s /bin/bash -g root -G sudo -u 1001 lucas

# WORKDIR /workspace

# USER lucas