function getParamValue(param) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    return urlParams.get(param);
}

const cookieName = 'cookiesBanner';
const hasCookie = getCookie(cookieName);

if (hasCookie) {
    if (getCookie(cookieName) !== 0) {
        setCookie(cookieName, getParamValue('evento'), 31);
    }
}
