FROM php:8.2-apache

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite headers

# Install Python for scraper
RUN apt-get update && apt-get install -y python3 python3-pip chromium chromium-driver \
    && rm -rf /var/lib/apt/lists/*

RUN pip3 install --break-system-packages requests beautifulsoup4 mysql-connector-python selenium webdriver-manager

WORKDIR /var/www/html
COPY MainMatka_Game/ /var/www/html/
COPY scraper/ /opt/scraper/

RUN chown -R www-data:www-data /var/www/html

# Start script runs both Apache and scraper
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
