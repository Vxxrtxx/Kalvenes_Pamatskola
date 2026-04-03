// ============================================
// KALVENES PAMATSKOLA - ULTIMATE ANIMATION ENGINE v3.0
// Enhanced Dark/Light Mode + Scroll-Triggered Animations
// Advanced Interactions & Performance Optimizations
// ============================================

// Enhanced theme application with ultra-smooth transitions
function applyTheme(theme, instant = false) {
    const html = document.documentElement;
    const toggle = document.querySelector('.dark-mode-toggle');
    
    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('site-theme', 'dark');
        if (toggle) {
            toggle.textContent = '🌙';
            toggle.setAttribute('aria-label', 'Switch to light mode');
            toggle.style.transform = 'rotate(0deg)';
        }
    } else {
        html.classList.remove('dark');
        localStorage.setItem('site-theme', 'light');
        if (toggle) {
            toggle.textContent = '☀️';
            toggle.setAttribute('aria-label', 'Switch to dark mode');
            toggle.style.transform = 'rotate(0deg)';
        }
    }
    
    // Enhanced theme transition with ripple effect
    if (!instant) {
        html.style.transition = 'background-color 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94), color 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        setTimeout(() => {
            html.style.transition = '';
        }, 1500);
    }
}

// Enhanced theme toggle with spectacular visual feedback
function toggleTheme() {
    const toggle = document.querySelector('.dark-mode-toggle');
    const isDark = document.documentElement.classList.contains('dark');
    
    // Multi-stage rotation animation
    if (toggle) {
        toggle.style.transform = 'rotate(180deg) scale(1.3)';
        toggle.style.boxShadow = '0 0 60px var(--accent-light)';
        setTimeout(() => {
            toggle.style.transform = '';
            toggle.style.boxShadow = '';
        }, 600);
    }
    
    // Apply theme with enhanced delay for smooth transition
    setTimeout(() => {
        applyTheme(isDark ? 'light' : 'dark');
    }, 300);
}

// Initialize theme with system preference and smooth loading
document.addEventListener('DOMContentLoaded', () => {
    // Determine initial theme with enhanced priority
    const savedTheme = localStorage.getItem('site-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    
    // Apply initial theme instantly for no flash
    applyTheme(initialTheme, true);
    
    // Enhanced theme toggle with multiple event listeners
    const themeToggle = document.querySelector('.dark-mode-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
        
        // Enhanced keyboard support
        themeToggle.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleTheme();
            }
        });
        
        // Touch support for mobile
        themeToggle.addEventListener('touchstart', (e) => {
            e.preventDefault();
            toggleTheme();
        });
    }
    
    // ============================================
    // ADVANCED SCROLL-TRIGGERED ANIMATIONS
    // ============================================
    
    // Enhanced Intersection Observer with multiple thresholds
    const scrollObserverOptions = {
        threshold: [0.1, 0.3, 0.5, 0.7],
        rootMargin: '0px 0px -100px 0px'
    };
    
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const element = entry.target;
            const ratio = entry.intersectionRatio;
            
            if (ratio >= 0.1) {
                // Progressive animation based on visibility
                const progress = Math.min(ratio * 2, 1);
                element.style.opacity = progress;
                element.style.transform = 	ranslateY(px) scale();
                
                if (ratio >= 0.5) {
                    element.classList.add('revealed');
                    scrollObserver.unobserve(element);
                }
            }
        });
    }, scrollObserverOptions);
    
    // Observe all animatable elements
    const animatableElements = document.querySelectorAll('.card, .timeline-card, .section, .scroll-reveal');
    animatableElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(50px) scale(0.9)';
        element.style.transition = 'opacity 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        scrollObserver.observe(element);
    });
    
    // ============================================
    // ENHANCED MOBILE MENU WITH ANIMATIONS
    // ============================================
    
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');
    
    if (menuToggle && navbar) {
        menuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isExpanded = navbar.classList.contains('expanded');
            
            navbar.classList.toggle('expanded');
            
            // Animated hamburger transformation
            if (isExpanded) {
                menuToggle.style.transform = 'rotate(0deg) scale(1)';
                menuToggle.textContent = '☰';
            } else {
                menuToggle.style.transform = 'rotate(90deg) scale(1.1)';
                menuToggle.textContent = '✕';
                setTimeout(() => {
                    menuToggle.style.transform = 'rotate(0deg) scale(1)';
                }, 300);
            }
        });
    }
    
    // Enhanced close menu on outside click
    document.addEventListener('click', (e) => {
        if (navbar && !navbar.contains(e.target)) {
            navbar.classList.remove('expanded');
            if (menuToggle) {
                menuToggle.style.transform = 'rotate(0deg) scale(1)';
                menuToggle.textContent = '☰';
            }
        }
    });
    
    // ============================================
    // ULTIMATE DROPDOWN MENUS WITH STAGGERED ANIMATIONS
    // ============================================
    
    function setupDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;
        
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const content = dropdown.querySelector('.dropdown-content');
        
        if (!trigger || !content) return;
        
        let hideTimeout;
        
        // Enhanced desktop hover with smooth animations
        const showMenu = () => {
            if (window.innerWidth <= 768) return;
            clearTimeout(hideTimeout);
            content.classList.add('show');
            
            // Staggered item animations with enhanced effects
            const items = content.querySelectorAll('a');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-30px) rotateY(-10deg)';
                item.style.transition = 'opacity 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0) rotateY(0deg)';
                }, index * 100);
            });
        };
        
        const hideMenu = () => {
            if (window.innerWidth <= 768) return;
            hideTimeout = setTimeout(() => {
                content.classList.remove('show');
                // Reset item styles
                const items = content.querySelectorAll('a');
                items.forEach(item => {
                    item.style.opacity = '';
                    item.style.transform = '';
                });
            }, 300);
        };
        
        dropdown.addEventListener('mouseenter', showMenu);
        dropdown.addEventListener('mouseleave', hideMenu);
        
        // Enhanced mobile click behavior
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
                    
                    // Animate items in
                    const items = content.querySelectorAll('a');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.animation = slideInLeftSlow 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
                        }, index * 80);
                    });
                }
            }
        });
    }
    
    // Setup all dropdowns
    setupDropdown('SchoolDropdown');
    setupDropdown('AdmissionDropdown');
    
    // ============================================
    // ADVANCED PARALLAX & SCROLL EFFECTS
    // ============================================
    
    // Enhanced hero parallax with multiple layers
    const heroVideo = document.querySelector('.hero-video');
    const heroContent = document.querySelector('.hero-content');
    
    if (heroVideo) {
        let lastScrollY = window.scrollY;
        
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const rate = scrolled * -0.5;
            const blurRate = Math.min(scrolled * 0.001, 5);
            
            heroVideo.style.transform = scale(1.1) translateY(px);
            heroVideo.style.filter = rightness() blur(px);
            
            lastScrollY = scrolled;
        }, { passive: true });
    }
    
    // Floating elements animation
    const floatingElements = document.querySelectorAll('.card, .timeline-card');
    floatingElements.forEach((element, index) => {
        const delay = index * 0.5;
        element.style.animation = morphFloat 8s infinite ease-in-out s;
    });
    
    // ============================================
    // ENHANCED FORM INTERACTIONS
    // ============================================
    
    // Ultimate input focus animations
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--accent-primary)';
            this.style.boxShadow = '0 0 0 3px rgba(216, 27, 96, 0.15), 0 8px 16px rgba(216, 27, 96, 0.1)';
            this.style.transform = 'translateY(-4px) scale(1.02)';
            this.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            
            // Ripple effect
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(216, 27, 96, 0.1)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = '50%';
            ripple.style.top = '50%';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.marginLeft = '-10px';
            ripple.style.marginTop = '-10px';
            this.parentNode.style.position = 'relative';
            this.parentNode.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = 'var(--border-color)';
            this.style.boxShadow = 'var(--shadow-sm)';
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Enhanced button interactions
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-6px) scale(1.05)';
            this.style.boxShadow = '0 12px 24px rgba(216, 27, 96, 0.3)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = 'var(--shadow-lg)';
        });
        
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        
        button.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-6px) scale(1.05)';
        });
    });
    
    // ============================================
    // ADVANCED RESPONSIVE BEHAVIOR
    // ============================================
    
    // Touch device optimizations with enhanced feedback
    if ('ontouchstart' in window) {
        document.body.classList.add('touch-device');
        
        // Enhanced touch feedback
        const interactiveElements = document.querySelectorAll('.nav-link, .dropdown-trigger, .card, .timeline-card, button');
        interactiveElements.forEach(element => {
            element.addEventListener('touchstart', function(e) {
                this.style.transform = 'scale(0.98)';
                this.style.transition = 'transform 0.1s ease';
                
                // Create touch ripple
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(216, 27, 96, 0.2)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.4s linear';
                ripple.style.left = ${e.touches[0].clientX - this.getBoundingClientRect().left}px;
                ripple.style.top = ${e.touches[0].clientY - this.getBoundingClientRect().top}px;
                ripple.style.width = '20px';
                ripple.style.height = '20px';
                ripple.style.marginLeft = '-10px';
                ripple.style.marginTop = '-10px';
                ripple.style.pointerEvents = 'none';
                this.style.position = 'relative';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 400);
            });
            
            element.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
    }
    
    // Enhanced resize handler with smooth transitions
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Smooth mobile menu transitions
            if (window.innerWidth > 768) {
                if (navbar) {
                    navbar.classList.remove('expanded');
                }
                if (menuToggle) {
                    menuToggle.style.transform = 'rotate(0deg) scale(1)';
                    menuToggle.textContent = '☰';
                }
                
                // Reset dropdown displays with animation
                document.querySelectorAll('.dropdown-content').forEach(content => {
                    content.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    content.classList.remove('show');
                });
            }
        }, 300);
    });
    
    // ============================================
    // ACCESSIBILITY ENHANCEMENTS
    // ============================================
    
    // Enhanced keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Enhanced close all overlays
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.classList.remove('show');
            });
            if (navbar) {
                navbar.classList.remove('expanded');
            }
            if (menuToggle) {
                menuToggle.style.transform = 'rotate(0deg) scale(1)';
                menuToggle.textContent = '☰';
            }
            
            // Remove focus from interactive elements
            document.activeElement.blur();
        }
        
        // Enhanced tab navigation
        if (e.key === 'Tab') {
            const focusableElements = document.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    lastElement.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement) {
                    firstElement.focus();
                    e.preventDefault();
                }
            }
        }
    });
    
    // Focus management for mobile menu
    if (menuToggle) {
        menuToggle.addEventListener('focus', () => {
            menuToggle.style.outline = '2px solid var(--accent-primary)';
            menuToggle.style.boxShadow = '0 0 0 4px rgba(216, 27, 96, 0.1)';
        });
        
        menuToggle.addEventListener('blur', () => {
            menuToggle.style.outline = '';
            menuToggle.style.boxShadow = '';
        });
    }
    
    // ============================================
    // PERFORMANCE OPTIMIZATIONS
    // ============================================
    
    // Enhanced lazy loading with intersection observer
    const lazyImages = document.querySelectorAll('img[data-src]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        }, { rootMargin: '50px' });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Enhanced fallback
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.classList.add('loaded');
        });
    }
    
    // Request animation frame for smooth animations
    let animationFrame;
    function smoothAnimations() {
        // Update any continuous animations here
        animationFrame = requestAnimationFrame(smoothAnimations);
    }
    smoothAnimations();
    
    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    
    // Enhanced smooth scroll with easing
    window.smoothScrollTo = function(elementId, duration = 1000) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const start = window.pageYOffset;
        const end = element.getBoundingClientRect().top + window.pageYOffset;
        const distance = end - start;
        let startTime = null;
        
        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1);
            
            // Easing function
            const easeInOutCubic = progress < 0.5 
                ? 4 * progress * progress * progress 
                : 1 - Math.pow(-2 * progress + 2, 3) / 2;
            
            window.scrollTo(0, start + distance * easeInOutCubic);
            
            if (progress < 1) {
                requestAnimationFrame(animation);
            }
        }
        
        requestAnimationFrame(animation);
    };
    
    // Enhanced debounce with immediate option
    window.debounce = function(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    };
    
    // Theme change event dispatcher
    window.addEventListener('themeChange', (e) => {
        applyTheme(e.detail.theme);
    });
    
    // ============================================
    // RIPPLE EFFECT CSS (for touch interactions)
    // ============================================
    
    const style = document.createElement('style');
    style.textContent = 
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .loaded {
            animation: fadeInScale 0.6s ease-out;
        }
    ;
    document.head.appendChild(style);
});

// ============================================
// GLOBAL ERROR HANDLING & PERFORMANCE MONITORING
// ============================================

window.addEventListener('error', (e) => {
    console.warn('JavaScript error:', e.error);
    // Could send to analytics service
});

window.addEventListener('unhandledrejection', (e) => {
    console.warn('Unhandled promise rejection:', e.reason);
    // Could send to analytics service
});

// Performance monitoring
if ('performance' in window && 'PerformanceObserver' in window) {
    try {
        const observer = new PerformanceObserver((list) => {
            for (const entry of list.getEntries()) {
                if (entry.entryType === 'largest-contentful-paint') {
                    console.log('LCP:', entry.startTime);
                }
            }
        });
        observer.observe({ entryTypes: ['largest-contentful-paint'] });
    } catch (e) {
        console.warn('Performance monitoring not supported');
    }
}
