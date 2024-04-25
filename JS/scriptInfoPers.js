$(document).ready(function(){
    $(".option-ip").click(function(){
        var img = $(this).find('img');
        var originalSrc = img.data('original-src');
        var alternateSrc = img.data('alternate-src');
        
        if (img.attr('src') === originalSrc) {
            img.attr('src', alternateSrc);
        } else {
            img.attr('src', originalSrc);
        }
        
        $(this).next('.content-ip').slideToggle("slow");
    });
});

function ModificaNome() {
    var campoNome = document.getElementById('campoNome-ip');
    campoNome.readOnly = false;
    campoNome.focus();
    campoNome.select();
}

function ModificaCognome() {
    var campoCognome = document.getElementById('campoCognome-ip');
    campoCognome.readOnly = false;
    campoCognome.focus();
    campoCognome.select();
}

function ModificaNick() {
    var campoNick = document.getElementById('campoNick-ip');
    campoNick.readOnly = false;
    campoNick.focus();
    campoNick.select();
}

function ModificaEmail() {
    var campoEmail = document.getElementById('campoEmail-ip');
    campoEmail.readOnly = false;
    campoEmail.focus();
    campoEmail.select();
}

function ModificaTel() {
    var campoTel = document.getElementById('campoTel-ip');
    campoTel.readOnly = false;
    campoTel.focus();
    campoTel.select();
}

function salvaModificheNome() {
    var campoNome = document.getElementById('campoNome-ip');
    var campoCognome = document.getElementById('campoCognome-ip');
    var nuovoNome = campoNome.value;
    var nuovoCognome = campoCognome.value;
    if(nuovoNome != "" && nuovoCognome != ""){
        var nomeCompleto = nuovoNome + " " + nuovoCognome;
        alert("Confermare le modifiche? Il nuovo nome è: " + nomeCompleto);
        campoNome.readOnly = true;
        campoCognome.readOnly = true;
        campoNome.value = nuovoNome;
        campoCognome.value = nuovoCognome;
    }
    else{
        alert("Devi inserire un nome ed un cognome");
    }
}

function salvaModificheNick() {
    var campoNick = document.getElementById('campoNick-ip');
    var nuovoNome = campoNick.value;
    alert("Confermare le modifiche? Il nuovo Nickname è: " + nuovoNome);
    campoNick.readOnly = true;
    campoNick.value = nuovoNome;
}

function salvaModificheGenere() {
    var genereSelezionato = document.querySelector('input[name="genere"]:checked');
    if (genereSelezionato) {
        var genereValue = genereSelezionato.value;
        alert("Hai selezionato il genere: " + genereValue);
    } else {
        alert("Seleziona un genere prima di procedere.");
    }
}
function updateDays() {
    var monthSelect = document.getElementById('month-ip');
    var dayInput = document.getElementById('day-ip');
    var selectedMonth = parseInt(monthSelect.value, 10);
    var daysInMonth = new Date(new Date().getFullYear(), selectedMonth, 0).getDate();
    dayInput.setAttribute('max', daysInMonth);
    if (parseInt(dayInput.value, 10) > daysInMonth) {
        dayInput.value = '';
    }
}

function aggiornaDataNascita() {
    updateDays();
    var day = document.getElementById('day-ip').value;
    var month = document.getElementById('month-ip').value;
    var year = document.getElementById('year-ip').value;
    if (!(day >= 1 && day <= parseInt(document.getElementById('day-ip').getAttribute('max'), 10))) {
        alert("Inserisci un giorno valido per il mese selezionato.");
        return;
    }
    var currentYear = new Date().getFullYear();
    if (!(year >= 1900 && year <= currentYear)) {
        alert("Inserisci un anno valido (tra 1900 e " + currentYear + ").");
        return;
    }
    alert("Data di nascita aggiornata: " + day + "/" + month + "/" + year);
}

function salvaModificheEmail() {
    var campoEmail = document.getElementById('campoEmail-ip');
    var nuovoNome = campoEmail.value;
    alert("Confermare le modifiche? La nuova email è: " + nuovoNome);
    campoEmail.readOnly = true;
    campoEmail.value = nuovoNome;
}


function verificaNumeroTelefono(numero) {
    const numeroSenzaSpazi = numero.replace(/\s/g, '');
    if (/^\d{10}$/.test(numeroSenzaSpazi)) {
        return true;
    } else {
        return false;
    }
}

function salvaModificheTel() {
    const numeroInserito = document.getElementById('campoTel-ip').value;
    if (verificaNumeroTelefono(numeroInserito)) {
        document.getElementById('campoTel-ip').readOnly = true;
        alert('Modifiche salvate con successo.');
    } else {
        alert('Errore: Inserisci un numero di telefono valido (10 cifre).');
    }
}

function vaiAPaginaProfilo() {
    var destinazionePagina = '../HTML/indexProfilo.html';
    if (window.location.href.includes(destinazionePagina)) {
        alert('Sei già sulla pagina desiderata!');
    } else {
        window.location.href = destinazionePagina;
    }
}

function vaiAPaginaInfo() {
    var destinazionePagina = '../HTML/infoPersonali.html';
    if (window.location.href.includes(destinazionePagina)) {
        alert('Sei già sulla pagina desiderata!');
    } else {
        window.location.href = destinazionePagina;
    }
  }

function toggleDropdown() {
    const dropdownMenu = document.getElementById("dropdownMenu-ip");
    if (dropdownMenu.style.display === "none" || dropdownMenu.style.display === "") {
        dropdownMenu.style.display = "block";
    } else {
        dropdownMenu.style.display = "none";
    }
}