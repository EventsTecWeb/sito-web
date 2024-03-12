<?php
require_once 'queries.php';
session_start();
include 'user_session.php';
$accedi_stringa = gestisciAccesso($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION['username']) || isset($_SESSION['email'])) {
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['email'];
    $userData = getUserByMailOrUsername($conn, $user); 
    if ($userData) {
        $userId = $userData['utente_id'];
        $profile_image_path = getProfilePhoto($conn, $userId);
        if (!$profile_image_path) {
            $profile_image_path = '../Images/people_icon_small.png'; 
        } 
    }
  }
}

    // Assicurati che l'ID dell'evento sia stato passato come parametro o recuperato da qualche altra fonte
    if(isset($_GET['evento_id'])) {
    $evento_id = $_GET['evento_id'];
    } else {
    // Gestione del caso in cui l'ID dell'evento non è stato fornito
    echo "ID dell'evento non specificato.";
    exit;
    }

    // Ottieni l'evento corrente
    $row = getEventiByIdQuery($conn, $evento_id);

    // Ottieni i partecipanti all'evento corrente
    $resultPartecipanti = getPartecipantiEvento($conn, $evento_id);
    $frase_partecipanti = "Interessato a ";
    if ($resultPartecipanti && $resultPartecipanti->num_rows > 0) {
    while ($partecipante = $resultPartecipanti->fetch_assoc()) {
    $frase_partecipanti .= $partecipante['nome'] . " " . $partecipante['cognome'] . ", ";
    }
    // Rimuovi l'ultima virgola e aggiungi il punto finale
    $frase_partecipanti = rtrim($frase_partecipanti, ", ") . ".";
    } else {
        $frase_partecipanti .= "nessuno al momento.";
    }   
        
$conn->close();
?>
