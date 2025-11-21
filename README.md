# Laravel Chat - Application de Chat Temps Réel avec Docker

Application de chat temps réel construite avec Laravel, Livewire, Reverb, et Docker.

## 🚀 Stack Technique

- **Backend/API :** Laravel 11
- **Web Server :** Nginx
- **Base de Données :** MySQL 8.0
- **WebSockets :** Laravel Reverb
- **Cache/Broker :** Redis 7
- **Frontend :** Livewire 3 + Blade
- **Authentification :** Laravel Breeze

## 📋 Prérequis

- Docker et Docker Compose installés
- Ports 8000, 8080, 3306, 6379 disponibles

## 🛠️ Installation Rapide

### Option 1 : Script d'initialisation automatique

```bash
./init.sh
```

### Option 2 : Installation manuelle

1. **Construire et démarrer les conteneurs :**
```bash
docker compose up -d --build
```

2. **Attendre que MySQL soit prêt (environ 10 secondes)**

3. **Installer les dépendances Laravel :**
```bash
docker compose exec app composer install
```

4. **Créer le fichier .env :**
```bash
docker compose exec app cp .env.example .env
```

Si `.env.example` n'existe pas, créez un fichier `.env` dans `laravel-chat/` avec cette configuration minimale :

```env
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
```

5. **Générer la clé d'application :**
```bash
docker compose exec app php artisan key:generate
```

6. **Exécuter les migrations :**
```bash
docker compose exec app php artisan migrate
```

7. **Installer les dépendances NPM :**
```bash
docker compose exec app npm install
```

8. **Compiler les assets :**
```bash
docker compose exec app npm run build
```

## 🌐 Accès à l'application

- **Application Web :** http://localhost:8000
- **Reverb WebSocket :** ws://localhost:8080

> **Professeur** : avant de lancer `docker compose up -d --build`, démarrez bien Docker Desktop (ou `dockerd`) pour que le socket `unix:///Users/bounabat/.docker/run/docker.sock` soit accessible. Sans ça, `nginx` renverra du 502 parce qu’il ne joint pas PHP-FPM sur le port 9000. En cas d’erreur, relancez `docker compose logs -f nginx` puis `docker compose logs -f app` pour voir si `app` accepte bien les connexions.

## 🐳 Services Docker

- **app** : Application Laravel (PHP-FPM 8.2)
- **nginx** : Serveur web (port 8000)
- **mysql** : Base de données MySQL 8.0 (port 3306)
- **redis** : Cache et broker de messages (port 6379)
- **reverb** : Serveur WebSocket Laravel Reverb (port 8080)

### Description des services

- **`nginx`** sert de reverse-proxy public : il expose `http://localhost:8000`, fait suivre les requêtes au conteneur `app` (PHP-FPM) et gère les assets.
- **`app`** contient Laravel, sert les Blade/Livewire et traite les jobs/événements. C’est ici qu’on installe composer/npm et qu’on exécute `php artisan`.
- **`redis`** est utilisé pour la cache, le broadcasting (Livewire + Echo) et la queue : Laravel utilise Redis comme cache store et broker de Reverb/Echo. Il doit rester actif même si l’interface semble fonctionner sans lui.
- **`mysql`** stocke les données persistantes (messages, utilisateurs, migrations). Les volumes `mysql_data`/`redis_data` assurent leur persistance.

## 📝 Utilisation

1. Accédez à http://localhost:8000
2. Créez un compte ou connectez-vous
3. Accédez à la page de chat
4. Envoyez des messages en temps réel !

## 🔧 Commandes utiles

**Arrêter les conteneurs :**
```bash
docker compose down
```

**Voir les logs :**
```bash
docker compose logs -f
```

**Voir les logs d'un service spécifique :**
```bash
docker compose logs -f app
docker compose logs -f reverb
```

**Accéder au shell du conteneur app :**
```bash
docker compose exec app bash
```

**Exécuter des commandes Artisan :**
```bash
docker compose exec app php artisan [commande]
```

**Réinitialiser la base de données :**
```bash
docker compose exec app php artisan migrate:fresh
```

**Recompiler les assets en mode développement :**
```bash
docker compose exec app npm run dev
```

## 🏗️ Structure du Projet

```
.
├── docker-compose.yml          # Configuration Docker Compose
├── init.sh                      # Script d'initialisation
├── nginx/
│   └── default.conf            # Configuration Nginx
└── laravel-chat/               # Application Laravel
    ├── app/
    │   ├── Events/
    │   │   └── MessageSent.php # Événement de diffusion
    │   ├── Livewire/
    │   │   └── Chat.php        # Composant Livewire du chat
    │   └── Models/
    │       └── Message.php     # Modèle Message
    ├── config/
    │   ├── broadcasting.php    # Configuration Broadcasting
    │   └── reverb.php          # Configuration Reverb
    ├── database/
    │   └── migrations/
    │       └── *_create_messages_table.php
    ├── resources/
    │   ├── js/
    │   │   └── app.js          # Configuration Laravel Echo
    │   └── views/
    │       └── livewire/
    │           └── chat.blade.php
    └── routes/
        └── channels.php        # Routes de canaux de diffusion
```

## 🔐 Configuration Reverb

Les clés Reverb par défaut sont définies dans le `.env`. Pour la production, générez de nouvelles clés sécurisées :

```bash
docker compose exec app php artisan reverb:install
```

## 🐛 Dépannage

**Les messages ne s'affichent pas en temps réel :**
- Vérifiez que le service Reverb est démarré : `docker compose ps`
- Vérifiez les logs Reverb : `docker compose logs reverb`
- Vérifiez que les variables VITE_REVERB_* sont bien définies dans le `.env`
- Recompilez les assets : `docker compose exec app npm run build`

**Erreur de connexion à la base de données :**
- Attendez quelques secondes que MySQL soit complètement démarré
- Vérifiez les logs MySQL : `docker compose logs mysql`

**Les assets ne se chargent pas :**
- Recompilez les assets : `docker compose exec app npm run build`
- Vérifiez que Vite est bien configuré dans `vite.config.js`

