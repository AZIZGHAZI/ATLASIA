<?php
$activePage  = 'ai-studio';
$pageTitle   = 'AI Research Studio';
$pageSubtitle = 'Analysez vos données, suivez la presse marocaine et explorez les sources officielles';
include 'includes/header.php';

$features = [
  ['icon'=>'📝','title'=>'Résumé automatique','desc'=>'Résumez automatiquement tout document PDF, rapport ou étude en quelques secondes grâce à l\'IA.','color'=>'#dbeafe'],
  ['icon'=>'🔍','title'=>'Analyse de texte','desc'=>'Extrayez les thèmes principaux, entités nommées et tendances d\'un corpus de documents scientifiques.','color'=>'#d1fae5'],
  ['icon'=>'😊','title'=>'Analyse de sentiments','desc'=>'Mesurez le sentiment et les émotions dans les données textuelles (enquêtes, forums, presse).','color'=>'#fef3c7'],
  ['icon'=>'📊','title'=>'Statistiques descriptives','desc'=>'Générez automatiquement des tableaux, graphiques et analyses à partir de vos jeux de données.','color'=>'#ede9fe'],
  ['icon'=>'🗺️','title'=>'Géo-analyse','desc'=>'Localisez et visualisez les tendances sociales sur la carte du Maroc par région et province.','color'=>'#ffedd5'],
  ['icon'=>'📋','title'=>'Rédaction de rapports','desc'=>'Générez une première ébauche de rapport structuré (intro, méthodo, résultats, conclusion) en un clic.','color'=>'#fee2e2'],
  ['icon'=>'🔗','title'=>'Détection de lacunes','desc'=>'Identifiez les Research Gaps dans un corpus de littérature académique grâce au traitement du langage naturel.','color'=>'#ecfeff'],
  ['icon'=>'🤝','title'=>'Recommandations','desc'=>'Obtenez des recommandations politiques (Policy Briefs) basées sur l\'analyse automatique des données sociales.','color'=>'#f0fdf4'],
  ['icon'=>'🌐','title'=>'Veille en ligne','desc'=>'Analysez les derniers articles de la presse marocaine sur un sujet donné.','color'=>'#e0f2fe'],
  ['icon'=>'📡','title'=>'Agrégateur 2026','desc'=>'Résumez les données HCP T2 2026 et leur couverture médiatique.','color'=>'#f5f3ff'],
];

// Sources institutionnelles
$institutions = [
  ['nom'=>'Haut-Commissariat au Plan (HCP)','url'=>'https://www.hcp.ma','desc'=>'Statistiques officielles : recensements, enquêtes emploi, comptes nationaux et indices des prix.'],
  ['nom'=>'Bank Al-Maghrib','url'=>'https://www.bkam.ma','desc'=>'Banque centrale : politique monétaire, taux directeur, inflation et statistiques financières.'],
  ['nom'=>'Ministère de l\'Économie et des Finances','url'=>'https://www.finances.gov.ma','desc'=>'Loi de finances, budget de l\'État, dette publique et politiques économiques.'],
  ['nom'=>'Observatoire National du Développement Humain (ONDH)','url'=>'https://www.ondh.ma','desc'=>'Suivi et évaluation des politiques de développement humain et de lutte contre la pauvreté.'],
  ['nom'=>'Conseil Économique, Social et Environnemental (CESE)','url'=>'https://www.cese.ma','desc'=>'Avis et rapports consultatifs sur les grandes politiques publiques du Royaume.'],
  ['nom'=>'Agence de Promotion et de l\'Investissement (APEI)','url'=>'https://www.apei.ma','desc'=>'Promotion de l\'investissement, appui à l\'entreprise et attractivité des territoires.'],
  ['nom'=>'Office des Changes','url'=>'https://www.oc.gov.ma','desc'=>'Statistiques du commerce extérieur, balance des paiements et transferts des MRE.'],
  ['nom'=>'Portail national Open Data','url'=>'https://www.data.gov.ma','desc'=>'Données ouvertes de l\'administration marocaine, accessibles et réutilisables.'],
  ['nom'=>'ONDH — Observatoires thématiques','url'=>'https://www.ondh.ma/fr/content/observatoires','desc'=>'Observatoires sectoriels du développement humain (santé, éducation, territoires).'],
  ['nom'=>'Caisse de Dépôt et de Gestion (CDG)','url'=>'https://www.cdg.ma','desc'=>'Investisseur institutionnel de long terme au service du développement du pays.'],
  ['nom'=>'Maroc PME','url'=>'https://www.marocpme.gov.ma','desc'=>'Agence nationale d\'appui et de financement des très petites, petites et moyennes entreprises.'],
  ['nom'=>'OFPPT','url'=>'https://www.ofppt.ma','desc'=>'Office de la Formation Professionnelle et de la Promotion du Travail.'],
];

// Presse marocaine en ligne
$presse = [
  ['nom'=>'Hespress','url'=>'https://fr.hespress.com','desc'=>'Premier site d\'information généraliste du Maroc (édition française).'],
  ['nom'=>'Medias24','url'=>'https://www.medias24.com','desc'=>'Média économique et financier de référence, données et enquêtes.'],
  ['nom'=>'Le360','url'=>'https://fr.le360.ma','desc'=>'Actualité générale, politique, économie et société.'],
  ['nom'=>'TelQuel','url'=>'https://telquel.ma','desc'=>'Magazine d\'analyse politique, économique et sociétale.'],
  ['nom'=>'Yabiladi','url'=>'https://www.yabiladi.com','desc'=>'Actualité du Maroc et des Marocains résidant à l\'étranger.'],
  ['nom'=>'La Vie Éco','url'=>'https://www.lavieeco.com','desc'=>'Hebdomadaire économique et financier de référence.'],
  ['nom'=>'Challenge','url'=>'https://www.challenge.ma','desc'=>'Magazine d\'actualité économique et de business.'],
  ['nom'=>'Aujourd\'hui le Maroc','url'=>'https://aujourdhui.ma','desc'=>'Quotidien d\'information générale et économique.'],
  ['nom'=>'Le Site Info','url'=>'https://www.lesiteinfo.com','desc'=>'Actualité en continu et information de proximité.'],
  ['nom'=>'Barlamane','url'=>'https://www.barlamane.com','desc'=>'Actualité politique, parlementaire et institutionnelle.'],
  ['nom'=>'Le Desk','url'=>'https://ledesk.ma','desc'=>'Média indépendant d\'investigation et de data-journalisme.'],
  ['nom'=>'EcoActu','url'=>'https://www.ecoactu.ma','desc'=>'Actualité économique, financière et entrepreneuriale.'],
];

// Revues académiques & rapports
$academiques = [
  ['nom'=>'RMEDC — Revue Marocaine d\'Études et de Développement Comparé','url'=>'','desc'=>'Publications universitaires sur l\'économie et le développement au Maroc.'],
  ['nom'=>'REMAREM — Revue Marocaine de Recherche en Management et Marketing','url'=>'https://revues.imist.ma','desc'=>'Recherche académique en management, marketing et sciences de gestion.'],
  ['nom'=>'PNUD — Rapport Mondial sur le Développement Humain','url'=>'https://hdr.undp.org','desc'=>'Indice de Développement Humain (IDH) et analyses comparatives internationales.'],
  ['nom'=>'World Bank Open Data — Maroc','url'=>'https://data.worldbank.org/country/morocco','desc'=>'Indicateurs de développement de la Banque mondiale pour le Maroc.'],
  ['nom'=>'IMF Data Mapper — Maroc','url'=>'https://www.imf.org/external/datamapper/profile/MAR','desc'=>'Données et projections macroéconomiques du Fonds monétaire international.'],
];
?>

<style>
  .tab-nav { display:flex; gap:6px; border-bottom:2px solid var(--border); margin-bottom:22px; flex-wrap:wrap; }
  .tab-btn { background:none; border:none; padding:12px 20px; font-size:14px; font-weight:600; color:var(--text-muted); cursor:pointer; border-bottom:3px solid transparent; margin-bottom:-2px; transition:var(--transition); border-radius:8px 8px 0 0; }
  .tab-btn:hover { color:var(--primary); background:var(--primary-light); }
  .tab-btn.active { color:var(--primary); border-bottom-color:var(--primary); }
  .tab-panel { display:none; }
  .tab-panel.active { display:block; animation:fadeIn .25s ease; }
  @keyframes fadeIn { from{opacity:0;transform:translateY(6px);} to{opacity:1;transform:translateY(0);} }

  .news-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px; }
  .article-card { background:var(--card-bg); border:1px solid var(--border); border-radius:var(--radius-lg); padding:16px; display:flex; flex-direction:column; box-shadow:var(--shadow); transition:var(--transition); }
  .article-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
  .source-badge { display:inline-block; color:#fff; font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; margin-bottom:10px; align-self:flex-start; }
  .article-title { font-size:14px; font-weight:600; color:var(--text); line-height:1.35; margin-bottom:8px; text-decoration:none; }
  a.article-title:hover { color:var(--primary); text-decoration:underline; }
  .article-excerpt { font-size:12.5px; color:var(--text-muted); line-height:1.5; flex:1; margin-bottom:12px; }
  .article-meta { display:flex; align-items:center; justify-content:space-between; gap:8px; }
  .article-date { font-size:11.5px; color:var(--text-muted); }

  .ref-section-title { font-size:16px; font-weight:700; color:var(--text); margin:24px 0 14px; padding-bottom:8px; border-bottom:1px solid var(--border); }
  .ref-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:14px; }
  .inst-card, .press-card { background:var(--card-bg); border:1px solid var(--border); border-left:4px solid var(--primary); border-radius:var(--radius); padding:14px 16px; transition:var(--transition); }
  .press-card { border-left-color:var(--danger); }
  .inst-card:hover, .press-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
  .ref-card-title { font-size:13.5px; font-weight:700; color:var(--text); margin-bottom:6px; }
  .ref-card-desc { font-size:12px; color:var(--text-muted); line-height:1.5; margin-bottom:10px; }
  .ref-card-link { font-size:12px; font-weight:600; color:var(--primary); text-decoration:none; }
  .ref-card-link:hover { text-decoration:underline; }
  .news-toolbar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:16px; }
  .news-counter { font-size:12.5px; color:var(--text-muted); margin-bottom:14px; }
</style>

<!-- Onglets -->
<div class="tab-nav">
  <button class="tab-btn active" data-tab="assistant" onclick="switchTab('assistant')">🤖 Assistant IA</button>
  <button class="tab-btn" data-tab="presse" onclick="switchTab('presse')">📰 Veille Presse</button>
  <button class="tab-btn" data-tab="sources" onclick="switchTab('sources')">📚 Sources &amp; Références</button>
</div>

<!-- ============ ONGLET 1 : ASSISTANT IA ============ -->
<div class="tab-panel active" id="tab-assistant">
  <!-- Features Grid -->
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
    <?php foreach ($features as $f): ?>
    <div class="ai-feature-card" onclick="openAIFeature('<?= addslashes($f['title']) ?>')">
      <div class="ai-feature-icon" style="background:<?= $f['color'] ?>;border-radius:16px;width:60px;height:60px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;"><?= $f['icon'] ?></div>
      <div class="ai-feature-title"><?= $f['title'] ?></div>
      <div class="ai-feature-desc"><?= $f['desc'] ?></div>
      <button class="btn btn-primary btn-sm" style="margin-top:14px;width:100%;">Utiliser →</button>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Zone de test IA interactive -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">🤖 Assistant IA — Test en direct</div>
      <span class="badge badge-success" id="aiModeBadge">🟢 IA disponible</span>
    </div>
    <div class="card-body">
      <div style="background:#f8fafc;border-radius:var(--radius);padding:16px;min-height:200px;margin-bottom:16px;overflow-y:auto;max-height:340px;" id="aiChat">
        <div style="display:flex;gap:10px;margin-bottom:12px;">
          <div style="width:32px;height:32px;border-radius:50%;background:#2563eb;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0;">🤖</div>
          <div style="background:white;border-radius:0 10px 10px 10px;padding:10px 14px;font-size:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);max-width:80%;">
            Bonjour ! Je suis l'IA d'ATLASIA. Je peux analyser vos documents, résumer des études, exploiter les indicateurs HCP T2 2026 et la presse marocaine récente. Que souhaitez-vous faire ?
          </div>
        </div>
      </div>
      <div style="display:flex;gap:10px;">
        <input type="text" class="form-input" id="aiInput" placeholder="Ex: Résume l'étude sur le chômage des jeunes au Maroc..." style="flex:1;">
        <button class="btn btn-primary" onclick="sendAIMessage()">▶ Envoyer</button>
      </div>
      <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
        <button class="btn btn-outline btn-sm" onclick="setPrompt('Résume les tendances du chômage au Maroc 2025')">📊 Chômage 2025</button>
        <button class="btn btn-outline btn-sm" onclick="setPrompt('Analyse les données de scolarisation rurales')">🎓 Scolarisation</button>
        <button class="btn btn-outline btn-sm" onclick="setPrompt('Génère un rapport sur la cohésion sociale')">📋 Rapport cohésion</button>
        <button class="btn btn-outline btn-sm" onclick="setPrompt('Quels sont les research gaps en sociologie marocaine ?')">🔍 Research Gaps</button>
        <button class="btn btn-outline btn-sm" onclick="setPrompt('Quels sujets dominent la presse marocaine en ce moment ?')">📰 Presse &amp; société</button>
        <button class="btn btn-outline btn-sm" onclick="setPrompt('Synthèse des indicateurs HCP T2 2026 : chômage 9,5%, activité 42,2%, PIB +4,8%, IPC +1,2%. Analyse et implications.')">🌍 T2 2026 : synthèse</button>
        <button class="btn btn-outline btn-sm" onclick="setPrompt('Analyse de l\'emploi des femmes au Maroc : activité 18,1%, emploi 15,4% (T2 2026).')">👩 Emploi féminin</button>
      </div>
    </div>
  </div>
</div>

<!-- ============ ONGLET 2 : VEILLE PRESSE ============ -->
<div class="tab-panel" id="tab-presse">
  <div class="card">
    <div class="card-header">
      <div class="card-title">📰 Veille de la presse marocaine</div>
      <span class="badge badge-info">Corpus RSS en direct</span>
    </div>
    <div class="card-body">
      <div class="news-toolbar">
        <input type="text" class="form-input" id="newsSearch" placeholder="🔍 Rechercher un mot-clé (chômage, eau, investissement…)" style="flex:1;min-width:220px;">
        <select class="form-input" id="newsSource" style="max-width:240px;" onchange="loadArticles(document.getElementById('newsSearch').value, this.value)">
          <option value="">Toutes les sources</option>
        </select>
        <button class="btn btn-outline btn-sm" onclick="document.getElementById('newsSearch').value='';loadArticles('', document.getElementById('newsSource').value);">↺ Réinitialiser</button>
      </div>
      <div class="news-counter" id="newsCounter">Chargement des articles…</div>
      <div class="news-grid" id="newsGrid"></div>
    </div>
  </div>
</div>

<!-- ============ ONGLET 3 : SOURCES & RÉFÉRENCES ============ -->
<div class="tab-panel" id="tab-sources">
  <div class="card">
    <div class="card-body">
      <p style="font-size:13px;color:var(--text-muted);margin-bottom:4px;">
        ATLASIA s'appuie exclusivement sur des sources publiques, officielles et vérifiables. Voici les principales références mobilisées.
      </p>

      <div class="ref-section-title">🏛️ Sources institutionnelles</div>
      <div class="ref-grid">
        <?php foreach ($institutions as $s): ?>
        <div class="inst-card">
          <div class="ref-card-title"><?= htmlspecialchars($s['nom']) ?></div>
          <div class="ref-card-desc"><?= htmlspecialchars($s['desc']) ?></div>
          <a class="ref-card-link" href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener">Visiter le site ↗</a>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="ref-section-title">📰 Presse marocaine en ligne</div>
      <div class="ref-grid">
        <?php foreach ($presse as $s): ?>
        <div class="press-card">
          <div class="ref-card-title"><?= htmlspecialchars($s['nom']) ?></div>
          <div class="ref-card-desc"><?= htmlspecialchars($s['desc']) ?></div>
          <a class="ref-card-link" href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener">Visiter le site ↗</a>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="ref-section-title">📚 Revues académiques &amp; rapports</div>
      <div class="ref-grid">
        <?php foreach ($academiques as $s): ?>
        <div class="inst-card" style="border-left-color:var(--success);">
          <div class="ref-card-title"><?= htmlspecialchars($s['nom']) ?></div>
          <div class="ref-card-desc"><?= htmlspecialchars($s['desc']) ?></div>
          <?php if (!empty($s['url'])): ?>
          <a class="ref-card-link" href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener">Consulter ↗</a>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<script>
/* =================== Gestion des onglets =================== */
function switchTab(name) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.toggle('active', b.dataset.tab === name));
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.toggle('active', p.id === 'tab-' + name));
  if (history.replaceState) history.replaceState(null, '', '#tab-' + name);
  if (name === 'presse' && !window._articlesLoaded) {
    loadArticles();
    window._articlesLoaded = true;
  }
}

/* =================== Assistant IA =================== */
let currentTool = '';
const TOOL_PROMPTS = {
  'Résumé automatique'      : "Résume les principales tendances sociales et économiques du Maroc à partir des données disponibles.",
  'Analyse de texte'        : "Analyse le corpus de presse : quels sont les thèmes principaux et les mots les plus fréquents ?",
  'Analyse de sentiments'   : "Quel climat social se dégage des sujets et mots les plus discutés dans la presse ?",
  'Statistiques descriptives':"Présente les statistiques descriptives clés (chômage, activité, population) au niveau national et régional.",
  'Géo-analyse'             : "Compare les régions du Maroc selon le chômage et le volume de couverture presse. Quelles disparités territoriales ?",
  'Rédaction de rapports'   : "Rédige l'ébauche d'un rapport structuré (intro, méthodo, résultats, recommandations) sur la situation sociale du Maroc.",
  'Détection de lacunes'    : "À partir des sujets couverts par la presse, identifie les Research Gaps / angles morts thématiques ou régionaux.",
  'Recommandations'         : "Formule des recommandations de politique publique (Policy Brief) fondées sur les données disponibles.",
  'Veille en ligne'         : "Analyse les derniers articles de la presse marocaine : quels sont les sujets dominants et le climat social actuel ?",
  'Agrégateur 2026'         : "Résume les indicateurs HCP du 2e trimestre 2026 (chômage, activité, emploi, PIB, IPC) et leur couverture médiatique récente."
};

function openAIFeature(title) {
  currentTool = title;
  const p = TOOL_PROMPTS[title] || ('Utilise l\'outil : ' + title);
  document.getElementById('aiInput').value = p;
  document.getElementById('aiChat').scrollIntoView({behavior:'smooth', block:'center'});
  sendAIMessage();
}
function setPrompt(text) {
  currentTool = '';
  document.getElementById('aiInput').value = text;
  document.getElementById('aiInput').focus();
}

function _bubbleUser(msg){
  return `<div style="display:flex;gap:10px;margin-bottom:12px;justify-content:flex-end;">
    <div style="background:#2563eb;color:white;border-radius:10px 0 10px 10px;padding:10px 14px;font-size:13px;max-width:80%;">${msg.replace(/</g,'&lt;')}</div>
    <div style="width:32px;height:32px;border-radius:50%;background:#10b981;display:flex;align-items:center;justify-content:center;color:white;font-size:12px;font-weight:700;flex-shrink:0;">GA</div>
  </div>`;
}
function _bubbleAI(html){
  return `<div style="display:flex;gap:10px;margin-bottom:12px;">
    <div style="width:32px;height:32px;border-radius:50%;background:#2563eb;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0;">🤖</div>
    <div class="ai-answer" style="background:white;border-radius:0 10px 10px 10px;padding:10px 14px;font-size:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);max-width:85%;">${html}</div>
  </div>`;
}

function sendAIMessage() {
  const input = document.getElementById('aiInput');
  const msg = input.value.trim();
  if (!msg) return;
  const chat = document.getElementById('aiChat');
  const tool = currentTool;

  chat.innerHTML += _bubbleUser(msg);
  input.value = '';
  chat.scrollTop = chat.scrollHeight;

  chat.innerHTML += `<div id="typing" style="display:flex;gap:10px;margin-bottom:12px;">
    <div style="width:32px;height:32px;border-radius:50%;background:#2563eb;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;flex-shrink:0;">🤖</div>
    <div style="background:white;border-radius:0 10px 10px 10px;padding:10px 14px;font-size:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);">💬 En cours d'analyse…</div>
  </div>`;
  chat.scrollTop = chat.scrollHeight;

  fetch('api/ai.php', {
    method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ prompt: msg, tool: tool })
  }).then(r => r.json()).then(res => {
    document.getElementById('typing')?.remove();
    const badge = document.getElementById('aiModeBadge');
    if (res.mode === 'ai') { badge.textContent = '✨ IA générative active'; badge.className = 'badge badge-success'; }
    else { badge.textContent = '🔒 Analyse locale (données)'; badge.className = 'badge badge-info'; }
    chat.innerHTML += _bubbleAI(res.text || '<p>Aucune réponse.</p>');
    chat.scrollTop = chat.scrollHeight;
    currentTool = '';
  }).catch(() => {
    document.getElementById('typing')?.remove();
    chat.innerHTML += _bubbleAI('<p style="color:#b45309">⚠️ Service IA indisponible. Chargez l\'application via un serveur (XAMPP) et réessayez.</p>');
    chat.scrollTop = chat.scrollHeight;
    currentTool = '';
  });
}

document.getElementById('aiInput').addEventListener('keydown', e => {
  if (e.key === 'Enter') { currentTool = ''; sendAIMessage(); }
});

/* =================== Veille Presse =================== */
const SOURCE_COLORS = {
  'hespress':'#ef4444', 'medias24':'#2563eb', 'le360':'#f59e0b',
  'telquel':'#8b5cf6', 'yabiladi':'#10b981'
};
function sourceColor(src) {
  const s = (src || '').toLowerCase();
  for (const key in SOURCE_COLORS) { if (s.indexOf(key) !== -1) return SOURCE_COLORS[key]; }
  return '#6b7280';
}
function relativeDate(iso) {
  if (!iso) return '';
  const d = new Date(iso);
  if (isNaN(d)) return '';
  const diff = (Date.now() - d.getTime()) / 1000;
  if (diff < 3600) return 'il y a ' + Math.max(1, Math.round(diff/60)) + ' min';
  if (diff < 86400) return 'il y a ' + Math.round(diff/3600) + ' h';
  if (diff < 2592000) return 'il y a ' + Math.round(diff/86400) + ' j';
  return d.toLocaleDateString('fr-FR', {day:'2-digit', month:'short', year:'numeric'});
}
function esc(s){ return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

function renderArticles(data) {
  const grid = document.getElementById('newsGrid');
  const counter = document.getElementById('newsCounter');
  const arts = data.articles || [];
  // Remplir le sélecteur de sources (une seule fois)
  const sel = document.getElementById('newsSource');
  if (sel.options.length <= 1 && (data.sources || []).length) {
    data.sources.forEach(s => {
      const o = document.createElement('option'); o.value = s; o.textContent = s; sel.appendChild(o);
    });
  }
  let maj = '';
  if (data.generated) {
    const d = new Date(data.generated);
    if (!isNaN(d)) maj = ' · dernière mise à jour ' + d.toLocaleString('fr-FR', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'});
  }
  counter.textContent = (data.total || arts.length) + ' article' + ((data.total||arts.length) > 1 ? 's' : '') + maj;

  if (!arts.length) { grid.innerHTML = '<p style="color:var(--text-muted);font-size:13px;">Aucun article ne correspond à votre recherche.</p>'; return; }

  grid.innerHTML = arts.map(a => {
    const color = sourceColor(a.source);
    const excerpt = (a.summary || '').replace(/\s+/g,' ').trim().slice(0, 100);
    const exStr = excerpt ? esc(excerpt) + (a.summary && a.summary.length > 100 ? '…' : '') : '';
    const titleHtml = a.url
      ? `<a class="article-title" href="${esc(a.url)}" target="_blank" rel="noopener">${esc(a.title)}</a>`
      : `<span class="article-title">${esc(a.title)}</span>`;
    const payload = esc(JSON.stringify({t:a.title||'', s:a.source||'', d:a.summary||''}));
    return `<div class="article-card">
      <span class="source-badge" style="background:${color}">${esc(a.source)}</span>
      ${titleHtml}
      <div class="article-excerpt">${exStr}</div>
      <div class="article-meta">
        <span class="article-date">🕒 ${relativeDate(a.date)}</span>
        <button class="btn btn-outline btn-sm" onclick='analyzeArticleData(${payload})'>🔄 Analyser avec l'IA</button>
      </div>
    </div>`;
  }).join('');
}

let _newsTimer = null;
function loadArticles(q, source) {
  q = (q !== undefined) ? q : document.getElementById('newsSearch').value;
  source = (source !== undefined) ? source : document.getElementById('newsSource').value;
  const counter = document.getElementById('newsCounter');
  counter.textContent = 'Chargement des articles…';
  const params = new URLSearchParams({ q: q || '', source: source || '', limit: 24 });
  fetch('api/news.php?' + params.toString())
    .then(r => r.json())
    .then(renderArticles)
    .catch(() => { counter.textContent = '⚠️ Impossible de charger les articles (lancez l\'application via un serveur PHP).'; });
}

function analyzeArticleData(obj) {
  analyzeArticle(obj.t, obj.s, obj.d);
}
function analyzeArticle(title, source, summary) {
  const prompt = `Analyse cet article de la presse marocaine [${source}] : « ${title} ». ${summary ? 'Résumé : ' + summary + '. ' : ''}Quels enseignements sociaux ou économiques peut-on en tirer, à la lumière des données ATLASIA ?`;
  switchTab('assistant');
  currentTool = 'Veille en ligne';
  document.getElementById('aiInput').value = prompt;
  document.getElementById('aiChat').scrollIntoView({behavior:'smooth', block:'center'});
  sendAIMessage();
}

// Recherche avec anti-rebond
document.getElementById('newsSearch').addEventListener('input', function() {
  clearTimeout(_newsTimer);
  const v = this.value, src = document.getElementById('newsSource').value;
  _newsTimer = setTimeout(() => loadArticles(v, src), 400);
});

/* =================== Initialisation depuis le hash =================== */
(function initFromHash() {
  const h = (location.hash || '').replace('#tab-', '');
  if (h === 'presse' || h === 'sources' || h === 'assistant') switchTab(h);
  // Précharger les articles pour que l'onglet Presse soit prêt
  if (h !== 'presse') { loadArticles(); window._articlesLoaded = true; }
})();
</script>
<?php include 'includes/footer.php'; ?>
