<?php
$activePage  = 'observatoire';
$pageTitle   = 'Observatoire Social';
$pageSubtitle = 'Suivi des indicateurs sociaux et tendances au Maroc';
$pageActions = '<button class="btn btn-outline btn-sm" onclick="window.print()">🖨️ Imprimer</button>';

// ===== Données réelles HCP =====
$dataFile   = __DIR__ . '/data/regions_data.json';
$hcp        = json_decode(@file_get_contents($dataFile), true) ?: ['national'=>[], 'regions'=>[]];
$national   = $hcp['national'] ?? [];
$regionsHCP = $hcp['regions'] ?? [];
$series     = $hcp['national_series'] ?? [];
$popRec     = $hcp['population_recensements'] ?? [];
$hcpSource  = $hcp['_meta']['source'] ?? 'HCP — Annuaire Statistique du Maroc 2024';

if (!function_exists('fr_int')) { function fr_int($n){ return number_format((float)$n,0,',',' '); } }
if (!function_exists('fr_pct')) { function fr_pct($n){ return number_format((float)$n,1,',',' ').'%'; } }

include 'includes/header.php';
?>

<!-- Indicateurs nationaux (données réelles HCP) -->
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:20px;">
  <div class="indicator-card orange"><div class="indicator-label">Chômage national (T2 2026)</div><div class="indicator-value" style="color:#f59e0b;"><?= fr_pct($national['taux_chomage_2026_t2'] ?? 9.5) ?></div><div class="indicator-trend" style="color:#64748b;">Urbain <?= fr_pct($national['taux_chomage_urbain_2026_t2'] ?? 11.9) ?></div></div>
  <div class="indicator-card blue"><div class="indicator-label">Taux d'activité (T2 2026)</div><div class="indicator-value" style="color:#2563eb;"><?= fr_pct($national['taux_activite_2026_t2'] ?? 42.2) ?></div><div class="indicator-trend" style="color:#64748b;">Rural <?= fr_pct($national['taux_activite_rural_2023'] ?? 47.3) ?></div></div>
  <div class="indicator-card green"><div class="indicator-label">Population (RGPH 2024)</div><div class="indicator-value" style="color:#10b981;font-size:19px;"><?= fr_int($national['population_2024'] ?? 36828330) ?></div><div class="indicator-trend" style="color:#64748b;">Urbaine <?= fr_int($national['population_urbaine'] ?? 23110108) ?></div></div>
  <div class="indicator-card blue" style="border-color:#8b5cf6;"><div class="indicator-label">Lits hospitaliers (2022)</div><div class="indicator-value" style="color:#8b5cf6;"><?= fr_int($national['lits_hopitaux_2022'] ?? 21455) ?></div><div class="indicator-trend" style="color:#64748b;"><?= fr_int($national['hopitaux_publics_2022'] ?? 152) ?> hôpitaux</div></div>
  <div class="indicator-card red"><div class="indicator-label">Admissions (2022)</div><div class="indicator-value" style="color:#ef4444;font-size:19px;"><?= fr_int($national['admissions_hopitaux_2022'] ?? 1043904) ?></div><div class="indicator-trend" style="color:#64748b;">Hôpitaux publics</div></div>
</div>
<div style="font-size:12px;color:#94a3b8;margin:-10px 0 16px;">Source : <?= htmlspecialchars($hcpSource) ?></div>

<!-- ===== ÉVOLUTION TEMPORELLE (séries HCP 2015-2023) ===== -->
<?php if (!empty($series['annees'])): ?>
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">📈 Évolution des indicateurs nationaux (<?= $series['annees'][0] ?> – <?= end($series['annees']) ?>)</div>
    <span class="badge badge-info" style="align-self:center;">Séries HCP — Annuaires 2017-2024 · EMO T2 2026</span>
  </div>
  <div class="card-body">
    <div class="dashboard-grid">
      <div>
        <div style="font-size:13px;font-weight:600;color:#334155;margin-bottom:6px;">Chômage &amp; activité (15 ans et plus)</div>
        <div style="position:relative;height:240px;"><canvas id="evoEmploiChart"></canvas></div>
      </div>
      <div>
        <div style="font-size:13px;font-weight:600;color:#334155;margin-bottom:6px;">Produit intérieur brut (prix courants)</div>
        <div style="position:relative;height:240px;"><canvas id="evoPibChart"></canvas></div>
      </div>
    </div>
  </div>
  <?php
    // Dernière valeur PIB non nulle (T2 2026 = null car trimestriel)
    $pibVals = array_values(array_filter($series['pib_prix_courants_mdh']['valeurs'], fn($v) => $v !== null));
    $pibFirst = $pibVals[0] ?? 1;
    $pibLast  = end($pibVals) ?: $pibFirst;
    $pibHausse = $pibFirst ? ($pibLast/$pibFirst - 1) * 100 : 0;
  ?>
  <div class="map-update-info" style="padding:10px 16px;">ℹ️ Le taux de chômage national est passé de <?= fr_pct($series['taux_chomage']['valeurs'][0]) ?> en <?= $series['annees'][0] ?> à <?= fr_pct(end($series['taux_chomage']['valeurs'])) ?> en <?= end($series['annees']) ?>. PIB en hausse de <?= number_format($pibHausse,0,',',' ') ?>% (2015 → 2025).</div>
</div>

<!-- Évolution de la population (recensements) -->
<?php if (!empty($popRec['annees'])): ?>
<div class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title">👥 Évolution de la population du Maroc (RGPH <?= min($popRec['annees']) ?>–<?= max($popRec['annees']) ?>)</div>
    <span class="badge badge-info" style="align-self:center;">Recensements HCP</span>
  </div>
  <div class="card-body"><div style="position:relative;height:180px;"><canvas id="evoPopChart"></canvas></div></div>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Graphiques -->
<div class="dashboard-grid" style="margin-bottom:16px;">
  <div class="card">
    <div class="card-header"><div class="card-title">📊 Taux de chômage par région (T2 2026)</div></div>
    <div class="card-body"><canvas id="chomageChart" height="240"></canvas></div>
  </div>
  <div class="card">
    <div class="card-header"><div class="card-title">👨‍👩‍👧‍👦 Population par région (RGPH 2024)</div></div>
    <div class="card-body"><canvas id="popChart" height="240"></canvas></div>
  </div>
</div>

<!-- Tableau des indicateurs par région -->
<div class="card">
  <div class="card-header">
    <div class="card-title">📋 Tableau de bord des indicateurs régionaux</div>
    <div style="display:flex;gap:10px;">
      <span class="badge badge-info" style="align-self:center;">Source : <?= htmlspecialchars($hcpSource) ?></span>
    </div>
  </div>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Région</th>
          <th>Population (2024)</th>
          <th>Chômage (T2 2026)</th>
          <th>Activité (T2 2026)</th>
          <th>Hôpitaux (2022)</th>
          <th>Lits (2022)</th>
          <th>Admissions (2022)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($regionsHCP as $r):
          $e26 = $r['emploi_2026_t2'] ?? [];
          $ch = $e26['taux_chomage'] ?? ($r['emploi']['taux_chomage_2023'] ?? null);
          $ac = $e26['taux_activite'] ?? ($r['emploi']['taux_activite_2023'] ?? null);
        ?>
        <tr>
          <td><strong><?= htmlspecialchars($r['nom']) ?></strong></td>
          <td><?= fr_int($r['population']['total_2024'] ?? 0) ?></td>
          <td><span style="color:<?= ($ch===null?'#64748b':($ch>12?'#ef4444':($ch>9?'#f59e0b':'#10b981'))) ?>;font-weight:600;"><?= $ch!==null?fr_pct($ch):'n.s.' ?></span></td>
          <td><?= $ac!==null?fr_pct($ac):'—' ?></td>
          <td><?= fr_int($r['sante']['hopitaux_publics_2022'] ?? 0) ?></td>
          <td><?= fr_int($r['sante']['lits_fonctionnels_2022'] ?? 0) ?></td>
          <td><?= fr_int($r['sante']['admissions_2022'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="map-update-info" style="padding:10px 16px;">ℹ️ Données emploi issues de l'Enquête sur la main-d'œuvre (EMO) du HCP, 2ᵉ trimestre 2026. « n.s. » = taux non significatif (faible effectif, Dakhla-Oued Ed-Dahab). Données santé (hôpitaux, lits, admissions) : Ministère de la Santé 2022.</div>
</div>

<script>
<?php
// Préparer les séries à partir des données réelles (triées par chômage décroissant)
$rowsChomage = [];
$rowsPop = [];
foreach ($regionsHCP as $r) {
  $rowsChomage[] = ['nom' => $r['nom'], 'v' => ($r['emploi_2026_t2']['taux_chomage'] ?? $r['emploi']['taux_chomage_2023'] ?? 0)];
  $rowsPop[]     = ['nom' => $r['nom'], 'v' => $r['population']['total_2024'] ?? 0];
}
usort($rowsChomage, fn($a,$b) => $b['v'] <=> $a['v']);
usort($rowsPop, fn($a,$b) => $b['v'] <=> $a['v']);
?>
const chomageRows = <?= json_encode($rowsChomage, JSON_UNESCAPED_UNICODE) ?>;
const popRows     = <?= json_encode($rowsPop, JSON_UNESCAPED_UNICODE) ?>;

// ===== Séries temporelles nationales (HCP) =====
const series   = <?= json_encode($series, JSON_UNESCAPED_UNICODE) ?>;
const popRec   = <?= json_encode($popRec, JSON_UNESCAPED_UNICODE) ?>;

if (series && series.annees) {
  // Évolution chômage + activité (axe %)
  new Chart(document.getElementById('evoEmploiChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: series.annees,
      datasets: [
        { label: 'Taux de chômage (%)', data: series.taux_chomage.valeurs, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,.08)', tension:.3, fill:true, pointRadius:4, pointBackgroundColor:'#ef4444' },
        { label: "Taux d'activité (%)", data: series.taux_activite.valeurs, borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,.06)', tension:.3, fill:true, pointRadius:4, pointBackgroundColor:'#2563eb' }
      ]
    },
    options: {
      responsive:true, maintainAspectRatio:false,
      plugins:{ legend:{position:'bottom', labels:{boxWidth:12,font:{size:11}}}, tooltip:{callbacks:{label:c=>' '+c.dataset.label+': '+c.raw+'%'}} },
      scales:{ y:{ ticks:{callback:v=>v+'%'}, grid:{color:'rgba(0,0,0,.04)'} }, x:{grid:{display:false}} }
    }
  });

  // Évolution PIB (Milliards DH)
  new Chart(document.getElementById('evoPibChart').getContext('2d'), {
    type: 'bar',
    data: {
      labels: series.annees,
      datasets: [{ label:'PIB (Milliards DH)', data: series.pib_prix_courants_mdh.valeurs.map(v=> v==null ? null : v/1000), backgroundColor:'#10b981', borderRadius:6 }]
    },
    options: {
      responsive:true, maintainAspectRatio:false,
      plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>' '+Number(c.raw).toLocaleString('fr-FR',{maximumFractionDigits:0})+' Mds DH'}} },
      scales:{ y:{ ticks:{callback:v=>v+' Mds'}, grid:{color:'rgba(0,0,0,.04)'} }, x:{grid:{display:false}} }
    }
  });
}

// Évolution population (recensements)
if (popRec && popRec.annees && document.getElementById('evoPopChart')) {
  new Chart(document.getElementById('evoPopChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: popRec.annees,
      datasets: [{ label:'Population légale', data: popRec.valeurs, borderColor:'#8b5cf6', backgroundColor:'rgba(139,92,246,.10)', tension:.3, fill:true, pointRadius:5, pointBackgroundColor:'#8b5cf6' }]
    },
    options: {
      responsive:true, maintainAspectRatio:false,
      plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>' '+Number(c.raw).toLocaleString('fr-FR')+' hab.'}} },
      scales:{ y:{ ticks:{callback:v=>(v/1000000).toLocaleString('fr-FR')+' M'}, grid:{color:'rgba(0,0,0,.04)'} }, x:{grid:{display:false}} }
    }
  });
}

// Chômage par région (2023)
new Chart(document.getElementById('chomageChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: chomageRows.map(r => r.nom),
    datasets: [{
      label: 'Taux de chômage T2 2026 (%)',
      data: chomageRows.map(r => r.v),
      backgroundColor: ctx => { const v = ctx.raw; return v > 15 ? '#fca5a5' : (v > 11 ? '#fcd34d' : '#6ee7b7'); },
      borderRadius: 6,
    }]
  },
  options: {
    indexAxis: 'y',
    responsive: true, maintainAspectRatio: false,
    plugins: { legend:{display:false}, tooltip:{callbacks:{label: c => ' ' + c.raw + '%'}} },
    scales: { x: { min:0, max:22, ticks:{callback:v=>v+'%'}, grid:{color:'rgba(0,0,0,.04)'} }, y:{grid:{display:false}, ticks:{font:{size:10}}} }
  }
});

// Population par région (RGPH 2024)
new Chart(document.getElementById('popChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: popRows.map(r => r.nom),
    datasets: [{
      label: 'Population 2024',
      data: popRows.map(r => r.v),
      backgroundColor: '#3b82f6',
      borderRadius: 6,
    }]
  },
  options: {
    indexAxis: 'y',
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}, tooltip:{callbacks:{label: c => ' ' + Number(c.raw).toLocaleString('fr-FR') + ' hab.'}}},
    scales:{x:{ticks:{callback:v=> (v/1000000).toLocaleString('fr-FR')+' M'}, grid:{color:'rgba(0,0,0,.04)'}}, y:{grid:{display:false}, ticks:{font:{size:10}}}}
  }
});
</script>
<?php include 'includes/footer.php'; ?>
