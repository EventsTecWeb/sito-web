<?php
require_once 'queries.php';
session_start();

$txt_error = "";

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    header("Location: ../HTML/index.html");
    exit();
}

if (isset($_SESSION['username']) || isset($_SESSION['email'])) {
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

if (isset($_POST['salva_evento'])) {
    $evento_id = $_POST['salva_evento'];
    $userid = $_SESSION['user_id'];
    if (salvaEvento($conn, $userid, $evento_id)) {
        $txt_error = "L'evento è stato salvato con successo.";
    } else {
        $txt_error = "Si è verificato un errore durante il salvataggio dell'evento.";
    }
}

if (isset($_POST['elimina'])) {
    if(eliminaEvento($conn, $_GET['evento'])){
        header("Location: ../php/home.php");
        exit();
    }else{
        $txt_error = "Si è verificato un errore durante l`eliminazione dell'evento.";
    }
}

if (isset($_POST['disdici_evento'])) {
    if(nonInteressato($conn, $_GET['evento'], $_SESSION['user_id'])) {
        $txt_error = "Evento tolto dagli eventi salvati";
    } else {
        $txt_error = "Si è verificato un errore durante la rimozione dell'evento dagli eventi salvati";
    }
}

$evento_id = $_GET["evento"];

$template = file_get_contents("../HTML/pageEvent.html");

$row = getEventiByIdQuery($conn, $evento_id);

$resultPartecipanti = getPartecipantiEvento($conn, $evento_id);
$frase_partecipanti = "Interessa a: ";
if ($resultPartecipanti && $resultPartecipanti->num_rows > 0) {
    while ($partecipante = $resultPartecipanti->fetch_assoc()) {
        $frase_partecipanti .= $partecipante['nome'] . " " . $partecipante['cognome'] . ", ";
    }
    $frase_partecipanti = rtrim($frase_partecipanti, ", ") . ".";
} else {
    $frase_partecipanti .= " Nessuno.";
}

$mesi_italiano = array(
    'January' => 'Gennaio',
    'February' => 'Febbraio',
    'March' => 'Marzo',
    'April' => 'Aprile',
    'May' => 'Maggio',
    'June' => 'Giugno',
    'July' => 'Luglio',
    'August' => 'Agosto',
    'September' => 'Settembre',
    'October' => 'Ottobre',
    'November' => 'Novembre',
    'December' => 'Dicembre'
);

function getItalianMonth($month) {
    global $mesi_italiano;
    return $mesi_italiano[$month];
}

$data_inizio = strftime("%d", strtotime($row['data_inizio'])) . ' ' . getItalianMonth(strftime("%B", strtotime($row['data_inizio']))) . strftime(" %Y", strtotime($row['data_inizio']));
$orario_inizio = strftime("%H:%M", strtotime($row['orario_inizio']));

if($row['data_fine'] == null){
    $data_fine = $data_inizio;
}else{
    $data_fine = strftime("%d", strtotime($row['data_fine'])) . ' ' . getItalianMonth(strftime("%B", strtotime($row['data_fine']))) . strftime(" %Y", strtotime($row['data_fine']));
}

if($row['orario_fine'] == null){
    $orario_fine = $orario_inizio;
}else{
    $orario_fine = strftime("%H:%M", strtotime($row['orario_fine']));
}

$username_creatore = getUsernameById($conn, $row['creatore_id']);

$is_admin = 0;

if ($userData) {
    $is_admin = $userData['permessi'];
}

$immagine = '<div class="boxImage-pe">
<div class="imgEvent-pe">
    <img src="' . $row['url_immagine'] . '" alt="immagine evento">
</div>
</div>';

$evento = '<div id="pannello-principale-pe">
    <div class="containerPrincipale-pe">
        <div class="container_sx-pe">
            <h2>' . $row['titolo'] . '</h2>
            <p class="dataEvento-pe">Inizio: <time datetime="' . $row['data_inizio'] . '">' . $data_inizio . ' alle ore ' . $orario_inizio . '</time></p>
            <p class="dataEvento-pe">Fine: <time datetime="' . $row['data_fine'] . '">' . $data_fine . ' alle ore ' . $orario_fine . '</time></p>
            <p>Luogo: ' . $row['luogo'] . '</p>
            <p>Prezzo: ' . $row['costo'] . '</p>
        </div>
        <div class="container_dx-pe">';
            if (interessato($conn, $_SESSION['user_id'], $_GET['evento'])) {
                $evento .= '<form method="post" action="pageEvent.php?evento='.$row["evento_id"].'">
                <button id="salvaEventoButton" class="sonoInteressato-pe" name="disdici_evento" value="' . $row['evento_id'] . '">Non mi interessa più</button>
            </form>';
            }else{
                $evento .= '<form method="post" action="pageEvent.php?evento='.$row["evento_id"].'">
                <button id="salvaEventoButton" class="sonoInteressato-pe" name="salva_evento" value="' . $row['evento_id'] . '">Mi interessa!</button>
            </form>';
            }
            if ($is_admin == 1) {
                $evento .= '<form method="POST" action="pageEvent.php?evento='.$row["evento_id"].'"><button name="elimina" class="elimina-pe">Elimina</button></form>';
            }
$evento .= '</div>
    </div>
    <div class="containerSecondario-pe">
        <h2><span style="font-weight:bold;">Dettagli</span></h2>
        <div class="accountEvento-pe">
            <img src="../Images/idea.png" alt="Account">
            <p class="accountName-pe">Evento di <span style="font-weight:bold;">' . $username_creatore . '</span></p>
        </div>
        <div class="personeInteressate-pe">
            <img src="../Images/people_icon_small.png" alt="Immagine persone interessate">
            <p class="peopleInterested-pe">' . $frase_partecipanti . '</p>
        </div>
        <div class="descrizione-pe"><span style="font-weight:bold;">Descrizione: </span>' . $row['descrizione'] . '</div>
    </div>
</div>';

$errore = "<p class='event-error' tabindex='0'> $txt_error </p>";

$template = str_replace('{IMMAGINE}', $immagine, $template);
$template = str_replace('{ERRORE}', $errore, $template);
$template = str_replace('{EVENTO}', $evento, $template);
echo $template;

$conn->close();
?>
