#!/bin/bash
# ------------------------------
# Setup Laravel Travel Orders
# ------------------------------

set -e

echo "Limpando containers antigos..."
docker compose down -v || true

echo "### 1. Subindo containers Docker..."
docker compose up -d --build

echo "### Aguardando MySQL ficar disponível..."
until docker exec travel_mysql mysqladmin ping -h"localhost" --silent; do
  sleep 2
done

echo "MySQL está pronto!"

echo "### 2. Ajustando permissões..."
docker exec travel_app chown -R www-data:www-data storage bootstrap/cache
docker exec travel_app chmod -R 775 storage bootstrap/cache

echo "### 3. Instalando dependências PHP..."
docker exec -it travel_app composer install

echo "### 4. Copiando .env.example para .env..."
docker exec -it travel_app cp .env.example .env

echo "### 5. Gerando chave da aplicação..."
docker exec -it travel_app php artisan key:generate

echo "### 6. Rodando migrations..."
docker exec -it travel_app php artisan migrate

echo "### 7. Limpando cache..."
docker exec -it travel_app php artisan optimize:clear

echo "### 8. Criando usuários de teste (normal e admin) e exibindo tokens..."
docker exec -it travel_app php artisan tinker --execute "\
\$user = \App\Models\User::factory()->create([
    'email' => 'user@test.com',
    'password' => bcrypt('12345678')
]); \
\$admin = \App\Models\User::factory()->create([
    'email' => 'admin@test.com',
    'password' => bcrypt('12345678'),
    'is_admin' => true
]); \
echo 'User Token: ' . \$user->createToken('API Token')->plainTextToken . '\n'; \
echo 'Admin Token: ' . \$admin->createToken('API Token')->plainTextToken . '\n';"

echo "### 9. Criando pedidos de teste para o usuário normal..."
docker exec -it travel_app php artisan tinker --execute "\
\App\Models\TravelOrder::factory()->count(5)->create(['user_id' => 1]); \
echo '5 pedidos de viagem criados para o usuário 1\n';"

echo "### ✅ Setup concluído!"
echo "API pronta para uso: http://localhost:8000/api/ping"
echo "Use os tokens gerados acima para testar endpoints via Postman."
