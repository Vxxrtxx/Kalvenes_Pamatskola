// ============================================
// THEME MANAGEMENT - Dark/Light Mode Toggle
// ============================================

// Apply theme with smooth transition
function applyTheme(theme) {
    const html = document.documentElement;
    
    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('site-theme', 'dark');
        updateThemeIcon('light'); // Show light mode icon (click to go light)
    } else {
        html.classList.remove('dark');
        localStorage.setItem('site-theme', 'light');
        updateThemeIcon('dark'); // Show dark mode icon (click to go dark)
    }
}

// Update the theme toggle button icon
function updateThemeIcon(nextTheme) {
    const toggle = document.querySelector('.dark-mode-toggle');
    if (!toggle) return;
    
    if (nextTheme === 'light') {
        toggle.textContent = '☀️';
        toggle.setAttribute('aria-label', 'Switch to light mode');
    } else {
        toggle.textContent = '🌙';
        toggle.setAttribute('aria-label', 'Switch to dark mode');
    }
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
    // Determine initial theme
    const savedTheme = localStorage.getItem('site-theme');
    const preferDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = savedTheme || (preferDark ? 'dark' : 'light');
    
    // Apply initial theme
    applyTheme(initialTheme);
    
    // Theme toggle button handler
    const themeToggle = document.querySelector('.dark-mode-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isDark = document.documentElement.classList.contains('dark');
            applyTheme(isDark ? 'light' : 'dark');
        });
    }
    
    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');
    
    if (menuToggle && navbar) {
        menuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            navbar.classList.toggle('expanded');
        });
    }
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (navbar && !navbar.contains(e.target)) {
            navbar.classList.remove('expanded');
        }
    });
    
    // ============================================
    // DROPDOWN MENUS
    // ============================================
    
    function setupDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;
        
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const content = dropdown.querySelector('.dropdown-content');
        
        if (!trigger || !content) return;
        
        let hideTimeout;
        
        // Desktop hover behavior
        const showMenu = () => {
            if (window.innerWidth <= 768) return;
            clearTimeout(hideTimeout);
            content.classList.add('show');
        };
        
        const hideMenu = () => {
            if (window.innerWidth <= 768) return;
            hideTimeout = setTimeout(() => {
                content.classList.remove('show');
            }, 200);
        };
        
        dropdown.addEventListener('mouseenter', showMenu);
        dropdown.addEventListener('mouseleave', hideMenu);
        
        // Mobile click behavior
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            if (window.innerWidth <= 768) {
                content.classList.toggle('show');
            }
        });
    }
    
    // Setup all dropdowns
    setupDropdown('SchoolDropdown');
    setupDropdown('AdmissionDropdown');
    
    // ============================================
    // SMOOTH SCROLL & ANIMATIONS
    // ============================================
    
    // Hero content fade-in animation
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        const observer = new IntersectionObserver(
            entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        heroContent.style.opacity = '1';
                        heroContent.style.transform = 'translateY(0)';
                    }
                });
            },
            { threshold: 0.3 }
        );
        
        observer.observe(heroContent);
    }
    
    // Animate cards on scroll
    const cards = document.querySelectorAll('.card');
    const cardObserver = new IntersectionObserver(
        entries => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        },
        { threshold: 0.1 }
    );
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        cardObserver.observe(card);
    });
    
    // ============================================
    // RESPONSIVE BEHAVIOR
    // ============================================
    
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            // Close mobile menu on resize to desktop
            if (navbar) {
                navbar.classList.remove('expanded');
            }
            
            // Reset dropdown displays
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.classList.remove('show');
            });
        }
    });
    
    // ============================================
    // FORM ENHANCEMENTS
    // ============================================
    
    // Add focus animations to form inputs
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--accent-color)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = 'var(--border-color)';
        });
    });
});

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Smooth scroll to element
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
