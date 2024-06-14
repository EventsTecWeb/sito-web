$(document).ready(function(){
    $(".content-sicu").hide();

    $(".option-sicu").click(function(){
        toggleContent($(this));
    });

    $(".option-sicu").keypress(function(event){
        if (event.which === 13) {
            toggleContent($(this));
        }
    });

    function toggleContent(element) {
        var img = element.find('img');
        var originalSrc = img.data('original-src');
        var alternateSrc = img.data('alternate-src');
        if (img.attr('src') === originalSrc) {
            img.attr('src', alternateSrc);
        } else {
            img.attr('src', originalSrc);
        }
        element.next('.content-sicu').slideToggle("slow");
    }

    $("#searchButton").click(function() {
        $("#searchBarA").submit();
    });

    window.togliCookie = function() {
        document.cookie = "cookiesBanner=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";
    };
});
