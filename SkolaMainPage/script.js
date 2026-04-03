// ============================================
// KALVENES PAMATSKOLA - ADVANCED THEME MANAGEMENT v2.0
// Enhanced Dark/Light Mode with Smooth Transitions
// Advanced Animations & Interactions
// ============================================

// Enhanced theme application with better transitions
function applyTheme(theme, instant = false) {
    const html = document.documentElement;
    const toggle = document.querySelector('.dark-mode-toggle');
    
    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('site-theme', 'dark');
        if (toggle) {
            toggle.textContent = '☀️';
            toggle.setAttribute('aria-label', 'Switch to light mode');
            toggle.style.transform = 'rotate(0deg)';
        }
    } else {
        html.classList.remove('dark');
        localStorage.setItem('site-theme', 'light');
        if (toggle) {
            toggle.textContent = '🌙';
            toggle.setAttribute('aria-label', 'Switch to dark mode');
            toggle.style.transform = 'rotate(0deg)';
        }
    }
    
    // Trigger theme change animation
    if (!instant) {
        html.style.transition = 'background-color 0.5s ease, color 0.5s ease';
        setTimeout(() => {
            html.style.transition = '';
        }, 500);
    }
}

// Enhanced theme toggle with visual feedback
function toggleTheme() {
    const toggle = document.querySelector('.dark-mode-toggle');
    const isDark = document.documentElement.classList.contains('dark');
    
    // Add rotation animation
    if (toggle) {
        toggle.style.transform = 'rotate(180deg) scale(1.2)';
        setTimeout(() => {
            toggle.style.transform = '';
        }, 300);
    }
    
    // Apply theme with slight delay for smooth transition
    setTimeout(() => {
        applyTheme(isDark ? 'light' : 'dark');
    }, 150);
}

// Initialize theme on page load with system preference detection
document.addEventListener('DOMContentLoaded', () => {
    // Determine initial theme with priority: saved > system > light
    const savedTheme = localStorage.getItem('site-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    
    // Apply initial theme instantly to avoid flash
    applyTheme(initialTheme, true);
    
    // Theme toggle button handler with enhanced feedback
    const themeToggle = document.querySelector('.dark-mode-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
        
        // Add keyboard support
        themeToggle.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleTheme();
            }
        });
    }
    
    // ============================================
    // ADVANCED MOBILE MENU
    // ============================================
    
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');
    
    if (menuToggle && navbar) {
        menuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            navbar.classList.toggle('expanded');
            
            // Animate hamburger icon
            const isExpanded = navbar.classList.contains('expanded');
            menuToggle.style.transform = isExpanded ? 'rotate(90deg)' : 'rotate(0deg)';
        });
    }
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (navbar && !navbar.contains(e.target)) {
            navbar.classList.remove('expanded');
            if (menuToggle) {
                menuToggle.style.transform = 'rotate(0deg)';
            }
        }
    });
    
    // ============================================
    // ENHANCED DROPDOWN MENUS
    // ============================================
    
    function setupDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;
        
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const content = dropdown.querySelector('.dropdown-content');
        
        if (!trigger || !content) return;
        
        let hideTimeout;
        
        // Desktop hover behavior with smooth animations
        const showMenu = () => {
            if (window.innerWidth <= 768) return;
            clearTimeout(hideTimeout);
            content.classList.add('show');
            
            // Stagger animation for menu items
            const items = content.querySelectorAll('a');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-10px)';
                setTimeout(() => {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 50);
            });
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
                const isOpen = content.classList.contains('show');
                if (isOpen) {
                    content.classList.remove('show');
                } else {
                    // Close other dropdowns first
                    document.querySelectorAll('.dropdown-content').forEach(c => c.classList.remove('show'));
                    content.classList.add('show');
                }
            }
        });
    }
    
    // Setup all dropdowns
    setupDropdown('SchoolDropdown');
    setupDropdown('AdmissionDropdown');
    
    // ============================================
    // ADVANCED ANIMATIONS & INTERACTIONS
    // ============================================
    
    // Intersection Observer for scroll-triggered animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const delay = index * 100; // Stagger animations
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, delay);
                
                // Unobserve after animation to prevent re-triggering
                observer.unobserve(element);
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animateElements = document.querySelectorAll('.card, .timeline-card, .section');
    animateElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(element);
    });
    
    // Hero parallax effect (subtle)
    const heroVideo = document.querySelector('.hero-video');
    if (heroVideo) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroVideo.style.transform = scale(1.1) translateY(px);
        });
    }
    
    // ============================================
    // FORM ENHANCEMENTS
    // ============================================
    
    // Enhanced input focus animations
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--accent-primary)';
            this.style.boxShadow = '0 0 0 3px rgba(216, 27, 96, 0.1)';
            this.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = 'var(--border-color)';
            this.style.boxShadow = 'var(--shadow-sm)';
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Button hover effects
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // ============================================
    // RESPONSIVE BEHAVIOR & TOUCH SUPPORT
    // ============================================
    
    // Touch device optimizations
    if ('ontouchstart' in window) {
        document.body.classList.add('touch-device');
        
        // Add touch feedback for interactive elements
        const interactiveElements = document.querySelectorAll('.nav-link, .dropdown-trigger, .card, button');
        interactiveElements.forEach(element => {
            element.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            element.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
    }
    
    // Resize handler with debouncing
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Close mobile menu on resize to desktop
            if (window.innerWidth > 768) {
                if (navbar) {
                    navbar.classList.remove('expanded');
                }
                if (menuToggle) {
                    menuToggle.style.transform = 'rotate(0deg)';
                }
                
                // Reset dropdown displays
                document.querySelectorAll('.dropdown-content').forEach(content => {
                    content.classList.remove('show');
                });
            }
        }, 250);
    });
    
    // ============================================
    // ACCESSIBILITY ENHANCEMENTS
    // ============================================
    
    // Keyboard navigation for dropdowns
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Close all dropdowns and mobile menu
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.classList.remove('show');
            });
            if (navbar) {
                navbar.classList.remove('expanded');
            }
            if (menuToggle) {
                menuToggle.style.transform = 'rotate(0deg)';
            }
        }
    });
    
    // Focus management for mobile menu
    if (menuToggle) {
        menuToggle.addEventListener('focus', () => {
            menuToggle.style.outline = '2px solid var(--accent-primary)';
        });
        
        menuToggle.addEventListener('blur', () => {
            menuToggle.style.outline = '';
        });
    }
    
    // ============================================
    // PERFORMANCE OPTIMIZATIONS
    // ============================================
    
    // Lazy load images
    const images = document.querySelectorAll('img[data-src]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers without IntersectionObserver
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    }
    
    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    
    // Smooth scroll to element
    window.smoothScrollTo = function(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    };
    
    // Debounce function for performance
    window.debounce = function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };
    
    // Theme change listener for external scripts
    window.addEventListener('themeChange', (e) => {
        applyTheme(e.detail.theme);
    });
});

// ============================================
// GLOBAL ERROR HANDLING
// ============================================

window.addEventListener('error', (e) => {
    console.warn('JavaScript error:', e.error);
    // Could send to analytics service
});

window.addEventListener('unhandledrejection', (e) => {
    console.warn('Unhandled promise rejection:', e.reason);
    // Could send to analytics service
});
