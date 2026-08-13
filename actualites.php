<?php
$activePage = 'actualites';
// Charger les données pour les actualités
$regionsData = [];
if (file_exists(__DIR__.'/data/regions_data.json')) {
    $regionsData = json_decode(file_get_contents(__DIR__.'/data/regions_data.json'), true) ?: [];
}
$corpus = [];
if (file_exists(__DIR__.'/data/psychosocial_corpus.json')) {
    $corpus = json_decode(file_get_contents(__DIR__.'/data/psychosocial_corpus.json'), true) ?: [];
}
$articles = $corpus['articles'] ?? [];
$lastUpdate = $regionsData['last_update'] ?? date('Y-m-d');
?>
<?php include 'includes/header.php'; ?>
<div class="main-content">
  <div class="page-header">
    <h1>📰 Actualités & Veille</h1>
    <p class="subtitle">Revue de presse et veille médiatique sur les dynamiques sociales au Maroc</p>
  </div>

  <!-- Stats -->
  <div class="kpi-grid" style="margin-bottom:24px;">
    <div class="kpi-card">
      <div class="kpi-value"><?= count($articles) ?></div>
      <div class="kpi-label">Articles analysés</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-value"><?= count($corpus['sources'] ?? []) ?></div>
      <div class="kpi-label">Sources médias</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-value"><?= date('d/m/Y', strtotime($lastUpdate)) ?></div>
      <div class="kpi-label">Dernière mise à jour</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-value"><?= count($regionsData['regions'] ?? []) ?></div>
      <div class="kpi-label">Régions couvertes</div>
    </div>
  </div>

  <!-- Articles récents -->
  <div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h3>📄 Articles récents analysés</h3>
      <span style="font-size:12px;color:#64748b;">Mis à jour le <?= date('d/m/Y', strtotime($lastUpdate)) ?></span>
    </div>
    <div class="card-body">
      <?php if (empty($articles)): ?>
        <div style="text-align:center;padding:40px;color:#64748b;">
          <div style="font-size:40px;margin-bottom:12px;">📭</div>
          <p>Aucun article disponible. Lancez un rafraîchissement depuis l'<a href="admin.php" style="color:#2563eb;">administration</a>.</p>
        </div>
      <?php else: ?>
        <div style="display:grid;gap:16px;">
          <?php foreach(array_slice($articles, 0, 20) as $art): ?>
          <div style="padding:16px;border:1px solid #e2e8f0;border-radius:10px;background:#fafbfc;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
              <div style="flex:1;">
                <div style="font-weight:600;color:#1e293b;margin-bottom:6px;font-size:14px;"><?= htmlspecialchars($art['title'] ?? 'Sans titre') ?></div>
                <?php if(!empty($art['summary'])): ?>
                  <div style="font-size:13px;color:#475569;line-height:1.5;"><?= htmlspecialchars(mb_substr($art['summary'], 0, 180)) ?>...</div>
                <?php endif; ?>
                <div style="display:flex;gap:10px;margin-top:8px;flex-wrap:wrap;">
                  <?php if(!empty($art['source'])): ?>
                    <span style="background:#eff6ff;color:#2563eb;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;"><?= htmlspecialchars($art['source']) ?></span>
                  <?php endif; ?>
                  <?php if(!empty($art['keywords'])): ?>
                    <?php foreach(array_slice($art['keywords'], 0, 3) as $kw): ?>
                      <span style="background:#f1f5f9;color:#475569;padding:2px 8px;border-radius:12px;font-size:11px;"><?= htmlspecialchars($kw) ?></span>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
              <?php if(!empty($art['url'])): ?>
                <a href="<?= htmlspecialchars($art['url']) ?>" target="_blank" rel="noopener" style="color:#2563eb;font-size:12px;white-space:nowrap;flex-shrink:0;">Lire →</a>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
