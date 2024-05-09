<?php
require_once 'queries.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../HTML/index.html');
    exit();
}
error_reporting(E_ERROR | E_PARSE);

$template = file_get_contents('../HTML/indexProfilo.html');
$messaggio = "";
$profilo = "";

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
            $messaggio= "<p class='impo-error'>Errore: La data di inizio dell'evento non può essere successiva alla data di fine.</p>";
        } elseif ($eventStartDate == $eventEndDate && $eventStartTime >= $eventEndTime) {
            $messaggio= "<p class='impo-error'>Errore: L'ora di inizio dell'evento non può essere successiva o uguale all'ora di fine.</p>";
        }
        $eventImageURL = $_POST["imageURL"];
        if (!empty($eventName) && !empty($eventStartDate) && !empty($eventImageURL)) {
            if (file_exists($eventImageURL)) {
                $sql = "INSERT INTO eventi (titolo, descrizione, data_inizio, data_fine, orario_inizio, orario_fine, luogo, costo, categoria, creatore_id, url_immagine) VALUES ('$eventName', '$eventDescription', '$eventStartDate','$eventEndDate','$eventStartTime','$eventEndTime', '$eventLocation','$eventCost','$eventCategory','$userId', '$eventImageURL')";
                if ($conn->query($sql) === TRUE) {
                    $messaggio="<p class='impo-error'>Inserito con successo</p>";
                } else {
                    $messaggio= "<p class='impo-error'>Errore: " . $sql . "<br>" . $conn->error."</p>";
                }
            } else {
                $messaggio = "<p class='impo-error'>Errore: L'immagine specificata non esiste nell'archivio.</p>";
            }
        } else {
            $messaggio = "<p class='impo-error'>I campi Nome, Data inizio e URL Immagine sono obbligatori.</p>";
        }
    } else {
        header('Location: ../php/505.php');
    }
} else {
    header('Location: ../php/accessNav.php');
}

$template = str_replace('{FOTOPROFILO}', $profilo, $template);
$template = str_replace('{MESSAGGIO}', $messaggio, $template);

echo $template;

$conn->close();
?>