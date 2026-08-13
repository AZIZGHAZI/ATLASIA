#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Fusionne les 16 anciennes regions du Maroc en 12 nouvelles regions (decoupage 2015).
Source geometrie : data/morocco-regions.geojson (Natural Earth, 16 regions).
"""
import json
import geopandas as gpd
from shapely.ops import unary_union

SRC = "morocco-regions.geojson"
OUT = "morocco-regions-12.geojson"

# Mapping : nouvelle region -> liste des anciennes regions (champ 'name' du GeoJSON source)
MAPPING = {
    "Tanger-Tétouan-Al Hoceïma": ["Tanger - Tétouan", "Taza - Al Hoceima - Taounate"],
    "L'Oriental": ["Oriental"],
    "Fès-Meknès": ["Fès - Boulemane", "Meknès - Tafilalet"],
    "Rabat-Salé-Kénitra": ["Rabat - Salé - Zemmour - Zaer", "Gharb - Chrarda - Béni Hssen"],
    "Béni Mellal-Khénifra": ["Tadla - Azilal"],
    "Casablanca-Settat": ["Grand Casablanca", "Chaouia - Ouardigha", "Doukkala - Abda"],
    "Marrakech-Safi": ["Marrakech - Tensift - Al Haouz"],
    "Souss-Massa": ["Souss - Massa - Draâ"],
    "Guelmim-Oued Noun": ["Guelmim - Es-Semara"],
    "Laâyoune-Sakia El Hamra": ["Laâyoune - Boujdour - Sakia El Hamra"],
    "Dakhla-Oued Ed-Dahab": ["Oued el Dahab"],
    # Drâa-Tafilalet : pas de correspondance ancienne exacte -> approximation
    # (voir plus bas : decoupe depuis Meknès-Tafilalet + Souss-Massa-Draâ)
}

# Metadonnees par region
META = {
    "Tanger-Tétouan-Al Hoceïma": {"id": "tanger-tetouan-al-hoceima", "nom_arabe": "طنجة - تطوان - الحسيمة", "chef_lieu": "Tanger"},
    "L'Oriental": {"id": "l-oriental", "nom_arabe": "الشرق", "chef_lieu": "Oujda"},
    "Fès-Meknès": {"id": "fes-meknes", "nom_arabe": "فاس - مكناس", "chef_lieu": "Fès"},
    "Rabat-Salé-Kénitra": {"id": "rabat-sale-kenitra", "nom_arabe": "الرباط - سلا - القنيطرة", "chef_lieu": "Rabat"},
    "Béni Mellal-Khénifra": {"id": "beni-mellal-khenifra", "nom_arabe": "بني ملال - خنيفرة", "chef_lieu": "Béni Mellal"},
    "Casablanca-Settat": {"id": "casablanca-settat", "nom_arabe": "الدار البيضاء - سطات", "chef_lieu": "Casablanca"},
    "Marrakech-Safi": {"id": "marrakech-safi", "nom_arabe": "مراكش - آسفي", "chef_lieu": "Marrakech"},
    "Drâa-Tafilalet": {"id": "draa-tafilalet", "nom_arabe": "درعة - تافيلالت", "chef_lieu": "Errachidia"},
    "Souss-Massa": {"id": "souss-massa", "nom_arabe": "سوس - ماسة", "chef_lieu": "Agadir"},
    "Guelmim-Oued Noun": {"id": "guelmim-oued-noun", "nom_arabe": "كلميم - واد نون", "chef_lieu": "Guelmim"},
    "Laâyoune-Sakia El Hamra": {"id": "laayoune-sakia-el-hamra", "nom_arabe": "العيون - الساقية الحمراء", "chef_lieu": "Laâyoune"},
    "Dakhla-Oued Ed-Dahab": {"id": "dakhla-oued-ed-dahab", "nom_arabe": "الداخلة - وادي الذهب", "chef_lieu": "Dakhla"},
}

gdf = gpd.read_file(SRC)
gdf = gdf[["name", "geometry"]].copy()

def geom_for(names):
    sub = gdf[gdf["name"].isin(names)]
    if sub.empty:
        raise ValueError(f"Aucune geometrie pour {names}")
    return unary_union(list(sub.geometry))

# Construire les geometries fusionnees
geoms = {}
for new_name, olds in MAPPING.items():
    geoms[new_name] = geom_for(olds)

# --- Drâa-Tafilalet : approximation ---
# Cette region couvre la zone Errachidia-Ouarzazate.
# On l'approxime en decoupant la partie est de "Meknès - Tafilalet" (Tafilalet/Errachidia)
# et la partie est de "Souss - Massa - Draâ" (Ouarzazate/Zagora) via une bbox.
# Approche simple et robuste : on prend l'union de Meknès-Tafilalet et Souss-Massa-Draâ,
# puis on decoupe une bande sud-est correspondant approximativement a Drâa-Tafilalet.
mek_taf = gdf[gdf["name"] == "Meknès - Tafilalet"]
sm_draa = gdf[gdf["name"] == "Souss - Massa - Draâ"]

from shapely.geometry import box
south_east_union = unary_union(list(mek_taf.geometry) + list(sm_draa.geometry))
# Boite englobant grossierement la zone Drâa-Tafilalet (Errachidia/Ouarzazate/Zagora/Midelt)
# Longitudes ~ -7.0 a -3.0 ; latitudes ~ 29.5 a 33.5
draa_bbox = box(-7.2, 29.3, -3.2, 33.5)
draa_geom = south_east_union.intersection(draa_bbox)
geoms["Drâa-Tafilalet"] = draa_geom

# Retirer la portion Drâa-Tafilalet de Fès-Meknès et Souss-Massa pour eviter le chevauchement
geoms["Fès-Meknès"] = geoms["Fès-Meknès"].difference(draa_bbox)
geoms["Souss-Massa"] = geoms["Souss-Massa"].difference(draa_bbox)

# Ordre officiel des 12 regions
ORDER = [
    "Tanger-Tétouan-Al Hoceïma", "L'Oriental", "Fès-Meknès", "Rabat-Salé-Kénitra",
    "Béni Mellal-Khénifra", "Casablanca-Settat", "Marrakech-Safi", "Drâa-Tafilalet",
    "Souss-Massa", "Guelmim-Oued Noun", "Laâyoune-Sakia El Hamra", "Dakhla-Oued Ed-Dahab",
]

features = []
for name in ORDER:
    m = META[name]
    geom = geoms[name]
    # Simplifier legerement pour alleger le fichier
    geom = geom.simplify(0.01, preserve_topology=True)
    features.append({
        "type": "Feature",
        "properties": {
            "id": m["id"],
            "nom": name,
            "name": name,  # compat : le JS utilise feature.properties.name / nom
            "nom_arabe": m["nom_arabe"],
            "chef_lieu": m["chef_lieu"],
        },
        "geometry": json.loads(gpd.GeoSeries([geom]).to_json())["features"][0]["geometry"],
    })

fc = {"type": "FeatureCollection", "features": features}
with open(OUT, "w", encoding="utf-8") as f:
    json.dump(fc, f, ensure_ascii=False)

print(f"OK: {OUT} cree avec {len(features)} regions")
for feat in features:
    print(" -", feat["properties"]["nom"], "|", feat["geometry"]["type"])
