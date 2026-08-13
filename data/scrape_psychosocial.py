#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
ATLASIA — Scraper des dynamiques psychosociales (presse marocaine publique).

Collecte les articles récents des flux RSS de la presse marocaine, calcule de
VRAIES fréquences de mots sociaux, les sujets dominants, la co-occurrence de
termes et le volume de corpus par région, puis met à jour le bloc
"psychosocial" de data/regions_data.json.

Ré-exécutable à volonté :  python3 data/scrape_psychosocial.py
Le bouton « Rafraîchir » de l'admin appelle ce même script via api/refresh_psychosocial.php.

Aucune donnée illustrative : tout provient du corpus réellement téléchargé.
Sources = presse publique + RSS (aucun réseau social fermé n'est scrapé).
"""
import os, re, ssl, json, html, unicodedata, datetime, sys
from collections import defaultdict, Counter

ssl._create_default_https_context = ssl._create_unverified_context

try:
    import feedparser
except ImportError:
    sys.exit("Installez feedparser :  pip install feedparser")

HERE = os.path.dirname(os.path.abspath(__file__))
DATA_FILE = os.path.join(HERE, "regions_data.json")
CORPUS_FILE = os.path.join(HERE, "psychosocial_corpus.json")

# ---------------------------------------------------------------------------
# 1. Flux RSS de la presse marocaine publique
# ---------------------------------------------------------------------------
FEEDS = [
    ("Hespress FR", "https://fr.hespress.com/feed"),
    ("Hespress — Économie", "https://fr.hespress.com/economie/feed"),
    ("Hespress — Société", "https://fr.hespress.com/societe/feed"),
    ("Hespress — Politique", "https://fr.hespress.com/politique/feed"),
    ("Hespress — Régions", "https://fr.hespress.com/regions/feed"),
    ("Hespress — Sport", "https://fr.hespress.com/sport/feed"),
    ("Hespress — Monde", "https://fr.hespress.com/monde/feed"),
    ("Medias24", "https://www.medias24.com/feed/"),
    ("TelQuel", "https://telquel.ma/feed"),
    ("Yabiladi", "https://www.yabiladi.com/rss/actualites.xml"),
    # --- Sources ajoutées (URLs vérifiées joignables avant intégration) ---
    ("Le Site Info", "https://www.lesiteinfo.com/feed/"),
    ("La Vie Éco", "https://lavieeco.com/feed"),
    ("Challenge", "https://www.challenge.ma/feed"),
    ("Aujourd'hui le Maroc", "https://aujourdhui.ma/feed"),
    ("Barlamane", "https://www.barlamane.com/feed"),
    ("Le Desk", "https://ledesk.ma/feed/"),
    ("Bladi", "https://www.bladi.net/spip.php?page=backend"),
    ("Maroc Local", "https://maroclocal.com/feed/"),
    ("Le Reporter", "https://lereporter.ma/feed/"),
    ("EcoActu", "https://www.ecoactu.ma/feed/"),
]

# ---------------------------------------------------------------------------
# 2. Lexique social (terme canonique -> variantes regex, sans accents)
#    Les fréquences sont comptées sur le texte normalisé (sans accents, minuscule).
# ---------------------------------------------------------------------------
LEXIQUE = {
    "Emploi":            [r"emploi", r"embauche", r"recrutement", r"travail\b", r"marche du travail", r"salari"],
    "Chômage":           [r"chomage", r"chomeur", r"sans[- ]emploi", r"demandeur d.emploi"],
    "Jeunesse":          [r"jeune", r"jeunesse", r"neet"],
    "Éducation":         [r"educ", r"ecole", r"enseign", r"scolaris", r"eleve", r"universit", r"etudiant", r"bac\b", r"baccalaureat"],
    "Santé":             [r"sante", r"hopita", r"hospital", r"medecin", r"soin", r"maladie", r"clinique", r"medical"],
    "Cherté de la vie":  [r"cherte", r"pouvoir d.achat", r"inflation", r"hausse des prix", r"prix des", r"cout de la vie", r"vie chere"],
    "Logement":          [r"logement", r"immobilier", r"habitat", r"loyer", r"bidonville"],
    "Prix":              [r"\bprix\b", r"tarif", r"facture", r"carburant", r"gasoil", r"essence"],
    "Sécurité":          [r"securit", r"police", r"criminalit", r"delinqu", r"vol\b", r"agress"],
    "Justice":           [r"justice", r"tribunal", r"proces", r"condamn", r"parquet", r"prison"],
    "Corruption":        [r"corruption", r"pot[- ]de[- ]vin", r"detournement", r"malversation"],
    "Migration":         [r"migrat", r"migrant", r"immigr", r"emigr", r"harraga", r"clandestin"],
    "Femmes":            [r"\bfemme", r"feminin", r"feminis", r"genre\b", r"violence.* femme"],
    "Eau":               [r"\beau\b", r"barrage", r"hydrique", r"secheresse", r"penurie d.eau", r"stress hydrique"],
    "Environnement":     [r"environnement", r"climat", r"pollution", r"canicule", r"rechauffement", r"dechet"],
    "Manifestation":     [r"manifest", r"protestation", r"sit[- ]in", r"contestation", r"mouvement social"],
    "Grève":             [r"greve", r"debrayage", r"syndicat", r"protestation sociale"],
    "Agriculture":       [r"agricol", r"agriculture", r"paysan", r"recolte", r"campagne agricole", r"fellah"],
    "Tourisme":          [r"tourism", r"touriste", r"hotel", r"riad"],
    "Investissement":    [r"investiss", r"invest", r"projet.* dh", r"million.* dirham", r"milliard.* dirham"],
    "Inégalités":        [r"inegalit", r"pauvret", r"precarit", r"vulnerab", r"exclusion"],
    "Retraite":          [r"retraite", r"pension", r"cnss", r"cimr"],
    "Protection sociale":[r"protection sociale", r"aide sociale", r"amo\b", r"couverture medicale", r"ramed", r"allocation"],
    "Transport":         [r"transport", r"tramway", r"autoroute", r"train\b", r"oncf", r"bus\b"],
    "Numérique":         [r"numeriq", r"digital", r"internet", r"intelligence artificielle", r"\bia\b"],
}

# ---------------------------------------------------------------------------
# 3. Détection régionale (région -> villes / mots-clés, sans accents)
# ---------------------------------------------------------------------------
REGIONS = {
    "rabat-sale-kenitra":        ("Rabat-Salé-Kénitra",        [r"rabat", r"sale\b", r"salé", r"kenitra", r"temara", r"skhirat", r"sidi kacem", r"sidi slimane", r"khemisset"]),
    "casablanca-settat":         ("Casablanca-Settat",         [r"casablanca", r"casa\b", r"settat", r"mohammedia", r"el jadida", r"berrechid", r"benslimane", r"nouaceur"]),
    "marrakech-safi":            ("Marrakech-Safi",            [r"marrakech", r"safi", r"essaouira", r"kelaa", r"chichaoua", r"rehamna", r"youssoufia"]),
    "fes-meknes":                ("Fès-Meknès",                [r"\bfes\b", r"fès", r"meknes", r"meknès", r"ifrane", r"taza", r"sefrou", r"el hajeb", r"taounate", r"boulemane"]),
    "tanger-tetouan-al-hoceima": ("Tanger-Tétouan-Al Hoceïma", [r"tanger", r"tetouan", r"tétouan", r"al hoceima", r"hoceïma", r"larache", r"chefchaouen", r"ksar el kebir", r"fnideq", r"martil"]),
    "souss-massa":               ("Souss-Massa",               [r"agadir", r"souss", r"massa", r"taroudant", r"tiznit", r"chtouka", r"inezgane", r"ait melloul"]),
    "l-oriental":                ("L'Oriental",                [r"oujda", r"oriental", r"nador", r"berkane", r"driouch", r"jerada", r"figuig", r"taourirt", r"saidia"]),
    "beni-mellal-khenifra":      ("Béni Mellal-Khénifra",      [r"beni mellal", r"béni mellal", r"khenifra", r"khouribga", r"azilal", r"fquih ben salah", r"kasba tadla"]),
    "draa-tafilalet":            ("Drâa-Tafilalet",            [r"errachidia", r"ouarzazate", r"tinghir", r"zagora", r"midelt", r"tafilalet", r"draa", r"rissani", r"merzouga"]),
    "guelmim-oued-noun":         ("Guelmim-Oued Noun",         [r"guelmim", r"tan[- ]?tan", r"sidi ifni", r"assa", r"oued noun"]),
    "laayoune-sakia-el-hamra":   ("Laâyoune-Sakia El Hamra",   [r"laayoune", r"laâyoune", r"laayoun", r"smara", r"boujdour", r"tarfaya", r"sakia"]),
    "dakhla-oued-ed-dahab":      ("Dakhla-Oued Ed-Dahab",      [r"dakhla", r"oued ed[- ]?dahab", r"aousserd"]),
}

# Sujets « macro » suivis pour le classement (sous-ensemble du lexique)
SUJETS_SUIVIS = ["Emploi", "Chômage", "Cherté de la vie", "Éducation", "Santé",
                 "Logement", "Sécurité", "Migration", "Eau", "Environnement",
                 "Femmes", "Jeunesse", "Corruption", "Manifestation", "Agriculture"]

STOP = set("""au aux avec ce ces dans de des du elle en et eux il je la le les leur lui ma mais me meme mes moi mon ne
nos notre nous on ou par pas pour qu que qui sa se ses son sur ta te tes toi ton tu un une vos votre vous c d j l a m n s t y
plus tout tous toute toutes cette cet apres avant entre vers chez sans sous selon pendant depuis contre alors donc car
etre avoir fait faire dit dire ans an deux trois quatre cinq million millions milliard milliards dh dirham dirhams maroc
marocain marocaine marocains maroc""".split())


def strip_accents(s):
    return "".join(c for c in unicodedata.normalize("NFD", s) if unicodedata.category(c) != "Mn")


def clean_html(s):
    s = re.sub(r"<[^>]+>", " ", s or "")
    s = html.unescape(s)
    return re.sub(r"\s+", " ", s).strip()


def fetch_articles():
    articles, sources_ok = [], []
    for name, url in FEEDS:
        try:
            d = feedparser.parse(url)
            if not d.entries:
                continue
            sources_ok.append(name)
            for e in d.entries:
                title = clean_html(getattr(e, "title", ""))
                summary = clean_html(getattr(e, "summary", getattr(e, "description", "")))
                if not title:
                    continue
                pub = None
                for k in ("published_parsed", "updated_parsed"):
                    if getattr(e, k, None):
                        pub = datetime.datetime(*getattr(e, k)[:6])
                        break
                articles.append({"source": name, "title": title,
                                 "summary": summary, "url": clean_html(getattr(e, "link", "")),
                                 "date": pub.isoformat() if pub else None})
        except Exception as ex:
            print(f"  ! {name}: {ex}", file=sys.stderr)
    # déduplication par titre
    seen, uniq = set(), []
    for a in articles:
        k = a["title"].lower()
        if k in seen:
            continue
        seen.add(k)
        uniq.append(a)
    return uniq, sources_ok


def match_terms(text_norm):
    """Retourne {terme: nb_occurrences} sur le texte normalisé."""
    found = {}
    for term, pats in LEXIQUE.items():
        c = 0
        for p in pats:
            c += len(re.findall(p, text_norm))
        if c:
            found[term] = c
    return found


def match_regions(text_norm):
    found = set()
    for rid, (nom, pats) in REGIONS.items():
        for p in pats:
            if re.search(strip_accents(p), text_norm):
                found.add(rid)
                break
    return found


def build():
    print("→ Téléchargement des flux de presse marocaine…")
    articles, sources_ok = fetch_articles()
    n = len(articles)
    print(f"  {n} articles uniques collectés depuis {len(sources_ok)} sources.")
    if n == 0:
        sys.exit("Aucun article récupéré (réseau ?). regions_data.json inchangé.")

    now = datetime.datetime.now()
    # Seuil médian des dates : sépare le corpus en moitié « récente » / « ancienne »
    dates = sorted(d for d in (a.get("date") for a in articles) if d)
    median_date = dates[len(dates) // 2] if dates else None

    term_freq = Counter()          # occurrences pondérées par article
    term_articles = Counter()      # nb d'articles contenant le terme
    term_recent = Counter()        # articles récents (<=10 j) contenant le terme
    term_old = Counter()           # articles plus anciens
    cooc = defaultdict(Counter)    # co-occurrence terme<->terme
    region_terms = defaultdict(Counter)   # region -> Counter(term)
    region_vol = Counter()         # nb d'articles rattachés à la région
    word_regions = defaultdict(set)       # term -> régions où il apparaît

    for a in articles:
        raw = f"{a['title']} {a['summary']}"
        tnorm = strip_accents(raw.lower())
        term_counts = match_terms(tnorm)      # {terme: occurrences}
        terms = list(term_counts.keys())
        regs = match_regions(tnorm)
        # récence : moitié récente du corpus (date >= médiane)
        recent = bool(a["date"] and median_date and a["date"] >= median_date)
        for t, c in term_counts.items():
            term_freq[t] += c              # occurrences totales (taille du nuage)
            term_articles[t] += 1          # nb d'articles (couverture)
            (term_recent if recent else term_old)[t] += 1
            for r in regs:
                region_terms[r][t] += 1
                word_regions[t].add(r)
        for t1 in terms:
            for t2 in terms:
                if t1 != t2:
                    cooc[t1][t2] += 1
        for r in regs:
            region_vol[r] += 1

    # ---- mots_cles (nuage de mots + fenêtre mot) ----
    mots = []
    for term, freq in term_freq.most_common():
        if freq < 1:
            continue
        assoc = [w for w, _ in cooc[term].most_common(4)]
        regs = sorted(word_regions[term],
                      key=lambda r: region_terms[r][term], reverse=True)[:2]
        regs_noms = [REGIONS[r][0] for r in regs]
        mots.append({
            "mot": term,
            "freq": int(freq),
            "etudes": int(term_articles[term]),
            "regions": regs_noms,
            "associes": assoc,
        })

    # ---- sujets suivis (classement + évolution récente vs ancienne) ----
    sujets = []
    for t in SUJETS_SUIVIS:
        f = term_freq.get(t, 0)
        if f == 0:
            continue
        rec, old = term_recent.get(t, 0), term_old.get(t, 0)
        if old > 0:
            evo = round((rec - old) / old * 100)
        else:
            evo = 100 if rec > 0 else 0
        evo = max(-99, min(300, evo))
        top_region = None
        best = -1
        for r, c in region_terms.items():
            if c[t] > best:
                best = c[t]; top_region = REGIONS[r][0]
        sujets.append({"nom": t, "freq": int(f), "evolution": int(evo),
                       "region": top_region or "National"})
    sujets.sort(key=lambda s: s["freq"], reverse=True)

    # ---- régions ----
    regions_out = {}
    covered = 0
    for rid, (nom, _) in REGIONS.items():
        vol = int(region_vol.get(rid, 0))
        if vol > 0:
            covered += 1
        top = [t for t, _ in region_terms[rid].most_common(5)]
        regions_out[rid] = {
            "nom": nom,
            "articles_presse": vol,
            "mentions": int(sum(region_terms[rid].values())),
            "themes_distincts": int(len(region_terms[rid])),
            "volume_total": vol,
            "sujets": top,
            "derniere_maj": now.strftime("%d/%m/%Y %H:%M"),
        }

    kpis = {
        "volume_total": n,                                   # articles analysés
        "mentions_total": int(sum(term_freq.values())),      # occurrences de mots sociaux
        "etudes_total": int(sum(term_articles.values())),
        "regions_couvertes": covered,
        "nb_regions": len(REGIONS),
        "nb_sujets": len(sujets),
        "nb_mots": len(mots),
    }

    meta = {
        "titre": "Observatoire National des Dynamiques Psychosociales",
        "accroche": "Une page d'analyse basée sur la psychologie sociale appliquée pour "
                    "expliquer le climat social, comprendre les changements comportementaux "
                    "et appuyer la prise de décision.",
        "version": "V2.0 — corpus presse réel",
        "derniere_mise_a_jour": now.strftime("%d/%m/%Y %H:%M"),
        "sources": sources_ok,
        "nb_articles": n,
        "methode": "Fréquences réelles calculées sur les titres et chapôs des articles RSS "
                   "de la presse marocaine publique. La carte est colorée selon un critère "
                   "MESURABLE : le volume d'articles rattachés à chaque région.",
        "note": "Corpus presse publique (RSS). Les couleurs de la carte traduisent le volume "
                "de données disponibles par région, non l'intensité réelle des phénomènes.",
    }

    psychosocial = {
        "_meta": meta, "kpis": kpis, "regions": regions_out,
        "mots_cles": mots, "sujets": sujets,
    }

    # ---- écriture ----
    data = json.load(open(DATA_FILE, encoding="utf-8"))
    data["psychosocial"] = psychosocial
    tmp = DATA_FILE + ".tmp"
    json.dump(data, open(tmp, "w", encoding="utf-8"), ensure_ascii=False, indent=2)
    os.replace(tmp, DATA_FILE)

    json.dump({"generated": now.isoformat(), "articles": articles},
              open(CORPUS_FILE, "w", encoding="utf-8"), ensure_ascii=False, indent=2)

    print(f"✓ psychosocial mis à jour : {len(mots)} mots, {len(sujets)} sujets, "
          f"{covered}/{len(REGIONS)} régions couvertes, {n} articles.")
    print(f"  Top mots : " + ", ".join(f"{m['mot']}({m['freq']})" for m in mots[:8]))
    print(f"  Corpus brut : {CORPUS_FILE}")


if __name__ == "__main__":
    build()
