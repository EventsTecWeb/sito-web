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