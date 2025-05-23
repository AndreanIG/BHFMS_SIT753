# FROM laravelsail/php82-composer:latest

# # Install Node.js
# RUN apt-get update && \
#     apt-get install -y curl unzip git && \
#     curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
#     apt-get install -y nodejs


# WORKDIR /app

# COPY . .

# RUN composer install

# RUN cp .env.example .env && \
#     php artisan key:generate && \
#     php artisan storage:link

# WORKDIR /app
# RUN npm install && npm run build

# EXPOSE 8001

# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]


FROM laravelsail/php82-composer:latest

# Install Node.js
RUN apt-get update && \
    apt-get install -y curl unzip git && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

WORKDIR /app

# Copy files EXCEPT .env.example so Laravel won't try to parse it during build
COPY . .

# Prevent Laravel from trying to parse env during composer install
RUN rm -f .env || true

# Install PHP dependencies
RUN composer install

# Install Node dependencies
RUN npm install && npm run build

# Expose port
EXPOSE 8001

# Copy env and prepare Laravel ONLY AT RUNTIME
CMD sh -c "cp .env.example .env && php artisan key:generate && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8001"
