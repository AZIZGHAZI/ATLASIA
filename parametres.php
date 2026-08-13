<?php
$activePage  = 'parametres';
$pageTitle   = 'Paramètres';
$pageSubtitle = 'Gérez votre compte et les préférences de la plateforme';
include 'includes/header.php';
?>

<div class="tabs">
  <div class="tab active" onclick="showTab(this,'tab-profil')">👤 Mon profil</div>
  <div class="tab" onclick="showTab(this,'tab-notifs')">🔔 Notifications</div>
  <div class="tab" onclick="showTab(this,'tab-secure')">🔒 Sécurité</div>
  <div class="tab" onclick="showTab(this,'tab-pref')">⚙️ Préférences</div>
</div>

<div class="tab-content active" id="tab-profil">
  <div class="dashboard-grid">
    <div class="card">
      <div class="card-header"><div class="card-title">Informations personnelles</div></div>
      <div class="card-body">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:22px;">
          <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#0ea5e9);display:flex;align-items:center;justify-content:center;color:white;font-size:26px;font-weight:700;">GA</div>
          <div>
            <div style="font-size:17px;font-weight:700;">Ghazi Abdessalam</div>
            <div style="font-size:13px;color:#64748b;">Chercheur Principal · ATLASIA</div>
            <button class="btn btn-outline btn-sm" style="margin-top:6px;" onclick="alert('Upload photo')">📷 Changer la photo</button>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Prénom</label><input class="form-input" value="Ghazi"></div>
          <div class="form-group"><label class="form-label">Nom</label><input class="form-input" value="Abdessalam"></div>
        </div>
        <div class="form-group"><label class="form-label">Email</label><input class="form-input" value="g.abdessalam@atlasia.ma"></div>
        <div class="form-group"><label class="form-label">Spécialité</label><input class="form-input" value="Sociologie, Sciences Sociales, Données"></div>
        <div class="form-group"><label class="form-label">Institution</label><input class="form-input" value="ATLASIA — Plateforme Nationale"></div>
        <div class="form-group"><label class="form-label">Biographie</label><textarea class="form-textarea">Chercheur en sciences sociales spécialisé dans l'analyse des données sociales marocaines. Fondateur et directeur de la plateforme ATLASIA.</textarea></div>
        <button class="btn btn-primary" onclick="alert('Profil mis à jour avec succès !')">💾 Enregistrer</button>
      </div>
    </div>
    <div class="card" style="height:fit-content;">
      <div class="card-header"><div class="card-title">Statistiques du compte</div></div>
      <div class="card-body">
        <div style="display:flex;flex-direction:column;gap:14px;">
          <?php foreach([['📋','Projets créés','12'],['📊','Datasets publiés','8'],['📖','Études partagées','24'],['👥','Collaborateurs','47'],['🔭','Vues du profil','1,840 ce mois']] as $s): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:#f8fafc;border-radius:8px;">
            <span><?= $s[0] ?> <?= $s[1] ?></span>
            <span style="font-weight:700;color:#2563eb;"><?= $s[2] ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="tab-content" id="tab-notifs">
  <div class="card">
    <div class="card-header"><div class="card-title">Préférences de notifications</div></div>
    <div class="card-body">
      <?php
      $notifs = [
        ['Nouveaux datasets publiés','Soyez notifié lors de l\'ajout de nouvelles données',true],
        ['Nouvelles études partagées','Alertes pour les publications récentes',true],
        ['Activités réseau','Messages, commentaires et collaborations',false],
        ['Alertes indicateurs','Variations anormales des indicateurs sociaux',true],
        ['Résumé hebdomadaire','Rapport hebdomadaire par email',false],
        ['Mises à jour plateforme','Nouvelles fonctionnalités et améliorations',true],
      ];
      foreach ($notifs as $n): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid #f1f5f9;">
        <div><div style="font-size:13.5px;font-weight:600;"><?= $n[0] ?></div><div style="font-size:12px;color:#64748b;"><?= $n[1] ?></div></div>
        <label style="position:relative;display:inline-block;width:44px;height:24px;cursor:pointer;">
          <input type="checkbox" <?= $n[2]?'checked':'' ?> style="opacity:0;width:0;height:0;" onchange="this.parentElement.querySelector('span').style.background=this.checked?'#2563eb':'#cbd5e1'">
          <span style="position:absolute;inset:0;border-radius:24px;background:<?= $n[2]?'#2563eb':'#cbd5e1' ?>;transition:.2s;">
            <span style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:white;border-radius:50%;transition:.2s;<?= $n[2]?'transform:translateX(20px)':'' ?>"></span>
          </span>
        </label>
      </div>
      <?php endforeach; ?>
      <button class="btn btn-primary" style="margin-top:16px;" onclick="alert('Préférences enregistrées !')">💾 Enregistrer</button>
    </div>
  </div>
</div>

<div class="tab-content" id="tab-secure">
  <div class="card" style="max-width:540px;">
    <div class="card-header"><div class="card-title">🔒 Modifier le mot de passe</div></div>
    <div class="card-body">
      <div class="form-group"><label class="form-label">Mot de passe actuel</label><input type="password" class="form-input" placeholder="••••••••"></div>
      <div class="form-group"><label class="form-label">Nouveau mot de passe</label><input type="password" class="form-input" placeholder="••••••••"></div>
      <div class="form-group"><label class="form-label">Confirmer le nouveau mot de passe</label><input type="password" class="form-input" placeholder="••••••••"></div>
      <button class="btn btn-primary" onclick="alert('Mot de passe modifié avec succès !')">🔄 Modifier</button>
    </div>
  </div>
</div>

<div class="tab-content" id="tab-pref">
  <div class="card" style="max-width:600px;">
    <div class="card-header"><div class="card-title">⚙️ Préférences générales</div></div>
    <div class="card-body">
      <div class="form-group"><label class="form-label">Langue de l'interface</label><select class="form-select"><option>Français</option><option>العربية</option><option>English</option></select></div>
      <div class="form-group"><label class="form-label">Thème</label><select class="form-select"><option>Clair (par défaut)</option><option>Sombre</option><option>Automatique</option></select></div>
      <div class="form-group"><label class="form-label">Fuseau horaire</label><select class="form-select"><option>UTC+1 (Maroc)</option><option>UTC+0</option><option>UTC+2</option></select></div>
      <div class="form-group"><label class="form-label">Format de date</label><select class="form-select"><option>DD/MM/YYYY</option><option>MM/DD/YYYY</option><option>YYYY-MM-DD</option></select></div>
      <button class="btn btn-primary" onclick="alert('Préférences enregistrées !')">💾 Enregistrer</button>
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
</script>
<?php include 'includes/footer.php'; ?>
