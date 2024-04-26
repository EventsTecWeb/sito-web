//funzione per avere i menù a tendina
$(document).ready(function(){
    $(".option-sicu").click(function(){
        var img = $(this).find('img');
        var originalSrc = img.data('original-src');
        var alternateSrc = img.data('alternate-src');
        
        if (img.attr('src') === originalSrc) {
            img.attr('src', alternateSrc);
        } else {
            img.attr('src', originalSrc);
        }
        
        $(this).next('.content-sicu').slideToggle("slow");
    });
});