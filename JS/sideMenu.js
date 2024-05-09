document.addEventListener('DOMContentLoaded', (event) => {
    var sideMenu = document.querySelector('.side-menu');
    var overlay = document.getElementById('overlay');
    var hamburgerBtn = document.querySelector('.bottoneHamburger');
    var closeBtn = document.querySelector('.closebtn');
    var footer = document.getElementById('footer');

    hamburgerBtn.addEventListener('click', function() {
        if (!sideMenu.classList.contains('open')) {
            sideMenu.classList.add('open');
            overlay.style.width = '100%';
            sideMenu.style.width = '250px';
            sideMenu.setAttribute('aria-hidden', 'false');
            var menuLinks = sideMenu.querySelectorAll('a');
            menuLinks.forEach(function(link) {
                link.setAttribute('tabindex', '0');
            });
            if (footer) {
                footer.style.display = 'none';
            }
        } else {
            sideMenu.classList.remove('open');
            overlay.style.width = '0';
            sideMenu.style.width = '0';
            sideMenu.setAttribute('aria-hidden', 'true');
            var menuLinks = sideMenu.querySelectorAll('a');
            menuLinks.forEach(function(link) {
                link.setAttribute('tabindex', '-1');
            });
            if (footer) {
                footer.style.display = 'block';
            }
        }
    });

    closeBtn.addEventListener('click', function() {
        sideMenu.classList.remove('open');
        overlay.style.width = '0';
        sideMenu.style.width = '0';
        sideMenu.setAttribute('aria-hidden', 'true');
        var menuLinks = sideMenu.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.setAttribute('tabindex', '-1');
        });
        if (footer) {
            footer.style.display = 'block';
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            sideMenu.classList.remove('open');
            overlay.style.width = '0';
            sideMenu.style.width = '0';
            sideMenu.setAttribute('aria-hidden', 'true');
            var menuLinks = sideMenu.querySelectorAll('a');
            menuLinks.forEach(function(link) {
                link.setAttribute('tabindex', '-1');
            });
            if (footer) {
                footer.style.display = 'block';
            }
        }
    });
});