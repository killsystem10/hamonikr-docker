FROM ubuntu:16.04

ARG MYSQL_ROOT_PASSWORD=exitem08EXITEM)*
ENV DEBIAN_FRONTEND=noninteractive
ENV MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}

RUN set -eux; \
    echo "mysql-server-5.7 mysql-server/root_password password ${MYSQL_ROOT_PASSWORD}" | debconf-set-selections; \
    echo "mysql-server-5.7 mysql-server/root_password_again password ${MYSQL_ROOT_PASSWORD}" | debconf-set-selections; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        apache2 \
        libapache2-mod-php7.0 \
        php7.0 \
        php7.0-cli \
        php7.0-common \
        php7.0-curl \
        php7.0-gd \
        php7.0-intl \
        php7.0-mbstring \
        php7.0-mcrypt \
        php7.0-mysql \
        php7.0-json \
        php7.0-xml \
        libapache2-mod-geoip \
        geoip-database-extra \
        mysql-server-5.7 \
        openssl \
        wget \
        curl \
        ca-certificates \
        procps \
        gzip \
        python3 \
        python3-pip; \
    a2enmod rewrite ssl headers geoip; \
    rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/log/mysql /var/run/mysqld \
    && chown -R mysql:mysql /var/run/mysqld /var/log/mysql /var/lib/mysql

# Install Python packages
RUN pip3 install --no-cache-dir pymysql==1.0.2 beautifulsoup4

COPY mysql/my.cnf /etc/mysql/my.cnf
COPY mysql/mysql.conf.d/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

RUN mkdir -p /opt/hamonikr-migration/db /etc/letsencrypt/live/hamonikr.org-0001

COPY db/hamonikr_20251213.sql.gz /opt/hamonikr-migration/db/hamonikr.sql.gz
COPY files.tar.gz /opt/hamonikr-migration/files.tar.gz
COPY site/ /var/www/hamonikr/
RUN chown -R www-data:www-data /var/www/hamonikr

COPY certs/hamonikr/*.pem /etc/letsencrypt/live/hamonikr.org-0001/
RUN chmod 640 /etc/letsencrypt/live/hamonikr.org-0001/privkey.pem

COPY apache/options-ssl-apache.conf /etc/letsencrypt/options-ssl-apache.conf
COPY apache/hamonikr.conf /etc/apache2/sites-available/hamonikr.conf
COPY apache/hamonikr-le-ssl.conf /etc/apache2/sites-available/hamonikr-le-ssl.conf

RUN a2dissite 000-default.conf default-ssl.conf || true
RUN a2ensite hamonikr.conf hamonikr-le-ssl.conf

# Create default virtual host to handle IP-based requests
RUN cat > /etc/apache2/sites-available/000-default.conf << 'EOF'
<VirtualHost *:80>
        ServerName _
        DocumentRoot /var/www/hamonikr

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        <Directory "/var/www/hamonikr/">
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Require all granted
        </Directory>
</VirtualHost>
EOF

RUN a2ensite 000-default.conf

COPY start-services.sh /usr/local/bin/start-services.sh
RUN chmod +x /usr/local/bin/start-services.sh

# Copy site files with correct structure
RUN rm -rf /var/www/hamonikr/*
COPY site/hamonikr.org/ /var/www/hamonikr/

# Copy AI response system
COPY xe_gpt.py /app/xe_gpt.py
COPY manual/ /app/manual/
RUN mkdir -p /app && chmod +x /app/xe_gpt.py

VOLUME /var/lib/mysql /var/log/mysql

EXPOSE 80 443 3306

ENTRYPOINT ["/usr/local/bin/start-services.sh"]
