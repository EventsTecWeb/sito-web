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
});