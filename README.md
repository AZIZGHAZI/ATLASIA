# 🗺️ ATLASIA — Plateforme Nationale de Données Sociales

## Installation sur XAMPP

### Étapes d'installation

1. **Télécharger et installer XAMPP** (si ce n'est pas déjà fait)
   - URL : https://www.apachefriends.org/download.html

2. **Copier le dossier `atlasia/`** dans le répertoire `htdocs` de XAMPP :
   - **Windows** : `C:\xampp\htdocs\atlasia\`
   - **macOS** : `/Applications/XAMPP/htdocs/atlasia/`
   - **Linux** : `/opt/lampp/htdocs/atlasia/`

3. **Démarrer Apache** via le panneau de contrôle XAMPP

4. **Ouvrir l'application** dans votre navigateur :
   ```
   http://localhost/atlasia/
   ```

### Structure du projet

```
atlasia/
├── index.php                    → Redirection vers le dashboard
├── dashboard.php                → 🧠 Intelligence Stratégique (page principale)
├── espaces-travail.php          → 📁 Espaces de Travail & Projets
├── production.php               → 📊 Production & Collecte des Données
├── referentiel.php              → 🗄️  Référentiel de Données
├── ai-studio.php                → 🤖 AI Research Studio
├── observatoire.php             → 🔭 Observatoire Social
├── bibliotheque.php             → 📚 Bibliothèque Scientifique
├── reseau.php                   → 🤝 Réseau des Chercheurs
├── parametres.php               → ⚙️  Paramètres
├── css/
│   └── style.css                → Feuille de style principale
├── data/
│   └── morocco-regions.geojson  → Données géographiques du Maroc (16 régions)
├── includes/
│   ├── header.php               → En-tête + barre latérale (include commun)
│   └── footer.php               → Pied de page + scripts (include commun)
└── uploads/
    ├── almassa-01-07-2026.pdf   → جريدة المساء 1 يوليوز 2026
    ├── almassa-01-03-05-2026.pdf → جريدة المساء ماي 2026
    ├── almassa-01-12-2025.pdf   → جريدة المساء دجنبر 2025
    └── social-relationships.pdf → Livre : Social Relationships (Forgas & Fitness)
```

## Fonctionnalités

### 🧠 Intelligence Stratégique (Dashboard)
- **Carte interactive du Maroc** par région (Leaflet.js + GeoJSON)
- **5 indicateurs** : Chômage, Éducation, Pauvreté, Confiance, Migration
- **KPIs** en temps réel : 128 enquêtes, 2 458 datasets, 342 études, 1 256 chercheurs
- **Graphiques de tendances** (Chart.js) — 3 ans / 5 ans / 10 ans
- **Messages exécutifs** (Executive Insights)
- **Génération d'Executive Brief** avec formulaire
- **Alertes système** automatiques

### 📁 Espaces de Travail
- Gestion de projets de recherche (CRUD)
- Suivi d'avancement avec barres de progression
- Types : Master, Doctorat, Gouvernemental, Institutionnel

### 📊 Production & Collecte
- Tableau de bord des enquêtes terrain en cours
- Suivi des enquêteurs par zone
- Contrôle qualité des questionnaires

### 🗄️ Référentiel de Données
- Catalogue de 2 458 datasets
- Filtres par statut, accès, source
- Métadonnées complètes (format, variables, lignes)

### 🤖 AI Research Studio
- Interface de chat avec l'IA
- 8 fonctionnalités : Résumé, Analyse texte, Sentiments, Stats, Géo-analyse, Rapports, Research Gaps, Recommandations

### 🔭 Observatoire Social
- Tableau des 16 régions avec indicateurs colorés
- Graphiques comparatifs (chômage, scolarisation)

### 📚 Bibliothèque Scientifique
- **4 documents disponibles** (vos PDFs intégrés)
- Visionneuse PDF intégrée
- Analyse IA des documents
- Formulaire de dépôt

### 🤝 Réseau des Chercheurs
- Profils de chercheurs
- Forum de discussion
- Appels à collaboration

## Technologies utilisées

| Technologie | Usage |
|-------------|-------|
| PHP | Backend / templating |
| HTML5/CSS3 | Interface utilisateur |
| JavaScript | Interactivité |
| Leaflet.js | Carte interactive du Maroc |
| Chart.js | Graphiques et tendances |
| GeoJSON | Données géographiques |

## ⚠️ Notes importantes

- **Internet requis** pour charger la carte (tuiles OpenStreetMap via CDN)
- Pour une **utilisation 100% hors-ligne**, téléchargez les tuiles localement ou utilisez un serveur de tuiles local
- Les **données sont fictives/démo** — remplacez-les par vos données réelles

## 📞 Contact

ATLASIA — Plateforme Nationale de Données Sociales
Développé pour Ghazi Abdessalam — Chercheur Principal
