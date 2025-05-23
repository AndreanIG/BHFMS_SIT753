FROM laravelsail/php82-composer:latest

# Install Node.js
RUN apt-get update && \
    apt-get install -y curl unzip git && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs


WORKDIR /app

COPY . .

RUN composer install
RUN cp .env.example .env && php artisan key:generate

WORKDIR /app/resources/js
RUN npm install && npm run build

EXPOSE 8001

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]