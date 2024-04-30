
document.addEventListener("DOMContentLoaded", function() {
    const $cookieBanner = document.querySelector(".cookie-banner");
    const $cookieBannerButton = document.querySelector(".cookie-banner button");
    const $evidenzaBox = document.querySelector(".home-evidenza-last-box");

    if ($cookieBannerButton) {
        const cookieName = 'cookiesBanner';
        const hasCookie = getCookie(cookieName);

        if(!hasCookie) {
            $cookieBanner.classList.remove("hidden");
        } else if(getCookie(cookieName) !== "0") {
            $evidenzaBox.classList.remove("hidden");
        }

        $cookieBannerButton.addEventListener("click", () => {
            setCookie(cookieName, 0, 31);
            $cookieBanner.remove();
        });
    } else {
        console.error("Pulsante non trovato. Assicurati che il pulsante esista nel tuo HTML.");
    }
});