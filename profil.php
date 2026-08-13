<?php $activePage = 'profil'; ?>
<?php include 'includes/header.php'; ?>
<div class="main-content">
  <div class="page-header">
    <h1>👤 Mon Profil</h1>
    <p class="subtitle">Gestion de votre compte utilisateur ATLASIA</p>
  </div>
  <div class="card" style="max-width:600px;margin:0 auto;">
    <div class="card-header"><h3>Informations du compte</h3></div>
    <div class="card-body" style="padding:32px;">
      <div style="display:flex;align-items:center;gap:20px;margin-bottom:32px;">
        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:32px;color:#fff;">👤</div>
        <div>
          <div style="font-size:20px;font-weight:700;color:#1e293b;">Administrateur ATLASIA</div>
          <div style="color:#64748b;font-size:14px;">admin@atlasia.ma</div>
          <span style="background:#dcfce7;color:#16a34a;padding:2px 10px;border-radius:20px;font-size:12px;font-weight:600;">Actif</span>
        </div>
      </div>
      <div style="border-top:1px solid #e2e8f0;padding-top:24px;">
        <div style="margin-bottom:16px;">
          <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Nom d'utilisateur</label>
          <input type="text" value="admin" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;" readonly>
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Email</label>
          <input type="email" value="admin@atlasia.ma" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;" readonly>
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Rôle</label>
          <input type="text" value="Administrateur Plateforme" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;" readonly>
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Dernière connexion</label>
          <input type="text" value="<?= date('d/m/Y H:i') ?>" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;" readonly>
        </div>
      </div>
      <div style="margin-top:20px;padding:14px;background:#f0f9ff;border-radius:8px;border-left:4px solid #2563eb;font-size:13px;color:#374151;">
        ℹ️ La gestion des utilisateurs est disponible dans la section <a href="admin.php" style="color:#2563eb;font-weight:600;">Administration</a>.
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
