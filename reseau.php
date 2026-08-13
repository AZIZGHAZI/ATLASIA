<?php
$activePage  = 'reseau';
$pageTitle   = 'Réseau des Chercheurs & Académie';
$pageSubtitle = 'Connectez-vous avec la communauté scientifique marocaine et internationale';
$pageActions = '<button class="btn btn-primary" onclick="alert(\'Compléter votre profil chercheur\')">👤 Mon profil</button>';
include 'includes/header.php';

$researchers = [
  ['name'=>'Dr. Mohammed Alami','title'=>'Sociologue · Université Mohammed V','etudes'=>24,'datasets'=>8,'color'=>'#2563eb','initials'=>'MA','spec'=>'Marchés du travail'],
  ['name'=>'Pr. Sanaa Benali','title'=>'Économiste · INSEA Rabat','etudes'=>31,'datasets'=>15,'color'=>'#10b981','initials'=>'SB','spec'=>'Économie sociale'],
  ['name'=>'Dr. Karim Amrani','title'=>'Démographe · HCP Maroc','etudes'=>18,'datasets'=>22,'color'=>'#7c3aed','initials'=>'KA','spec'=>'Migration & population'],
  ['name'=>'Pr. Leila Moussaoui','title'=>'Psychologue · UCA Marrakech','etudes'=>12,'datasets'=>5,'color'=>'#f59e0b','initials'=>'LM','spec'=>'Cohésion sociale'],
  ['name'=>'Dr. Zakaria Ouali','title'=>'Politologue · UM6P Ben Guerir','etudes'=>19,'datasets'=>11,'color'=>'#ef4444','initials'=>'ZO','spec'=>'Gouvernance locale'],
  ['name'=>'Dr. Nadia Chraibi','title'=>'Anthropologue · CNRST','etudes'=>9,'datasets'=>4,'color'=>'#0ea5e9','initials'=>'NC','spec'=>'Identités culturelles'],
];
?>

<!-- Stats réseau -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
  <div class="stat-card" style="padding:16px;"><div class="stat-icon blue">👥</div><div class="stat-body"><div class="stat-label">Chercheurs inscrits</div><div class="stat-value" style="font-size:22px;">1,256</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon green">🎓</div><div class="stat-body"><div class="stat-label">Institutions partenaires</div><div class="stat-value" style="font-size:22px;">48</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon orange">🤝</div><div class="stat-body"><div class="stat-label">Collaborations actives</div><div class="stat-value" style="font-size:22px;">124</div></div></div>
  <div class="stat-card" style="padding:16px;"><div class="stat-icon purple">🌍</div><div class="stat-body"><div class="stat-label">Pays représentés</div><div class="stat-value" style="font-size:22px;">18</div></div></div>
</div>

<!-- Chercheurs vedettes -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">⭐ Chercheurs en vedette</div>
    <div style="display:flex;gap:8px;">
      <input type="text" class="form-input" style="width:220px;" placeholder="🔍 Chercher un chercheur...">
      <select class="form-select" style="width:160px;">
        <option>Toutes spécialités</option><option>Sociologie</option><option>Économie</option><option>Démographie</option><option>Psychologie</option>
      </select>
    </div>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
      <?php foreach ($researchers as $r): ?>
      <div class="researcher-card">
        <div class="researcher-avatar" style="background:<?= $r['color'] ?>;"><?= $r['initials'] ?></div>
        <div class="researcher-name"><?= $r['name'] ?></div>
        <div class="researcher-title"><?= $r['title'] ?></div>
        <span class="badge badge-info" style="margin-bottom:10px;"><?= $r['spec'] ?></span>
        <div class="researcher-stats">
          <div><div class="researcher-stat-value" style="color:<?= $r['color'] ?>;"><?= $r['etudes'] ?></div><div class="researcher-stat-label">Études</div></div>
          <div><div class="researcher-stat-value" style="color:<?= $r['color'] ?>;"><?= $r['datasets'] ?></div><div class="researcher-stat-label">Datasets</div></div>
        </div>
        <div style="display:flex;gap:6px;margin-top:12px;">
          <button class="btn btn-outline btn-sm" onclick="alert('Profil de <?= $r['name'] ?>')">👁️ Profil</button>
          <button class="btn btn-primary btn-sm" onclick="alert('Message envoyé à <?= $r['name'] ?>')">💬 Contacter</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Forum + Appels à collaboration -->
<div class="dashboard-grid">
  <div class="card">
    <div class="card-header"><div class="card-title">💬 Forum de discussion</div><button class="btn btn-primary btn-sm" onclick="alert('Nouveau fil de discussion')">+ Nouveau fil</button></div>
    <div class="card-body">
      <?php
      $topics = [
        ['🔬','Méthodes mixtes en recherche sociale','12 réponses','Sociologie'],
        ['📊','Utilisation de R vs SPSS pour l\'analyse HCP','8 réponses','Stats'],
        ['🏙️','Indicateurs de cohésion urbaine — Revue de littérature','5 réponses','Urbanisme'],
        ['📰','Analyse de la presse marocaine comme source de données','3 réponses','Méthodo'],
      ];
      foreach ($topics as $t): ?>
      <div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9;cursor:pointer;" onclick="alert('Ouverture du fil : ' + '<?= $t[1] ?>')">
        <div style="font-size:24px;"><?= $t[0] ?></div>
        <div style="flex:1;">
          <div style="font-size:13.5px;font-weight:600;margin-bottom:3px;"><?= $t[1] ?></div>
          <div style="font-size:11.5px;color:#64748b;"><?= $t[2] ?> · <span class="badge badge-gray" style="font-size:10px;"><?= $t[3] ?></span></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">🤝 Appels à collaboration</div><button class="btn btn-primary btn-sm" onclick="alert('Publier un appel')">+ Publier</button></div>
    <div class="card-body">
      <?php
      $calls = [
        ['Cherche co-auteur — Étude Migration Tanger 2025','Dr. Amrani','Démographie','Urgent'],
        ['Besoin enquêteurs terrain — Région Souss-Massa','Pr. Moussaoui','Terrain','Ouvert'],
        ['Collaboration analyse données — Big Data Social','Dr. Ouali','Data Science','Ouvert'],
        ['Revue par pairs — Article cohésion sociale','Dr. Chraibi','Review','Fermé'],
      ];
      foreach ($calls as $c): ?>
      <div style="padding:10px;border:1.5px solid #e2e8f0;border-radius:8px;margin-bottom:8px;cursor:pointer;transition:.2s;" onmouseover="this.style.borderColor='#2563eb'" onmouseout="this.style.borderColor='#e2e8f0'" onclick="alert('Détails : ' + '<?= $c[0] ?>')">
        <div style="font-size:13px;font-weight:600;margin-bottom:4px;"><?= $c[0] ?></div>
        <div style="display:flex;gap:6px;align-items:center;">
          <span style="font-size:11.5px;color:#64748b;">Par <?= $c[1] ?></span>
          <span class="badge badge-info" style="font-size:10px;"><?= $c[2] ?></span>
          <span class="badge <?= $c[3]==='Urgent'?'badge-danger':($c[3]==='Ouvert'?'badge-success':'badge-gray') ?>" style="font-size:10px;"><?= $c[3] ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
