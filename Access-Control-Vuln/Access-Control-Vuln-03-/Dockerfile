FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    php-mysql \
    mysql-server \
    supervisor \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN sed -i 's/index.html/index.php index.html/' /etc/apache2/mods-enabled/dir.conf

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && rm -f /var/www/html/index.html

COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

COPY db.sql /

RUN service mysql restart \
    && mysql -uroot -e "CREATE USER 'admin'@'%' IDENTIFIED BY 'pass';" \
    && mysqladmin create asdf \
    && mysql -uroot < db.sql && rm -rf db.sql \
    && mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' WITH GRANT OPTION;"

EXPOSE 80 3306

CMD ["/usr/bin/supervisord", "-n"]