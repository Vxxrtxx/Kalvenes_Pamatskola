function setupDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (!dropdown) return;

    const menu = dropdown.querySelector(".dropdown-content");
    if (!menu) return;

    let hideTimeout;

    const showMenu = () => {
        if (window.innerWidth <= 700) return;
        clearTimeout(hideTimeout);
        menu.classList.add("show");
    };

    const hideMenu = () => {
        if (window.innerWidth <= 700) return;
        hideTimeout = setTimeout(() => {
            menu.classList.remove("show");
        }, 200);
    };

    dropdown.addEventListener("mouseenter", showMenu);
    dropdown.addEventListener("mouseleave", hideMenu);
}

document.addEventListener("DOMContentLoaded", () => {
    setupDropdown("SchoolDropdown");
    setupDropdown("AdmissionDropdown");

    const heroContent = document.querySelector(".hero-content");
    if (heroContent) {
        const observer = new IntersectionObserver(
            entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        heroContent.classList.add("animate");
                    } else {
                        heroContent.classList.remove("animate");
                    }
                });
            },
            {
                threshold: 0.3
            }
        );

        observer.observe(heroContent);
    }

    const toggle = document.querySelector(".menu-toggle");
    const navbar = document.querySelector(".navbar");

    if (toggle && navbar) {
        toggle.addEventListener("click", () => {
            navbar.classList.toggle("expanded");
        });
    }

    document.querySelectorAll(".dropdown-trigger").forEach(trigger => {
        trigger.addEventListener("click", function (e) {
            if (window.innerWidth <= 700) {
                e.preventDefault();
                const parent = this.closest(".dropdown");
                if (parent) {
                    parent.classList.toggle("open");
                }
            }
        });
    });

    const applyTheme = (theme) => {
        const root = document.documentElement;
        if (theme === 'dark') {
            root.classList.add('dark');
            localStorage.setItem('site-theme', 'dark');
            themeToggle.textContent = '☀︎';
            themeToggle.setAttribute('aria-label', 'Switch to light mode');
        } else {
            root.classList.remove('dark');
            localStorage.setItem('site-theme', 'light');
            themeToggle.textContent = '🌙';
            themeToggle.setAttribute('aria-label', 'Switch to dark mode');
        }
    };

    const themeToggle = document.querySelector('.dark-mode-toggle');
    const savedTheme = localStorage.getItem('site-theme');
    const preferDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = savedTheme || (preferDark ? 'dark' : 'light');

    if (themeToggle) {
        applyTheme(initialTheme);
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.contains('dark');
            applyTheme(isDark ? 'light' : 'dark');
        });
    } else {
        // No toggle on page, but apply default anyway.
        if (savedTheme === 'dark' || (!savedTheme && preferDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    window.addEventListener("resize", () => {
        if (window.innerWidth > 700) {
            document.querySelectorAll(".dropdown").forEach(dropdown => {
                dropdown.classList.remove("open");
                const menu = dropdown.querySelector(".dropdown-content");
                if (menu) {
                    menu.classList.remove("show");
                }
            });

            if (navbar) {
                navbar.classList.remove("expanded");
            }
        }
    });
});