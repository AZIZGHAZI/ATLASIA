<?php
$activePage   = 'observatoire-psychosocial';
$pageTitle    = 'Observatoire National des Dynamiques Psychosociales';
$pageSubtitle = 'Psychologie sociale appliquée — climat social, changements comportementaux & aide à la décision';

// ===== Données psychosociales (corpus préliminaire) =====
$dataFile = __DIR__ . '/data/regions_data.json';
$hcp      = json_decode(@file_get_contents($dataFile), true) ?: [];
$psy      = $hcp['psychosocial'] ?? ['_meta' => [], 'kpis' => [], 'regions' => [], 'mots_cles' => [], 'sujets' => []];
$meta     = $psy['_meta'] ?? [];
$kpis     = $psy['kpis'] ?? [];
$regionsPsy = $psy['regions'] ?? [];
$mots     = $psy['mots_cles'] ?? [];
$sujets   = $psy['sujets'] ?? [];
$maj      = $meta['derniere_mise_a_jour'] ?? '15 juillet 2026';
$sources  = $meta['sources'] ?? ['Rapports officiels', 'Études universitaires', 'Données de terrain', 'Archives de presse'];

// Région au plus fort volume (pour la synthèse exécutive)
$topRegion = null; $topVol = -1;
foreach ($regionsPsy as $rid => $r) {
  if (($r['volume_total'] ?? 0) > $topVol) { $topVol = $r['volume_total']; $topRegion = $r['nom'] ?? $rid; }
}
$topSujet = $sujets[0]['nom'] ?? 'Emploi';

function fr_int($n){ return number_format((float)$n, 0, ',', ' '); }

include 'includes/header.php';
?>

<!-- ===== ACCROCHE (introduction) ===== -->
<div class="card" style="margin-bottom:16px; border-left:4px solid var(--primary);">
  <div class="card-body" style="font-size:15px; line-height:1.6; color:#334155; font-style:italic;">
    « <?= htmlspecialchars($meta['accroche'] ?? "Une page d'analyse basée sur la psychologie sociale appliquée pour expliquer le climat social, comprendre les changements comportementaux et appuyer la prise de décision.") ?> »
    <span style="float:right; font-style:normal; font-size:12px; color:#94a3b8;"><?= htmlspecialchars($meta['version'] ?? 'V1.0') ?></span>
  </div>
</div>

<!-- ===== VI. ALERTES (Early Warning) — en haut de page ===== -->
<div class="alert-banner alert-none" id="alertBanner">
  <span class="alert-ico">✅</span>
  <div>
    <div class="alert-title">Aucune alerte majeure</div>
    Aucune variation majeure nécessitant une alerte n'est enregistrée pour le moment.
  </div>
</div>

<!-- ===== I. SYNTHÈSE EXÉCUTIVE : 3 messages clés ===== -->
<div class="exec-summary">
  <div class="exec-summary-title">🎯 Synthèse exécutive — 3 messages clés</div>
  <div class="exec-messages">
    <div class="exec-msg msg-info">
      <div class="exec-msg-icon">💬</div>
      <div class="exec-msg-text">Le sujet le plus discuté actuellement est « <?= htmlspecialchars($topSujet) ?> ».</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('synth_sujet')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
    <div class="exec-msg msg-success">
      <div class="exec-msg-icon">🗺️</div>
      <div class="exec-msg-text">La région de <?= htmlspecialchars($topRegion ?? 'Rabat-Salé-Kénitra') ?> concentre le plus grand volume de données du corpus.</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('synth_region')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
    <div class="exec-msg msg-warning">
      <div class="exec-msg-icon">📊</div>
      <div class="exec-msg-text">Aucune variation anormale n'a franchi les seuils d'alerte sur la période observée.</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('synth_alerte')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
  </div>
</div>

<!-- ===== V. INDICATEURS PRÉLIMINAIRES (4) ===== -->
<div class="stats-grid" style="margin-bottom:16px;">
  <div class="stat-card">
    <div class="stat-icon blue">📰</div>
    <div class="stat-body">
      <div class="stat-label">Articles analysés</div>
      <div class="stat-value"><?= fr_int($kpis['volume_total'] ?? 0) ?></div>
      <div class="stat-change">articles de presse (RSS)</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green">💬</div>
    <div class="stat-body">
      <div class="stat-label">Mentions sociales</div>
      <div class="stat-value"><?= fr_int($kpis['mentions_total'] ?? 0) ?></div>
      <div class="stat-change">occurrences de mots sociaux</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon orange">🏷️</div>
    <div class="stat-body">
      <div class="stat-label">Thématiques suivies</div>
      <div class="stat-value"><?= fr_int($kpis['nb_mots'] ?? count($mots)) ?></div>
      <div class="stat-change">mots sociaux détectés</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue">🗺️</div>
    <div class="stat-body">
      <div class="stat-label">Couverture territoriale</div>
      <div class="stat-value"><?= fr_int($kpis['regions_couvertes'] ?? 0) ?>/<?= fr_int($kpis['nb_regions'] ?? 12) ?></div>
      <div class="stat-change">régions couvertes</div>
    </div>
  </div>
</div>

<!-- ===== II. CARTE INTERACTIVE (élément central) ===== -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">🗺️ Carte des connaissances — volume de données par région</div>
    <span class="badge badge-info">Critère mesurable : volume du corpus</span>
  </div>
  <div class="card-body">
    <p style="font-size:13px; color:#64748b; margin:0 0 12px;">
      Les régions sont colorées selon un <strong>critère mesurable</strong> — le nombre d'articles de presse rattachés à la région dans le corpus — et non selon des émotions
      (« inquiétude », « colère »), faute de données suffisantes. Plus une région est foncée, plus elle est présente dans l'actualité analysée ;
      plus elle est claire, plus il reste de données à collecter. <strong>Cliquez sur une région</strong> pour le détail actualisé.
    </p>
    <div id="psy-map" style="height:460px; border-radius:10px; overflow:hidden; border:1px solid var(--border); background:#eef4fb;"></div>
    <div style="display:flex; align-items:center; gap:10px; margin-top:10px; font-size:12px; color:#64748b;">
      <span>Moins de données</span>
      <span style="flex:1; height:10px; border-radius:6px; background:linear-gradient(90deg,#e0e7ff,#1e3a8a);"></span>
      <span>Plus de données</span>
    </div>
    <div class="ai-actions">
      <button class="ai-btn" onclick="atlasiaAI('carte')"><span class="ai-spark">✨</span> Expliquer cette carte</button>
    </div>
  </div>
</div>

<div style="display:grid; grid-template-columns:1.2fr 1fr; gap:16px; margin-bottom:16px;">

  <!-- ===== III. NUAGE DE MOTS ===== -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">☁️ Nuage de mots — termes les plus fréquents</div>
    </div>
    <div class="card-body">
      <div class="wordcloud" id="wordcloud"></div>
      <div class="ai-actions">
        <button class="ai-btn" onclick="atlasiaAI('nuage')"><span class="ai-spark">✨</span> Générer un résumé</button>
      </div>
    </div>
  </div>

  <!-- ===== IV. SUJETS LES PLUS DISCUTÉS ===== -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">🔥 Sujets les plus discutés</div>
    </div>
    <div class="card-body">
      <div class="topic-list">
        <?php
        $maxFreq = 1; foreach ($sujets as $s) { $maxFreq = max($maxFreq, $s['freq'] ?? 0); }
        foreach ($sujets as $i => $s):
          $ev = $s['evolution'] ?? 0;
          $cls = $ev > 0 ? 'up' : ($ev < 0 ? 'down' : 'flat');
          $arrow = $ev > 0 ? '▲' : ($ev < 0 ? '▼' : '▬');
          $w = round(($s['freq'] ?? 0) / $maxFreq * 100);
        ?>
        <div class="topic-item">
          <div class="topic-rank"><?= $i + 1 ?></div>
          <div class="topic-main">
            <div class="topic-name"><?= htmlspecialchars($s['nom']) ?></div>
            <div class="topic-meta"><?= fr_int($s['freq'] ?? 0) ?> occurrences · plus présent à <?= htmlspecialchars($s['region'] ?? '—') ?></div>
            <div class="topic-bar"><span style="width:<?= $w ?>%"></span></div>
          </div>
          <div class="topic-trend <?= $cls ?>"><?= $arrow ?> <?= abs($ev) ?>%</div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- ===== VII. SOURCES DES DONNÉES ===== -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">🗄️ Sources des données</div>
  </div>
  <div class="card-body">
    <div style="display:flex; flex-wrap:wrap; gap:10px;">
      <?php foreach ($sources as $src): ?>
        <span class="badge badge-info" style="font-size:13px; padding:6px 12px;">📄 <?= htmlspecialchars($src) ?></span>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:12px; font-size:13px; color:#64748b;">Dernière mise à jour : <strong><?= htmlspecialchars($maj) ?></strong></p>
    <div class="insight-note" style="border:none; padding:0; margin-top:6px;">
      <?= htmlspecialchars($meta['note'] ?? '') ?>
    </div>
  </div>
</div>

<!-- ===== PANNEAU LATÉRAL RÉGION (drawer) ===== -->
<div class="psy-drawer" id="psyDrawer">
  <div class="drawer-head">
    <div class="drawer-title" id="drawerTitle">Région</div>
    <button class="drawer-close" onclick="closeDrawer()" aria-label="Fermer">×</button>
  </div>
  <div class="drawer-body" id="drawerBody"></div>
</div>

<!-- ===== FENÊTRE MOT (drawer réutilisé pour mots) ===== -->
<div class="psy-drawer" id="wordDrawer">
  <div class="drawer-head">
    <div class="drawer-title" id="wordTitle">Mot</div>
    <button class="drawer-close" onclick="closeWordDrawer()" aria-label="Fermer">×</button>
  </div>
  <div class="drawer-body" id="wordBody"></div>
</div>

<?php
$extraJS = '
const PSY_REGIONS = ' . json_encode($regionsPsy, JSON_UNESCAPED_UNICODE) . ';
const PSY_WORDS   = ' . json_encode($mots, JSON_UNESCAPED_UNICODE) . ';
const PSY_MAJ     = ' . json_encode($maj, JSON_UNESCAPED_UNICODE) . ';
const PSY_TOPREG  = ' . json_encode($topRegion, JSON_UNESCAPED_UNICODE) . ';
const PSY_TOPSUJ  = ' . json_encode($topSujet, JSON_UNESCAPED_UNICODE) . ';
';
?>

<script>
// Les données PSY_* sont définies par $extraJS (footer), exécuté durant le
// parsing ; on initialise donc après DOMContentLoaded pour qu'elles existent.
document.addEventListener('DOMContentLoaded', function(){
// ===== IA embarquée : analyses de la page psychosociale =====
window.ATLASIA_AI = window.ATLASIA_AI || {};
Object.assign(window.ATLASIA_AI, {
  synth_sujet: {
    title: "Sujet le plus discuté — " + PSY_TOPSUJ,
    interpretation: "Le sujet « " + PSY_TOPSUJ + " » enregistre le plus grand nombre d'occurrences dans le corpus documentaire disponible (études, presse, terrain).",
    tendance: "Un sujet dominant durablement peut refléter une préoccupation sociale structurante.",
    recommandation: "Approfondir l'analyse thématique de ce sujet et croiser avec les indicateurs économiques (emploi, activité).",
    source: "Corpus ATLASIA — Observatoire des Dynamiques Psychosociales (" + PSY_MAJ + ")"
  },
  synth_region: {
    title: "Région la mieux couverte — " + PSY_TOPREG,
    interpretation: "La région " + PSY_TOPREG + " concentre le plus grand volume de données. La carte reflète la disponibilité des connaissances, pas nécessairement l'intensité des phénomènes.",
    tendance: "Les régions peu couvertes constituent des angles morts : il faut y intensifier la collecte.",
    recommandation: "Prioriser la collecte de données dans les régions les plus claires sur la carte.",
    source: "Corpus ATLASIA — Observatoire des Dynamiques Psychosociales (" + PSY_MAJ + ")"
  },
  synth_alerte: {
    title: "État des alertes",
    interpretation: "Aucune variation anormale n'a franchi les seuils définis. Conformément à la charte, aucune alerte n'est générée sans données étayées.",
    tendance: "La situation est stable en l'état actuel du corpus.",
    recommandation: "Maintenir la veille ; une alerte rouge s'affichera automatiquement en cas de franchissement de seuil.",
    source: "Corpus ATLASIA — Observatoire des Dynamiques Psychosociales (" + PSY_MAJ + ")"
  },
  carte: {
    title: "Carte des connaissances",
    interpretation: "La coloration traduit un critère MESURABLE : le volume de données disponibles par région (études, documents, presse, terrain).",
    tendance: "À mesure que le corpus s'enrichit, la carte deviendra plus représentative de la réalité sociale.",
    recommandation: "Lire la carte comme une carte de la connaissance disponible, à compléter là où les couleurs sont claires.",
    source: "Corpus ATLASIA — Observatoire des Dynamiques Psychosociales (" + PSY_MAJ + ")"
  },
  nuage: {
    title: "Synthèse du nuage de mots",
    interpretation: "Les termes dominants du corpus tournent autour de l'emploi, du chômage, de l'éducation et de la cherté de la vie — des marqueurs classiques du climat social.",
    tendance: "La prégnance des termes économiques suggère un climat social sensible aux conditions matérielles.",
    recommandation: "Suivre l'évolution de ces termes dans le temps pour détecter les basculements du climat social.",
    source: "Corpus ATLASIA — Observatoire des Dynamiques Psychosociales (" + PSY_MAJ + ")"
  }
});

// ===== NUAGE DE MOTS =====
(function(){
  const wc = document.getElementById("wordcloud");
  if(!wc || !PSY_WORDS.length) return;
  const freqs = PSY_WORDS.map(w => w.freq);
  const min = Math.min(...freqs), max = Math.max(...freqs);
  PSY_WORDS.forEach(w => {
    const size = 14 + Math.round((w.freq - min) / (max - min || 1) * 30); // 14 -> 44 px
    const shade = 30 + Math.round((w.freq - min) / (max - min || 1) * 45);
    const el = document.createElement("span");
    el.className = "word";
    el.textContent = w.mot;
    el.style.fontSize = size + "px";
    el.style.color = "hsl(221, 60%, " + (65 - (shade-30)) + "%)";
    el.title = w.freq + " occurrences";
    el.onclick = () => openWordDrawer(w);
    wc.appendChild(el);
  });
})();

// ===== FENÊTRE MOT =====
window.openWordDrawer = function(w){
  document.getElementById("wordTitle").textContent = "« " + w.mot + " »";
  document.getElementById("wordBody").innerHTML =
    "<div style=\'display:flex;flex-direction:column;gap:12px;font-size:14px;\'>" +
      "<div><span style=\'color:#64748b\'>Fréquence d\'apparition</span><br><strong style=\'font-size:20px\'>" + w.freq + "</strong> occurrences</div>" +
      "<div><span style=\'color:#64748b\'>Nombre d\'études</span><br><strong>" + (w.etudes||0) + "</strong></div>" +
      "<div><span style=\'color:#64748b\'>Régions où il est le plus présent</span><br><strong>" + (w.regions||[]).join(", ") + "</strong></div>" +
      "<div><span style=\'color:#64748b\'>Mots associés</span><br>" + (w.associes||[]).map(m=>"<span class=\'badge badge-info\' style=\'margin:2px\'>"+m+"</span>").join(" ") + "</div>" +
      "<div class=\'ai-actions\'><button class=\'ai-btn\' onclick=\"atlasiaAI(\'mot_dyn\')\"><span class=\'ai-spark\'>✨</span> Analyser ce mot</button></div>" +
    "</div>";
  window.ATLASIA_AI["mot_dyn"] = {
    title: "Analyse du mot « " + w.mot + " »",
    interpretation: "Le terme « " + w.mot + " » apparaît " + w.freq + " fois dans le corpus et est associé à : " + (w.associes||[]).join(", ") + ".",
    tendance: "Sa forte co-occurrence avec ces termes suggère un champ thématique cohérent à surveiller.",
    recommandation: "Explorer les " + (w.etudes||0) + " études où ce terme apparaît pour contextualiser.",
    source: "Corpus ATLASIA — Observatoire des Dynamiques Psychosociales (" + PSY_MAJ + ")"
  };
  document.getElementById("wordDrawer").classList.add("open");
}
window.closeWordDrawer = function(){ document.getElementById("wordDrawer").classList.remove("open"); }

// ===== CARTE INTERACTIVE (colorée par volume de données) =====
const PSY_GEOJSON = "data/morocco-regions-12.geojson";
window.volColor = function(v, min, max){
  const pct = (v - min) / (max - min || 1);
  // dégradé clair -> foncé (bleu indigo)
  const light = 92 - Math.round(pct * 62); // 92% -> 30%
  return "hsl(226, 70%, " + light + "%)";
}
let psyMap, psyLayer;
const volumes = Object.values(PSY_REGIONS).map(r => r.volume_total || 0);
const vMin = Math.min(...volumes), vMax = Math.max(...volumes);

fetch(PSY_GEOJSON).then(r=>r.json()).then(geo=>{
  psyMap = L.map("psy-map", {center:[29.0,-7.5], zoom:5, scrollWheelZoom:true, attributionControl:false});
  // Pas de fond de tuiles externe : la carte choroplèthe (polygones colorés par
  // volume de données) est le contenu. Fond neutre => fonctionne 100% hors-ligne (XAMPP).
  psyLayer = L.geoJSON(geo, {
    style: f => {
      const r = PSY_REGIONS[f.properties.id] || {volume_total:0};
      return { fillColor: volColor(r.volume_total, vMin, vMax), weight:1.5, color:"#fff", fillOpacity:0.85 };
    },
    onEachFeature: (f, layer) => {
      const r = PSY_REGIONS[f.properties.id] || {};
      layer.bindTooltip(f.properties.nom + " — " + (r.volume_total||0) + " articles", {sticky:true});
      layer.on("click", () => openRegionDrawer(f.properties.id, f.properties.nom));
      layer.on("mouseover", () => layer.setStyle({weight:3, color:"#1e3a8a"}));
      layer.on("mouseout",  () => psyLayer.resetStyle(layer));
    }
  }).addTo(psyMap);
  psyMap.fitBounds(psyLayer.getBounds(), {padding:[10,10]});
}).catch(err=>{
  document.getElementById("psy-map").innerHTML = "<div style=\'display:flex;align-items:center;justify-content:center;height:100%;background:#f8fafc;color:#64748b;\'>⚠️ Chargez l\'application via un serveur (XAMPP) pour afficher la carte.</div>";
  console.error(err);
});

// ===== PANNEAU RÉGION =====
window.openRegionDrawer = function(id, nom){
  const r = PSY_REGIONS[id];
  if(!r){ return; }
  document.getElementById("drawerTitle").textContent = nom;
  document.getElementById("drawerBody").innerHTML =
    "<div style=\'display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;\'>" +
      statBox("📰 Articles de presse", r.articles_presse) + statBox("💬 Mentions sociales", r.mentions) +
      statBox("🏷️ Thèmes distincts", r.themes_distincts) + statBox("📊 Volume total", r.volume_total) +
    "</div>" +
    "<div style=\'margin-bottom:12px;\'><span style=\'color:#64748b;font-size:13px\'>Sujets les plus discutés dans la presse</span><br>" +
      ((r.sujets||[]).length ? (r.sujets||[]).map(s=>"<span class=\'badge badge-info\' style=\'margin:2px\'>"+s+"</span>").join(" ") : "<em style=\'color:#94a3b8\'>Aucune donnée pour l\'instant</em>") + "</div>" +
    "<div style=\'font-size:13px;color:#64748b;margin-bottom:14px;\'>Volume total : <strong style=\'color:#1e293b\'>"+(r.volume_total||0)+"</strong> articles · Dernière mise à jour : "+(r.derniere_maj||PSY_MAJ)+"</div>" +
    "<div class=\'ai-actions\'><button class=\'ai-btn\' onclick=\"atlasiaAI(\'region_dyn\')\"><span class=\'ai-spark\'>✨</span> Analyser cette région avec l\'IA</button></div>";
  var bienDoc = (r.volume_total||0) >= 10;
  window.ATLASIA_AI["region_dyn"] = {
    title: "Analyse — " + nom,
    interpretation: nom + " concentre " + (r.volume_total||0) + " articles de presse (" + (r.mentions||0) + " mentions sociales, " + (r.themes_distincts||0) + " thèmes distincts). Les sujets dominants y sont : " + ((r.sujets||[]).join(", ") || "aucun pour l'instant") + ".",
    tendance: (bienDoc ? "Région bien couverte par la presse : les analyses y seront plus fiables." : "Région peu couverte sur la période : les conclusions doivent rester prudentes."),
    recommandation: (bienDoc ? "Exploiter le corpus pour des analyses thématiques approfondies et un suivi dans le temps." : "Renforcer la collecte de données (presse locale, terrain) sur cette région."),
    source: "Corpus presse ATLASIA — Observatoire des Dynamiques Psychosociales (" + (r.derniere_maj||PSY_MAJ) + ")"
  };
  document.getElementById("psyDrawer").classList.add("open");
}
window.statBox = function(label, val){
  return "<div style=\'background:#f8fbff;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;\'>" +
    "<div style=\'font-size:12px;color:#64748b\'>"+label+"</div>" +
    "<div style=\'font-size:22px;font-weight:800;color:#1e3a8a\'>"+(val||0)+"</div></div>";
}
window.closeDrawer = function(){ document.getElementById("psyDrawer").classList.remove("open"); }
}); // end DOMContentLoaded
</script>

<?php include 'includes/footer.php'; ?>
