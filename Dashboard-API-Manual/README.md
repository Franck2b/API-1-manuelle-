# Dashboard API - API REST Manuelle (Sans API Platform)

API REST manuelle développée avec Symfony 6.4 pour alimenter le Dashboard Analytics Next.js.

## 📋 Table des matières

- [Objectif](#objectif)
- [Technologies utilisées](#technologies-utilisées)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Base de données](#base-de-données)
- [Authentification](#authentification)
- [Endpoints](#endpoints)
- [Tests](#tests)
- [Documentation API](#documentation-api)
- [Déploiement](#déploiement)

## 🎯 Objectif

Cette API REST a été développée "from scratch" avec Symfony pour gérer :
- **Campagnes** : Création, modification, suppression et consultation de campagnes marketing
- **Utilisateurs** : Gestion des utilisateurs et collaborateurs
- **Statistiques** : Revenus, commandes et abonnements avec calculs de tendances

## 🛠 Technologies utilisées

- **Symfony 6.4** (PHP 8.1+)
- **Doctrine ORM** : Gestion de la base de données
- **Lexik JWT Authentication Bundle** : Authentification par JWT
- **Nelmio CORS Bundle** : Configuration CORS pour Next.js
- **Nelmio API Doc Bundle** : Documentation Swagger
- **Symfony Serializer** : Sérialisation JSON
- **Symfony Validator** : Validation des données
- **PHPUnit** : Tests fonctionnels

## 📦 Prérequis

- PHP ≥ 8.1
- Composer
- MySQL/MariaDB ou PostgreSQL
- Extension PHP : `pdo`, `pdo_mysql` ou `pdo_pgsql`, `openssl`, `json`

## 🚀 Installation

1. **Cloner le dépôt** (ou utiliser ce projet)
```bash
cd Dashboard-API-Manual
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer les variables d'environnement**
```bash
cp .env .env.local
```

Éditer `.env.local` et configurer :
```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/dashboard_api?serverVersion=8.0"
# ou pour PostgreSQL:
# DATABASE_URL="postgresql://user:password@127.0.0.1:5432/dashboard_api?serverVersion=13&charset=utf8"
```

4. **Générer les clés JWT** (déjà fait, mais si besoin) :
```bash
php bin/console lexik:jwt:generate-keypair
```

## ⚙️ Configuration

### Base de données

La configuration de la base de données se fait dans `.env.local` via la variable `DATABASE_URL`.

### CORS

CORS est configuré pour autoriser `http://localhost:3000` (votre application Next.js).

Configuration dans `config/packages/nelmio_cors.yaml`.

### JWT

Les clés JWT sont générées automatiquement dans `config/jwt/`. Les variables d'environnement sont :
- `JWT_SECRET_KEY`
- `JWT_PUBLIC_KEY`
- `JWT_PASSPHRASE`

## 🗄 Base de données

### Créer la base de données

```bash
php bin/console doctrine:database:create
```

### Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

### Schéma de la base de données

#### Table `user`
- `id` : INT (PK)
- `email` : VARCHAR(180) (UNIQUE)
- `password` : VARCHAR(255)
- `roles` : JSON
- `first_name` : VARCHAR(100)
- `last_name` : VARCHAR(100)
- `initials` : VARCHAR(50)
- `color` : VARCHAR(50)
- `role` : VARCHAR(100)
- `created_at` : DATETIME
- `updated_at` : DATETIME

#### Table `campaign`
- `id` : INT (PK)
- `platform` : VARCHAR(50) (facebook, instagram, google, etc.)
- `title` : VARCHAR(255)
- `status` : VARCHAR(50) (draft, in_progress, archived)
- `start_date` : DATE
- `end_date` : DATE
- `progress` : INT (0-100)
- `last_updated` : DATETIME
- `created_at` : DATETIME

#### Table `campaign_collaborator` (Many-to-Many)
- `campaign_id` : INT (FK)
- `user_id` : INT (FK)

#### Table `revenue`
- `id` : INT (PK)
- `amount` : DECIMAL(10,2)
- `date` : DATE
- `created_at` : DATETIME
- `updated_at` : DATETIME

#### Table `order`
- `id` : INT (PK)
- `amount` : DECIMAL(10,2)
- `order_date` : DATE
- `status` : VARCHAR(50)
- `created_at` : DATETIME
- `updated_at` : DATETIME

#### Table `subscription`
- `id` : INT (PK)
- `subscription_date` : DATE
- `plan` : VARCHAR(50)
- `status` : VARCHAR(50)
- `created_at` : DATETIME
- `updated_at` : DATETIME

## 🔐 Authentification

L'API utilise **JWT (JSON Web Tokens)** pour l'authentification.

### Connexion

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

**Réponse :**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### Utilisation du token

Ajouter le header dans toutes les requêtes protégées :
```http
Authorization: Bearer {token}
```

## 📡 Endpoints

### Campagnes

#### Liste des campagnes
```http
GET /api/campaigns
Authorization: Bearer {token}
```

**Paramètres de requête :**
- `page` : Numéro de page (défaut: 1)
- `limit` : Nombre d'éléments par page (défaut: 10, max: 100)
- `status` : Filtrer par statut (draft, in_progress, archived)
- `platform` : Filtrer par plateforme
- `search` : Recherche dans le titre
- `sort` : Champ de tri (id, title, status, platform, lastUpdated, createdAt)
- `order` : Ordre (ASC, DESC)

**Réponse :**
```json
{
  "items": [...],
  "pagination": {
    "page": 1,
    "limit": 10,
    "total": 50,
    "totalPages": 5,
    "hasNext": true,
    "hasPrev": false
  }
}
```

#### Détails d'une campagne
```http
GET /api/campaigns/{id}
Authorization: Bearer {token}
```

#### Créer une campagne
```http
POST /api/campaigns
Authorization: Bearer {token}
Content-Type: application/json

{
  "platform": "facebook",
  "title": "Nouvelle campagne",
  "status": "draft",
  "startDate": "2023-06-01",
  "endDate": "2023-08-01",
  "progress": 0,
  "collaborators": [1, 2, 3]
}
```

#### Modifier une campagne
```http
PUT /api/campaigns/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Titre modifié",
  "status": "in_progress",
  "progress": 50
}
```

#### Supprimer une campagne
```http
DELETE /api/campaigns/{id}
Authorization: Bearer {token}
```

### Statistiques

#### Revenus totaux
```http
GET /api/stats/revenue?startDate=2023-01-01&endDate=2023-12-31
Authorization: Bearer {token}
```

#### Commandes
```http
GET /api/stats/orders?startDate=2023-01-01&endDate=2023-12-31
Authorization: Bearer {token}
```

#### Abonnements
```http
GET /api/stats/subscriptions?startDate=2023-01-01&endDate=2023-12-31
Authorization: Bearer {token}
```

#### Dashboard complet
```http
GET /api/stats/dashboard
Authorization: Bearer {token}
```

## 🧪 Tests

### Exécuter les tests

```bash
php bin/phpunit
```

### Tests fonctionnels inclus

1. **GET liste** : `testGetCampaignsList()` - Test de récupération de la liste des campagnes
2. **POST valide** : `testCreateCampaignValid()` - Test de création d'une campagne valide
3. **POST invalide** : `testCreateCampaignInvalid()` - Test de création avec données invalides (validation)

## 📚 Documentation API

### Swagger UI

Une fois le serveur démarré, accéder à :
```
http://localhost:8000/api/doc
```

La documentation Swagger est générée automatiquement à partir des annotations des contrôleurs.

### Exemples de requêtes

Voir le dossier `docs/` pour des exemples Postman ou cURL.

## 🚢 Déploiement

### Variables d'environnement de production

Créer un fichier `.env.prod` avec :
```env
APP_ENV=prod
APP_SECRET=your-secret-key
DATABASE_URL="mysql://user:password@host:3306/database"
```

### Optimisations

```bash
# Vider le cache
php bin/console cache:clear --env=prod

# Optimiser l'autoloader
composer dump-autoload --optimize --classmap-authoritative

# Précharger les classes
composer dump-autoload --apcu
```

### Sécurité

- Changer `APP_SECRET` en production
- Utiliser HTTPS
- Configurer correctement CORS pour votre domaine
- Limiter les tentatives de connexion
- Utiliser des mots de passe forts

## 🔗 Intégration avec Next.js

Dans votre application Next.js, créer un fichier `.env.local` :

```env
NEXT_PUBLIC_API_BASE=http://localhost:8000
```

Exemple d'utilisation dans Next.js :

```typescript
const res = await fetch(`${process.env.NEXT_PUBLIC_API_BASE}/api/campaigns`, {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  }
});

const data = await res.json();
```

## 📝 Notes

- L'API retourne toujours du JSON
- Les codes HTTP suivent les standards REST :
  - `200` : Succès
  - `201` : Créé
  - `204` : Pas de contenu (suppression)
  - `400` : Requête invalide
  - `401` : Non authentifié
  - `403` : Interdit
  - `404` : Non trouvé
  - `422` : Erreur de validation

## 👥 Auteur

Développé dans le cadre du projet EEMI - Intégration Front.

## 📄 Licence

Ce projet est un projet éducatif.

