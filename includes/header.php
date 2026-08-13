<?php
// Définit la page active et le titre si pas déjà défini
$activePage = isset($activePage) ? $activePage : 'dashboard';
$pageTitle = isset($pageTitle) ? $pageTitle : 'ATLASIA';
$pageSubtitle = isset($pageSubtitle) ? $pageSubtitle : '';

// Menu items
$menuItems = [
  ['id' => 'dashboard',    'label' => 'Intelligence Stratégique', 'icon' => '⚡', 'href' => 'dashboard.php',  'badge' => null],
  ['id' => 'workspaces',   'label' => 'Espaces de Travail',       'icon' => '📁', 'href' => 'espaces-travail.php', 'badge' => '3'],
  ['id' => 'production',   'label' => 'Production & Collecte',    'icon' => '📊', 'href' => 'production.php', 'badge' => null],
  ['id' => 'referentiel',  'label' => 'Référentiel de Données',   'icon' => '🗄️', 'href' => 'referentiel.php', 'badge' => null],
  ['id' => 'ai-studio',    'label' => 'AI Research Studio',       'icon' => '🤖', 'href' => 'ai-studio.php',  'badge' => 'IA'],
  ['id' => 'observatoire', 'label' => 'Observatoire Social',      'icon' => '🔭', 'href' => 'observatoire.php','badge' => null],
  ['id' => 'observatoire-psychosocial', 'label' => 'Dynamiques Psychosociales', 'icon' => '🧠', 'href' => 'observatoire-psychosocial.php','badge' => 'V2'],
  ['id' => 'mots-sociaux', 'label' => 'Mots sociaux du web', 'icon' => '☁️', 'href' => 'mots-sociaux.php','badge' => '✨'],
  ['id' => 'bibliotheque', 'label' => 'Bibliothèque Scientifique','icon' => '📚', 'href' => 'bibliotheque.php','badge' => '4'],
  ['id' => 'reseau',       'label' => 'Réseau des Chercheurs',    'icon' => '🤝', 'href' => 'reseau.php',     'badge' => null],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> — ATLASIA</title>
  
  <!-- Styles -->
  <link rel="stylesheet" href="css/style.css">
  
  <!-- Leaflet.js pour la carte -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
  
  <!-- Chart.js pour les graphiques -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  
  <style>
    /* Overrides spécifiques à la page */
    <?= isset($extraCSS) ? $extraCSS : '' ?>
  </style>
</head>
<body>
<div class="app-layout">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    
    <!-- Logo -->
    <div class="sidebar-logo">
      <div class="logo-icon">A</div>
      <div class="logo-text">
        <span class="logo-name">ATLASIA</span>
        <span class="logo-tagline">Mapping Society · Powering Insight</span>
      </div>
    </div>

    <!-- Recherche dans la sidebar -->
    <div class="sidebar-search">
      <input type="text" placeholder="Rechercher dans la plateforme..." id="sidebarSearch">
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <div class="nav-section-label">PLATEFORME</div>
      
      <?php foreach ($menuItems as $item): ?>
      <a href="<?= $item['href'] ?>" class="nav-item <?= ($activePage === $item['id']) ? 'active' : '' ?>">
        <span class="nav-icon"><?= $item['icon'] ?></span>
        <span><?= $item['label'] ?></span>
        <?php if ($item['badge']): ?>
        <span class="nav-badge"><?= $item['badge'] ?></span>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>

      <div class="nav-section-label" style="margin-top:8px;">COMPTE</div>
      <a href="profil.php" class="nav-item <?= ($activePage === 'profil') ? 'active' : '' ?>">
        <span class="nav-icon">👤</span>
        <span>Mon Profil</span>
      </a>
      <a href="actualites.php" class="nav-item <?= ($activePage === 'actualites') ? 'active' : '' ?>">
        <span class="nav-icon">📰</span>
        <span>Actualités</span>
      </a>
      <a href="parametres.php" class="nav-item <?= ($activePage === 'parametres') ? 'active' : '' ?>">
        <span class="nav-icon">⚙️</span>
        <span>Paramètres</span>
      </a>
      <a href="admin.php" class="nav-item <?= ($activePage === 'admin') ? 'active' : '' ?>">
        <span class="nav-icon">🔒</span>
        <span>Administration</span>
      </a>
    </nav>

    <!-- Section aide -->
    <div class="sidebar-help">
      <div class="help-title">🤔 Besoin d'aide ?</div>
      <div class="help-desc">Accédez à la documentation et au support technique.</div>
      <a href="#" class="help-link">Consultez notre centre d'aide →</a>
    </div>

  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <div class="main-content">

    <!-- Top Header -->
    <header class="top-header">
      
      <!-- Hamburger (mobile) -->
      <button class="header-btn" onclick="document.getElementById('sidebar').classList.toggle('open')" style="display:none;" id="menuToggle">
        ☰
      </button>

      <!-- Recherche globale -->
      <div class="header-search">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Rechercher dans la plateforme ATLASIA..." id="globalSearch">
      </div>

      <!-- Actions -->
      <div class="header-actions">
        <div class="header-btn" data-tooltip="Notifications" onclick="alert('Aucune nouvelle notification')">
          🔔
          <span class="notification-badge"></span>
        </div>
        <div class="header-btn" data-tooltip="Messages" onclick="alert('Messagerie')">💬</div>
        <div class="header-btn" data-tooltip="Aide" onclick="alert('Centre d\'aide')">❓</div>
        
        <!-- User profile -->
        <div class="header-user">
          <div class="user-avatar">GA</div>
          <div class="user-info">
            <span class="user-name">Ghazi Abdessalam</span>
            <span class="user-role">Chercheur Principal</span>
          </div>
          <span style="color:#94a3b8; margin-left:4px;">▾</span>
        </div>
      </div>
    </header>

    <!-- Page content -->
    <div class="page-content" id="pageContent">
      <!-- Page header -->
      <?php if ($pageTitle): ?>
      <div class="page-header">
        <div>
          <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
          <?php if ($pageSubtitle): ?>
          <p class="page-subtitle"><?= htmlspecialchars($pageSubtitle) ?></p>
          <?php endif; ?>
        </div>
        <?php if (isset($pageActions)): ?>
        <div class="page-actions"><?= $pageActions ?></div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
