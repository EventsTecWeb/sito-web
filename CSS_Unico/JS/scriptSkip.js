// Aggiungi un listener per l'evento focus sulla finestra
window.addEventListener('focus', function(event) {
    // Verifica se l'elemento attualmente in focus è il link "Salta al contenuto principale"
    if (event.target.id === 'skipLink') {
      // Rimuovi la classe visually-hidden per mostrare il link
      event.target.classList.remove('visually-hidden');
    }
  }, true);