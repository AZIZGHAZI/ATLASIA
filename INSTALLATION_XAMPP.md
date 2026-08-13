# Guide d'installation — ATLASIA sur serveur local XAMPP

Ce guide explique comment installer et exécuter la plateforme **ATLASIA** sur un
serveur local Windows/macOS/Linux à l'aide de **XAMPP** (Apache + PHP).

> ATLASIA fonctionne **sans base de données** : toutes les données sont stockées
> dans des fichiers JSON (`data/regions_data.json`). Aucun MySQL n'est requis.

---

## 1. Pré-requis

- **XAMPP** avec **PHP 8.0 ou supérieur** (téléchargeable sur
  https://www.apachefriends.org).
- Le module **Apache** (le module MySQL n'est **pas** nécessaire).

---

## 2. Installation pas à pas

### Étape 1 — Installer XAMPP
Installez XAMPP en suivant l'assistant. Sous Windows, le dossier par défaut est
`C:\xampp`.

### Étape 2 — Copier les fichiers ATLASIA
1. Décompressez l'archive `atlasia-xampp.zip`.
2. Placez le dossier **`atlasia`** obtenu dans le répertoire `htdocs` de XAMPP :
   - **Windows** : `C:\xampp\htdocs\atlasia`
   - **macOS** : `/Applications/XAMPP/htdocs/atlasia`
   - **Linux** : `/opt/lampp/htdocs/atlasia`

Le chemin final doit ressembler à :
```
C:\xampp\htdocs\atlasia\dashboard.php
C:\xampp\htdocs\atlasia\index.php
C:\xampp\htdocs\atlasia\data\regions_data.json
...
```

### Étape 3 — Démarrer Apache
1. Ouvrez le **XAMPP Control Panel**.
2. Cliquez sur **Start** en face de **Apache**.
   (Il n'est pas nécessaire de démarrer MySQL.)

### Étape 4 — Accéder à la plateforme
Ouvrez votre navigateur et rendez-vous sur :

```
http://localhost/atlasia/
```

ou directement le tableau de bord :

```
http://localhost/atlasia/dashboard.php
```

---

## 3. Accès administrateur

La page d'administration permet de modifier les données (indicateurs régionaux,
séries temporelles nationales, etc.) :

```
http://localhost/atlasia/admin.php
```

- **Mot de passe administrateur** : `atlasia2024`

Après modification, cliquez sur **Enregistrer** : les données sont écrites dans
`data/regions_data.json` et une sauvegarde horodatée est créée automatiquement
dans `data/backups/`.

---

## 4. Permissions d'écriture (important)

Pour que l'enregistrement des données fonctionne, Apache doit pouvoir **écrire**
dans le dossier `data/` :

- **Windows** : aucun réglage particulier n'est généralement nécessaire.
- **macOS / Linux** : accordez les droits d'écriture au serveur, par exemple :
  ```bash
  chmod -R 775 /opt/lampp/htdocs/atlasia/data
  ```

Le dossier `data/backups/` doit exister (il est inclus, vide, dans l'archive) et
être accessible en écriture.

---

## 5. Structure des fichiers

| Élément | Rôle |
|---|---|
| `index.php` | Page d'accueil |
| `dashboard.php` | Tableau de bord (indicateurs + tendances HCP) |
| `observatoire.php` | Observatoire Social (évolution des indicateurs nationaux) |
| `observatoire-psychosocial.php` | Observatoire National des Dynamiques Psychosociales |
| `admin.php` | Administration / édition des données |
| `api/update_data.php` | Point d'écriture des données (JSON) |
| `data/regions_data.json` | **Toutes les données** (régions + séries nationales) |
| `data/backups/` | Sauvegardes automatiques horodatées |
| `data/*.geojson` | Fonds de carte des 12 régions du Maroc |
| `css/` `includes/` | Styles et éléments partagés (en-tête, pied de page) |
| `uploads/` | Documents de la bibliothèque |

---

## 6. Sources des données

Les données statistiques proviennent du **Haut-Commissariat au Plan (HCP)** —
*Annuaires Statistiques du Maroc, éditions 2017 à 2024*, ainsi que des résultats
des Recensements Généraux de la Population et de l'Habitat (RGPH).

Principaux indicateurs intégrés :
- Séries nationales **2015 → 2023** : taux de chômage, taux d'activité, PIB (prix courants).
- Population aux recensements **1971 → 2024**.
- Indicateurs régionaux (population RGPH 2024, emploi, santé) pour les 12 régions.

---

## 7. Dépannage

| Problème | Solution |
|---|---|
| Page blanche | Vérifiez que PHP ≥ 8.0 est actif dans XAMPP. |
| « 404 Not Found » | Vérifiez que le dossier s'appelle bien `atlasia` dans `htdocs`. |
| L'enregistrement échoue | Vérifiez les droits d'écriture sur `data/` et `data/backups/`. |
| Les graphiques ne s'affichent pas | Vérifiez la connexion Internet (Chart.js est chargé via CDN). |

---

*Plateforme ATLASIA — installation locale XAMPP. Données © HCP Maroc.*
