# ATLASIA — Nouveautés : données réelles, IA générative & mots sociaux

Ce document décrit les fonctionnalités ajoutées à la plateforme.

## 1. Données psychosociales RÉELLES (presse marocaine)

Fini les données illustratives : les mots sociaux, sujets, tendances et volumes
par région sont désormais **calculés à partir de vrais articles de presse**.

- **Sources** : flux RSS publics (Hespress FR & rubriques, Medias24, TelQuel, Yabiladi).
- **Script** : `data/scrape_psychosocial.py` — télécharge les articles, calcule les
  fréquences réelles des mots sociaux, la co-occurrence, les sujets et le volume par région,
  puis met à jour `data/regions_data.json` (bloc `psychosocial`).
- **Corpus brut** conservé dans `data/psychosocial_corpus.json`.

### Rafraîchir les données
Deux possibilités :

1. **Depuis l'administration** (`admin.php` → carte « Observatoire psychosocial ») :
   bouton **🔄 Rafraîchir les données psychosociales**.
2. **En ligne de commande** :
   ```bash
   pip install feedparser
   python3 data/scrape_psychosocial.py
   ```

> ⚠️ Le rafraîchissement nécessite **Python 3 + feedparser** installés sur le serveur,
> et un accès Internet. Sans cela, les dernières données déjà enregistrées restent affichées.

## 2. IA générative embarquée (boutons ✨)

Chaque bouton ✨ (« Expliquer », « Analyser cette région », etc.) interroge une **vraie IA
générative**, ancrée strictement sur les données de la plateforme (Charte ATLASIA).

### Activer l'IA générative — deux méthodes

**Méthode 1 — clé dans le fichier de config** (recommandée sur XAMPP)
1. Ouvrez `api/config.php`.
2. Collez votre clé dans `'api_key' => '...'` et choisissez le fournisseur :

   | Fournisseur      | `base_url`                        | `model` (exemple) |
   |------------------|-----------------------------------|-------------------|
   | Abacus RouteLLM  | `https://routellm.abacus.ai/v1`   | `gpt-4o-mini`     |
   | OpenAI           | `https://api.openai.com/v1`       | `gpt-4o-mini`     |
   | Groq / Together / OpenRouter / Ollama… | (leur URL /v1)     | (leur modèle)     |

   > Par défaut, `base_url` pointe déjà sur **RouteLLM (Abacus.AI)**.

**Méthode 2 — variable d'environnement** (aucune modification de fichier)
Définissez, côté serveur, l'une de ces variables ; elle est lue automatiquement :
- `OPENAI_API_KEY` (utilise le `base_url` configuré), ou
- `ABACUS_API_KEY` (bascule automatiquement sur l'endpoint RouteLLM).

Dès qu'une clé est présente, toutes les réponses passent en mode **✨ IA générative**
(badge visible dans chaque fenêtre et dans l'AI Research Studio).

> **Sans aucune clé** : la plateforme retombe automatiquement sur un **moteur d'analyse
> local** (badge 🔒), fondé sur les vraies données de la plateforme — aucune page cassée,
> aucun chiffre inventé, fonctionne 100 % hors-ligne.

Un **champ de question libre** (« Demander à l'IA ») est disponible dans chaque fenêtre
d'analyse : l'utilisateur pose sa propre question, l'IA répond en restant ancrée sur les données.

### AI Research Studio opérationnel
La page **AI Research Studio** (`ai-studio.php`) est branchée sur le même moteur :
- les **8 outils** (Résumé, Analyse de texte, Analyse de sentiments, Statistiques,
  Géo-analyse, Rédaction de rapports, Détection de lacunes, Recommandations) lancent une
  **vraie analyse** au clic (plus aucune réponse pré-enregistrée) ;
- l'**assistant en direct** répond aux questions libres via `api/ai.php` ;
- un badge indique le mode réel (**✨ IA générative** ou **🔒 Analyse locale**).

## 3. Carte des 12 régions corrigée

Les frontières proviennent désormais d'une **source officielle** (geoBoundaries ADM1).
La région **Fès-Meknès**, auparavant mal délimitée, est correcte.
Régénération : `python3 data/build_regions12_official.py /chemin/vers/mar_adm1.geojson`.

Le **clic sur une région** ouvre un panneau avec ses **données réelles actualisées**
(articles de presse, mentions sociales, thèmes distincts, sujets dominants, date de MàJ).

## 4. Nouvelle page « Mots sociaux du web »

`mots-sociaux.php` (menu ☁️ **Mots sociaux du web**) : un grand **nuage de mots** où la
**taille de chaque terme est proportionnelle à sa fréquence réelle** dans la presse,
plus un classement détaillé et une fenêtre par mot (occurrences, articles, régions, mots associés).

## Fichiers ajoutés / modifiés
- `data/scrape_psychosocial.py` *(nouveau)* — scraper presse.
- `data/build_regions12_official.py` *(nouveau)* — geojson officiel 12 régions.
- `data/morocco-regions-12.geojson` — remplacé (frontières officielles, Fès-Meknès corrigée).
- `api/ai.php`, `api/config.php`, `api/config.sample.php` *(nouveaux)* — proxy IA + config.
- `api/refresh_psychosocial.php` *(nouveau)* — rafraîchissement depuis l'admin.
- `mots-sociaux.php` *(nouveau)* — page nuage de mots.
- `observatoire-psychosocial.php`, `admin.php`, `includes/header.php`, `includes/footer.php`, `css/style.css` — mis à jour.
