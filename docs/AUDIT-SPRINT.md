# Audit sprint — Qualité du code

Audit réalisé sur **CandidatureTracker** : validation, sécurité, clarté du code et expérience utilisateur.

**Date :** mai 2026  
**Statut global :** ✅ Conforme après corrections

---

## 1. Validation — Form Requests (obligatoire)

| Entrée | Form Request | Statut |
|--------|--------------|--------|
| Créer candidature | `StoreCandidatureRequest` | ✅ |
| Modifier candidature | `UpdateCandidatureRequest` | ✅ |
| Filtrer la liste | `FilterCandidatureRequest` | ✅ |
| Créer entretien | `StoreEntretienRequest` | ✅ |
| Modifier entretien | `UpdateEntretienRequest` | ✅ |
| Profil | `ProfileUpdateRequest` (Breeze) | ✅ |
| Connexion | `LoginRequest` (Breeze) | ✅ |

**Règle :** aucune validation métier dans les contrôleurs (`$request->validate()` interdit pour le domaine).

---

## 2. Messages d'erreur en français (pas HTML5 navigateur)

| Point | Avant | Après |
|-------|--------|--------|
| Attribut `required` dans les vues métier | Bulle navigateur « Please fill in this field » | Supprimé |
| Attribut `novalidate` sur les formulaires | Non | ✅ create / edit candidature, entretien |
| Fichier `lang/fr/validation.php` | Absent | ✅ Messages Laravel en français |
| `APP_LOCALE` | `en` | `fr` (`.env.example`) |
| Résumé des erreurs | Champ par champ seulement | ✅ Composant `<x-validation-summary />` |

**Exemple attendu après correction :**

> Le champ entreprise est obligatoire.

---

## 3. Sécurité — Policies (pas de `abort(404)` métier)

| Action | Mécanisme | Statut |
|--------|-----------|--------|
| Voir / modifier candidature | `CandidaturePolicy` + `$this->authorize()` | ✅ |
| Entretien | `EntretienPolicy` + policy sur la candidature liée | ✅ |
| Fichier hors candidature | `scopeBindings()` sur les routes | ✅ (404 Laravel automatique) |
| Fichier absent sur disque | Redirection + message `warning` | ✅ (plus de `abort(404)`) |
| Restaurer / supprimer archive | `auth()->user()->candidatures()->withTrashed()->findOrFail()` | ✅ |
| `user_id` à la création | Toujours `auth()->id()` dans le contrôleur | ✅ |
| Accès croisé (Bob → Alice) | 403 Policy | ✅ (test `PolicyTest`) |

**Supprimé :**

- `abort(404)` dans `CandidatureFichierController`
- `abort_unless($fichier->candidature_id === …)` (remplacé par liaison de route imbriquée)

---

## 4. Architecture contrôleurs

| Bonne pratique | Implémentation |
|----------------|----------------|
| Contrôleurs fins | Logique fichier dans `StoresCandidatureFichiers` |
| Eager loading | `with(['entretiens', 'fichiers'])` sur index / show |
| Routes nommées | `routes/web.php` |
| Ordre routes | `/candidatures/archives` avant `/{candidature}` |

---

## 5. Points de vigilance (connus, acceptés)

| Point | Détail |
|-------|--------|
| Auth Breeze | Formulaires login/register gardent `required` HTML (hors périmètre métier) |
| `authorize(): true` remplacé | Par `$this->user() !== null` dans les Form Requests ; la **Policy** reste sur les modèles |
| Vérification e-mail | Dashboard protégé par `verified` |

---

## 6. Checklist avant jury

- [ ] `APP_LOCALE=fr` dans votre `.env`
- [ ] Soumettre un formulaire candidature vide → messages **en français** sous les champs + encadré rouge
- [ ] `php artisan test` → tous verts
- [ ] Bob ne peut pas modifier la candidature d'Alice → **403**
- [ ] Sidebar fixe → Déconnexion visible sans scroll

---

## 7. Fichiers modifiés lors de cet audit

```
lang/fr/validation.php
app/Http/Requests/FilterCandidatureRequest.php
app/Http/Controllers/CandidatureFichierController.php
app/Http/Controllers/CandidatureController.php
app/Providers/AppServiceProvider.php
routes/web.php (scopeBindings)
resources/views/components/validation-summary.blade.php
resources/views/candidatures/create.blade.php
resources/views/candidatures/edit.blade.php
resources/views/entretiens/create.blade.php
resources/views/entretiens/edit.blade.php
resources/views/entretiens/partials/form-fields.blade.php
.env.example
docs/AUDIT-SPRINT.md
```

---

## 8. Synthèse pour le correcteur

Le projet respecte le pattern **Laravel Form Request + Policy** : la validation est centralisée, les messages sont en français côté serveur, et les accès non autorisés passent par les policies (403) plutôt que par des `abort()` dispersés dans le métier.
