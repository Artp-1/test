FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
    openssh-server \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
    supervisor \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir /var/run/sshd
RUN sed -i 's/#PasswordAuthentication yes/PasswordAuthentication yes/' /etc/ssh/sshd_config
RUN sed -i 's/PasswordAuthentication no/PasswordAuthentication yes/' /etc/ssh/sshd_config
RUN useradd -m socora && echo "socora:pass" | chpasswd

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && rm -f /var/www/html/index.html

RUN echo "DirectoryIndex index.php" > /etc/apache2/mods-enabled/dir.conf

COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf

EXPOSE 80 22

CMD ["/usr/bin/supervisord", "-n"]
