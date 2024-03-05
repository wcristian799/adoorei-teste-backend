# Usa uma imagem base do PHP com Apache, especificando a versão desejada.
FROM php:8.1-apache

# Instala as extensões do PHP necessárias para o Laravel.
RUN docker-php-ext-install pdo pdo_mysql bcmath ctype fileinfo json mbstring xml tokenizer

# Habilita o Apache mod_rewrite.
RUN a2enmod rewrite

# Copia o código da aplicação para dentro do container, no diretório padrão do Apache.
COPY . /var/www/html

# Altera a propriedade do diretório da aplicação para o usuário do Apache.
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta 80.
EXPOSE 80
 