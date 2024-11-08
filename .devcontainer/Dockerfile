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
    php-curl \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    php-json

# Instale o PHIVE 
RUN wget -O phive.phar https://phar.io/releases/phive.phar && \ 
    wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc && \ 
    gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79 && \ 
    gpg --verify phive.phar.asc phive.phar && \ 
    chmod +x phive.phar && \ 
    mv phive.phar /usr/local/bin/phive