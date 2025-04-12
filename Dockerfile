FROM php:8.1-apache

# Active le module rewrite d'Apache
RUN a2enmod rewrite

# Installe mysqli pour pouvoir se connecter à la base de données
RUN docker-php-ext-install mysqli

# Copie les fichiers dans le dossier web
COPY . /var/www/html/

# Donne les bons droits à Apache
RUN chown -R www-data:www-data /var/www/html
