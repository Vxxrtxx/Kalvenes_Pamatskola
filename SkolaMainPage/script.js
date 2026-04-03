function applyTheme(theme, instant = false) {
    const html = document.documentElement;
    const toggle = document.querySelector('.dark-mode-toggle');

    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('site-theme', 'dark');
        if (toggle) {
            toggle.textContent = '🌙';
            toggle.setAttribute('aria-label', 'Switch to light mode');
        }
    } else {
        html.classList.remove('dark');
        localStorage.setItem('site-theme', 'light');
        if (toggle) {
            toggle.textContent = '☀️';
            toggle.setAttribute('aria-label', 'Switch to dark mode');
        }
    }

    if (!instant) {
        html.style.transition = 'background-color 0.5s ease, color 0.5s ease';
        window.setTimeout(() => {
            html.style.transition = '';
        }, 500);
    }
}

function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    applyTheme(isDark ? 'light' : 'dark');
}

function canShowOpeningOverlay() {
    const openingEnabled = document.body?.dataset.enableOpening === '1';
    const currentPath = window.location.pathname.toLowerCase();

    return openingEnabled && currentPath.endsWith('/skolamainpage/lapa.php');
}

function setupOpeningAnimation() {
    document.body.classList.add('is-preloading');
    document.documentElement.classList.add('is-preloading');

    const overlay = document.createElement('div');
    overlay.className = 'page-opening-overlay';
    overlay.innerHTML = `
        <div class="opening-title" aria-hidden="true">
            <span class="opening-line opening-line-one">Kalvenes</span>
            <span class="opening-line opening-line-two">Pamatskola</span>
        </div>
    `;
    document.body.appendChild(overlay);

    window.setTimeout(() => {
        document.body.classList.add('opening-complete');
        document.body.classList.remove('is-preloading');
        document.documentElement.classList.remove('is-preloading');
    }, 1400);

    window.setTimeout(() => {
        overlay.remove();
    }, 1900);
}

function setupRevealAnimations() {
    const revealTargets = [
        ...document.querySelectorAll('.hero-content, .aktualitate-card, .timeline-card')
    ];

    if (!('IntersectionObserver' in window)) {
        revealTargets.forEach((element) => element.classList.add('revealed'));
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('revealed');
            obs.unobserve(entry.target);
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px'
    });

    revealTargets.forEach((element) => {
        observer.observe(element);
    });
}

function setupMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');
    if (!menuToggle || !navbar) return;

    let resizeFrame = null;
    menuToggle.setAttribute('aria-expanded', 'false');

    const collapseMenu = () => {
        navbar.classList.remove('expanded');
        menuToggle.textContent = '☰';
        menuToggle.setAttribute('aria-expanded', 'false');
    };

    menuToggle.addEventListener('click', (event) => {
        event.preventDefault();
        const expanded = navbar.classList.toggle('expanded');
        menuToggle.textContent = expanded ? '✕' : '☰';
        menuToggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    });

    document.addEventListener('click', (event) => {
        if (navbar.contains(event.target)) return;
        collapseMenu();
    });

    navbar.querySelectorAll('.nav-left a, .nav-right a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth > 768) return;
            if (link.classList.contains('dropdown-trigger')) return;
            collapseMenu();
        });
    });

    window.addEventListener('resize', () => {
        if (resizeFrame !== null) return;

        resizeFrame = window.requestAnimationFrame(() => {
            resizeFrame = null;
            if (window.innerWidth > 768) {
                collapseMenu();
            }
        });
    });
}

function setupDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (!dropdown) return;

    const trigger = dropdown.querySelector('.dropdown-trigger');
    const content = dropdown.querySelector('.dropdown-content');
    if (!trigger || !content) return;

    const closeAll = () => {
        document.querySelectorAll('.dropdown-content').forEach((menu) => menu.classList.remove('show'));
    };

    trigger.addEventListener('click', (event) => {
        event.preventDefault();
        const isOpen = content.classList.contains('show');
        closeAll();
        if (!isOpen) {
            content.classList.add('show');
        }
    });

    dropdown.addEventListener('mouseenter', () => {
        if (window.innerWidth <= 768) return;
        content.classList.add('show');
    });

    dropdown.addEventListener('mouseleave', () => {
        if (window.innerWidth <= 768) return;
        content.classList.remove('show');
    });
}

function setupScrollUI() {
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    document.body.appendChild(progressBar);

    const backToTop = document.createElement('button');
    backToTop.className = 'back-to-top';
    backToTop.type = 'button';
    backToTop.ariaLabel = 'Back to top';
    backToTop.textContent = '↑';
    document.body.appendChild(backToTop);

    let scrollFrame = null;

    const updateScrollUI = () => {
        scrollFrame = null;
        const scrolled = window.scrollY;
        const maxHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = maxHeight > 0 ? (scrolled / maxHeight) * 100 : 0;
        progressBar.style.width = `${Math.min(100, Math.max(0, progress))}%`;

        if (scrolled > 250) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    };

    const onScroll = () => {
        if (scrollFrame !== null) return;
        scrollFrame = window.requestAnimationFrame(updateScrollUI);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    updateScrollUI();

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

function setupLazyImages() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    if (!lazyImages.length) return;

    if (!('IntersectionObserver' in window)) {
        lazyImages.forEach((img) => {
            img.src = img.dataset.src;
            img.classList.add('loaded');
        });
        return;
    }

    const imageObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.add('loaded');
            obs.unobserve(img);
        });
    }, { rootMargin: '80px' });

    lazyImages.forEach((img) => imageObserver.observe(img));
}

document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('site-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    const openingEnabled = canShowOpeningOverlay();

    applyTheme(initialTheme, true);

    if (openingEnabled) {
        setupOpeningAnimation();
    } else {
        document.body.classList.add('opening-complete');
        document.body.classList.remove('is-preloading');
        document.documentElement.classList.remove('is-preloading');

        const staleOverlay = document.querySelector('.page-opening-overlay');
        if (staleOverlay) {
            staleOverlay.remove();
        }
    }

    const themeToggle = document.querySelector('.dark-mode-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
        themeToggle.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleTheme();
            }
        });
    }

    setupMobileMenu();
    setupDropdown('SchoolDropdown');
    setupDropdown('AdmissionDropdown');
    if (openingEnabled) {
        setupRevealAnimations();
    }
    setupScrollUI();
    setupLazyImages();

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;

        document.querySelectorAll('.dropdown-content').forEach((content) => content.classList.remove('show'));
        const navbar = document.querySelector('.navbar');
        const menuToggle = document.querySelector('.menu-toggle');
        if (navbar) {
            navbar.classList.remove('expanded');
        }
        if (menuToggle) {
            menuToggle.textContent = '☰';
        }
    });
});

window.addEventListener('error', (event) => {
    console.warn('JavaScript error:', event.error);
});

window.addEventListener('unhandledrejection', (event) => {
    console.warn('Unhandled promise rejection:', event.reason);
});
