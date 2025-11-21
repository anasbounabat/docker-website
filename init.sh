#!/bin/bash

echo "🚀 Initialisation du projet Laravel Chat..."

# Vérifier si Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé. Veuillez installer Docker d'abord."
    exit 1
fi

if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo "❌ Docker Compose n'est pas installé. Veuillez installer Docker Compose d'abord."
    exit 1
fi

# Construire et démarrer les conteneurs
echo "📦 Construction et démarrage des conteneurs Docker..."
docker compose up -d --build

# Attendre que MySQL soit prêt
echo "⏳ Attente que MySQL soit prêt..."
sleep 10

# Installer les dépendances Composer
echo "📥 Installation des dépendances Composer..."
docker compose exec -T app composer install --no-interaction

# Créer le fichier .env s'il n'existe pas
if [ ! -f "laravel-chat/.env" ]; then
    echo "📝 Création du fichier .env..."
    docker compose exec -T app cp .env.example .env 2>/dev/null || echo "⚠️  .env.example n'existe pas. Création d'un .env basique..."
    
    # Si .env.example n'existe pas, créer un .env basique
    if [ ! -f "laravel-chat/.env" ]; then
        cat > laravel-chat/.env << 'EOF'
APP_NAME="Laravel Chat"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_chat
DB_USERNAME=laravel
DB_PASSWORD=password

BROADCAST_DRIVER=reverb
CACHE_STORE=redis
QUEUE_CONNECTION=redis

REDIS_HOST=redis
REDIS_PORT=6379

REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
EOF
    fi
fi

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
docker compose exec -T app php artisan key:generate --force

# Exécuter les migrations
echo "🗄️  Exécution des migrations..."
docker compose exec -T app php artisan migrate --force

# Installer les dépendances NPM
echo "📦 Installation des dépendances NPM..."
docker compose exec -T app npm install

# Compiler les assets
echo "🎨 Compilation des assets..."
docker compose exec -T app npm run build

echo "✅ Initialisation terminée !"
echo ""
echo "🌐 L'application est accessible sur : http://localhost:8000"
echo "🔌 Reverb WebSocket est accessible sur : ws://localhost:8080"
echo ""
echo "Pour voir les logs : docker compose logs -f"
echo "Pour arrêter : docker compose down"

