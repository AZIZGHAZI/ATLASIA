<?php
$activePage  = 'production';
$pageTitle   = 'Production & Collecte des Données';
$pageSubtitle = 'Pilotez vos enquêtes terrain de la conception à la validation';
$pageActions = '<button class="btn btn-primary" onclick="alert(\'Lancer une nouvelle enquête terrain\')">+ Nouvelle collecte</button>';
include 'includes/header.php';
?>

<!-- Dashboard collecte -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
  <div class="stat-card" style="padding:16px;"><div class="stat-icon blue">📋</div><div class="stat-body"><div class="stat-label">Enquêtes actives</div><div class="stat-value" style="font-size:22px;">7</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon green">✅</div><div class="stat-body"><div class="stat-label">Questionnaires validés</div><div class="stat-value" style="font-size:22px;">4,832</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon orange">👷</div><div class="stat-body"><div class="stat-label">Enquêteurs actifs</div><div class="stat-value" style="font-size:22px;">34</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon purple">⚠️</div><div class="stat-body"><div class="stat-label">En attente révision</div><div class="stat-value" style="font-size:22px;">128</div></div></div>
</div>

<!-- Enquêtes en cours -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">🔄 Enquêtes en cours</div>
    <button class="btn btn-outline btn-sm" onclick="alert('Suivi temps réel')">🔴 Temps réel</button>
  </div>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr><th>Enquête</th><th>Responsable</th><th>Progression</th><th>Collecté</th><th>Objectif</th><th>Délai</th><th>Alertes</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php
        $enquetes = [
          ['Enquête Emploi Jeunes 2025','M. Alami',72,'720','1000','30/08/2025',0,'En cours'],
          ['Perceptions éducation rurale','S. Benali',45,'450','1000','15/09/2025',2,'En cours'],
          ['Cohésion sociale Casablanca','K. Amrani',89,'267','300','31/07/2025',0,'Finalisation'],
          ['Migration interne Souss-Massa','L. Moussaoui',28,'140','500','30/10/2025',1,'Phase terrain'],
          ['Indice confiance institutions','Z. Ouali',55,'550','1000','15/08/2025',0,'En cours'],
        ];
        foreach ($enquetes as $e):
          $pct = $e[2];
          $color = $pct > 75 ? '#10b981' : ($pct > 40 ? '#f59e0b' : '#ef4444');
        ?>
        <tr>
          <td style="font-weight:600;"><?= $e[0] ?></td>
          <td><?= $e[1] ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="progress-bar" style="width:90px;"><div class="progress-fill" style="width:<?= $pct ?>%;background:<?= $color ?>;"></div></div>
              <span style="font-weight:700;color:<?= $color ?>;font-size:12px;"><?= $pct ?>%</span>
            </div>
          </td>
          <td><?= $e[3] ?></td>
          <td><?= $e[4] ?></td>
          <td><?= $e[5] ?></td>
          <td>
            <?php if ($e[6] > 0): ?><span class="badge badge-danger">⚠️ <?= $e[6] ?> alerte(s)</span>
            <?php else: ?><span class="badge badge-success">✅ OK</span><?php endif; ?>
          </td>
          <td><button class="btn btn-primary btn-sm" onclick="alert('Tableau de bord terrain : ' + '<?= $e[0] ?>')">📊 Voir</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Carte des enquêteurs + Contrôle qualité -->
<div class="dashboard-grid">
  <div class="card">
    <div class="card-header"><div class="card-title">👷 Équipe terrain</div></div>
    <div class="card-body">
      <table>
        <thead><tr><th>Enquêteur</th><th>Zone</th><th>Réalisés</th><th>Taux</th><th>Statut</th></tr></thead>
        <tbody>
          <?php
          $team = [
            ['Mohamed A.','Rabat','125/150','83%','🟢'],
            ['Fatima B.','Kénitra','98/100','98%','🟢'],
            ['Youssef M.','Salé','67/100','67%','🟡'],
            ['Aicha O.','Casablanca','148/150','99%','🟢'],
            ['Karim T.','Fès','72/100','72%','🟡'],
            ['Sara L.','Meknès','110/150','73%','🟡'],
          ];
          foreach ($team as $t):
          ?>
          <tr>
            <td style="font-weight:600;"><?= $t[0] ?></td>
            <td><?= $t[1] ?></td>
            <td><?= $t[2] ?></td>
            <td style="font-weight:700;"><?= $t[3] ?></td>
            <td><?= $t[4] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">🔍 Contrôle qualité</div></div>
    <div class="card-body">
      <div style="display:flex;flex-direction:column;gap:10px;">
        <div class="alert-item info"><span class="alert-icon">ℹ️</span><span>128 questionnaires en attente de révision superviseur</span></div>
        <div class="alert-item warning"><span class="alert-icon">⚠️</span><span>Doublons détectés : 12 questionnaires (Souss-Massa)</span></div>
        <div class="alert-item danger"><span class="alert-icon">🔴</span><span>Incohérence : Âge < 18 ans + statut marié (8 cas)</span></div>
        <div class="alert-item info"><span class="alert-icon">ℹ️</span><span>45 questionnaires incomplets (< 70% remplis)</span></div>
      </div>
      <div style="margin-top:16px;display:flex;gap:8px;">
        <button class="btn btn-primary" onclick="alert('Ouverture de la file de révision')">✅ Réviser maintenant</button>
        <button class="btn btn-outline" onclick="alert('Envoi des alertes aux enquêteurs')">📤 Notifier équipe</button>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
