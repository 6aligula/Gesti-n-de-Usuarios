FROM php:8.2-apache

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar netcat-openbsd para el script de espera
RUN apt-get update && apt-get install -y netcat-openbsd

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar el script wait-for.sh al contenedor y hacerlo ejecutable
COPY wait-for.sh /usr/local/bin/wait-for.sh
RUN chmod +x /usr/local/bin/wait-for.sh

# Configurar permisos del directorio
RUN chown -R www-data:www-data /var/www/html/

# (Opcional) CMD original para levantar Apache
CMD ["apache2-foreground"]
