# Usa uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Habilita módulos do Apache necessários conforme seu .htaccess
# mod_rewrite é essencial para o roteamento do MVC
# mod_headers, mod_expires e mod_deflate são usados para segurança e cache
RUN a2enmod rewrite headers expires deflate

# Instala extensões do PHP recomendadas (opcional, mas comum para projetos PHP)
# Adicione outras extensões aqui se precisar (ex: pdo_mysql, gd)
RUN docker-php-ext-install pdo pdo_mysql

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do projeto para o container
COPY . /var/www/html

# Ajusta as permissões para o usuário do Apache (importante para uploads e logs)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# A sua configuração de upload permite até 5M, podemos ajustar o PHP.ini aqui se necessário
# mas o seu .htaccess já tenta ajustar isso via php_value

# Expor a porta 80
EXPOSE 80