<?php
$activePage  = 'referentiel';
$pageTitle   = 'Référentiel de Données';
$pageSubtitle = 'Dépôt officiel — Tous les jeux de données de la plateforme';
$pageActions = '<button class="btn btn-primary" onclick="alert(\'Formulaire de dépôt de dataset\')">+ Déposer un dataset</button>';
include 'includes/header.php';

$datasets = [
  ['id'=>'DS-2025-001','title'=>'Enquête nationale emploi Q1 2025','source'=>'HCP','rows'=>'24,850','vars'=>42,'format'=>'CSV / SPSS','date'=>'15/04/2025','status'=>'Validé','access'=>'Public'],
  ['id'=>'DS-2025-002','title'=>'Youth Employment Survey 2025 — Maroc','source'=>'ANAPEC','rows'=>'5,320','vars'=>67,'format'=>'CSV','date'=>'02/05/2025','status'=>'Validé','access'=>'Public'],
  ['id'=>'DS-2024-018','title'=>'Indice de confiance institutionnelle 2024','source'=>'ONDH','rows'=>'12,400','vars'=>38,'format'=>'SPSS / Excel','date'=>'10/01/2025','status'=>'Validé','access'=>'Restreint'],
  ['id'=>'DS-2024-015','title'=>'Recensement scolarisation rurale 2024','source'=>'Ministère de l\'Éducation','rows'=>'186,000','vars'=>28,'format'=>'CSV','date'=>'05/12/2024','status'=>'Validé','access'=>'Public'],
  ['id'=>'DS-2024-012','title'=>'Migration interne Maroc 2023-2024','source'=>'HCP','rows'=>'8,750','vars'=>54,'format'=>'CSV / JSON','date'=>'20/11/2024','status'=>'En révision','access'=>'Public'],
  ['id'=>'DS-ATLASIA-004','title'=>'Analyse presse arabe — Données sociales 2025','source'=>'ATLASIA IA','rows'=>'3,240','vars'=>18,'format'=>'JSON','date'=>'01/07/2026','status'=>'Nouveau','access'=>'Membres'],
];
?>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
  <div class="stat-card" style="padding:16px;"><div class="stat-icon blue">🗂️</div><div class="stat-body"><div class="stat-label">Datasets totaux</div><div class="stat-value" style="font-size:22px;">2,458</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon green">✅</div><div class="stat-body"><div class="stat-label">Datasets validés</div><div class="stat-value" style="font-size:22px;">2,401</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon orange">⏳</div><div class="stat-body"><div class="stat-label">En révision</div><div class="stat-value" style="font-size:22px;">42</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon purple">🔒</div><div class="stat-body"><div class="stat-label">Accès restreint</div><div class="stat-value" style="font-size:22px;">215</div></div></div>
</div>

<!-- Filtres -->
<div class="card" style="margin-bottom:18px;">
  <div class="card-body" style="padding:14px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
      <input type="text" class="form-input" placeholder="🔍 Rechercher un dataset..." style="flex:1;min-width:200px;">
      <select class="form-select" style="width:150px;"><option>Tous les statuts</option><option>Validé</option><option>En révision</option><option>Nouveau</option></select>
      <select class="form-select" style="width:140px;"><option>Tout accès</option><option>Public</option><option>Restreint</option><option>Membres</option></select>
      <select class="form-select" style="width:140px;"><option>Toutes sources</option><option>HCP</option><option>ANAPEC</option><option>ONDH</option><option>ATLASIA</option></select>
    </div>
  </div>
</div>

<!-- Tableau -->
<div class="card">
  <div class="card-header">
    <div class="card-title">📦 Jeux de données disponibles</div>
    <div style="display:flex;gap:8px;">
      <button class="btn btn-outline btn-sm" onclick="alert('Export CSV')">📥 Exporter catalogue</button>
    </div>
  </div>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID Dataset</th>
          <th>Titre</th>
          <th>Source</th>
          <th>Lignes</th>
          <th>Variables</th>
          <th>Format</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Accès</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($datasets as $ds): ?>
        <tr>
          <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:11px;"><?= $ds['id'] ?></code></td>
          <td style="font-weight:600;max-width:220px;"><?= $ds['title'] ?></td>
          <td><?= $ds['source'] ?></td>
          <td><?= $ds['rows'] ?></td>
          <td><?= $ds['vars'] ?></td>
          <td><span class="badge badge-gray"><?= $ds['format'] ?></span></td>
          <td><?= $ds['date'] ?></td>
          <td>
            <?php if ($ds['status']==='Validé'): ?><span class="badge badge-success">✅ Validé</span>
            <?php elseif ($ds['status']==='En révision'): ?><span class="badge badge-warning">⏳ En révision</span>
            <?php else: ?><span class="badge badge-info">🆕 Nouveau</span><?php endif; ?>
          </td>
          <td>
            <?php if ($ds['access']==='Public'): ?><span class="badge badge-success">🌐 Public</span>
            <?php elseif ($ds['access']==='Restreint'): ?><span class="badge badge-danger">🔒 Restreint</span>
            <?php else: ?><span class="badge badge-purple">👥 Membres</span><?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:5px;">
              <button class="btn btn-primary btn-sm" onclick="viewDataset('<?= $ds['id'] ?>')">👁️</button>
              <button class="btn btn-outline btn-sm" onclick="alert('Téléchargement : ' + '<?= $ds['id'] ?>')">⬇️</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:12.5px;color:#64748b;">Affichage de 6 datasets sur 2,458 — <a href="#" style="color:#2563eb;">Voir tout</a></span>
    <div style="display:flex;gap:6px;">
      <button class="btn btn-outline btn-sm" onclick="changePage(-1)">◀ Préc.</button>
      <button class="btn btn-primary btn-sm page-btn active-page" data-page="1" onclick="goToPage(1)">1</button>
      <button class="btn btn-outline btn-sm page-btn" data-page="2" onclick="goToPage(2)">2</button>
      <button class="btn btn-outline btn-sm page-btn" data-page="3" onclick="goToPage(3)">3</button>
      <button class="btn btn-outline btn-sm" onclick="changePage(1)">Suiv. ▶</button>
    </div>
  </div>
</div>

<script>
function viewDataset(id) {
  alert('📦 Dataset : ' + id + '\n\nOnglets disponibles :\n• 📊 Données brutes et traitées\n• 📋 Description & Métadonnées\n• 📖 Dictionnaire des variables\n• 📈 Statistiques descriptives\n• 🔍 Historique des modifications\n• 📥 Télécharger (CSV, SPSS, JSON)');
}

let currentPage = 1;
const totalPages = 3;
function goToPage(page) {
  currentPage = page;
  document.querySelectorAll('.page-btn').forEach(btn => {
    const p = parseInt(btn.dataset.page);
    btn.className = p === page ? 'btn btn-primary btn-sm page-btn active-page' : 'btn btn-outline btn-sm page-btn';
  });
}
function changePage(dir) {
  const next = currentPage + dir;
  if (next >= 1 && next <= totalPages) goToPage(next);
}
</script>
<?php include 'includes/footer.php'; ?>
