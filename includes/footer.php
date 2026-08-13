    </div><!-- end page-content -->
  </div><!-- end main-content -->
</div><!-- end app-layout -->

<!-- ===== MODAL IA EMBARQUÉE (Charte · Principe 7 : IA embarquée) ===== -->
<div class="ai-modal-overlay" id="aiModalOverlay" onclick="if(event.target===this)closeAiModal()">
  <div class="ai-modal" role="dialog" aria-modal="true">
    <div class="ai-modal-head">
      <div class="ai-modal-title"><span>✨</span> <span id="aiModalTitle">Assistant d'analyse ATLASIA</span></div>
      <button class="ai-modal-close" onclick="closeAiModal()" aria-label="Fermer">×</button>
    </div>
    <div class="ai-modal-body" id="aiModalBody"></div>
  </div>
</div>

<script>
/* =====================================================================
   IA EMBARQUÉE ATLASIA — grounded sur les données disponibles
   Chaque page enregistre ses analyses dans window.ATLASIA_AI[cle].
   Une analyse = { title, interpretation, tendance, recommandation, source }
   Conforme à la Charte : réponses fondées uniquement sur les données
   de la plateforme, avec mention explicite des sources.
   ===================================================================== */
window.ATLASIA_AI = window.ATLASIA_AI || {};

function _aiRender(a){
  var html = '';
  if(a.interpretation){ html += '<h4>🔎 Interprétation potentielle</h4><p>'+a.interpretation+'</p>'; }
  if(a.tendance){ html += '<h4>📈 Tendance prospective</h4><p>'+a.tendance+'</p>'; }
  if(a.recommandation){ html += '<h4>🎯 Recommandation opérationnelle</h4><p>'+a.recommandation+'</p>'; }
  if(a.resume){ html += '<h4>📝 Synthèse</h4><p>'+a.resume+'</p>'; }
  html += '<p class="ai-source">'+(a.source ? ('Source : '+a.source+'. ') : '')+
          'Lecture analytique fondée sur les données disponibles dans la plateforme — non une vérité absolue.</p>';
  return html;
}

var _aiCurrent = null; // { key, title, a }

function _aiFooterHTML(){
  return '<div class="ai-ask">'+
    '<textarea id="aiQuestion" class="ai-ask-input" rows="2" '+
      'placeholder="Posez une question sur ces données (optionnel)…"></textarea>'+
    '<button class="ai-btn" onclick="atlasiaAsk()"><span class="ai-spark">✨</span> Demander à l\'IA</button>'+
  '</div>';
}

// Appelle le proxy IA générative (api/ai.php). Repli local géré côté serveur.
function _aiCall(payload, body){
  fetch('api/ai.php', {
    method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify(payload)
  }).then(function(r){ return r.json(); })
    .then(function(res){
      var badge = res.mode === 'ai'
        ? '<span class="ai-mode ai-mode-live">✨ IA générative</span>'
        : '<span class="ai-mode ai-mode-local">🔒 Analyse locale</span>';
      body.innerHTML = badge + (res.text || '') + _aiFooterHTML();
    })
    .catch(function(){
      // réseau totalement indisponible => rendu local pur
      body.innerHTML = (_aiCurrent && _aiCurrent.a ? _aiRender(_aiCurrent.a)
        : '<p>Service IA indisponible.</p>') + _aiFooterHTML();
    });
}

function atlasiaAI(key, title){
  var overlay = document.getElementById('aiModalOverlay');
  var body = document.getElementById('aiModalBody');
  var titleEl = document.getElementById('aiModalTitle');
  var a = window.ATLASIA_AI[key] || null;
  _aiCurrent = { key:key, title:(a && a.title) ? a.title : (title||''), a:a };
  titleEl.textContent = _aiCurrent.title || "Assistant d'analyse ATLASIA";
  overlay.classList.add('open');
  body.innerHTML = '<div class="ai-loading"><span class="ai-spinner"></span> Analyse des données disponibles…</div>';
  _aiCall({ key:key, title:_aiCurrent.title, context:(a||{}) }, body);
}

// Question libre de l'utilisateur sur le même élément (grounding conservé).
function atlasiaAsk(){
  var q = (document.getElementById('aiQuestion')||{}).value || '';
  if(!q.trim()){ return; }
  var body = document.getElementById('aiModalBody');
  body.innerHTML = '<div class="ai-loading"><span class="ai-spinner"></span> L\'IA analyse votre question…</div>';
  var a = _aiCurrent ? _aiCurrent.a : null;
  _aiCall({ key:(_aiCurrent&&_aiCurrent.key)||'', title:(_aiCurrent&&_aiCurrent.title)||'',
            context:(a||{}), question:q }, body);
}
function closeAiModal(){ document.getElementById('aiModalOverlay').classList.remove('open'); }
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeAiModal(); });
</script>

<!-- Scripts globaux -->
<script>
// Init Lucide icons
if (typeof lucide !== 'undefined') lucide.createIcons();

// Mobile menu toggle
function initMobileMenu() {
  const toggle = document.getElementById('menuToggle');
  if (window.innerWidth <= 900) {
    if (toggle) toggle.style.display = 'flex';
  } else {
    if (toggle) toggle.style.display = 'none';
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.remove('open');
  }
}
window.addEventListener('resize', initMobileMenu);
initMobileMenu();

// Close sidebar on outside click (mobile)
document.addEventListener('click', function(e) {
  const sidebar = document.getElementById('sidebar');
  const toggle = document.getElementById('menuToggle');
  if (window.innerWidth <= 900 && sidebar && !sidebar.contains(e.target) && e.target !== toggle) {
    sidebar.classList.remove('open');
  }
});

// Search global
document.getElementById('globalSearch')?.addEventListener('input', function() {
  const q = this.value.toLowerCase();
  if (q.length >= 3) {
    console.log('Recherche:', q);
  }
});

// Sidebar search
document.getElementById('sidebarSearch')?.addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.nav-item').forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(q) ? '' : 'none';
  });
});

// Animate cards on load
document.querySelectorAll('.stat-card, .card, .project-card, .file-card').forEach((el, i) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(12px)';
  setTimeout(() => {
    el.style.transition = 'opacity .3s ease, transform .3s ease';
    el.style.opacity = '1';
    el.style.transform = 'translateY(0)';
  }, i * 60);
});
</script>

<?php if (isset($extraJS)): ?>
<script><?= $extraJS ?></script>
<?php endif; ?>

</body>
</html>
