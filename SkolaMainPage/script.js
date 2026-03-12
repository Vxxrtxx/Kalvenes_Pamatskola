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