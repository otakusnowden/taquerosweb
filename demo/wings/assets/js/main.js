/* Hot Wings — interacciones del sitio (JS vanilla) */
(function () {
    'use strict';

    /* ---------- Header sticky: sombra al hacer scroll ---------- */
    const header = document.getElementById('siteHeader');
    if (header) {
        const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 12);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* ---------- Menú hamburguesa ---------- */
    const toggle = document.getElementById('navToggle');
    const nav = document.getElementById('primaryNav');
    if (toggle && nav) {
        const backdrop = document.createElement('div');
        backdrop.className = 'nav-backdrop';
        document.body.appendChild(backdrop);

        const setOpen = (open) => {
            nav.classList.toggle('open', open);
            toggle.classList.toggle('open', open);
            backdrop.classList.toggle('show', open);
            document.body.classList.toggle('nav-open', open);
            toggle.setAttribute('aria-expanded', String(open));
            toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
        };

        toggle.addEventListener('click', () => setOpen(!nav.classList.contains('open')));
        backdrop.addEventListener('click', () => setOpen(false));
        nav.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => setOpen(false)));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setOpen(false); });
    }

    /* ---------- Carrusel reutilizable ---------- */
    function initCarousel(root, opts) {
        opts = opts || {};
        const track = root.querySelector('[data-track]');
        const slides = Array.from(root.querySelectorAll('[data-slide]'));
        if (!track || slides.length === 0) return;

        const dotsWrap = root.querySelector('[data-dots]');
        const prevBtn = root.querySelector('[data-prev]');
        const nextBtn = root.querySelector('[data-next]');
        let index = 0;
        let timer = null;

        const dots = [];
        if (dotsWrap) {
            slides.forEach((_, i) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.setAttribute('aria-label', 'Ir a la promoción ' + (i + 1));
                b.addEventListener('click', () => { go(i); restart(); });
                dotsWrap.appendChild(b);
                dots.push(b);
            });
        }

        function go(i) {
            index = (i + slides.length) % slides.length;
            track.style.transform = 'translateX(' + (-index * 100) + '%)';
            dots.forEach((d, di) => d.classList.toggle('active', di === index));
        }
        const next = () => go(index + 1);
        const prev = () => go(index - 1);

        if (nextBtn) nextBtn.addEventListener('click', () => { next(); restart(); });
        if (prevBtn) prevBtn.addEventListener('click', () => { prev(); restart(); });

        function start() {
            if (opts.interval && slides.length > 1) timer = setInterval(next, opts.interval);
        }
        function restart() { clearInterval(timer); start(); }

        // Pausa al pasar el mouse
        root.addEventListener('mouseenter', () => clearInterval(timer));
        root.addEventListener('mouseleave', restart);

        // Soporte táctil (swipe)
        let startX = 0, dx = 0, dragging = false;
        const onStart = (x) => { startX = x; dx = 0; dragging = true; clearInterval(timer); };
        const onMove = (x) => { if (dragging) dx = x - startX; };
        const onEnd = () => {
            if (!dragging) return;
            dragging = false;
            if (Math.abs(dx) > 45) (dx < 0 ? next : prev)();
            restart();
        };
        track.addEventListener('touchstart', (e) => onStart(e.touches[0].clientX), { passive: true });
        track.addEventListener('touchmove', (e) => onMove(e.touches[0].clientX), { passive: true });
        track.addEventListener('touchend', onEnd);

        go(0);
        start();
    }

    document.querySelectorAll('[data-carousel]').forEach((el) => {
        initCarousel(el, { interval: parseInt(el.dataset.interval, 10) || 0 });
    });

    /* ---------- Lightbox para imagen de menú ---------- */
    const zoomImg = document.querySelector('[data-zoom]');
    if (zoomImg) {
        const lb = document.createElement('div');
        lb.className = 'lightbox';
        lb.innerHTML = '<button class="lightbox-close" aria-label="Cerrar">&times;</button><img alt="Menú Hot Wings">';
        document.body.appendChild(lb);
        const lbImg = lb.querySelector('img');
        const open = () => { lbImg.src = zoomImg.dataset.full || zoomImg.src; lb.classList.add('open'); document.body.style.overflow = 'hidden'; };
        const close = () => { lb.classList.remove('open'); document.body.style.overflow = ''; };
        zoomImg.addEventListener('click', open);
        lb.addEventListener('click', (e) => { if (e.target === lb || e.target.classList.contains('lightbox-close')) close(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    }

    /* ---------- Reveal on scroll ---------- */
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((en) => {
                if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
            });
        }, { threshold: 0 });
        reveals.forEach((el) => io.observe(el));
    } else {
        reveals.forEach((el) => el.classList.add('in'));
    }

    /* ---------- Formulario de reservación → WhatsApp ---------- */
    const form = document.getElementById('reservaForm');
    if (form) {
        const phone = form.dataset.phone || '';
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            let ok = true;
            form.querySelectorAll('[required]').forEach((el) => {
                const field = el.closest('.field');
                const valid = el.value.trim() !== '';
                if (field) field.classList.toggle('invalid', !valid);
                if (!valid && ok) { ok = false; el.focus(); }
            });
            if (!ok) return;

            const v = (id) => (form.querySelector('#' + id)?.value || '').trim();
            const msg =
                'Hola, deseo realizar una reservación.\n\n' +
                'Nombre:\n' + v('nombre') + '\n\n' +
                'Teléfono:\n' + v('telefono') + '\n\n' +
                'Fecha:\n' + v('fecha') + '\n\n' +
                'Hora:\n' + v('hora') + '\n\n' +
                'Personas:\n' + v('personas') + '\n\n' +
                'Área:\n' + v('area') + '\n\n' +
                'Comentarios:\n' + (v('comentarios') || 'Sin comentarios');

            window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(msg), '_blank');

            const okBox = document.getElementById('reservaOk');
            if (okBox) { okBox.style.display = 'block'; okBox.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
        });

        // Limpia el estado de error al escribir
        form.querySelectorAll('input, select, textarea').forEach((el) => {
            el.addEventListener('input', () => el.closest('.field')?.classList.remove('invalid'));
        });
    }
})();
