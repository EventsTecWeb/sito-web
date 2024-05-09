document.addEventListener("DOMContentLoaded", function() {
    const $cookieBanner = document.querySelector(".cookie-banner");
    const $cookieBannerAcceptButton = document.querySelector(".cookie-banner .accept-btn");
    const $cookieBannerRejectButton = document.querySelector(".cookie-banner .reject-btn");
    const $evidenzaBox = document.querySelector(".home-evidenza-last-box");

    if ($cookieBannerAcceptButton && $cookieBannerRejectButton) {
        const cookieName = 'cookiesBanner';
        const hasCookie = getCookie(cookieName);
        const sessionStorageKey = 'cookieAccepted';

        const sessionStorageAccepted = sessionStorage.getItem(sessionStorageKey);

        if (!hasCookie && sessionStorageAccepted !== null) {
            if (sessionStorageAccepted === 'true') {
                $evidenzaBox.classList.remove("hidden");
            }
            $cookieBanner.classList.add("hidden");
        } else if (!hasCookie) {
            $cookieBanner.classList.remove("hidden");
        } else if (getCookie(cookieName) !== "0") {
            $evidenzaBox.classList.remove("hidden");
        }

        $cookieBannerAcceptButton.addEventListener("click", () => {
            setCookie(cookieName, 0, 31);
            sessionStorage.setItem(sessionStorageKey, 'true');
            $cookieBanner.remove();
        });

        $cookieBannerRejectButton.addEventListener("click", () => {
            sessionStorage.setItem(sessionStorageKey, 'false');
            $cookieBanner.remove();
        });
    } else {
        console.error("Pulsanti non trovati. Assicurati che i pulsanti esistano nel tuo HTML.");
    }
});