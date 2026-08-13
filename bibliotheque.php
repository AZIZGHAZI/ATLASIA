<?php
$activePage  = 'bibliotheque';
$pageTitle   = 'Bibliothèque Scientifique';
$pageSubtitle = 'Accédez aux études, rapports, journaux et ouvrages scientifiques';
$pageActions = '
  <button class="btn btn-outline btn-sm" onclick="openUploadModal()">📤 Déposer un document</button>
  <button class="btn btn-primary" onclick="openUploadModal()">+ Ajouter une ressource</button>
';
include 'includes/header.php';

// Documents disponibles (incluant les PDFs uploadés)
$documents = [
  [
    'id'       => 1,
    'title'    => 'جريدة المساء المغربية — 01 يوليوز 2026',
    'title_fr' => 'Journal Al Massa Marocain — 1er Juillet 2026',
    'authors'  => 'Al Massa',
    'date'     => '01/07/2026',
    'type'     => 'Presse',
    'lang'     => 'Arabe',
    'tags'     => ['Actualités','Société','Presse'],
    'file'     => 'uploads/almassa-01-07-2026.pdf',
    'pages'    => '24',
    'icon'     => '📰',
    'color'    => '#fee2e2',
    'desc'     => 'Édition du quotidien Al Massa — couverture des actualités sociales, politiques et économiques du Maroc.',
  ],
  [
    'id'       => 2,
    'title'    => 'جريدة المساء المغربية — 01/02/03 ماي 2026',
    'title_fr' => 'Journal Al Massa Marocain — 1-2-3 Mai 2026',
    'authors'  => 'Al Massa',
    'date'     => '01-03/05/2026',
    'type'     => 'Presse',
    'lang'     => 'Arabe',
    'tags'     => ['Actualités','Économie','Social'],
    'file'     => 'uploads/almassa-01-03-05-2026.pdf',
    'pages'    => '48',
    'icon'     => '📰',
    'color'    => '#fee2e2',
    'desc'     => 'Édition spéciale 3 jours du quotidien Al Massa — dossiers thématiques sur l\'économie et la société marocaine.',
  ],
  [
    'id'       => 3,
    'title'    => 'جريدة المساء المغربية — 01 دجنبر 2025',
    'title_fr' => 'Journal Al Massa Marocain — 1er Décembre 2025',
    'authors'  => 'Al Massa',
    'date'     => '01/12/2025',
    'type'     => 'Presse',
    'lang'     => 'Arabe',
    'tags'     => ['Actualités','Politique','Social'],
    'file'     => 'uploads/almassa-01-12-2025.pdf',
    'pages'    => '24',
    'icon'     => '📰',
    'color'    => '#fee2e2',
    'desc'     => 'Édition du 1er décembre 2025 du quotidien Al Massa — actualités et analyses sociales.',
  ],
  [
    'id'       => 4,
    'title'    => 'Social Relationships: Cognitive, Affective and Motivational Perspectives',
    'title_fr' => 'Relations Sociales : Perspectives Cognitives, Affectives et Motivationnelles',
    'authors'  => 'Joseph P. Forgas, Julie Fitness (Eds.)',
    'date'     => '2008',
    'type'     => 'Ouvrage scientifique',
    'lang'     => 'Anglais',
    'tags'     => ['Psychologie Sociale','Relations Humaines','Cognition','Motivation'],
    'file'     => 'uploads/social-relationships.pdf',
    'pages'    => '390',
    'icon'     => '📚',
    'color'    => '#d1fae5',
    'desc'     => 'Cet ouvrage académique explore les dimensions cognitives, affectives et motivationnelles des relations sociales humaines. Édité par Forgas et Fitness, il rassemble des contributions de chercheurs internationaux en psychologie sociale.',
  ],
];

// Filtres
$types   = ['Tous', 'Ouvrage scientifique', 'Presse', 'Rapport', 'Étude', 'Thèse'];
$langues = ['Toutes', 'Arabe', 'Français', 'Anglais'];
?>

<!-- Stats rapides -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
  <div class="stat-card" style="padding:16px;">
    <div class="stat-icon blue">📚</div>
    <div class="stat-body">
      <div class="stat-label">Ouvrages scientifiques</div>
      <div class="stat-value" style="font-size:22px;">1</div>
    </div>
  </div>
  <div class="stat-card" style="padding:16px;">
    <div class="stat-icon orange">📰</div>
    <div class="stat-body">
      <div class="stat-label">Articles de presse</div>
      <div class="stat-value" style="font-size:22px;">3</div>
    </div>
  </div>
  <div class="stat-card" style="padding:16px;">
    <div class="stat-icon green">📄</div>
    <div class="stat-body">
      <div class="stat-label">Total documents</div>
      <div class="stat-value" style="font-size:22px;">4</div>
    </div>
  </div>
  <div class="stat-card" style="padding:16px;">
    <div class="stat-icon purple">🌐</div>
    <div class="stat-body">
      <div class="stat-label">Langues</div>
      <div class="stat-value" style="font-size:22px;">2</div>
    </div>
  </div>
</div>

<!-- Filtres + Recherche -->
<div class="card" style="margin-bottom:18px;">
  <div class="card-body" style="padding:16px;">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
      <div style="flex:1;min-width:240px;position:relative;">
        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;">🔍</span>
        <input type="text" class="form-input" id="libSearch" placeholder="Rechercher un document, auteur, thème..." style="padding-left:34px;" oninput="filterDocs()">
      </div>
      <select class="form-select" style="width:160px;" id="typeFilter" onchange="filterDocs()">
        <?php foreach ($types as $t): ?>
        <option><?= $t ?></option>
        <?php endforeach; ?>
      </select>
      <select class="form-select" style="width:140px;" id="langFilter" onchange="filterDocs()">
        <?php foreach ($langues as $l): ?>
        <option><?= $l ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-outline btn-sm" onclick="resetFilters()">✕ Réinitialiser</button>
    </div>
  </div>
</div>

<!-- Liste des documents -->
<div id="docsGrid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
  <?php foreach ($documents as $doc): ?>
  <div class="file-card" data-type="<?= $doc['type'] ?>" data-lang="<?= $doc['lang'] ?>" data-title="<?= strtolower($doc['title'].' '.$doc['title_fr'].' '.$doc['authors']) ?>">
    <div class="file-icon pdf" style="background:<?= $doc['color'] ?>;font-size:24px;width:52px;height:52px;">
      <?= $doc['icon'] ?>
    </div>
    <div style="flex:1;min-width:0;">
      <div style="font-size:12px;color:#64748b;font-weight:500;margin-bottom:3px;">
        <span class="badge badge-<?= $doc['type']==='Ouvrage scientifique'?'info':($doc['type']==='Presse'?'warning':'gray') ?>"><?= $doc['type'] ?></span>
        <span style="margin-left:6px;">🌐 <?= $doc['lang'] ?></span>
        <span style="margin-left:6px;">📄 <?= $doc['pages'] ?> pages</span>
      </div>
      <div class="file-name"><?= $doc['title_fr'] ?></div>
      <?php if ($doc['title'] !== $doc['title_fr']): ?>
      <div style="font-size:12px;color:#64748b;margin-bottom:4px;direction:rtl;text-align:right;font-style:italic;"><?= $doc['title'] ?></div>
      <?php endif; ?>
      <div class="file-meta">✍️ <?= $doc['authors'] ?> &nbsp;|&nbsp; 📅 <?= $doc['date'] ?></div>
      <div style="font-size:12px;color:#475569;margin-top:5px;line-height:1.5;"><?= $doc['desc'] ?></div>
      <div style="display:flex;gap:5px;margin-top:8px;flex-wrap:wrap;">
        <?php foreach ($doc['tags'] as $tag): ?>
        <span class="tag tag-blue"><?= $tag ?></span>
        <?php endforeach; ?>
      </div>
      <div class="file-actions">
        <button class="btn btn-primary btn-sm" onclick="openPDF('<?= $doc['file'] ?>')">👁️ Consulter</button>
        <button class="btn btn-outline btn-sm" onclick="downloadDoc(<?= $doc['id'] ?>)">⬇️ Télécharger</button>
        <button class="btn btn-outline btn-sm" onclick="analyzeWithAI(<?= $doc['id'] ?>,'<?= addslashes($doc['title_fr']) ?>')">🤖 Analyser IA</button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div id="noResults" style="display:none;" class="empty-state">
  <div class="empty-icon">📭</div>
  <div class="empty-title">Aucun document trouvé</div>
  <div class="empty-desc">Modifiez vos critères de recherche ou ajoutez de nouveaux documents.</div>
</div>

<!-- Modal Upload -->
<div class="modal-overlay" id="uploadModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">📤 Déposer un document</div>
      <div class="modal-close" onclick="closeUpload()">✕</div>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Titre du document</label>
        <input class="form-input" placeholder="Ex : Enquête sur les conditions de vie 2025">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Type</label>
          <select class="form-select">
            <option>Ouvrage scientifique</option><option>Presse</option><option>Rapport</option><option>Étude</option><option>Thèse</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Langue</label>
          <select class="form-select">
            <option>Arabe</option><option>Français</option><option>Anglais</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Auteur(s)</label>
        <input class="form-input" placeholder="Nom(s) de l'auteur ou de l'institution">
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-textarea" style="min-height:80px;" placeholder="Résumé du document..."></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Fichier PDF</label>
        <div style="border:2px dashed #cbd5e1;border-radius:10px;padding:28px;text-align:center;cursor:pointer;background:#f8fafc;" onclick="document.getElementById('fileInput').click()">
          <div style="font-size:36px;margin-bottom:8px;">📁</div>
          <div style="font-size:14px;font-weight:600;color:#475569;">Glissez un fichier PDF ici</div>
          <div style="font-size:12px;color:#94a3b8;margin-top:4px;">ou cliquez pour sélectionner</div>
          <input type="file" id="fileInput" accept=".pdf" style="display:none;" onchange="this.parentElement.querySelector('div:nth-child(2)').textContent=this.files[0]?.name||'Glissez un fichier PDF ici'">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeUpload()">Annuler</button>
      <button class="btn btn-primary" onclick="alert('Document déposé avec succès ! Il sera validé par un administrateur.');closeUpload()">✅ Soumettre</button>
    </div>
  </div>
</div>

<!-- Modal Visionneuse PDF -->
<div class="modal-overlay" id="pdfModal" style="align-items:stretch;padding:20px;">
  <div class="modal" style="max-width:900px;width:100%;height:100%;display:flex;flex-direction:column;">
    <div class="modal-header">
      <div class="modal-title" id="pdfModalTitle">Visionneuse de document</div>
      <div style="display:flex;gap:8px;align-items:center;">
        <button class="btn btn-outline btn-sm" onclick="closePDFViewer()">✕ Fermer</button>
      </div>
    </div>
    <iframe id="pdfFrame" style="flex:1;border:none;width:100%;min-height:500px;border-radius:0 0 14px 14px;" src=""></iframe>
  </div>
</div>

<script>
function openUploadModal() {
  document.getElementById('uploadModal').classList.add('open');
}
function closeUpload() {
  document.getElementById('uploadModal').classList.remove('open');
}

function openPDF(file) {
  const modal = document.getElementById('pdfModal');
  document.getElementById('pdfFrame').src = file;
  modal.classList.add('open');
}
function closePDFViewer() {
  document.getElementById('pdfModal').classList.remove('open');
  document.getElementById('pdfFrame').src = '';
}

function downloadDoc(id) {
  alert('Téléchargement du document #' + id + ' en cours...');
}

function analyzeWithAI(id, title) {
  alert('🤖 Analyse IA en cours pour :\n"' + title + '"\n\nL\'IA va extraire :\n• Résumé automatique\n• Mots-clés principaux\n• Indicateurs sociaux identifiés\n• Recommandations\n\nRésultats disponibles dans AI Research Studio.');
}

function filterDocs() {
  const q    = document.getElementById('libSearch').value.toLowerCase();
  const type = document.getElementById('typeFilter').value;
  const lang = document.getElementById('langFilter').value;
  let visible = 0;
  document.querySelectorAll('.file-card').forEach(card => {
    const matchQ    = card.dataset.title.includes(q);
    const matchType = type === 'Tous' || card.dataset.type === type;
    const matchLang = lang === 'Toutes' || card.dataset.lang === lang;
    const show = matchQ && matchType && matchLang;
    card.style.display = show ? 'flex' : 'none';
    if (show) visible++;
  });
  document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
}

function resetFilters() {
  document.getElementById('libSearch').value = '';
  document.getElementById('typeFilter').value = 'Tous';
  document.getElementById('langFilter').value = 'Toutes';
  filterDocs();
}

// Close modals on outside click
['uploadModal','pdfModal'].forEach(id => {
  document.getElementById(id).addEventListener('click', function(e) {
    if (e.target === this) {
      this.classList.remove('open');
      if (id === 'pdfModal') document.getElementById('pdfFrame').src = '';
    }
  });
});
</script>

<?php include 'includes/footer.php'; ?>
