$(document).ready(function(){
    // Nascondi per impostazione predefinita le schede delle impostazioni e della sicurezza
    $(".content-sicu").hide();

    // Gestisci il clic sulle opzioni per aprire o chiudere le schede
    $(".option-sicu").click(function(){
        toggleContent($(this));
    });

    // Gestisci il tasto Invio sulla tastiera
    $(".option-sicu").keypress(function(event){
        // Verifica se il tasto premuto è il tasto Invio
        if (event.which === 13) {
            toggleContent($(this));
        }
    });

    function toggleContent(element) {
        var img = element.find('img');
        var originalSrc = img.data('original-src');
        var alternateSrc = img.data('alternate-src');
        
        // Cambia l'icona
        if (img.attr('src') === originalSrc) {
            img.attr('src', alternateSrc);
        } else {
            img.attr('src', originalSrc);
        }
        
        // Apre o chiude la sezione
        element.next('.content-sicu').slideToggle("slow");
    }
});