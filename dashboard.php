<?php
$activePage   = 'dashboard';
$pageTitle    = 'Intelligence Stratégique';
$pageSubtitle = 'Tableau de bord décisionnel — État de la société marocaine';
$pageActions  = '
  <button class="btn btn-outline btn-sm" onclick="window.print()">🖨️ Imprimer</button>
  <button class="btn btn-primary btn-sm" onclick="generateBrief()">📋 Executive Brief</button>
';

// ===== Données réelles HCP (Annuaire Statistique du Maroc 2024) =====
$dataFile  = __DIR__ . '/data/regions_data.json';
$hcp       = json_decode(@file_get_contents($dataFile), true) ?: ['national' => [], 'regions' => []];
$national  = $hcp['national'] ?? [];
$regionsHCP = $hcp['regions'] ?? [];
$series    = $hcp['national_series'] ?? [];
$hcpSource = $hcp['_meta']['source'] ?? 'HCP — Annuaire Statistique du Maroc 2024';
$hcpAnnee  = $hcp['_meta']['annee_reference'] ?? '2024';

// ===== KPIs réels HCP T2 2026 (avec repli sur anciennes clés) =====
$chomage26   = $national['taux_chomage_2026_t2']    ?? 9.5;
$activite26  = $national['taux_activite_2026_t2']    ?? 42.2;
$emploi26    = $national['taux_emploi_2026_t2']      ?? 38.2;
$pib26       = $national['pib_croissance_2026_t2']   ?? 4.8;
$ipc26       = $national['ipc_var_annuelle_mai_2026'] ?? 1.2;
$population  = $national['population_2025'] ?? $national['population_2024'] ?? 37010000;

// Helpers d'affichage
function fr_int($n){ return number_format((float)$n, 0, ',', ' '); }
function fr_pct($n){ return number_format((float)$n, 1, ',', ' ') . '%'; }

include 'includes/header.php';
?>

<!-- ===== SYNTHÈSE EXÉCUTIVE : 3 messages clés (Charte · Principe 5 : Executive First) ===== -->
<div class="exec-summary">
  <div class="exec-summary-title">🎯 Synthèse exécutive — 3 messages clés</div>
  <div class="exec-messages">
    <div class="exec-msg msg-success">
      <div class="exec-msg-icon">📉</div>
      <div class="exec-msg-text">Le chômage recule à <?= fr_pct($chomage26) ?> au 2ᵉ trimestre 2026 (contre 12,8 % au T1), porté par la reprise agricole et touristique.</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('msg_chomage')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
    <div class="exec-msg msg-warning">
      <div class="exec-msg-icon">👥</div>
      <div class="exec-msg-text">Le taux d'activité s'établit à <?= fr_pct($activite26) ?> (T2 2026) : la participation des femmes (18,1 %) reste très inférieure à celle des hommes (66,5 %).</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('msg_activite')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
    <div class="exec-msg msg-success">
      <div class="exec-msg-icon">💰</div>
      <div class="exec-msg-text">La croissance du PIB atteint <?= fr_pct($pib26) ?> au T2 2026 (estimation), avec une inflation maîtrisée à <?= fr_pct($ipc26) ?> sur un an.</div>
      <div class="ai-actions"><button class="ai-btn" onclick="atlasiaAI('msg_pib')"><span class="ai-spark">✨</span> En savoir plus</button></div>
    </div>
  </div>
</div>

<!-- ===== KPI CARDS (données réelles HCP) ===== -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon orange">💼</div>
    <div class="stat-body">
      <div class="stat-label">Taux de chômage</div>
      <div class="stat-value"><?= fr_pct($chomage26) ?></div>
      <div class="stat-change">HCP — EMO T2 2026</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue">👥</div>
    <div class="stat-body">
      <div class="stat-label">Taux d'activité</div>
      <div class="stat-value"><?= fr_pct($activite26) ?></div>
      <div class="stat-change">HCP — EMO T2 2026</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green">📈</div>
    <div class="stat-body">
      <div class="stat-label">Croissance du PIB</div>
      <div class="stat-value">+<?= fr_pct($pib26) ?></div>
      <div class="stat-change">T2 2026 (estimé)</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple">🛒</div>
    <div class="stat-body">
      <div class="stat-label">Inflation (IPC)</div>
      <div class="stat-value">+<?= fr_pct($ipc26) ?></div>
      <div class="stat-change">Sur un an · mai 2026</div>
    </div>
  </div>
</div>

<!-- ===== CARTE + TENDANCES ===== -->
<div class="dashboard-grid" style="margin-bottom:16px;">

  <!-- Carte du Maroc -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">🗺️ Carte des données sociales</div>
      <div class="map-indicator-select">
        <label>Indicateur :</label>
        <select id="mapIndicator" onchange="updateMapIndicator(this.value)">
          <option value="chomage">Taux de chômage (T2 2026)</option>
          <option value="activite">Taux d'activité (T2 2026)</option>
          <option value="population">Population (RGPH 2024)</option>
          <option value="sante">Sous-utilisation (T2 2026)</option>
        </select>
      </div>
    </div>
    <div id="morocco-map" style="height:330px;"></div>
    <div class="map-legend" id="mapLegend"></div>
    <div class="map-update-info">🗺️ 12 régions (découpage 2015) — Source : <?= htmlspecialchars($hcpSource) ?>. Cliquez sur une région pour les détails.</div>
  </div>

  <!-- Tendances clés (séries réelles HCP) -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">📈 Évolution nationale — Chômage, Activité &amp; PIB (HCP)</div>
      <div class="chart-period-select">
        <button class="period-btn" onclick="setPeriod(this, '3ans')">3 ans</button>
        <button class="period-btn" onclick="setPeriod(this, '5ans')">5 ans</button>
        <button class="period-btn active" onclick="setPeriod(this, 'all')">Depuis 2015</button>
      </div>
    </div>
    <div class="card-body" style="padding-top:12px;">
      <canvas id="trendsChart" height="270"></canvas>
    </div>
    <div class="card-footer" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
      <div style="display:flex;align-items:center;gap:5px;font-size:12px;"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block;"></span>Taux de chômage</div>
      <div style="display:flex;align-items:center;gap:5px;font-size:12px;"><span style="width:10px;height:10px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Taux d'activité</div>
      <div style="display:flex;align-items:center;gap:5px;font-size:12px;"><span style="width:10px;height:10px;border-radius:2px;background:#10b981;display:inline-block;"></span>PIB (Md DH, axe droit)</div>
      <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:#94a3b8;">Source : HCP — Annuaires 2017-2024 · EMO T2 2026</div>
    </div>
    <!-- Donnée interprétée (Charte · Principe 1) -->
    <div class="card-body" style="padding-top:0;">
      <div class="insight-box">
        <div class="insight-head">🧠 Lecture analytique</div>
        <div class="insight-row"><span class="insight-tag tag-interp">Interprétation</span><span class="insight-txt">Entre 2015 et 2023, le chômage monte de 9,7 % à 13,0 % puis recule à 9,5 % au T2 2026, porté par la reprise agricole (+20,5 %) et touristique. Le taux d'activité passe de 47,4 % (2015) à 42,2 % (T2 2026). Sur la même période, le PIB à prix courants progresse fortement (988 à 1&nbsp;720&nbsp;Md&nbsp;DH en 2025), une croissance en partie nominale (effet des prix).</span></div>
        <div class="insight-row"><span class="insight-tag tag-trend">Tendance</span><span class="insight-txt">Si les conditions actuelles se maintiennent, le découplage entre croissance du PIB et repli de l'activité devrait persister, avec un chômage durablement au‑dessus de 12&nbsp;%.</span></div>
        <div class="insight-row"><span class="insight-tag tag-reco">Recommandation</span><span class="insight-txt">Prioriser les dispositifs d'insertion des jeunes et des femmes (moteurs du recul d'activité) et suivre l'écart croissance/emploi comme indicateur d'alerte.</span></div>
        <div class="insight-note">Lecture analytique fondée sur les données HCP disponibles — non une vérité absolue (Charte, Principe 1).</div>
        <div class="ai-actions">
          <button class="ai-btn" onclick="atlasiaAI('evolution_nationale')"><span class="ai-spark">✨</span> Expliquer ce graphique</button>
          <button class="ai-btn" onclick="atlasiaAI('evolution_tendance')"><span class="ai-spark">✨</span> Interpréter la tendance</button>
        </div>
      </div>
      <details class="meta-fiche">
        <summary>ℹ️ Fiche de traçabilité &amp; méthodologie</summary>
        <div class="meta-grid">
          <div class="meta-item"><span class="meta-k">Source</span><span class="meta-v">HCP — Annuaires Statistiques du Maroc 2017-2024</span></div>
          <div class="meta-item"><span class="meta-k">Typologie</span><span class="meta-v">Statistique officielle</span></div>
          <div class="meta-item"><span class="meta-k">Période couverte</span><span class="meta-v">2015 → T2 2026</span></div>
          <div class="meta-item"><span class="meta-k">Dernière mise à jour</span><span class="meta-v"><?= htmlspecialchars($hcp['_meta']['derniere_mise_a_jour'] ?? '2024') ?></span></div>
          <div class="meta-item"><span class="meta-k">Couverture</span><span class="meta-v">Nationale</span></div>
          <div class="meta-item"><span class="meta-k">Degré de confiance</span><span class="meta-v">Élevé (source officielle)</span></div>
          <div class="meta-item"><span class="meta-k">Mode de calcul</span><span class="meta-v">Taux HCP (activité/chômage) ; PIB à prix courants</span></div>
          <div class="meta-item"><span class="meta-k">Limites</span><span class="meta-v">PIB nominal (non corrigé de l'inflation) ; révisions possibles entre éditions</span></div>
        </div>
      </details>
    </div>
  </div>

</div>

<!-- ===== INDICATEURS NATIONAUX (données réelles HCP) ===== -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">📊 Indicateurs nationaux clés</div>
    <span class="badge badge-info">Source : <?= htmlspecialchars($hcpSource) ?></span>
  </div>
  <div class="card-body">
    <div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:14px;">
      <div class="indicator-card orange">
        <div class="indicator-label">Chômage (T2 2026)</div>
        <div class="indicator-value" style="color:#f59e0b;"><?= fr_pct($chomage26) ?></div>
        <div class="indicator-trend" style="color:#64748b;">Urbain <?= fr_pct($national['taux_chomage_urbain_2026_t2'] ?? 11.9) ?> · Rural <?= fr_pct($national['taux_chomage_rural_2026_t2'] ?? 5.4) ?></div>
      </div>
      <div class="indicator-card blue">
        <div class="indicator-label">Taux d'emploi (T2 2026)</div>
        <div class="indicator-value" style="color:#2563eb;"><?= fr_pct($emploi26) ?></div>
        <div class="indicator-trend" style="color:#64748b;">Activité <?= fr_pct($activite26) ?></div>
      </div>
      <div class="indicator-card red">
        <div class="indicator-label">Chômage des jeunes 15-24 ans</div>
        <div class="indicator-value" style="color:#ef4444;"><?= fr_pct($national['taux_chomage_jeunes_15_24_2026_t2'] ?? 27.2) ?></div>
        <div class="indicator-trend" style="color:#64748b;">25-34 ans : <?= fr_pct($national['taux_chomage_25_34_2026_t2'] ?? 14.5) ?></div>
      </div>
      <div class="indicator-card blue" style="border-color:#8b5cf6;">
        <div class="indicator-label">Sous-utilisation main-d'œuvre</div>
        <div class="indicator-value" style="color:#8b5cf6;"><?= fr_pct($national['taux_sous_utilisation_2026_t2'] ?? 18.9) ?></div>
        <div class="indicator-trend" style="color:#64748b;">Indicateur composite · T2 2026</div>
      </div>
      <div class="indicator-card green">
        <div class="indicator-label">Population (2025)</div>
        <div class="indicator-value" style="color:#10b981; font-size:20px;"><?= fr_int($population) ?></div>
        <div class="indicator-trend" style="color:#64748b;">Urbaine <?= fr_int($national['population_urbaine_2025'] ?? 23372000) ?></div>
      </div>
    </div>
  </div>
</div>

<!-- ===== INDICATEURS RÉGIONAUX (données réelles HCP) ===== -->
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">📋 Indicateurs régionaux — 12 régions</div>
    <span class="badge badge-info">Source : <?= htmlspecialchars($hcpSource) ?></span>
  </div>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Région</th>
          <th>Chef-lieu</th>
          <th>Population (2024)</th>
          <th>Chômage (T2 2026)</th>
          <th>Activité (T2 2026)</th>
          <th>Emploi (T2 2026)</th>
          <th>Sous-utilisation (T2 2026)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($regionsHCP as $r):
          $e26 = $r['emploi_2026_t2'] ?? [];
          $ch = $e26['taux_chomage'] ?? null;
          $ac = $e26['taux_activite'] ?? null;
          $em = $e26['taux_emploi'] ?? null;
          $su = $e26['taux_sous_utilisation'] ?? null;
        ?>
        <tr>
          <td><strong><?= htmlspecialchars($r['nom']) ?></strong></td>
          <td><?= htmlspecialchars($r['chef_lieu'] ?? '—') ?></td>
          <td><?= fr_int($r['population']['total_2024'] ?? 0) ?></td>
          <td><span style="color:<?= ($ch===null?'#64748b':($ch>12?'#ef4444':($ch>9?'#f59e0b':'#10b981'))) ?>;font-weight:600;"><?= $ch!==null?fr_pct($ch):'n.s.' ?></span></td>
          <td><?= $ac!==null?fr_pct($ac):'—' ?></td>
          <td><?= $em!==null?fr_pct($em):'—' ?></td>
          <td><span style="color:<?= ($su!==null && $su>25?'#ef4444':($su!==null && $su>18?'#f59e0b':'#10b981')) ?>;font-weight:600;"><?= $su!==null?fr_pct($su):'—' ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="map-update-info" style="padding:10px 16px;">ℹ️ Données emploi régionales issues de l'Enquête sur la main-d'œuvre (EMO) du HCP, 2ᵉ trimestre 2026. « n.s. » = taux non significatif (faible effectif, Dakhla-Oued Ed-Dahab). La sous-utilisation composite agrège chômage, sous-emploi et main-d'œuvre potentielle.</div>
</div>

<!-- ===== ÉTUDES + ACTIVITÉ RÉSEAU + ACCÈS RAPIDE ===== -->
<div class="dashboard-grid-3">

  <!-- Études récentes -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">📄 Études récentes</div>
      <a href="bibliotheque.php" class="btn btn-outline btn-sm">Voir tout</a>
    </div>
    <div class="card-body" style="padding-top:8px;">
      <div class="study-item">
        <div class="study-icon" style="background:#dbeafe;font-size:18px;">📊</div>
        <div class="study-body">
          <div class="study-title">Dynamiques migratoires au Maroc en 2024</div>
          <div class="study-meta">Mai 2025</div>
          <div class="study-tags"><span class="tag tag-blue">Migration</span><span class="tag tag-gray">HCP</span></div>
        </div>
      </div>
      <div class="study-item">
        <div class="study-icon" style="background:#d1fae5;font-size:18px;">💼</div>
        <div class="study-body">
          <div class="study-title">Les jeunes et le marché de l'emploi</div>
          <div class="study-meta">Avril 2025</div>
          <div class="study-tags"><span class="tag tag-green">Emploi</span><span class="tag tag-orange">Jeunesse</span></div>
        </div>
      </div>
      <div class="study-item">
        <div class="study-icon" style="background:#ede9fe;font-size:18px;">🎓</div>
        <div class="study-body">
          <div class="study-title">Perceptions sociales de l'éducation</div>
          <div class="study-meta">Avril 2025</div>
          <div class="study-tags"><span class="tag tag-purple">Éducation</span><span class="tag tag-gray">Société</span></div>
        </div>
      </div>
      <div class="study-item" style="border-bottom:none; padding-bottom:0;">
        <div class="study-icon" style="background:#ffedd5;font-size:18px;">🏠</div>
        <div class="study-body">
          <div class="study-title">Logement et conditions de vie urbaine</div>
          <div class="study-meta">Mars 2025</div>
          <div class="study-tags"><span class="tag tag-orange">Logement</span><span class="tag tag-blue">Urbain</span></div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <a href="bibliotheque.php" class="btn btn-outline btn-sm" style="width:100%; justify-content:center;">Voir toutes les études →</a>
    </div>
  </div>

  <!-- Activité du réseau -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">🤝 Activité du réseau</div>
      <span class="badge badge-success">🟢 En ligne</span>
    </div>
    <div class="card-body" style="padding-top:8px;">
      <div class="activity-item">
        <div class="activity-avatar" style="background:#2563eb;">MA</div>
        <div class="activity-body">
          <div class="activity-text">Nouveau chercheur inscrit : <strong>Mohammed Alami</strong></div>
          <div class="activity-time">Il y a 2 heures</div>
        </div>
      </div>
      <div class="activity-item">
        <div class="activity-avatar" style="background:#10b981;">SB</div>
        <div class="activity-body">
          <div class="activity-text">Nouvelle étude partagée : <em>Relations sociales et cohésion</em></div>
          <div class="activity-time">Il y a 5 heures</div>
        </div>
      </div>
      <div class="activity-item">
        <div class="activity-avatar" style="background:#7c3aed;">KA</div>
        <div class="activity-body">
          <div class="activity-text">Nouveau commentaire dans le forum <strong>Sociologie</strong></div>
          <div class="activity-time">Il y a 1 jour</div>
        </div>
      </div>
      <div class="activity-item">
        <div class="activity-avatar" style="background:#f59e0b;">LM</div>
        <div class="activity-body">
          <div class="activity-text">Jeu de données publié : <em>Enquête HCP 2024 - Emploi</em></div>
          <div class="activity-time">Il y a 2 jours</div>
        </div>
      </div>
      <div class="activity-item" style="border-bottom:none;">
        <div class="activity-avatar" style="background:#ef4444;">ZO</div>
        <div class="activity-body">
          <div class="activity-text">Rapport validé : <strong>Observatoire Social Q2 2025</strong></div>
          <div class="activity-time">Il y a 3 jours</div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <a href="reseau.php" class="btn btn-outline btn-sm" style="width:100%; justify-content:center;">Accéder au réseau →</a>
    </div>
  </div>

  <!-- Accès rapide + Alertes -->
  <div style="display:flex; flex-direction:column; gap:16px;">
    <div class="card">
      <div class="card-header">
        <div class="card-title">⚡ Accès rapide</div>
      </div>
      <div class="card-body">
        <div class="quick-grid">
          <div class="quick-btn" onclick="window.location='production.php'">
            <div class="quick-icon" style="background:#dbeafe;">🔬</div>
            <span>Nouvelle recherche</span>
          </div>
          <div class="quick-btn" onclick="window.location='referentiel.php'">
            <div class="quick-icon" style="background:#d1fae5;">➕</div>
            <span>Ajouter un jeu de données</span>
          </div>
          <div class="quick-btn" onclick="generateBrief()">
            <div class="quick-icon" style="background:#ffedd5;">📝</div>
            <span>Créer un rapport</span>
          </div>
          <div class="quick-btn" onclick="window.location='espaces-travail.php'">
            <div class="quick-icon" style="background:#ede9fe;">💼</div>
            <span>Espace collaboratif</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Alertes -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">🚨 Alertes système</div>
        <span class="badge badge-warning">3 alertes</span>
      </div>
      <div class="card-body">
        <div class="alert-list">
          <div class="alert-item danger">
            <span class="alert-icon">🔴</span>
            <span>Région Laâyoune : aucune collecte depuis 14 mois.</span>
          </div>
          <div class="alert-item warning">
            <span class="alert-icon">⚠️</span>
            <span>Indicateur chômage : hausse anormale détectée (Fès-Meknès).</span>
          </div>
          <div class="alert-item info">
            <span class="alert-icon">ℹ️</span>
            <span>3 nouveaux jeux de données en attente de validation.</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- ===== MODAL EXECUTIVE BRIEF ===== -->
<div class="modal-overlay" id="briefModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">📋 Générer un Executive Brief</div>
      <div class="modal-close" onclick="closeBrief()">✕</div>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Titre du rapport</label>
        <input class="form-input" value="Briefing Stratégique — État de la Société Marocaine · Juillet 2025">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Période couverte</label>
          <select class="form-select">
            <option>2024 – 2025</option><option>2020 – 2025</option><option>Trimestre en cours</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Format</label>
          <select class="form-select">
            <option>PDF (2 pages)</option><option>Word (.docx)</option><option>Présentation</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Indicateurs à inclure</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:6px;">
          <?php foreach(['Chômage','Éducation','Pauvreté','Migration','Confiance','Logement'] as $ind): ?>
          <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
            <input type="checkbox" checked style="width:16px;height:16px;"> <?= $ind ?>
          </label>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Note pour le décideur</label>
        <textarea class="form-textarea" style="min-height:80px;" placeholder="Contexte ou instructions supplémentaires..."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeBrief()">Annuler</button>
      <button class="btn btn-primary" onclick="alert('Brief en cours de génération par l\'IA...\n\nDocument prêt dans quelques secondes.');closeBrief()">
        🤖 Générer avec IA
      </button>
    </div>
  </div>
</div>

<script>
// ===== CARTE DU MAROC (Leaflet) — 12 régions, données réelles HCP =====
<?php
// Construire les jeux de données de la carte à partir des données réelles HCP
$mapChomage = [];
$mapActivite = [];
$mapPopulation = [];
$mapSante = [];
foreach ($regionsHCP as $r) {
  $nom = $r['nom'];
  $e26 = $r['emploi_2026_t2'] ?? [];
  $chReg = $e26['taux_chomage'] ?? ($r['emploi']['taux_chomage_2023'] ?? null);
  $acReg = $e26['taux_activite'] ?? ($r['emploi']['taux_activite_2023'] ?? null);
  $mapChomage[$nom]    = ['value' => $chReg, 'label' => $nom];
  $mapActivite[$nom]   = ['value' => $acReg, 'label' => $nom];
  $mapPopulation[$nom] = ['value' => $r['population']['total_2024'] ?? null,     'label' => $nom];
  $mapSante[$nom]      = ['value' => $e26['taux_sous_utilisation'] ?? null,      'label' => $nom];
}
?>
const indicatorsData = {
  chomage: {
    label: 'Taux de chômage T2 2026 (%)',
    unit: '%',
    colors: ['#fee2e2','#fecaca','#fca5a5','#f87171','#ef4444','#dc2626','#b91c1c','#991b1b'],
    data: <?= json_encode($mapChomage, JSON_UNESCAPED_UNICODE) ?>,
    ranges: ['< 7%','7-8%','8-10%','10-12%','12-13%','> 13%']
  },
  activite: {
    label: "Taux d'activité T2 2026 (%)",
    unit: '%',
    colors: ['#dbeafe','#bfdbfe','#93c5fd','#60a5fa','#3b82f6','#2563eb','#1d4ed8','#1e40af'],
    data: <?= json_encode($mapActivite, JSON_UNESCAPED_UNICODE) ?>,
    ranges: ['< 36%','36-40%','40-43%','43-47%','47-55%','> 55%']
  },
  population: {
    label: 'Population (RGPH 2024)',
    unit: '',
    colors: ['#d1fae5','#a7f3d0','#6ee7b7','#34d399','#10b981','#059669','#047857','#065f46'],
    data: <?= json_encode($mapPopulation, JSON_UNESCAPED_UNICODE) ?>,
    ranges: ['< 500k','0.5-1.5M','1.5-2.5M','2.5-4M','4-5M','> 5M']
  },
  sante: {
    label: 'Sous-utilisation main-d\'œuvre T2 2026 (%)',
    unit: '%',
    colors: ['#ede9fe','#ddd6fe','#c4b5fd','#a78bfa','#8b5cf6','#7c3aed','#6d28d9','#5b21b6'],
    data: <?= json_encode($mapSante, JSON_UNESCAPED_UNICODE) ?>,
    ranges: ['< 15%','15-17%','17-21%','21-26%','26-30%','> 30%']
  }
};

const GEOJSON_FILE = 'data/morocco-regions-12.geojson';
let moroccoMap, geojsonLayer, currentIndicator = 'chomage';

// Initialiser la carte
fetch(GEOJSON_FILE)
  .then(r => r.json())
  .then(geojson => {
    moroccoMap = L.map('morocco-map', {
      center: [29.0, -7.5],
      zoom: 5,
      zoomControl: true,
      scrollWheelZoom: true,
      attributionControl: false
    });

    // Fond neutre clair
    L.tileLayer('https://www.arcgis.com/sharing/rest/content/items/64dc59ada3774a1d88ba6dfd9c4b4b30/info/thumbnail/ago_downloaded.png?f=json&w=400', {
      attribution: '&copy; CartoDB &copy; OSM',
      subdomains: 'abcd', maxZoom: 19
    }).addTo(moroccoMap);

    moroccoMap.attributionControl = false;
    loadMapIndicator('chomage', geojson);
  })
  .catch(err => {
    document.getElementById('morocco-map').innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;background:#f8fafc;color:#64748b;font-size:14px;">⚠️ Chargez l\'application via un serveur XAMPP pour afficher la carte.</div>';
    console.error('Map error:', err);
  });

function fmtVal(v, unit) {
  if (v === null || v === undefined || v === 'N/D') return 'N/D';
  if (unit === '%') return String(v).replace('.', ',') + '%';
  // Grand nombre : séparateur d'espace
  return Number(v).toLocaleString('fr-FR');
}

function getColor(value, indicator) {
  const ind = indicatorsData[indicator];
  const vals = Object.values(ind.data).map(d => d.value);
  const min = Math.min(...vals), max = Math.max(...vals);
  const pct = (value - min) / (max - min);
  const idx = Math.min(Math.floor(pct * (ind.colors.length - 1)), ind.colors.length - 1);
  return ind.colors[idx];
}

function loadMapIndicator(indicator, geojson) {
  if (geojsonLayer) moroccoMap.removeLayer(geojsonLayer);
  currentIndicator = indicator;

  geojsonLayer = L.geoJSON(geojson, {
    style: function(feature) {
      const name = feature.properties.name;
      const ind = indicatorsData[indicator];
      const regionData = ind.data[name] || {value: 0};
      return {
        fillColor: getColor(regionData.value, indicator),
        weight: 1.5, opacity: 1,
        color: 'white',
        fillOpacity: 0.82
      };
    },
    onEachFeature: function(feature, layer) {
      const name = feature.properties.name;
      const ind = indicatorsData[indicator];
      const regionData = ind.data[name] || {value: 'N/D'};
      layer.bindTooltip(`<strong>${regionData.label || name}</strong><br>${ind.label} : <strong>${fmtVal(regionData.value, ind.unit)}</strong>`, {sticky: true, className: 'leaflet-tooltip-custom'});
      layer.on({
        mouseover: function(e) { e.target.setStyle({weight:3, color:'#1e293b', fillOpacity:0.95}); },
        mouseout:  function(e) { geojsonLayer.resetStyle(e.target); },
        click:     function(e) { showRegionDetail(name, indicator, regionData); }
      });
    }
  }).addTo(moroccoMap);

  moroccoMap.fitBounds(geojsonLayer.getBounds(), {padding:[10,10]});
  updateLegend(indicator);
}

function updateLegend(indicator) {
  const ind = indicatorsData[indicator];
  const legend = document.getElementById('mapLegend');
  let html = `<div class="legend-title">${ind.label}</div><div class="legend-items">`;
  ind.ranges.forEach((range, i) => {
    const colorIdx = Math.floor(i * (ind.colors.length / ind.ranges.length));
    html += `<div class="legend-item"><div class="legend-color" style="background:${ind.colors[colorIdx]};"></div>${range}</div>`;
  });
  html += '</div>';
  legend.innerHTML = html;
}

function updateMapIndicator(indicator) {
  if (!moroccoMap) return;
  fetch(GEOJSON_FILE)
    .then(r => r.json())
    .then(geojson => loadMapIndicator(indicator, geojson));
}

function showRegionDetail(name, indicator, data) {
  const ind = indicatorsData[indicator];
  alert(`📍 Région : ${data.label || name}\n\n${ind.label} : ${fmtVal(data.value, ind.unit)}\n\nSource : <?= addslashes($hcpSource) ?>\n\nConsultez l'Observatoire Social pour les données complètes de cette région.`);
}

// ===== IA EMBARQUÉE : analyses ancrées sur les données HCP =====
window.ATLASIA_AI = window.ATLASIA_AI || {};
Object.assign(window.ATLASIA_AI, {
  msg_chomage: {
    title: "Chômage — 9,5 % (T2 2026)",
    interpretation: "Le taux de chômage recule à 9,5 % au 2ᵉ trimestre 2026, contre 12,8 % au T1. Ce repli marqué reflète la reprise saisonnière de l'emploi agricole (valeur ajoutée agricole +20,5 %) et touristique. Le chômage reste plus élevé en milieu urbain (11,9 %) qu'en rural (5,4 %), et frappe surtout les jeunes de 15-24 ans (27,2 %) et les femmes (14,8 %).",
    tendance: "La reprise de l'activité économique (PIB +4,8 % au T2) soutient l'emploi, mais le chômage des jeunes reste structurellement élevé.",
    recommandation: "Cibler l'insertion des jeunes 15-24 ans (27,2 %) et réduire l'écart de chômage femmes/hommes (14,8 % contre 8,1 %).",
    source: "HCP — Enquête sur la main-d'œuvre (EMO), T2 2026"
  },
  msg_activite: {
    title: "Taux d'activité — 42,2 % (T2 2026)",
    interpretation: "Le taux d'activité s'établit à 42,2 % au T2 2026. L'écart entre hommes (66,5 %) et femmes (18,1 %) reste considérable. Le taux d'emploi atteint 38,2 % et la sous-utilisation composite de la main-d'œuvre 18,9 %.",
    tendance: "Sans progression de la participation féminine, le potentiel de croissance de la main-d'œuvre reste bridé.",
    recommandation: "Lever les freins à l'activité des femmes (mobilité, garde d'enfants, adéquation formation-emploi).",
    source: "HCP — Enquête sur la main-d'œuvre (EMO), T2 2026"
  },
  msg_pib: {
    title: "Croissance & inflation (T2 2026)",
    interpretation: "La croissance du PIB atteint +4,8 % au T2 2026 (estimation), après +4,6 % au T1, portée par l'agriculture (+20,5 %), la consommation des ménages (+4,7 %) et le tertiaire. L'inflation reste maîtrisée à +1,1 % (IPC +1,2 % sur un an en mai), avec un taux directeur de Bank Al-Maghrib à 2,25 %.",
    tendance: "La prévision de croissance pour le T3 2026 est de +5,4 %, dans un contexte d'inflation contenue.",
    recommandation: "Veiller à ce que la croissance se traduise en emplois de qualité et suivre le déficit commercial (taux de couverture 80,9 %).",
    source: "HCP — Point de Conjoncture N°50, Juillet 2026"
  },
  evolution_nationale: {
    title: "Évolution nationale (2015 – T2 2026)",
    interpretation: "Le graphique croise trois indicateurs : chômage (rouge), activité (bleu) et PIB à prix courants (barres vertes). Le chômage monte, l'activité baisse, le PIB nominal croît.",
    tendance: "Le découplage entre la croissance du PIB et le recul de l'activité tend à se maintenir.",
    recommandation: "Analyser ces trois séries ensemble plutôt qu'isolément (Charte, Principe 6 : croisement des indicateurs).",
    source: "HCP — Annuaires Statistiques du Maroc 2017-2024"
  },
  evolution_tendance: {
    title: "Interprétation de la tendance",
    interpretation: "De 2020 à 2023, le chômage s'est maintenu à un palier élevé (11,9 % → 13,0 %), avant un net recul à 9,5 % au T2 2026 (+20,5 % en valeur ajoutée agricole). L'activité atteint 42,2 % au T2 2026.",
    tendance: "Sans inflexion des politiques d'emploi, le marché du travail resterait sous tension à l'horizon des prochaines années.",
    recommandation: "Mettre en place un suivi d'alerte sur l'écart entre croissance économique et création d'emplois.",
    source: "HCP — Annuaires Statistiques du Maroc 2017-2024"
  }
});

// ===== GRAPHIQUE DES TENDANCES (séries réelles HCP) =====
const nationalSeries = <?= json_encode($series, JSON_UNESCAPED_UNICODE) ?>;

let trendsChart;

function initTrendsChart(period = 'all') {
  const ctx = document.getElementById('trendsChart').getContext('2d');
  if (!nationalSeries || !nationalSeries.annees) return;

  // Découper la série selon la période choisie
  const allYears = nationalSeries.annees;
  let n = allYears.length;
  if (period === '3ans') n = Math.min(4, allYears.length);
  else if (period === '5ans') n = Math.min(6, allYears.length);
  const start = allYears.length - n;

  const labels   = allYears.slice(start);
  const chomage  = nationalSeries.taux_chomage.valeurs.slice(start);
  const activite = nationalSeries.taux_activite.valeurs.slice(start);
  // PIB en milliards de DH (source en MDH) pour lecture sur un axe secondaire
  const pibSrc   = (nationalSeries.pib_prix_courants_mdh && nationalSeries.pib_prix_courants_mdh.valeurs) || [];
  const pibMrd   = pibSrc.slice(start).map(v => v ? +(v/1000).toFixed(1) : null);

  if (trendsChart) trendsChart.destroy();
  trendsChart = new Chart(ctx, {
    data: {
      labels: labels,
      datasets: [
        {type:'line', label:"Taux de chômage",  yAxisID:'yPct', data:chomage,  borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,.08)', fill:true, borderWidth:2.5, pointRadius:4, pointHoverRadius:7, tension:.35, order:1},
        {type:'line', label:"Taux d'activité",  yAxisID:'yPct', data:activite, borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,.06)', fill:true, borderWidth:2.5, pointRadius:4, pointHoverRadius:7, tension:.35, order:1},
        {type:'bar',  label:"PIB (prix courants)", yAxisID:'yPib', data:pibMrd, backgroundColor:'rgba(16,185,129,.30)', borderColor:'#10b981', borderWidth:1.5, borderRadius:4, order:2, barPercentage:0.55, categoryPercentage:0.6}
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      interaction: { mode:'index', intersect:false },
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ctx.dataset.yAxisID === 'yPib'
            ? ` ${ctx.dataset.label}: ${ctx.raw} Md DH`
            : ` ${ctx.dataset.label}: ${ctx.raw}%` } }
      },
      scales: {
        x: { grid: { display:false }, ticks: { font:{size:11.5} } },
        yPct: {
          type:'linear', position:'left', min: 0, max: 55,
          grid: { color:'rgba(0,0,0,.05)' },
          ticks: { font:{size:11.5}, callback: v => v+'%' },
          title: { display:true, text:'Taux (%)', font:{size:10}, color:'#94a3b8' }
        },
        yPib: {
          type:'linear', position:'right', min: 0,
          grid: { drawOnChartArea:false },
          ticks: { font:{size:11.5}, callback: v => v+' Md' },
          title: { display:true, text:'PIB (Md DH)', font:{size:10}, color:'#10b981' }
        }
      }
    }
  });
}

initTrendsChart('all');

function setPeriod(btn, period) {
  document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  initTrendsChart(period);
}

// ===== EXECUTIVE BRIEF MODAL =====
function generateBrief() {
  document.getElementById('briefModal').classList.add('open');
}
function closeBrief() {
  document.getElementById('briefModal').classList.remove('open');
}
document.getElementById('briefModal').addEventListener('click', function(e) {
  if (e.target === this) closeBrief();
});
</script>

<?php include 'includes/footer.php'; ?>
