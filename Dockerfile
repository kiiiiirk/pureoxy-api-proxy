# Image officielle PHP avec Apache
FROM php:8.1-apache

# Copie les fichiers du dossier actuel vers le dossier de déploiement
COPY . /var/www/html/

# Active le mod_rewrite d'Apache (utile pour certains projets)
RUN a2enmod rewrite

# Définit les droits corrects pour Apache
RUN chown -R www-data:www-data /var/www/html

# Expose le port 80
EXPOSE 80
