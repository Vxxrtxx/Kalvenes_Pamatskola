function setupDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const menu = dropdown.querySelector('.dropdown-content');
    let hideTimeout;

    const showMenu = () => {
        clearTimeout(hideTimeout);
        menu.classList.add('show');
    };

    const hideMenu = () => {
        hideTimeout = setTimeout(() => {
            menu.classList.remove('show');
        }, 200);
    };

    dropdown.addEventListener('mouseenter', showMenu);
    dropdown.addEventListener('mouseleave', hideMenu);
    }

    setupDropdown('SchoolDropdown');
    setupDropdown('AdmissionDropdown');

    document.addEventListener("DOMContentLoaded", () => {
        const heroContent = document.querySelector('.hero-content');

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    heroContent.classList.add('animate');
                } else {
                    heroContent.classList.remove('animate');
                }
            });
        }, {
            threshold: 0.3 
        });

        observer.observe(heroContent);
    });
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.querySelector('.menu-toggle');
            const navbar = document.querySelector('.navbar');

        toggle.addEventListener('click', () => {
        navbar.classList.toggle('expanded');
            });
        });

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".dropdown-trigger").forEach(trigger => {
      trigger.addEventListener("click", function (e) {
        const parent = this.closest(".dropdown");
        parent.classList.toggle("open");
      });
    });
  });

