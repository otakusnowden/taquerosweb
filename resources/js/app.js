import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

Alpine.plugin(focus);
Alpine.plugin(collapse);
window.Alpine = Alpine;

/**
 * Global "Contratar" modal store.
 * Any element can open it with: @click="$store.contratar.open()"
 */
Alpine.store('contratar', {
    isOpen: false,
    open() {
        this.isOpen = true;
        document.documentElement.style.overflow = 'hidden';
    },
    close() {
        this.isOpen = false;
        document.documentElement.style.overflow = '';
    },
});

Alpine.start();

/**
 * Reveal-on-scroll.
 * Adds `.is-visible` to `.reveal` elements as they enter the viewport.
 * Falls back to showing everything if IntersectionObserver is unavailable
 * or the user prefers reduced motion.
 */
function initReveal() {
    const elements = document.querySelectorAll('.reveal');
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReduced || !('IntersectionObserver' in window)) {
        elements.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.1 }
    );

    elements.forEach((el) => observer.observe(el));
}

/**
 * Prefetch internal links on hover/focus to make navigation feel instant.
 */
function initPrefetch() {
    const prefetched = new Set();
    const prefetch = (url) => {
        if (prefetched.has(url)) return;
        prefetched.add(url);
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = url;
        document.head.appendChild(link);
    };

    document.addEventListener('mouseover', (e) => {
        const a = e.target.closest('a[href]');
        if (!a) return;
        if (a.origin === window.location.origin && !a.hasAttribute('data-no-prefetch')) {
            prefetch(a.href);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initPrefetch();
});
