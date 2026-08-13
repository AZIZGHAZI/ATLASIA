# 📊 ATLASIA — Mise à jour avec données HCP réelles 2023-2024

## ✅ Mission accomplie

L'application ATLASIA a été **entièrement mise à jour** pour utiliser les **données réelles officielles** du Haut Commissariat au Plan (HCP) et le nouveau découpage administratif des **12 régions du Maroc** (en vigueur depuis 2015).

---

## 🌐 Accès à l'application

**Dashboard principal** : [https://156ca66085.preview.abacusai.app/dashboard.php](https://156ca66085.preview.abacusai.app/dashboard.php)

**Interface d'administration** : [https://156ca66085.preview.abacusai.app/admin.php](https://156ca66085.preview.abacusai.app/admin.php)
- 🔑 Mot de passe : `atlasia2024`

**Observatoire Social** : [https://156ca66085.preview.abacusai.app/observatoire.php](https://156ca66085.preview.abacusai.app/observatoire.php)

---

## 📋 Données intégrées

### Données nationales (2023-2024)
- **Population totale** : 36 828 330 habitants (RGPH 2024)
  - Urbaine : 23 110 108
  - Rurale : 13 718 222
- **Taux de chômage national** : 13,0% (2023)
  - Urbain : 16,8%
  - Rural : 6,3%
- **Taux d'activité national** : 43,6% (2023)
- **Hôpitaux publics** : 152 établissements, 21 455 lits, 1 043 904 admissions (2022)

### Données par région (12 régions)

| Région | Population 2024 | Chômage 2023 | Activité 2023 | Hôpitaux 2022 |
|--------|-----------------|--------------|---------------|---------------|
| **Tanger-Tétouan-Al Hoceïma** | 4 030 222 | 10,1% | 48,7% | 18 |
| **L'Oriental** | 2 294 665 | 19,6% | 40,1% | 15 |
| **Fès-Meknès** | 4 467 911 | 14,2% | 41,8% | 19 |
| **Rabat-Salé-Kénitra** | 5 132 639 | 11,6% | 44,0% | 19 |
| **Béni Mellal-Khénifra** | 2 525 801 | 12,8% | 40,0% | 11 |
| **Casablanca-Settat** | 7 688 967 | 15,0% | 46,1% | 25 |
| **Marrakech-Safi** | 4 892 393 | 7,7% | 44,0% | 16 |
| **Drâa-Tafilalet** | 1 655 623 | 11,9% | 41,7% | 11 |
| **Souss-Massa** | 3 020 431 | 13,5% | 39,0% | 8 |
| **Guelmim-Oued Noun** | 448 685 | 20,3%* | 45,3%* | 5 |
| **Laâyoune-Sakia El Hamra** | 451 028 | 20,3%* | 45,3%* | 4 |
| **Dakhla-Oued Ed-Dahab** | 219 965 | 20,3%* | 45,3%* | 1 |

_*Les 3 régions du Sud partagent les données emploi HCP regroupées sous "Régions du Sud"_

---

## 🗺️ Carte géographique

Un nouveau fichier GeoJSON a été créé avec les **12 régions officielles** :
- Fichier : `/atlasia/data/morocco-regions-12.geojson`
- Méthode : Fusion géographique des 16 anciennes régions avec `geopandas`
- Format : GeoJSON standard avec propriétés : `id`, `nom`, `nom_arabe`, `chef_lieu`

---

## 🛠️ Fichiers créés/modifiés

### Nouveaux fichiers
1. **`/data/regions_data.json`** : Base de données structurée avec toutes les données HCP
2. **`/data/morocco-regions-12.geojson`** : Carte des 12 nouvelles régions
3. **`/admin.php`** : Interface d'administration (protégée par mot de passe)
4. **`/api/update_data.php`** : API de sauvegarde des modifications
5. **`/data/build_regions12.py`** : Script de génération du GeoJSON

### Fichiers mis à jour
1. **`dashboard.php`** : Intégration complète des données réelles
   - Indicateurs nationaux réels
   - Carte interactive avec 4 indicateurs (chômage, activité, population, santé)
   - Tableau détaillé des indicateurs régionaux
   - Sources HCP mentionnées

2. **`observatoire.php`** : Graphiques et tableaux avec données réelles
   - Graphique de chômage par région
   - Graphique de population par région
   - Tableau récapitulatif

3. **`includes/header.php`** : Ajout du lien vers l'administration

---

## 📚 Sources des données

**Source principale** : Haut Commissariat au Plan (HCP)
- Annuaire Statistique du Maroc, année 2024
- RGPH 2024 (Recensement Général de la Population et de l'Habitat)
- Enquête Nationale sur l'Emploi 2023
- Statistiques du Ministère de la Santé 2022

---

## 🔧 Utilisation de l'interface Admin

### Accès
1. Aller sur : [admin.php](https://156ca66085.preview.abacusai.app/admin.php)
2. Entrer le mot de passe : `atlasia2024`

### Fonctionnalités
- **Modifier l'année de référence** : Changer l'année des données
- **Mettre à jour les données régionales** : Modifier population, emploi, santé pour chaque région
- **Sauvegarder** : Les modifications sont enregistrées dans `regions_data.json`
- **Sauvegarde automatique** : Un backup horodaté est créé avant chaque modification dans `/data/backups/`
- **Exporter en JSON** : Télécharger une copie des données

### Processus de mise à jour annuelle
1. Se connecter à l'interface admin
2. Modifier l'année de référence
3. Mettre à jour les valeurs pour chaque région
4. Cliquer sur "💾 Sauvegarder les modifications"
5. Vérifier les changements sur le dashboard

---

## 🔄 Indicateurs disponibles sur la carte

Le dashboard affiche désormais **4 indicateurs dynamiques** :

1. **Taux de chômage** (2023) : Affiche le taux de chômage par région en %
2. **Taux d'activité** (2023) : Affiche le taux d'activité par région en %
3. **Population** (RGPH 2024) : Affiche la population totale par région
4. **Lits hospitaliers** (2022) : Affiche le nombre de lits fonctionnels par région

Chaque indicateur utilise une palette de couleurs spécifique et affiche des informations détaillées au survol.

---

## 🎯 Prochaines étapes recommandées

### Court terme
1. ✅ **Tester l'application** : Vérifier toutes les fonctionnalités
2. ✅ **Vérifier les données** : Comparer avec les sources HCP
3. 🔜 **Ajouter plus d'indicateurs** : Éducation, agriculture, etc.

### Moyen terme
1. **Intégrer les annuaires régionaux** : Données détaillées par région (Dakhla, Drâa-Tafilalet, Guelmim)
2. **Ajouter des graphiques supplémentaires** : Évolution temporelle des indicateurs
3. **Améliorer la sécurité** : Système d'authentification plus robuste pour l'admin

### Long terme
1. **API automatique HCP** : Connexion directe aux données HCP (si disponible)
2. **Module de prévision** : Projections statistiques avec IA
3. **Tableaux de bord personnalisables** : Permettre aux utilisateurs de créer leurs propres vues

---

## 📝 Notes techniques

### Structure des données (`regions_data.json`)
```json
{
  "_meta": {
    "source": "HCP",
    "annee_reference": "2024",
    "derniere_mise_a_jour": "timestamp"
  },
  "national": { ... },
  "regions": [
    {
      "id": "region-id",
      "nom": "Nom de la région",
      "nom_arabe": "الاسم بالعربية",
      "chef_lieu": "Ville principale",
      "population": { ... },
      "emploi": { ... },
      "sante": { ... }
    }
  ]
}
```

### Mapping anciennes → nouvelles régions
- Tanger-Tétouan + Taza-Al Hoceima → **Tanger-Tétouan-Al Hoceïma**
- Oriental → **L'Oriental**
- Fès-Boulemane + Meknès-Tafilalet → **Fès-Meknès**
- Rabat-Salé + Gharb-Chrarda → **Rabat-Salé-Kénitra**
- Tadla-Azilal → **Béni Mellal-Khénifra**
- Grand Casablanca + Chaouia + Doukkala-Abda → **Casablanca-Settat**
- Marrakech-Tensift → **Marrakech-Safi**
- Souss-Massa-Draâ → **Souss-Massa** + **Drâa-Tafilalet**
- Guelmim-Es-Semara → **Guelmim-Oued Noun**
- Laâyoune-Boujdour → **Laâyoune-Sakia El Hamra**
- Oued el Dahab → **Dakhla-Oued Ed-Dahab**

---

## 🐛 Résolution de problèmes

### Le serveur ne démarre pas
```bash
cd /home/ubuntu/atlasia
php -S 0.0.0.0:3000
```

### Erreur "regions_data.json not found"
Vérifier que le fichier existe :
```bash
ls -la /home/ubuntu/atlasia/data/regions_data.json
```

### La carte ne s'affiche pas
Vérifier la console JavaScript du navigateur (F12) et s'assurer que `morocco-regions-12.geojson` est accessible.

### Mot de passe admin oublié
Le mot de passe par défaut est : `atlasia2024`
Pour le modifier, éditer `/admin.php` et `/api/update_data.php`

---

## 📞 Contact & Support

Pour toute question ou assistance :
- Documentation HCP : [https://www.hcp.ma](https://www.hcp.ma)
- Annuaire Statistique du Maroc : [https://www.hcp.ma/Annuaire-Statistique-du-Maroc_a633.html](https://www.hcp.ma/Annuaire-Statistique-du-Maroc_a633.html)

---

**Version** : 2.0 — Données HCP 2023-2024
**Date de mise à jour** : 25 juillet 2026
**Statut** : ✅ Production Ready
