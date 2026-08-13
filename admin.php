<?php
$activePage   = 'admin';
$pageTitle    = 'Administration des données';
$pageSubtitle = 'Mise à jour annuelle des données statistiques (HCP)';

// Données actuelles
$dataFile   = __DIR__ . '/data/regions_data.json';
$hcp        = json_decode(@file_get_contents($dataFile), true) ?: ['_meta'=>[], 'national'=>[], 'regions'=>[]];
$national   = $hcp['national'] ?? [];
$regionsHCP = $hcp['regions'] ?? [];
$series     = $hcp['national_series'] ?? [];
$meta       = $hcp['_meta'] ?? [];
$hcpSource  = $meta['source'] ?? 'HCP — Annuaire Statistique du Maroc 2024';
$anneeRef   = $meta['annee_reference'] ?? '2024';
$psyMeta    = $hcp['psychosocial']['_meta'] ?? [];
$psyMaj     = $psyMeta['derniere_mise_a_jour'] ?? '—';
$psyNb      = $psyMeta['nb_articles'] ?? 0;

include 'includes/header.php';
?>

<!-- ===== ÉCRAN DE CONNEXION (affiché tant que non authentifié) ===== -->
<div id="loginGate" class="card" style="max-width:460px;margin:40px auto;">
  <div class="card-header"><div class="card-title">🔒 Accès administrateur</div></div>
  <div class="card-body">
    <p style="font-size:13px;color:#64748b;margin-bottom:14px;">Cette section permet la mise à jour annuelle des données statistiques. Entrez le mot de passe administrateur.</p>
    <div class="form-group">
      <label class="form-label">Mot de passe</label>
      <input type="password" id="adminPassword" class="form-input" placeholder="••••••••" onkeydown="if(event.key==='Enter')unlockAdmin()">
    </div>
    <div id="loginError" style="display:none;color:#ef4444;font-size:13px;margin-bottom:10px;">❌ Mot de passe incorrect.</div>
    <button class="btn btn-primary" style="width:100%;justify-content:center;" onclick="unlockAdmin()">Déverrouiller</button>
  </div>
</div>

<!-- ===== PANNEAU D'ADMINISTRATION (masqué jusqu'à connexion) ===== -->
<div id="adminPanel" style="display:none;">

  <div class="card" style="margin-bottom:16px;">
    <div class="card-header">
      <div class="card-title">⚙️ Paramètres généraux</div>
      <span class="badge badge-info">Source : <?= htmlspecialchars($hcpSource) ?></span>
    </div>
    <div class="card-body">
      <div class="form-row" style="display:grid;grid-template-columns:1fr 2fr;gap:14px;">
        <div class="form-group">
          <label class="form-label">Année de référence</label>
          <input type="text" id="anneeRef" class="form-input" value="<?= htmlspecialchars($anneeRef) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Source des données</label>
          <input type="text" id="metaSource" class="form-input" value="<?= htmlspecialchars($hcpSource) ?>">
        </div>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px;">
        <button class="btn btn-primary" onclick="saveData()">💾 Enregistrer les modifications</button>
        <button class="btn btn-outline" onclick="exportJSON()">📤 Exporter en JSON</button>
        <span id="saveStatus" style="align-self:center;font-size:13px;"></span>
      </div>
    </div>
  </div>

  <!-- ===== RAFRAÎCHISSEMENT DU CORPUS PSYCHOSOCIAL (presse réelle) ===== -->
  <div class="card" style="margin-bottom:16px;">
    <div class="card-header">
      <div class="card-title">🧠 Observatoire psychosocial — corpus presse</div>
      <span class="badge badge-info">Dernier scrape : <?= htmlspecialchars($psyMaj) ?> · <?= (int)$psyNb ?> articles</span>
    </div>
    <div class="card-body">
      <p style="font-size:13px;color:#64748b;margin:0 0 12px;">
        Recalcule les fréquences réelles de mots sociaux, les sujets dominants et le volume par région
        à partir des flux RSS de la presse marocaine publique (Hespress, Medias24, TelQuel, Yabiladi…).
        <br><em>Nécessite Python 3 + <code>feedparser</code> côté serveur.</em>
      </p>
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <button class="btn btn-primary" onclick="refreshPsycho()">🔄 Rafraîchir les données psychosociales</button>
        <span id="psyStatus" style="font-size:13px;"></span>
      </div>
      <pre id="psyLog" style="display:none;margin-top:12px;background:#0f172a;color:#e2e8f0;padding:12px;border-radius:8px;font-size:12px;max-height:220px;overflow:auto;white-space:pre-wrap;"></pre>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">🗺️ Données régionales — <?= count($regionsHCP) ?> régions</div>
    </div>
    <div class="table-wrapper">
      <table id="regionsTable">
        <thead>
          <tr>
            <th style="min-width:180px;">Région</th>
            <th>Population (2024)</th>
            <th>Chômage (%)</th>
            <th>Activité (%)</th>
            <th>Hôpitaux</th>
            <th>Lits</th>
            <th>Admissions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($regionsHCP as $i => $r): ?>
          <tr data-id="<?= htmlspecialchars($r['id'] ?? $i) ?>">
            <td>
              <strong><?= htmlspecialchars($r['nom']) ?></strong><br>
              <span style="font-size:11px;color:#94a3b8;"><?= htmlspecialchars($r['chef_lieu'] ?? '') ?></span>
            </td>
            <td><input type="number" class="form-input adm-input" data-field="population" value="<?= (int)($r['population']['total_2024'] ?? 0) ?>" style="width:110px;"></td>
            <td><input type="number" step="0.1" class="form-input adm-input" data-field="chomage" value="<?= htmlspecialchars($r['emploi']['taux_chomage_2023'] ?? '') ?>" style="width:80px;"></td>
            <td><input type="number" step="0.1" class="form-input adm-input" data-field="activite" value="<?= htmlspecialchars($r['emploi']['taux_activite_2023'] ?? '') ?>" style="width:80px;"></td>
            <td><input type="number" class="form-input adm-input" data-field="hopitaux" value="<?= (int)($r['sante']['hopitaux_publics_2022'] ?? 0) ?>" style="width:70px;"></td>
            <td><input type="number" class="form-input adm-input" data-field="lits" value="<?= (int)($r['sante']['lits_fonctionnels_2022'] ?? 0) ?>" style="width:80px;"></td>
            <td><input type="number" class="form-input adm-input" data-field="admissions" value="<?= (int)($r['sante']['admissions_2022'] ?? 0) ?>" style="width:100px;"></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="map-update-info" style="padding:10px 16px;">💡 Modifiez les valeurs puis cliquez sur « Enregistrer ». Une sauvegarde automatique de l'ancienne version est créée à chaque enregistrement.</div>
  </div>

  <!-- ===== ÉDITION DES SÉRIES TEMPORELLES NATIONALES ===== -->
  <?php if (!empty($series['annees'])): ?>
  <div class="card" style="margin-top:16px;">
    <div class="card-header">
      <div class="card-title">📈 Séries temporelles nationales (évolution annuelle)</div>
      <button class="btn btn-outline btn-sm" onclick="addSeriesYear()">➕ Ajouter une année</button>
    </div>
    <div class="table-wrapper">
      <table id="seriesTable">
        <thead>
          <tr>
            <th style="width:110px;">Année</th>
            <th>Taux de chômage (%)</th>
            <th>Taux d'activité (%)</th>
            <th>PIB prix courants (MDH)</th>
            <th style="width:60px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($series['annees'] as $idx => $an): ?>
          <tr>
            <td><input type="number" class="form-input ser-input" data-field="annee" value="<?= (int)$an ?>" style="width:90px;"></td>
            <td><input type="number" step="0.1" class="form-input ser-input" data-field="chomage" value="<?= htmlspecialchars($series['taux_chomage']['valeurs'][$idx] ?? '') ?>" style="width:90px;"></td>
            <td><input type="number" step="0.1" class="form-input ser-input" data-field="activite" value="<?= htmlspecialchars($series['taux_activite']['valeurs'][$idx] ?? '') ?>" style="width:90px;"></td>
            <td><input type="number" class="form-input ser-input" data-field="pib" value="<?= (int)($series['pib_prix_courants_mdh']['valeurs'][$idx] ?? 0) ?>" style="width:130px;"></td>
            <td><button class="btn btn-outline btn-sm" onclick="this.closest('tr').remove()" title="Supprimer">🗑️</button></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="map-update-info" style="padding:10px 16px;">📊 Pour la mise à jour annuelle : ajoutez une ligne avec la nouvelle année et ses valeurs (chômage/activité issus de la table 14-4 « Emploi : Ensemble » de l'annuaire, PIB issu de la synthèse des principaux indicateurs), puis « Enregistrer ».</div>
  </div>
  <?php endif; ?>

</div>

<script>
const ADMIN_PASSWORD = 'atlasia2024';
// Données complètes chargées côté serveur (pour préserver les champs non éditables)
const FULL_DATA = <?= json_encode($hcp, JSON_UNESCAPED_UNICODE) ?>;
let adminPwd = '';

function unlockAdmin() {
  const pwd = document.getElementById('adminPassword').value;
  if (pwd === ADMIN_PASSWORD) {
    adminPwd = pwd;
    document.getElementById('loginGate').style.display = 'none';
    document.getElementById('adminPanel').style.display = 'block';
    document.getElementById('loginError').style.display = 'none';
  } else {
    document.getElementById('loginError').style.display = 'block';
  }
}

function collectData() {
  const data = JSON.parse(JSON.stringify(FULL_DATA));
  if (!data._meta) data._meta = {};
  data._meta.annee_reference = document.getElementById('anneeRef').value.trim();
  data._meta.source = document.getElementById('metaSource').value.trim();

  document.querySelectorAll('#regionsTable tbody tr').forEach(tr => {
    const id = tr.getAttribute('data-id');
    const region = data.regions.find(x => (x.id || '') === id);
    if (!region) return;
    const getVal = f => tr.querySelector(`.adm-input[data-field="${f}"]`).value;
    region.population = region.population || {};
    region.population.total_2024 = parseInt(getVal('population')) || 0;
    region.emploi = region.emploi || {};
    region.emploi.taux_chomage_2023  = parseFloat(getVal('chomage'));
    region.emploi.taux_activite_2023 = parseFloat(getVal('activite'));
    region.sante = region.sante || {};
    region.sante.hopitaux_publics_2022  = parseInt(getVal('hopitaux')) || 0;
    region.sante.lits_fonctionnels_2022 = parseInt(getVal('lits')) || 0;
    region.sante.admissions_2022        = parseInt(getVal('admissions')) || 0;
  });

  // ===== Collecte des séries temporelles nationales =====
  const seriesTable = document.getElementById('seriesTable');
  if (seriesTable) {
    if (!data.national_series) data.national_series = {};
    const ns = data.national_series;
    ns.description = ns.description || "Évolution des principaux indicateurs nationaux (source : HCP, Annuaires Statistiques du Maroc)";
    ns.taux_chomage = ns.taux_chomage || {libelle:"Taux de chômage national (15 ans et plus, %)", unite:"%"};
    ns.taux_activite = ns.taux_activite || {libelle:"Taux d'activité national (15 ans et plus, %)", unite:"%"};
    ns.pib_prix_courants_mdh = ns.pib_prix_courants_mdh || {libelle:"Produit intérieur brut aux prix courants (Millions DH)", unite:"MDH"};

    const rows = [];
    seriesTable.querySelectorAll('tbody tr').forEach(tr => {
      const g = f => tr.querySelector(`.ser-input[data-field="${f}"]`).value;
      const annee = parseInt(g('annee'));
      if (!annee) return;
      rows.push({
        annee: annee,
        chomage: parseFloat(g('chomage')),
        activite: parseFloat(g('activite')),
        pib: parseInt(g('pib')) || 0
      });
    });
    rows.sort((a,b) => a.annee - b.annee); // tri chronologique
    ns.annees = rows.map(r => r.annee);
    ns.taux_chomage.valeurs = rows.map(r => r.chomage);
    ns.taux_activite.valeurs = rows.map(r => r.activite);
    ns.pib_prix_courants_mdh.valeurs = rows.map(r => r.pib);
  }

  return data;
}

function addSeriesYear() {
  const tbody = document.querySelector('#seriesTable tbody');
  if (!tbody) return;
  const last = tbody.querySelector('tr:last-child .ser-input[data-field="annee"]');
  const nextYear = last ? (parseInt(last.value) + 1) : new Date().getFullYear();
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td><input type="number" class="form-input ser-input" data-field="annee" value="${nextYear}" style="width:90px;"></td>
    <td><input type="number" step="0.1" class="form-input ser-input" data-field="chomage" value="" style="width:90px;"></td>
    <td><input type="number" step="0.1" class="form-input ser-input" data-field="activite" value="" style="width:90px;"></td>
    <td><input type="number" class="form-input ser-input" data-field="pib" value="" style="width:130px;"></td>
    <td><button class="btn btn-outline btn-sm" onclick="this.closest('tr').remove()" title="Supprimer">🗑️</button></td>`;
  tbody.appendChild(tr);
}

function saveData() {
  const status = document.getElementById('saveStatus');
  status.textContent = '⏳ Enregistrement...';
  status.style.color = '#64748b';
  fetch('api/update_data.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ password: adminPwd, data: collectData() })
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      status.textContent = '✅ ' + res.message + ' (' + (res.nb_regions||'') + ' régions)';
      status.style.color = '#10b981';
    } else {
      status.textContent = '❌ ' + res.message;
      status.style.color = '#ef4444';
    }
  })
  .catch(err => {
    status.textContent = '❌ Erreur réseau : ' + err + ' (lancez l\'application via un serveur PHP)';
    status.style.color = '#ef4444';
  });
}

function refreshPsycho() {
  const status = document.getElementById('psyStatus');
  const log = document.getElementById('psyLog');
  status.textContent = '⏳ Scraping de la presse en cours (30–60 s)…';
  status.style.color = '#64748b';
  log.style.display = 'none';
  fetch('api/refresh_psychosocial.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ password: adminPwd })
  })
  .then(r => r.json())
  .then(res => {
    status.textContent = (res.ok ? '✅ ' : '⚠️ ') + res.message;
    status.style.color = res.ok ? '#10b981' : '#f59e0b';
    if (res.log) { log.style.display = 'block'; log.textContent = res.log; }
  })
  .catch(err => {
    status.textContent = '❌ Erreur réseau : ' + err;
    status.style.color = '#ef4444';
  });
}

function exportJSON() {
  const data = collectData();
  const blob = new Blob([JSON.stringify(data, null, 2)], {type: 'application/json'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'regions_data_' + (data._meta.annee_reference || 'export') + '.json';
  a.click();
  URL.revokeObjectURL(url);
}
</script>

<?php include 'includes/footer.php'; ?>
