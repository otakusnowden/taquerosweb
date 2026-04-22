/* ═══════════════════════════════════════════════════════════════
   MAREA NOIR — app.js
   Módulos: navegación · reveal · galería · menú · reservas
   ═══════════════════════════════════════════════════════════════ */

// ─── CONFIGURACIÓN EDITABLE ────────────────────────────────────
const CONFIG = {
  whatsappNumber: '5215662866353',     // ← Cambia este número
  businessName:   'Marea Noir',
  currency:       'MXN'
};

// ══════════════════════════════════════════════════════════════
// 1. NAVEGACIÓN (scroll + mobile)
// ══════════════════════════════════════════════════════════════
const NavModule = {
  init() {
    this.nav = document.getElementById('mainNav');
    this.toggle = document.getElementById('navToggle');
    this.mobile = document.getElementById('navMobile');
    this.mobileClose = document.getElementById('navMobileClose');

    if (this.nav) {
      window.addEventListener('scroll', () => this.handleScroll());
      this.handleScroll();
    }
    if (this.toggle && this.mobile) {
      this.toggle.addEventListener('click', () => this.mobile.classList.add('open'));
      this.mobileClose?.addEventListener('click', () => this.mobile.classList.remove('open'));
      this.mobile.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => this.mobile.classList.remove('open'));
      });
    }

    // Smooth scroll para anchors internos
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  },

  handleScroll() {
    this.nav.classList.toggle('scrolled', window.scrollY > 60);
  }
};

// ══════════════════════════════════════════════════════════════
// 2. REVEAL ON SCROLL
// ══════════════════════════════════════════════════════════════
const RevealModule = {
  init() {
    const elements = document.querySelectorAll('.reveal');
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

    elements.forEach(el => observer.observe(el));
  }
};

// ══════════════════════════════════════════════════════════════
// 3. FORMULARIO DE RESERVAS → WhatsApp
// ══════════════════════════════════════════════════════════════
const ReserveModule = {
  init() {
    const form = document.getElementById('reserveForm');
    if (!form) return;

    // Fecha mínima = hoy
    const dateField = form.querySelector('[name="fecha"]');
    if (dateField) {
      const today = new Date().toISOString().split('T')[0];
      dateField.min = today;
    }

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      this.handleSubmit(form);
    });
  },

  handleSubmit(form) {
    const data = new FormData(form);
    const nombre = data.get('nombre')?.trim();
    const telefono = data.get('telefono')?.trim();
    const fecha = data.get('fecha')?.trim();
    const hora = data.get('hora')?.trim();
    const personas = data.get('personas')?.trim();
    const ocasion = data.get('ocasion')?.trim() || 'No especificada';

    // Validación mínima
    if (!nombre || !telefono || !fecha || !hora || !personas) {
      this.showToast('Completa todos los campos obligatorios', 'error');
      return;
    }

    // Formatea fecha bonita
    const fechaFmt = new Date(fecha + 'T00:00:00').toLocaleDateString('es-MX', {
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });

    // Arma mensaje
    const msg = `¡Hola ${CONFIG.businessName}! ✨\n\n` +
      `Quisiera reservar una mesa:\n\n` +
      `👤 Nombre: ${nombre}\n` +
      `📞 Teléfono: ${telefono}\n` +
      `📅 Fecha: ${fechaFmt}\n` +
      `🕐 Hora: ${hora}\n` +
      `👥 Personas: ${personas}\n` +
      `🎉 Ocasión: ${ocasion}\n\n` +
      `Espero su confirmación, gracias.`;

    const url = `https://wa.me/${CONFIG.whatsappNumber}?text=${encodeURIComponent(msg)}`;

    // Feedback visual + redirección
    this.showToast('Abriendo WhatsApp...', 'success');
    setTimeout(() => {
      window.open(url, '_blank');
      form.reset();
    }, 900);
  },

  showToast(message, type = 'success') {
    let toast = document.getElementById('toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toast';
      toast.className = 'toast';
      document.body.appendChild(toast);
    }
    const icon = type === 'success' ? '✦' : '⚠';
    toast.innerHTML = `<span class="toast-icon">${icon}</span> ${message}`;
    toast.classList.add('visible');
    setTimeout(() => toast.classList.remove('visible'), 3200);
  }
};

// ══════════════════════════════════════════════════════════════
// 4. GALERÍA (galeria.html) — carga dinámica + lightbox
// ══════════════════════════════════════════════════════════════
const GalleryModule = {
  items: [],
  currentIndex: 0,

  async init() {
    this.grid = document.getElementById('galleryGrid');
    this.lightbox = document.getElementById('lightbox');
    if (!this.grid) return;

    try {
      const response = await fetch('data/galeria.json');
      const data = await response.json();
      this.items = data;
      this.render();
      this.initFilters();
      this.initLightbox();
    } catch (err) {
      console.error('Error cargando galería:', err);
      this.grid.innerHTML = '<p style="color:var(--text-muted);grid-column:1/-1;text-align:center">No se pudo cargar la galería.</p>';
    }
  },

  render(filter = 'all') {
    const filtered = filter === 'all'
      ? this.items
      : this.items.filter(item => item.categoria === filter);

    this.grid.innerHTML = filtered.map((item, i) => `
      <div class="gallery-card reveal" data-index="${this.items.indexOf(item)}">
        <img src="${item.imagen}" alt="${item.titulo}" loading="lazy">
        <div class="gallery-card-zoom">⤢</div>
        <div class="gallery-card-overlay">
          <div>
            <div class="gallery-card-cat">${item.categoria}</div>
            <div class="gallery-card-title">${item.titulo}</div>
          </div>
        </div>
      </div>
    `).join('');

    // Re-activa reveal
    RevealModule.init();

    // Click handler
    this.grid.querySelectorAll('.gallery-card').forEach(card => {
      card.addEventListener('click', () => {
        const idx = parseInt(card.dataset.index);
        this.openLightbox(idx);
      });
    });
  },

  initFilters() {
    const filters = document.querySelectorAll('.filter-btn');
    if (!filters.length) return;

    // Construir categorías únicas
    const categories = ['all', ...new Set(this.items.map(i => i.categoria))];

    filters.forEach(btn => {
      btn.addEventListener('click', () => {
        filters.forEach(f => f.classList.remove('active'));
        btn.classList.add('active');
        this.render(btn.dataset.filter);
      });
    });
  },

  initLightbox() {
    if (!this.lightbox) return;
    this.lightbox.querySelector('.lightbox-close')?.addEventListener('click', () => this.closeLightbox());
    this.lightbox.addEventListener('click', (e) => {
      if (e.target === this.lightbox) this.closeLightbox();
    });
    document.addEventListener('keydown', (e) => {
      if (!this.lightbox.classList.contains('open')) return;
      if (e.key === 'Escape') this.closeLightbox();
      if (e.key === 'ArrowRight') this.navigate(1);
      if (e.key === 'ArrowLeft') this.navigate(-1);
    });
    this.lightbox.querySelector('[data-nav="next"]')?.addEventListener('click', () => this.navigate(1));
    this.lightbox.querySelector('[data-nav="prev"]')?.addEventListener('click', () => this.navigate(-1));
  },

  openLightbox(index) {
    this.currentIndex = index;
    this.updateLightbox();
    this.lightbox.classList.add('open');
    document.body.style.overflow = 'hidden';
  },

  closeLightbox() {
    this.lightbox.classList.remove('open');
    document.body.style.overflow = '';
  },

  navigate(dir) {
    this.currentIndex = (this.currentIndex + dir + this.items.length) % this.items.length;
    this.updateLightbox();
  },

  updateLightbox() {
    const item = this.items[this.currentIndex];
    this.lightbox.querySelector('.lightbox-image img').src = item.imagen;
    this.lightbox.querySelector('.lightbox-image img').alt = item.titulo;
    this.lightbox.querySelector('.lightbox-cat').textContent = item.categoria;
    this.lightbox.querySelector('.lightbox-title').innerHTML = item.titulo;
    this.lightbox.querySelector('.lightbox-desc').textContent = item.descripcion;
  }
};

// ══════════════════════════════════════════════════════════════
// 5. MENÚ (menu.html) — carga dinámica por categorías
// ══════════════════════════════════════════════════════════════
const MenuModule = {
  async init() {
    this.container = document.getElementById('menuContainer');
    this.nav = document.getElementById('menuNav');
    if (!this.container) return;

    try {
      const response = await fetch('data/menu.json');
      const data = await response.json();
      this.render(data);
      this.initScrollSpy();
    } catch (err) {
      console.error('Error cargando menú:', err);
      this.container.innerHTML = '<p style="color:var(--text-muted);text-align:center">No se pudo cargar el menú.</p>';
    }
  },

  render(categorias) {
    // Navegación de categorías
    if (this.nav) {
      this.nav.innerHTML = categorias.map((cat, i) =>
        `<a href="#cat-${this.slug(cat.nombre)}" class="menu-nav-link${i === 0 ? ' active' : ''}">${cat.nombre}</a>`
      ).join('');
    }

    // Render categorías
    this.container.innerHTML = categorias.map(cat => `
      <div class="menu-category reveal" id="cat-${this.slug(cat.nombre)}">
        <div class="menu-category-header">
          <h2 class="menu-category-title">${cat.nombre}</h2>
          <div class="menu-category-desc">${cat.descripcion || ''}</div>
          <div class="divider-ornament"><span class="diamond"></span></div>
        </div>
        <div class="menu-dishes-grid">
          ${cat.platillos.map(dish => this.renderDish(dish)).join('')}
        </div>
      </div>
    `).join('');

    RevealModule.init();
  },

  renderDish(dish) {
    const star = dish.destacado ? ' <span class="star">★</span>' : '';
    const subtitulo = dish.subtitulo ? `<em>${dish.subtitulo}</em>` : '';
    const tags = (dish.tags || []).map(t => `<span class="dish-tag">${t}</span>`).join('');
    const tagsHTML = tags ? `<div class="menu-page-dish-tags">${tags}</div>` : '';

    return `
      <div class="menu-page-dish">
        <div class="menu-page-dish-head">
          <div class="menu-page-dish-name">${dish.nombre}${star} ${subtitulo}</div>
          <div class="menu-page-dish-price">$${dish.precio}</div>
        </div>
        <div class="menu-page-dish-desc">${dish.descripcion}</div>
        ${tagsHTML}
      </div>
    `;
  },

  slug(str) {
    return str.toLowerCase()
      .replace(/[áàä]/g, 'a').replace(/[éèë]/g, 'e').replace(/[íìï]/g, 'i')
      .replace(/[óòö]/g, 'o').replace(/[úùü]/g, 'u').replace(/ñ/g, 'n')
      .replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  },

  initScrollSpy() {
    const links = document.querySelectorAll('.menu-nav-link');
    const sections = document.querySelectorAll('.menu-category');
    if (!links.length || !sections.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          links.forEach(l => l.classList.remove('active'));
          const activeLink = document.querySelector(`.menu-nav-link[href="#${entry.target.id}"]`);
          if (activeLink) activeLink.classList.add('active');
        }
      });
    }, { threshold: 0.3, rootMargin: '-120px 0px -50% 0px' });

    sections.forEach(s => observer.observe(s));
  }
};

// ══════════════════════════════════════════════════════════════
// 6. PARALLAX SUAVE EN HERO (opcional, ligero)
// ══════════════════════════════════════════════════════════════
const ParallaxModule = {
  init() {
    const heroBg = document.querySelector('.hero-bg');
    if (!heroBg) return;
    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          const scrolled = window.scrollY;
          if (scrolled < window.innerHeight) {
            heroBg.style.transform = `translateY(${scrolled * 0.35}px) scale(${1 + scrolled * 0.0003})`;
          }
          ticking = false;
        });
        ticking = true;
      }
    });
  }
};

// ══════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  NavModule.init();
  RevealModule.init();
  ReserveModule.init();
  GalleryModule.init();
  MenuModule.init();
  ParallaxModule.init();

  // Update footer year
  document.querySelectorAll('[data-year]').forEach(el => {
    el.textContent = new Date().getFullYear();
  });
});
