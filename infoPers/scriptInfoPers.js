//funzione per avere i menù a tendina
$(document).ready(function(){
    $(".option").click(function(){
        var img = $(this).find('img');
        var originalSrc = img.data('original-src');
        var alternateSrc = img.data('alternate-src');
        
        if (img.attr('src') === originalSrc) {
            img.attr('src', alternateSrc);
        } else {
            img.attr('src', originalSrc);
        }
        
        $(this).next('.content').slideToggle("slow");
    });
});




function ModificaNome() {
    var campoNome = document.getElementById('campoNome');
    campoNome.readOnly = false;
    campoNome.focus();
    campoNome.select();
}
function ModificaNick() {
    var campoNick = document.getElementById('campoNick');
    campoNick.readOnly = false;
    campoNick.focus();
    campoNick.select();
}
function ModificaEmail() {
    var campoEmail = document.getElementById('campoEmail');
    campoEmail.readOnly = false;
    campoEmail.focus();
    campoEmail.select();
}
function ModificaTel() {
    var campoTel = document.getElementById('campoTel');
    campoTel.readOnly = false;
    campoTel.focus();
    campoTel.select();
}


function salvaModificheNome() {
    var campoNome = document.getElementById('campoNome');
    var nuovoNome = campoNome.value;

    // Simula il salvataggio del nuovo nome (puoi inviarlo al server qui se necessario)
    alert("Confermare le modifiche? Il nuovo nome è: " + nuovoNome);

    // Imposta il campo come solo lettura e aggiorna il valore visualizzato
    campoNome.readOnly = true;
    campoNome.value = nuovoNome;
}

function salvaModificheNick() {
    var campoNick = document.getElementById('campoNick');
    var nuovoNome = campoNick.value;

    // Simula il salvataggio del nuovo nome (puoi inviarlo al server qui se necessario)
    alert("Confermare le modifiche? Il nuovo Nickname è: " + nuovoNome);

    // Imposta il campo come solo lettura e aggiorna il valore visualizzato
    campoNick.readOnly = true;
    campoNick.value = nuovoNome;
}

function salvaModificheGenere() {
    // Ottieni il valore del genere selezionato
    var genereSelezionato = document.querySelector('input[name="genere"]:checked');

    if (genereSelezionato) {
        // Invia il genere selezionato al server (puoi usare AJAX per una richiesta asincrona)
        var genereValue = genereSelezionato.value;
        alert("Hai selezionato il genere: " + genereValue);

        // Puoi qui implementare il codice per inviare il genere al server tramite AJAX o altra logica di gestione
    } else {
        alert("Seleziona un genere prima di procedere.");
    }
}
function updateDays() {
    var monthSelect = document.getElementById('month');
    var dayInput = document.getElementById('day');
    var selectedMonth = parseInt(monthSelect.value, 10);

    // Numero di giorni nel mese selezionato
    var daysInMonth = new Date(new Date().getFullYear(), selectedMonth, 0).getDate();

    // Imposta il massimo numero di giorni nel campo di input
    dayInput.setAttribute('max', daysInMonth);

    // Se il giorno attualmente selezionato è oltre il massimo, imposta a zero
    if (parseInt(dayInput.value, 10) > daysInMonth) {
        dayInput.value = '';
    }
}

function aggiornaDataNascita() {
    // Effettua il controllo sui giorni in base al mese selezionato
    updateDays();

    var day = document.getElementById('day').value;
    var month = document.getElementById('month').value;
    var year = document.getElementById('year').value;

    // Controllo se il giorno è compreso tra 1 e il massimo numero di giorni nel mese selezionato
    if (!(day >= 1 && day <= parseInt(document.getElementById('day').getAttribute('max'), 10))) {
        alert("Inserisci un giorno valido per il mese selezionato.");
        return;
    }

    // Controllo se l'anno è compreso tra 1900 e l'anno corrente
    var currentYear = new Date().getFullYear();
    if (!(year >= 1900 && year <= currentYear)) {
        alert("Inserisci un anno valido (tra 1900 e " + currentYear + ").");
        return;
    }

    // Altre operazioni o invio al server
    alert("Data di nascita aggiornata: " + day + "/" + month + "/" + year);
}

function salvaModificheEmail() {
    var campoEmail = document.getElementById('campoEmail');
    var nuovoNome = campoEmail.value;

    // Simula il salvataggio del nuovo nome (puoi inviarlo al server qui se necessario)
    alert("Confermare le modifiche? La nuova email è: " + nuovoNome);

    // Imposta il campo come solo lettura e aggiorna il valore visualizzato
    campoEmail.readOnly = true;
    campoEmail.value = nuovoNome;
}


function verificaNumeroTelefono(numero) {
    // Rimuovi eventuali spazi bianchi dal numero di telefono
    const numeroSenzaSpazi = numero.replace(/\s/g, '');

    // Controlla se il numero contiene solo cifre e ha una lunghezza di 10
    if (/^\d{10}$/.test(numeroSenzaSpazi)) {
        return true; // Il numero è valido
    } else {
        return false; // Il numero non è valido
    }
}

function salvaModificheTel() {
    // Ottieni il valore attuale del campo numero di telefono
    const numeroInserito = document.getElementById('campoTel').value;

    // Verifica se il numero di telefono è valido
    if (verificaNumeroTelefono(numeroInserito)) {
        // Il numero è valido, puoi salvare le modifiche e rendere il campo non editabile
        document.getElementById('campoTel').readOnly = true;
        alert('Modifiche salvate con successo.');
    } else {
        // Il numero non è valido, mostra un messaggio di errore
        alert('Errore: Inserisci un numero di telefono valido (10 cifre).');
    }
}

function vaiAPaginaProfilo() {
    // URL della pagina a cui vuoi andare
    var destinazionePagina = 'C:/Users/samsung/Desktop/TERZO%20ANNO/TEC%20WEB/Progetto/indexProfilo.html';
  
    // Verifica se sei già sulla pagina di destinazione
    if (window.location.href.includes(destinazionePagina)) {
      alert('Sei già sulla pagina desiderata!');
    } else {
      // Altrimenti, reindirizza alla pagina di destinazione
      window.location.href = destinazionePagina;
    }
}

function vaiAPaginaInfo() {
    // URL della pagina a cui vuoi andare
    var destinazionePagina = 'C:/Users/samsung/Desktop/TERZO%20ANNO/TEC%20WEB/Progetto/infoPers/informazioniPersonali.html';
  
    // Verifica se sei già sulla pagina di destinazione
    if (window.location.href.includes(destinazionePagina)) {
      alert('Sei già sulla pagina desiderata!');
    } else {
      // Altrimenti, reindirizza alla pagina di destinazione
      window.location.href = destinazionePagina;
    }
  }

  function toggleDropdown() {
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (dropdownMenu.style.display === "none" || dropdownMenu.style.display === "") {
        dropdownMenu.style.display = "block";
    } else {
        dropdownMenu.style.display = "none";
    }
}
