<?php
$activePage  = 'workspaces';
$pageTitle   = 'Espaces de Travail & Projets';
$pageSubtitle = 'Gérez vos projets de recherche de bout en bout';
$pageActions = '<button class="btn btn-primary" onclick="newProject()">+ Nouveau projet</button>';
include 'includes/header.php';

$projects = [
  ['id'=>1,'title'=>'Dynamiques migratoires au Maroc 2025','type'=>'Étude gouvernementale','org'=>'HCP','progress'=>72,'status'=>'En cours','team'=>4,'start'=>'01/01/2025','end'=>'30/06/2025','color'=>'#2563eb','desc'=>'Analyse des flux migratoires internes et externes, facteurs socio-économiques.'],
  ['id'=>2,'title'=>'Les jeunes et le marché de l\'emploi','type'=>'Étude institutionnelle','org'=>'ANAPEC','progress'=>45,'status'=>'En cours','team'=>3,'start'=>'15/02/2025','end'=>'15/08/2025','color'=>'#10b981','desc'=>'Enquête nationale sur l\'employabilité des jeunes de 18-35 ans.'],
  ['id'=>3,'title'=>'Perceptions sociales de l\'éducation','type'=>'Projet universitaire','org'=>'UM5 Rabat','progress'=>89,'status'=>'Finalisation','team'=>2,'start'=>'01/09/2024','end'=>'30/06/2025','color'=>'#7c3aed','desc'=>'Mesure des attitudes parentales et des aspirations éducatives en milieu rural.'],
  ['id'=>4,'title'=>'Cohésion sociale et liens communautaires','type'=>'Thèse de doctorat','org'=>'UCA Marrakech','progress'=>28,'status'=>'Phase initiale','team'=>1,'start'=>'01/03/2025','end'=>'28/02/2027','color'=>'#f59e0b','desc'=>'Étude longitudinale sur les facteurs de cohésion sociale dans les médinas marocaines.'],
];
?>

<!-- Tabs -->
<div class="tabs">
  <div class="tab active" onclick="showTab(this,'tab-all')">📁 Tous les projets (<?= count($projects) ?>)</div>
  <div class="tab" onclick="showTab(this,'tab-running')">🔄 En cours</div>
  <div class="tab" onclick="showTab(this,'tab-final')">✅ Finalisés</div>
  <div class="tab" onclick="showTab(this,'tab-archive')">📦 Archivés</div>
</div>

<div class="tab-content active" id="tab-all">
  <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
    <?php foreach ($projects as $p): ?>
    <div class="project-card">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;">
        <div>
          <div class="project-title"><?= $p['title'] ?></div>
          <div style="display:flex;gap:8px;margin-top:4px;flex-wrap:wrap;">
            <span class="badge badge-info"><?= $p['type'] ?></span>
            <span class="badge badge-gray">🏛️ <?= $p['org'] ?></span>
          </div>
        </div>
        <div style="width:48px;height:48px;border-radius:12px;background:<?= $p['color'] ?>22;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">📋</div>
      </div>
      <div class="project-desc"><?= $p['desc'] ?></div>
      <div class="project-meta">
        <span>👥 <?= $p['team'] ?> chercheur(s)</span>
        <span>📅 <?= $p['start'] ?> → <?= $p['end'] ?></span>
        <span class="badge <?= $p['status']==='Finalisation'?'badge-success':($p['status']==='En cours'?'badge-info':'badge-gray') ?>"><?= $p['status'] ?></span>
      </div>
      <div class="project-progress">
        <div class="project-progress-label">
          <span>Avancement</span>
          <span style="font-weight:700;color:<?= $p['color'] ?>;"><?= $p['progress'] ?>%</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width:<?= $p['progress'] ?>%;background:<?= $p['color'] ?>;"></div>
        </div>
      </div>
      <div style="display:flex;gap:8px;margin-top:14px;">
        <button class="btn btn-primary btn-sm" onclick="openProject(<?= $p['id'] ?>)">📂 Ouvrir</button>
        <button class="btn btn-outline btn-sm" onclick="alert('Ajout de membres')">👥 Équipe</button>
        <button class="btn btn-outline btn-sm" onclick="alert('Paramètres du projet')">⚙️</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="tab-content" id="tab-running">
  <div class="empty-state"><div class="empty-icon">🔄</div><div class="empty-title">Projets en cours</div><div class="empty-desc">Voir l'onglet "Tous les projets" pour les projets actifs.</div></div>
</div>
<div class="tab-content" id="tab-final">
  <div class="empty-state"><div class="empty-icon">✅</div><div class="empty-title">Aucun projet finalisé</div><div class="empty-desc">Les projets terminés apparaîtront ici.</div></div>
</div>
<div class="tab-content" id="tab-archive">
  <div class="empty-state"><div class="empty-icon">📦</div><div class="empty-title">Archives vides</div><div class="empty-desc">Aucun projet archivé pour le moment.</div></div>
</div>

<!-- Modal Nouveau Projet -->
<div class="modal-overlay" id="newProjectModal">
  <div class="modal" style="max-width:680px;">
    <div class="modal-header">
      <div class="modal-title">+ Nouveau Projet de Recherche</div>
      <div class="modal-close" onclick="document.getElementById('newProjectModal').classList.remove('open')">✕</div>
    </div>
    <div class="modal-body">
      <div class="form-group"><label class="form-label">Titre du projet *</label><input class="form-input" placeholder="Ex: Le chômage des jeunes dans la région de Rabat-Salé-Kénitra"></div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Type de projet *</label>
          <select class="form-select">
            <option>Mémoire de Master</option><option>Thèse de doctorat</option><option>Projet universitaire</option><option>Étude gouvernementale</option><option>Étude institutionnelle</option>
          </select>
        </div>
        <div class="form-group"><label class="form-label">Organisme responsable</label><input class="form-input" placeholder="Ex: Université Mohammed V"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Date de début</label><input type="date" class="form-input"></div>
        <div class="form-group"><label class="form-label">Date de fin prévisionnelle</label><input type="date" class="form-input"></div>
      </div>
      <div class="form-group"><label class="form-label">Description / Problématique</label><textarea class="form-textarea" placeholder="Décrivez l'objectif et la problématique du projet..."></textarea></div>
      <div class="form-group"><label class="form-label">Mots-clés</label><input class="form-input" placeholder="Ex: chômage, jeunes, Rabat, emploi (séparés par des virgules)"></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="document.getElementById('newProjectModal').classList.remove('open')">Annuler</button>
      <button class="btn btn-primary" onclick="alert('Projet créé ! L\'espace de travail a été généré automatiquement.');document.getElementById('newProjectModal').classList.remove('open')">🚀 Créer le projet</button>
    </div>
  </div>
</div>

<script>
function showTab(btn, tabId) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById(tabId).classList.add('active');
}
function newProject() { document.getElementById('newProjectModal').classList.add('open'); }
function openProject(id) {
  alert('Ouverture du projet #' + id + '\n\nOnglets disponibles :\n📋 Informations générales\n👥 Membres\n📁 Documents\n📖 Revue littérature\n🔬 Conception\n📊 Données\n📈 Analyse\n📝 Rapport\n🌐 Publication');
}
document.getElementById('newProjectModal').addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('open');
});
</script>
<?php include 'includes/footer.php'; ?>
