const getCookie = (name) => {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) {
        return parts.pop().split(";").shift();
    }
};

const setCookie = function (name, value, expiryDays, domain, path, secure) {
    const exdate = new Date();
    exdate.setHours(exdate.getHours() + (typeof expiryDays !== "number" ? 365 : expiryDays) * 24);
    document.cookie = 
    name +
    "=" +
    value +
    "; expires=" +
    exdate.toUTCString() +
    "; path=" +
    (path || "/") +
    (domain ? "; domain=" + domain : "") +
    (secure ? "; secure" : "");
};


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