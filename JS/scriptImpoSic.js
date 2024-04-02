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

/*funzione per non permettere di premere il bottone elimina senza aver riempito la checkbox*/
function enableButtonElimina() {
    var checkBox = document.getElementById("read-sicu");
    var button = document.getElementById("eliminaButton-sicu");
    if (checkBox.checked == true){
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}

/*funzione per non permettere di premere il bottone esci senza aver riempito la checkbox*/
function enableButtonEsci() {
    var checkBox = document.getElementById("read-sicu");
    var button = document.getElementById("esciButton-sicu");
    if (checkBox.checked == true){
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}