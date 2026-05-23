# MyDocuHub 📁

> Plateforme d'échange de documents pédagogiques entre stagiaires et formateurs.

[![CI MyDocuHub](https://github.com/MyriamD-ops/myDocuHub/actions/workflows/main.yml/badge.svg)](https://github.com/MyriamD-ops/myDocuHub/actions/workflows/main.yml)
[![PHP](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-13-red)](https://laravel.com/)
[![Render](https://img.shields.io/badge/Déployé%20sur-Render-purple)](https://mydocuhub-lxe2.onrender.com)

---

## 🌐 Démo en production

**URL :** [https://mydocuhub-lxe2.onrender.com](https://mydocuhub-lxe2.onrender.com)

| Rôle | Email | Mot de passe |
|---|---|---|
| Formateur (admin) | `formateur@mydocs.fr` | `MyDocs2026!` |

> ⚠️ Changer le mot de passe après la première connexion.

---

## 📋 Présentation

MyDocuHub permet aux stagiaires d'un centre de formation d'échanger des ressources pédagogiques (rapports, cours, templates…) de manière sécurisée, avec un accès contrôlé par invitation.

### Fonctionnalités principales

- 🔐 **Inscription par code d'invitation** — seules les personnes invitées peuvent créer un compte
- 📁 **Upload de documents** — PDF, Word, Excel, PowerPoint (10 Mo max)
- ⬇️ **Téléchargement sécurisé** — les fichiers sont stockés hors du webroot
- 🗂️ **Filtrage par catégorie** — Rapport, Cours, Template, Exercice, Correction, Autre
- 👤 **Page profil** — liste de ses propres documents
- 🛠️ **Espace formateur** — génération et gestion des codes d'invitation

---

## 🛠️ Stack technique

| Composant | Technologie |
|---|---|
| Back-end | Laravel 13 |
| Front-end | Blade + Tailwind CSS |
| Build assets | Vite 6 |
| Base locale | MySQL 8 |
| Base production | PostgreSQL 15 (Render) |
| Authentification | Laravel Breeze (modifié) |
| Déploiement | Render (Docker) |
| CI/CD | GitHub Actions |
| PHP | 8.4 |
| Node.js | 22 |

---

## ⚙️ Installation locale

### Prérequis

Vérifie que ces outils sont installés :

```bash
php -v       # PHP 8.4+
composer --version
node -v      # Node 22+
npm -v
mysql --version
```

### 1. Cloner le projet

```bash
git clone https://github.com/MyriamD-ops/myDocuHub.git
cd myDocuHub
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Ouvre `.env` et modifie :

```env
APP_NAME=MyDocuHub
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mydocuhub
DB_USERNAME=root
DB_PASSWORD=ton_mot_de_passe
```

### 4. Créer la base de données

```bash
mysql -u root -p
```

```sql
CREATE DATABASE mydocuhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 5. Lancer les migrations et seeders

```bash
php artisan migrate
php artisan db:seed --class=CategorieSeeder
php artisan db:seed --class=FormateurSeeder
```

### 6. Compiler les assets et lancer le serveur

```bash
npm run dev
php artisan serve
```

Ouvre [http://localhost:8000](http://localhost:8000) et connecte-toi avec :
- **Email :** `formateur@mydocuhub.fr`
- **Mot de passe :** `MyDocuHub2026!`

---

## 🗄️ Structure de la base de données

```
users          → id, nom, prenom, email, password, promotion, role (formateur|stagiaire)
invitations    → id, code, role, created_by (FK), used_by (FK), used_at, expires_at
categories     → id, nom
documents      → id, titre, description, fichier, nom_original, categorie_id (FK), user_id (FK)
```

> ⚠️ Les migrations utilisent `string()` au lieu de `enum()` pour la compatibilité MySQL ET PostgreSQL.

---

## 🗂️ Architecture du projet

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/RegisteredUserController.php   ← inscription par invitation
│   │   ├── DocumentController.php
│   │   ├── InvitationController.php
│   │   └── ProfilController.php
│   ├── Middleware/CheckRole.php                ← contrôle d'accès par rôle
│   └── Requests/Auth/InvitationRegisterRequest.php
├── Models/
│   ├── User.php         ← isFormateur(), isStagiaire()
│   ├── Invitation.php   ← isValide(), isExpired(), consommer()
│   ├── Categorie.php
│   └── Document.php
└── Policies/DocumentPolicy.php                 ← seul le propriétaire peut supprimer

database/
├── migrations/          ← users, categories, invitations, documents, sessions
└── seeders/
    ├── CategorieSeeder.php   ← 6 catégories prédéfinies
    └── FormateurSeeder.php   ← premier compte formateur

docker/
├── nginx.conf           ← Nginx port 8080, client_body_temp_path /tmp
└── start.sh             ← migrate + seed + cache + php-fpm + nginx

resources/views/
├── layouts/             ← app.blade.php, guest.blade.php
├── auth/                ← login, register
├── documents/           ← index, create, show
├── invitations/         ← index (espace formateur)
└── profil/              ← index (mes documents)
```

---

## 🚀 Déploiement sur Render

### Variables d'environnement requises

| Variable | Description |
|---|---|
| `APP_NAME` | `MyDocuHub` |
| `APP_ENV` | `production` |
| `APP_KEY` | Générer avec `php artisan key:generate --show` |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL Render (ex: `https://mydocuhub-lxe2.onrender.com`) |
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | Depuis Render DB → Info |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | Depuis Render DB → Info |
| `DB_USERNAME` | Depuis Render DB → Info |
| `DB_PASSWORD` | Depuis Render DB → Info |
| `FILESYSTEM_DISK` | `private` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `LOG_CHANNEL` | `stderr` |

### Déploiement

Le déploiement est automatique à chaque push sur `main` via GitHub Actions + Render.

```bash
git push origin main
# → CI GitHub Actions (build + test)
# → Render reconstruit l'image Docker et redéploie
```

En cas de cache Docker bloqué :
**Render → ton service → Manual Deploy → Clear build cache & deploy**

---

## 🔐 Sécurité

- Authentification par **code d'invitation** — inscription fermée
- Mots de passe **hashés avec bcrypt**
- Middleware **`auth`** sur toutes les routes protégées
- Middleware **`role:formateur`** pour l'espace admin
- **CSRF** natif Laravel sur tous les formulaires
- Fichiers stockés dans **`storage/app/private/`** (hors webroot)
- Téléchargement via **`Storage::download()`** (authentification obligatoire)
- **`DocumentPolicy`** — seul le propriétaire peut supprimer son document

---

## ⚠️ Points de vigilance connus

| Problème | Solution |
|---|---|
| `npm ci` échoue sur Linux avec Vite 6 | Toujours `rm -f package-lock.json && npm install` |
| Upload → erreur 500 Nginx | Vérifier `client_body_temp_path /tmp/nginx_client_body` dans nginx.conf |
| Modifications start.sh non appliquées | Render → Clear build cache & deploy |
| Enum() incompatible PostgreSQL | Utiliser `string()` + validation dans Form Request |
| Deux fichiers workflow actifs | Un seul fichier dans `.github/workflows/` |

---

## 👥 Routes principales

| Méthode | Route | Accès |
|---|---|---|
| `GET` | `/register/{code}` | Public |
| `GET/POST` | `/login` | Public |
| `GET` | `/documents` | Connecté |
| `GET/POST` | `/documents/create` | Connecté |
| `GET` | `/documents/{id}` | Connecté |
| `GET` | `/documents/{id}/download` | Connecté |
| `DELETE` | `/documents/{id}` | Propriétaire |
| `GET` | `/profil` | Connecté |
| `GET/POST` | `/admin/invitations` | Formateur |
| `DELETE` | `/admin/invitations/{id}` | Formateur |

---

## 📁 Catégories disponibles

`Rapport` · `Cours` · `Template` · `Exercice` · `Correction` · `Autre`

---

## 🤝 Contribuer

```bash
# Créer une branche
git checkout -b feature/ma-fonctionnalite

# Développer, tester, commiter
git commit -m "feat: description claire de la modification"

# Pusher et créer une Pull Request
git push origin feature/ma-fonctionnalite
```

> ⚠️ Ne jamais faire `git push --force` sur `main`.

---

## 📄 Licence

Projet réalisé dans le cadre d'une formation **Concepteur Développeur d'Applications (CDA)** — Mai 2026.
