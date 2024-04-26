document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("ricerca-genere-musica").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("searchBar").value = "Musica";
        document.getElementById("searchBarA").submit();
    });
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("ricerca-genere-arte").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("searchBar").value = "Arte";
        document.getElementById("searchBarA").submit();
    });
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("ricerca-genere-sport").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("searchBar").value = "Sport";
        document.getElementById("searchBarA").submit();
    });
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("ricerca-genere-teatro").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("searchBar").value = "Teatro";
        document.getElementById("searchBarA").submit();
    });
});