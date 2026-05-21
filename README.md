# CandidatureTracker

Application web de **suivi de candidatures** : offres d'emploi, entretiens, filtres, archivage (soft delete) et pièces jointes. Projet Laravel 13 réalisé dans un cadre pédagogique (5 sprints, user stories, tests, présentation jury).

---

## Table des matières

- [Aperçu](#aperçu)
- [Fonctionnalités](#fonctionnalités-user-stories)
- [Stack technique](#stack-technique)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Lancement](#lancement)
- [Comptes de démonstration](#comptes-de-démonstration)
- [Tests automatisés](#tests-automatisés)
- [Modèle de données (MCD / MLD)](#modèle-de-données-mcd--mld)
- [Architecture Laravel](#architecture-laravel)
- [Structure du projet](#structure-du-projet)
- [Documentation complémentaire](#documentation-complémentaire)
- [Scénario de démo (jury)](#scénario-de-démo-jury)
- [Licence](#licence)

---

## Aperçu

Chaque utilisateur authentifié gère **ses propres candidatures** :

- Création, modification, liste et détail
- Filtres par **statut** et **priorité**
- **Entretiens** rattachés à une candidature
- **Archivage** sans suppression définitive (`SoftDeletes`)
- **Policy** : impossible d'accéder aux données d'un autre utilisateur (403)
- **Bonus** : upload / téléchargement de CV ou lettre (PDF, DOC, DOCX)

| URL | Rôle |
|-----|------|
| [http://localhost:8000](http://localhost:8000) | Application (après `php artisan serve`) |
| [http://localhost:8081](http://localhost:8081) | phpMyAdmin (inspection BDD) |

---

## Fonctionnalités (User Stories)

| US | Description | Statut |
|----|-------------|--------|
| US1 | Authentification (inscription, connexion, déconnexion) — Breeze | ✅ |
| US2 | Liste des candidatures avec filtres statut / priorité | ✅ |
| US3 | Créer une candidature | ✅ |
| US4 | Détail d'une candidature + entretiens liés | ✅ |
| US5 | Modifier une candidature | ✅ |
| US6 | Archiver (soft delete) | ✅ |
| US7 | Page des archives | ✅ |
| US8 | Restaurer une candidature archivée | ✅ |
| US9 | Filtrer par statut et priorité | ✅ |
| US10 | Ajouter un entretien | ✅ |
| US11 | Modifier / supprimer un entretien | ✅ |
| Bonus | Upload et téléchargement de pièce jointe (PDF, DOC, DOCX) | ✅ |

---

## Stack technique

| Composant | Version / choix |
|-----------|-----------------|
| PHP | 8.3 |
| Laravel | 13 |
| Base de données | MySQL 8 (Docker) |
| Authentification | Laravel Breeze (Blade) |
| Front | Blade + Tailwind (Vite) |
| Tests | PHPUnit |
| Outils dev | Laravel Debugbar (détection N+1) |
| Conteneurs | Docker Compose (`compose.yaml`) |

---

## Prérequis

- **PHP** ≥ 8.3, **Composer**
- **Node.js** + npm (build des assets)
- **Docker** + Docker Compose (MySQL + phpMyAdmin)
- **Git**

---

## Installation

### 1. Cloner le dépôt

```bash
git clone <url-du-repo> candidature-tracker
cd candidature-tracker
```

### 2. Dépendances PHP et front

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install && npm run build
```

### 3. Démarrer MySQL et phpMyAdmin

```bash
docker compose -f compose.yaml up -d
```

Vérifier que le conteneur MySQL est actif :

```bash
docker compose -f compose.yaml ps
```

### 4. Base de données et données de démo

> `php artisan migrate` crée les tables **sans** utilisateurs. Pour Alice, Bob et les candidatures exemples, utilisez **`--seed`**.

```bash
php artisan migrate:fresh --seed
```

Alternative (sans effacer les tables existantes) :

```bash
php artisan migrate
php artisan db:seed
```

---

## Configuration

Fichier **`.env`** (valeurs alignées sur `compose.yaml`) :

```env
APP_NAME=CandidatureTracker
APP_URL=http://localhost:8000
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=candidature_tracker
DB_USERNAME=root
DB_PASSWORD=root

DEBUGBAR_ENABLED=true
FILESYSTEM_DISK=local
```

| Variable | Description |
|----------|-------------|
| `DB_PORT=3307` | Port MySQL exposé par Docker sur la machine hôte |
| `DB_DATABASE` | Base applicative (phpMyAdmin : **`candidature_tracker`**) |
| `DEBUGBAR_ENABLED` | Barre de debug + onglet SQL (audit N+1) |

---

## Lancement

```bash
php artisan serve
```

Ouvrir **http://localhost:8000** (ou le port indiqué dans le terminal).

**phpMyAdmin** : http://localhost:8081 — utilisateur `root`, mot de passe `root`, base **`candidature_tracker`**.

---

## Comptes de démonstration

Créés par `database/seeders/UserSeeder.php` :

| Email | Mot de passe | Usage recommandé |
|-------|--------------|-------------------|
| alice@example.com | password | Compte principal — candidatures seedées |
| bob@example.com | password | 2ᵉ navigateur — démo Policy (403) |

Vérification rapide :

```bash
php artisan tinker --execute="echo App\Models\User::count().' utilisateur(s)';"
```

---

## Tests automatisés

Les tests utilisent une base dédiée : **`candidature_tracker_testing`** (port **3307**).

### Créer la base de test (une fois)

```bash
mysql -h 127.0.0.1 -P 3307 -u root -proot \
  -e "CREATE DATABASE IF NOT EXISTS candidature_tracker_testing;"
```

### Lancer la suite

```bash
php artisan test
```

**Couverture métier** (`tests/Feature/`) :

- Accès invité → redirection login
- Policy → 403 accès croisé
- CRUD candidature + validation
- Archivage / restauration (soft delete)
- Filtres statut
- Upload / téléchargement de fichiers

---

## Modèle de données (MCD / MLD)

Diagrammes déposés dans le dossier **`docs/`** :

| Fichier | Description |
|---------|-------------|
| [`docs/mcd.png`](docs/mcd.png) | Modèle conceptuel (entités, cardinalités) |
| [`docs/mld.png`](docs/mld.png) | Modèle logique (tables, PK, FK, types) |

### MCD — Modèle conceptuel

![MCD — Modèle conceptuel de données](docs/mcd.png)

*Si l'image n'apparaît pas : ajoutez votre fichier `docs/mcd.png` (ou `.jpg`).*

### MLD — Modèle logique

![MLD — Modèle logique de données](docs/mld.png)

*Si l'image n'apparaît pas : ajoutez votre fichier `docs/mld.png` (ou `.jpg`).*

**Relations :**

- `User` **1 → N** `Candidature`
- `Candidature` **1 → N** `Entretien`
- `Candidature` : **Soft Deletes** (`deleted_at` pour l'archivage)

Documentation textuelle et schéma Mermaid de secours : **[docs/MCD-MLD.md](docs/MCD-MLD.md)**.

---

## Architecture Laravel

| Règle | Implémentation |
|-------|----------------|
| Routes nommées | `routes/web.php` — préfixe `/candidatures`, middleware `auth` |
| Ordre des routes | `/candidatures/archives` **avant** `/candidatures/{candidature}` |
| Validation | `StoreCandidatureRequest`, `UpdateCandidatureRequest`, `StoreEntretienRequest`, `UpdateEntretienRequest` |
| Autorisation | `CandidaturePolicy` + `$this->authorize()` (pas de `abort(403)` métier) |
| Propriété des données | `user_id` = `auth()->id()` à la création (jamais depuis le formulaire) |
| Archives | `$candidature->delete()` + `onlyTrashed()` / `restore()` |
| Performance | `with('entretiens')` sur les listes et détails (éviter N+1) |
| Fichiers | `Storage::disk('local')` — route `candidatures.download` |

---

## Structure du projet

```
candidature-tracker/
├── app/
│   ├── Http/Controllers/     # CandidatureController, EntretienController
│   ├── Http/Requests/        # Form Requests (validation)
│   ├── Models/               # User, Candidature, Entretien
│   └── Policies/             # CandidaturePolicy
├── database/
│   ├── migrations/
│   └── seeders/              # UserSeeder, CandidatureSeeder, EntretienSeeder
├── docs/
│   ├── mcd.png               # ← votre diagramme MCD
│   ├── mld.png               # ← votre diagramme MLD
│   ├── MCD-MLD.md            # Description tables + Mermaid
│   └── JURY-DAY.md           # Commandes jour J
├── resources/views/
│   └── candidatures/         # index, create, show, edit, archives
├── routes/web.php
├── tests/Feature/            # Tests PHPUnit métier
├── compose.yaml              # MySQL 3307 + phpMyAdmin 8081
└── README.md
```

---

## Documentation complémentaire

| Document | Contenu |
|----------|---------|
| [docs/JURY-DAY.md](docs/JURY-DAY.md) | Commandes, URLs, scénario démo, dépannage **jour du jury** |
| [docs/MCD-MLD.md](docs/MCD-MLD.md) | Tables, enums, cardinalités, schéma Mermaid |

---

## Scénario de démo (jury)

Ordre suggéré (~10 min) — détail complet dans [docs/JURY-DAY.md](docs/JURY-DAY.md) :

1. Connexion (`alice@example.com`)
2. Créer 2 candidatures (statuts / priorités différents)
3. Ajouter un entretien sur le détail
4. Filtrer la liste par statut
5. Archiver → page Archives → restaurer
6. Modifier une candidature
7. Bonus : upload fichier → téléchargement
8. 2ᵉ onglet `bob@example.com` → tenter d'éditer la candidature d'Alice → **403**
9. Terminal : `php artisan test` → tous verts

---

## Licence

MIT — voir le dépôt pour les détails.
