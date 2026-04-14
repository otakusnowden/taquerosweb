/* ================================================================
   WebAdmin — main.js
   ================================================================ */

'use strict';

// ── Sidebar toggle ───────────────────────────────────────────
(function () {
  const sidebar  = document.getElementById('sidebar');
  const mainContent = document.getElementById('main-content');
  const toggle   = document.getElementById('sidebarToggle');

  if (!sidebar || !toggle) return;

  const KEY = 'sidebar_collapsed';
  const isMobile = () => window.innerWidth < 768;

  function applyState(collapsed) {
    if (isMobile()) {
      sidebar.classList.toggle('open', !collapsed);
    } else {
      sidebar.classList.toggle('collapsed', collapsed);
      mainContent?.classList.toggle('expanded', collapsed);
    }
  }

  // Restore saved state (desktop only)
  const saved = localStorage.getItem(KEY);
  if (!isMobile() && saved === '1') applyState(true);

  toggle.addEventListener('click', () => {
    if (isMobile()) {
      const isOpen = sidebar.classList.contains('open');
      applyState(isOpen); // toggle
    } else {
      const collapsed = !sidebar.classList.contains('collapsed');
      applyState(collapsed);
      localStorage.setItem(KEY, collapsed ? '1' : '0');
    }
  });

  // Close sidebar on mobile when clicking outside
  document.addEventListener('click', (e) => {
    if (!isMobile()) return;
    if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
      applyState(true);
    }
  });
})();

// ── Confirm delete dialogs ───────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-confirm]').forEach((el) => {
    el.addEventListener('click', (e) => {
      if (!confirm(el.dataset.confirm || '¿Confirmas esta acción?')) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  });

  // Forms with data-confirm
  document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (e) => {
      if (!confirm(form.dataset.confirm || '¿Confirmas esta acción?')) {
        e.preventDefault();
      }
    });
  });
});

// ── Auto-dismiss alerts ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.alert:not(.alert-permanent)').forEach((alert) => {
    setTimeout(() => {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      bsAlert?.close();
    }, 5000);
  });
});

// ── Currency formatter helper ────────────────────────────────
function formatCurrency(value) {
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
    minimumFractionDigits: 2,
  }).format(value);
}
