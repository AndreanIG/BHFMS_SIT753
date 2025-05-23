FROM laravelsail/php82-composer:latest

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs npm unzip git

WORKDIR /app

COPY . .

RUN composer install
RUN cp .env.example .env && php artisan key:generate

WORKDIR /app/resources/js
RUN npm install && npm run build

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]