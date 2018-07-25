FROM ubuntu:16.04
MAINTAINER Muhammad Surya Ihsanuddin<surya.kejawen@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN sed -i 's/http:\/\/archive.ubuntu.com/http:\/\/kambing.ui.ac.id/g' /etc/apt/sources.list
RUN sed -i 's/http:\/\/security.ubuntu.com/http:\/\/kambing.ui.ac.id/g' /etc/apt/sources.list

# Install Software
RUN apt-get update && apt-get upgrade -y
RUN apt-get install nginx-full supervisor vim -y
RUN apt-get install software-properties-common python-software-properties -y
RUN apt-get install curl ca-certificates -y
RUN touch /etc/apt/sources.list.d/ondrej-php.list
RUN echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu xenial main" >> /etc/apt/sources.list.d/ondrej-php.list
RUN echo "deb-src http://ppa.launchpad.net/ondrej/php/ubuntu xenial main" >> /etc/apt/sources.list.d/ondrej-php.list
RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 4F4EA0AAE5267A6C
RUN apt-get update
RUN apt-get install php7.2 php7.2-cli php7.2-curl php7.2-intl php7.2-mbstring php7.2-xml php7.2-zip \
    php7.2-bcmath php7.2-cli php7.2-fpm php7.2-imap php7.2-json php7.2-opcache php7.2-apcu php7.2-xmlrpc \
    php7.2-bz2 php7.2-common php7.2-gd php7.2-ldap php7.2-pgsql php7.2-readline php7.2-soap php7.2-tidy php7.2-xsl php-apcu -y

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"
RUN apt-get remove --purge -y software-properties-common python-software-properties && \
    apt-get autoremove -y && \
    apt-get clean && \
    apt-get autoclean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ~/.composer

# Setup Environment
#ENV NGINX_WEBROOT /whatsapp/public
#ENV APP_ENV dev

# Nginx Configuration
ADD docker/nginx/sites-enabled/site.conf /etc/nginx/conf.d/default.conf
ADD docker/nginx/sites-enabled/php-fpm.conf /etc/nginx/conf.d/php-fpm.conf
ADD docker/nginx/nginx.conf /etc/nginx/nginx.conf
ADD docker/nginx/fastcgi_cache /etc/nginx/fastcgi_cache
ADD docker/nginx/logs/site.access.log /var/log/nginx/site.access.log
ADD docker/nginx/logs/site.error.log /var/log/nginx/site.error.log
ADD docker/nginx/etc/sysctl.conf /etc/sysctl.conf
ADD docker/nginx/etc/security/limits.conf /etc/security/limits.conf

RUN mkdir -p /tmp/nginx/cache
RUN chmod 777 -R /tmp/nginx

RUN chmod 777 /var/log/nginx/site.access.log
RUN chmod 777 /var/log/nginx/site.error.log

# PHP Configuration
ADD docker/php/php.ini /etc/php/7.2/fpm/php.ini
ADD docker/php/php.ini /etc/php/7.2/cli/php.ini
ADD docker/php/php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
RUN mkdir /run/php
RUN touch /run/php/php7.2-fpm.sock
RUN chmod 777 /run/php/php7.2-fpm.sock

# Setup Application
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer config -g repositories.packagist composer https://packagist.jp
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-suggest --optimize-autoloader --classmap-authoritative -vvv && \
    composer clear-cache

WORKDIR /whatsapp

COPY composer.json ./

RUN mkdir -p var/cache var/log var/sessions && \
    chmod 777 -R var/ && \
	composer install --prefer-dist --no-autoloader --no-scripts --no-suggest -vvv && \
	composer clear-cache

COPY bin bin/
COPY config config/
COPY public public/
COPY src src/
COPY templates templates/
COPY translations translations/

RUN composer dump-autoload --optimize --classmap-authoritative

# Supervisor Configuration
ADD docker/supervisor/supervisor.conf /etc/supervisord.conf

RUN chmod 755 -R config/

# Here we go
ADD docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 443 80

CMD ["/start.sh"]
