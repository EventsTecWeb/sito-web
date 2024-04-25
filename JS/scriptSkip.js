window.addEventListener('focus', function(event) {
  if (event.target.id === 'skipLink') {
    event.target.classList.remove('visually-hidden');
  }
}, true);