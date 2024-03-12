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
        $username = isset($userData['username']) ? $userData['username'] : '';
        // Ottieni i valori inviati dal modulo
        $eventName = $_POST["eventName"];
        $eventStartDate = $_POST["eventStartDate"];
        $eventStartTime = $_POST["eventStartTime"];
        $eventEndDate = $_POST["eventEndDate"];
        $eventEndTime = $_POST["eventEndTime"];
        $eventLocation = $_POST["eventLocation"];
        $eventCost = $_POST["eventCost"];
        $eventCategory = $_POST["eventCategory"];
        $eventDescription = $_POST["eventDescription"];

        if ($eventStartDate > $eventEndDate) {
            echo "Errore: La data di inizio dell'evento non può essere successiva alla data di fine.";
            exit();
        } elseif ($eventStartDate == $eventEndDate && $eventStartTime >= $eventEndTime) {
            echo "Errore: L'ora di inizio dell'evento non può essere successiva o uguale all'ora di fine.";
            exit();
        }

        $eventImageURL = $_POST["imageURL"];

        $sql = "INSERT INTO Eventi (titolo, descrizione, data_inizio, data_fine, orario_inizio, orario_fine, luogo, costo, categoria, creatore_id, url_immagine) VALUES ('$eventName', '$eventDescription', '$eventStartDate','$eventEndDate','$eventStartTime','$eventEndTime', '$eventLocation','$eventCost','$eventCategory','$userId', '$eventImageURL')";
        if ($conn->query($sql) === TRUE) {
            echo "Nuovo record inserito con successo.";
        } else {
            echo "Errore: " . $sql . "<br>" . $conn->error;
        }
    }
  }
}

// Chiudi la connessione al database
$conn->close();
?>
