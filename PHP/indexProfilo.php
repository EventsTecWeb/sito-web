<?php
require_once 'queries.php';
session_start();

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
        $profilo = '<div id="profile-info-container">
                        <img class="avatar-ev" src="../Images/' . $profile_image_path . '">
                        <ul id="descrizioneProfilo">
                            <li id="NomeUtente">' . $userData["nome"] . '</li>
                            <li id="NomeUtente">' . $userData["username"] . '</li>
                        </ul>
                    </div>';

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
            $messaggio= "<div>Errore: La data di inizio dell'evento non può essere successiva alla data di fine.</div>";
        } elseif ($eventStartDate == $eventEndDate && $eventStartTime >= $eventEndTime) {
            $messaggio= "<div>Errore: L'ora di inizio dell'evento non può essere successiva o uguale all'ora di fine.</div>";
        }

        $eventImageURL = $_POST["imageURL"];

        $sql = "INSERT INTO Eventi (titolo, descrizione, data_inizio, data_fine, orario_inizio, orario_fine, luogo, costo, categoria, creatore_id, url_immagine) VALUES ('$eventName', '$eventDescription', '$eventStartDate','$eventEndDate','$eventStartTime','$eventEndTime', '$eventLocation','$eventCost','$eventCategory','$userId', '$eventImageURL')";
        if ($conn->query($sql) === TRUE) {
            $messaggio="<div>inserito con successo</div>";
        } else {
            $messaggio= "<div>Errore: " . $sql . "<br>" . $conn->error."</div>";
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