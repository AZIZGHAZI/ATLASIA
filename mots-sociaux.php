<?php
$activePage   = 'mots-sociaux';
$pageTitle    = 'Mots sociaux du web';
$pageSubtitle = 'Les termes sociaux les plus utilisés dans la presse marocaine — taille proportionnelle à la fréquence réelle';

$dataFile = __DIR__ . '/data/regions_data.json';
$hcp      = json_decode(@file_get_contents($dataFile), true) ?: [];
$psy      = $hcp['psychosocial'] ?? [];
$meta     = $psy['_meta'] ?? [];
$kpis     = $psy['kpis'] ?? [];
$mots     = $psy['mots_cles'] ?? [];
$sujets   = $psy['sujets'] ?? [];
$maj      = $meta['derniere_mise_a_jour'] ?? '—';
$nbArt    = $meta['nb_articles'] ?? 0;
$sources  = $meta['sources'] ?? [];

// Tri décroissant par fréquence
usort($mots, fn($a, $b) => ($b['freq'] ?? 0) <=> ($a['freq'] ?? 0));
$topMot   = $mots[0]['mot'] ?? '—';
$topFreq  = $mots[0]['freq'] ?? 0;
$totalOcc = array_sum(array_map(fn($m) => $m['freq'] ?? 0, $mots));

function fr_int($n){ return number_format((float)$n, 0, ',', ' '); }

include 'includes/header.php';
?>

<!-- ===== SYNTHÈSE EXÉCUTIVE ===== -->
<div class="exec-summary">
  <div class="exec-summary-title">🎯 Synthèse exécutive — 3 messages clés</div>
  <div class="exec-messages">
    <div class="exec-msg msg-info">
      <div class="exec-msg-icon">🔤</div>
      <div class="exec-msg-text">Le mot social le plus présent dans la presse est « <strong><?= htmlspecialchars($topMot) ?></strong> » (<?= fr_int($topFreq) ?> occurrences).</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('ms_top')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
    <div class="exec-msg msg-success">
      <div class="exec-msg-icon">📰</div>
      <div class="exec-msg-text"><?= fr_int($nbArt) ?> articles analysés, <?= fr_int($totalOcc) ?> occurrences de <?= fr_int(count($mots)) ?> mots sociaux détectées.</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('ms_corpus')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
    <div class="exec-msg msg-warning">
      <div class="exec-msg-icon">📈</div>
      <div class="exec-msg-text">Les termes économiques et sociaux (emploi, prix, santé, éducation) structurent le climat social observé.</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('ms_lecture')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
  </div>
</div>

<!-- ===== NUAGE DE MOTS (centre) ===== -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">☁️ Nuage des mots sociaux — taille ∝ fréquence réelle</div>
    <span class="badge badge-info"><?= fr_int($nbArt) ?> articles · MàJ <?= htmlspecialchars($maj) ?></span>
  </div>
  <div class="card-body">
    <p style="font-size:13px; color:#64748b; margin:0 0 14px;">
      Chaque mot est dimensionné <strong>proportionnellement au nombre de fois où il apparaît</strong> dans le corpus de presse marocaine.
      Plus un mot est grand et foncé, plus il est présent dans l'actualité. <strong>Cliquez sur un mot</strong> pour son détail.
    </p>
    <div class="wordcloud wordcloud-xl" id="wordcloud"></div>
    <div class="ai-actions">
      <button class="ai-btn" onclick="atlasiaAI('ms_nuage')"><span class="ai-spark">✨</span> Générer un résumé du nuage</button>
    </div>
  </div>
</div>

<!-- ===== CLASSEMENT (barres) ===== -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">🏆 Classement des mots sociaux</div>
  </div>
  <div class="card-body">
    <div class="topic-list">
      <?php
      $maxFreq = $topFreq ?: 1;
      foreach (array_slice($mots, 0, 20) as $i => $m):
        $w = round(($m['freq'] ?? 0) / $maxFreq * 100);
      ?>
      <div class="topic-item">
        <div class="topic-rank"><?= $i + 1 ?></div>
        <div class="topic-main">
          <div class="topic-name"><?= htmlspecialchars($m['mot']) ?></div>
          <div class="topic-meta"><?= fr_int($m['freq'] ?? 0) ?> occurrences · <?= fr_int($m['etudes'] ?? 0) ?> articles<?= !empty($m['regions']) ? ' · surtout à ' . htmlspecialchars(implode(', ', $m['regions'])) : '' ?></div>
          <div class="topic-bar"><span style="width:<?= $w ?>%"></span></div>
        </div>
        <div class="topic-trend flat"><?= fr_int($m['freq'] ?? 0) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ===== SOURCES ===== -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header"><div class="card-title">🗄️ Sources des données</div></div>
  <div class="card-body">
    <div style="display:flex; flex-wrap:wrap; gap:10px;">
      <?php foreach ($sources as $src): ?>
        <span class="badge badge-info" style="font-size:13px; padding:6px 12px;">📄 <?= htmlspecialchars($src) ?></span>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:12px; font-size:13px; color:#64748b;">
      Fréquences calculées sur les titres et chapôs des articles RSS de la presse marocaine publique.
      Dernière mise à jour : <strong><?= htmlspecialchars($maj) ?></strong>.
      Actualisable depuis <a href="admin.php">l'administration</a> (bouton « Rafraîchir »).
    </p>
  </div>
</div>

<!-- ===== FENÊTRE MOT ===== -->
<div class="psy-drawer" id="wordDrawer">
  <div class="drawer-head">
    <div class="drawer-title" id="wordTitle">Mot</div>
    <button class="drawer-close" onclick="closeWordDrawer()" aria-label="Fermer">×</button>
  </div>
  <div class="drawer-body" id="wordBody"></div>
</div>

<?php
$extraJS = '
const MS_WORDS = ' . json_encode($mots, JSON_UNESCAPED_UNICODE) . ';
const MS_MAJ   = ' . json_encode($maj, JSON_UNESCAPED_UNICODE) . ';
';
?>

<script>
document.addEventListener('DOMContentLoaded', function(){
  window.ATLASIA_AI = window.ATLASIA_AI || {};
  const top = MS_WORDS[0] || {mot:'—', freq:0};
  Object.assign(window.ATLASIA_AI, {
    ms_top: {
      title: "Mot dominant — " + top.mot,
      interpretation: "« " + top.mot + " » est le terme social le plus fréquent du corpus (" + top.freq + " occurrences), présent surtout à " + ((top.regions||[]).join(", ")||"l'échelle nationale") + ".",
      tendance: "Un terme dominant durablement peut signaler une préoccupation sociale structurante.",
      recommandation: "Suivre l'évolution de ce mot dans le temps et le croiser avec les indicateurs économiques.",
      source: "Corpus presse ATLASIA (" + MS_MAJ + ")"
    },
    ms_corpus: {
      title: "Corpus analysé",
      interpretation: "Le nuage repose sur un corpus réel d'articles de presse marocaine, dont on extrait les mots sociaux et leur fréquence.",
      tendance: "Plus le corpus s'enrichit (rafraîchissements réguliers), plus le nuage reflète fidèlement le climat médiatique.",
      recommandation: "Rafraîchir régulièrement le corpus depuis l'administration pour suivre les basculements.",
      source: "Corpus presse ATLASIA (" + MS_MAJ + ")"
    },
    ms_lecture: {
      title: "Lecture du climat social",
      interpretation: "La prégnance des termes économiques et sociaux traduit un climat sensible aux conditions matérielles (emploi, prix, services publics).",
      tendance: "Ces marqueurs sont classiques d'un débat public centré sur le pouvoir d'achat et l'accès aux services.",
      recommandation: "Prioriser les politiques sur l'emploi, le coût de la vie, la santé et l'éducation.",
      source: "Corpus presse ATLASIA (" + MS_MAJ + ")"
    },
    ms_nuage: {
      title: "Résumé du nuage de mots",
      interpretation: "Les mots les plus grands du nuage sont ceux qui reviennent le plus dans l'actualité analysée : " + MS_WORDS.slice(0,6).map(w=>w.mot).join(", ") + ".",
      tendance: "Cette hiérarchie visuelle offre une lecture immédiate des priorités médiatiques.",
      recommandation: "Utiliser le nuage comme point d'entrée, puis explorer chaque mot pour le contexte.",
      source: "Corpus presse ATLASIA (" + MS_MAJ + ")"
    }
  });

  // ===== NUAGE DE MOTS — taille STRICTEMENT proportionnelle à la fréquence =====
  const wc = document.getElementById("wordcloud");
  if(wc && MS_WORDS.length){
    const freqs = MS_WORDS.map(w => w.freq);
    const min = Math.min(...freqs), max = Math.max(...freqs);
    // échelle racine carrée pour une meilleure répartition visuelle (18 -> 76 px)
    const MINF = 18, MAXF = 76;
    const scale = f => {
      const t = (Math.sqrt(f) - Math.sqrt(min)) / (Math.sqrt(max) - Math.sqrt(min) || 1);
      return Math.round(MINF + t * (MAXF - MINF));
    };
    // mélange l'ordre pour un rendu "nuage" (pas un simple tri)
    const shuffled = MS_WORDS.slice().sort(() => Math.random() - 0.5);
    shuffled.forEach(w => {
      const size = scale(w.freq);
      const t = (w.freq - min) / (max - min || 1);
      const light = 60 - Math.round(t * 35); // 60% (clair) -> 25% (foncé)
      const el = document.createElement("span");
      el.className = "word";
      el.textContent = w.mot;
      el.style.fontSize = size + "px";
      el.style.color = "hsl(221, 65%, " + light + "%)";
      el.title = w.freq + " occurrences";
      el.onclick = () => openWordDrawer(w);
      wc.appendChild(el);
    });
  }

  window.openWordDrawer = function(w){
    document.getElementById("wordTitle").textContent = "« " + w.mot + " »";
    document.getElementById("wordBody").innerHTML =
      "<div style=\'display:flex;flex-direction:column;gap:12px;font-size:14px;\'>" +
        "<div><span style=\'color:#64748b\'>Fréquence d\'apparition</span><br><strong style=\'font-size:22px;color:#1e3a8a\'>" + w.freq + "</strong> occurrences</div>" +
        "<div><span style=\'color:#64748b\'>Nombre d\'articles</span><br><strong>" + (w.etudes||0) + "</strong></div>" +
        "<div><span style=\'color:#64748b\'>Régions où il est le plus présent</span><br><strong>" + ((w.regions||[]).join(", ")||"—") + "</strong></div>" +
        "<div><span style=\'color:#64748b\'>Mots associés (co-occurrence)</span><br>" + ((w.associes||[]).map(m=>"<span class=\'badge badge-info\' style=\'margin:2px\'>"+m+"</span>").join(" ")||"—") + "</div>" +
        "<div class=\'ai-actions\'><button class=\'ai-btn\' onclick=\"atlasiaAI(\'ms_mot\')\"><span class=\'ai-spark\'>✨</span> Analyser ce mot avec l\'IA</button></div>" +
      "</div>";
    window.ATLASIA_AI["ms_mot"] = {
      title: "Analyse du mot « " + w.mot + " »",
      interpretation: "Le terme « " + w.mot + " » apparaît " + w.freq + " fois dans " + (w.etudes||0) + " articles, associé à : " + ((w.associes||[]).join(", ")||"aucun terme") + ".",
      tendance: "Sa co-occurrence avec ces termes dessine un champ thématique cohérent à surveiller.",
      recommandation: "Explorer les articles où ce terme apparaît pour contextualiser et suivre son évolution.",
      source: "Corpus presse ATLASIA (" + MS_MAJ + ")"
    };
    document.getElementById("wordDrawer").classList.add("open");
  };
  window.closeWordDrawer = function(){ document.getElementById("wordDrawer").classList.remove("open"); };
});
</script>

<?php include 'includes/footer.php'; ?>
