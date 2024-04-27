document.addEventListener('DOMContentLoaded', (event) => {
    var sideMenu = document.querySelector('.side-menu');
    var overlay = document.getElementById('overlay');
    var hamburgerBtn = document.querySelector('.bottoneHamburger');
    var closeBtn = document.querySelector('.closebtn');
    var footer = document.getElementById('footer');

    hamburgerBtn.addEventListener('click', function() {
        if (!sideMenu.classList.contains('open')) {
            // Apri il menu ad hamburger
            sideMenu.classList.add('open');
            overlay.style.width = '100%';
            sideMenu.style.width = '250px';
            // Imposta aria-hidden su false per rendere gli elementi del menu accessibili
            sideMenu.setAttribute('aria-hidden', 'false');
            // Imposta tabindex su 0 per gli elementi del menu quando il menu è aperto
            var menuLinks = sideMenu.querySelectorAll('a');
            menuLinks.forEach(function(link) {
                link.setAttribute('tabindex', '0');
            });
            // Nascondi il footer se presente
            if (footer) {
                footer.style.display = 'none';
            }
        } else {
            // Chiudi il menu ad hamburger
            sideMenu.classList.remove('open');
            overlay.style.width = '0';
            sideMenu.style.width = '0';
            // Imposta aria-hidden su true per nascondere gli elementi del menu
            sideMenu.setAttribute('aria-hidden', 'true');
            // Imposta tabindex su -1 per gli elementi del menu quando il menu è chiuso
            var menuLinks = sideMenu.querySelectorAll('a');
            menuLinks.forEach(function(link) {
                link.setAttribute('tabindex', '-1');
            });
            // Mostra il footer se presente
            if (footer) {
                footer.style.display = 'block';
            }
        }
    });

    closeBtn.addEventListener('click', function() {
        // Chiudi il menu ad hamburger
        sideMenu.classList.remove('open');
        overlay.style.width = '0';
        sideMenu.style.width = '0';
        // Imposta aria-hidden su true per nascondere gli elementi del menu
        sideMenu.setAttribute('aria-hidden', 'true');
        // Imposta tabindex su -1 per gli elementi del menu quando il menu è chiuso
        var menuLinks = sideMenu.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.setAttribute('tabindex', '-1');
        });
        // Mostra il footer se presente
        if (footer) {
            footer.style.display = 'block';
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            // Chiudi il menu ad hamburger
            sideMenu.classList.remove('open');
            overlay.style.width = '0';
            sideMenu.style.width = '0';
            // Imposta aria-hidden su true per nascondere gli elementi del menu
            sideMenu.setAttribute('aria-hidden', 'true');
            // Imposta tabindex su -1 per gli elementi del menu quando il menu è chiuso
            var menuLinks = sideMenu.querySelectorAll('a');
            menuLinks.forEach(function(link) {
                link.setAttribute('tabindex', '-1');
            });
            // Mostra il footer se presente
            if (footer) {
                footer.style.display = 'block';
            }
        }
    });
});