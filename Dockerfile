FROM php:8.2-apache

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite headers

# Install Python and Chrome for scraper
RUN apt-get update && apt-get install -y \
    python3 python3-pip \
    wget gnupg2 unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Chrome stable (matching version)
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o /usr/share/keyrings/google-chrome.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/google-chrome.gpg] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update && apt-get install -y google-chrome-stable \
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
