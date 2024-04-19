<?php
require_once 'queries.php';
session_start();

function sanitize($input) {
    return $input;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['username']) || isset($_SESSION['email'])) {
        $user = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['email'];
        $userData = getUserByMailOrUsername($conn, sanitize($user));
        if ($userData) {
            $userId = $userData['utente_id'];
            $profile_image_path = getProfilePhoto($conn, $userId);
            if (!$profile_image_path) {
                $profile_image_path = '../Images/people_icon_small.png';
            }
        }
    }
}

// TODO: DA IMPLEMENTARE CON IL COLLEGAMENTO DELLE PAGINE
/*if (isset($_GET['evento_id'])) {
    $evento_id = $_GET['evento_id'];
} else {
    // Handle the case where the event ID is not provided
    echo "ID dell'evento non specificato.";
    exit;
}*/
$evento_id=1;

$template = file_get_contents("../HTML/pageEvent.html");

$row = getEventiByIdQuery($conn, sanitize($evento_id));

$resultPartecipanti = getPartecipantiEvento($conn, sanitize($evento_id));
$frase_partecipanti = "Interessato a ";
if ($resultPartecipanti && $resultPartecipanti->num_rows > 0) {
    while ($partecipante = $resultPartecipanti->fetch_assoc()) {
        $frase_partecipanti .= $partecipante['nome'] . " " . $partecipante['cognome'] . ", ";
    }
    $frase_partecipanti = rtrim($frase_partecipanti, ", ") . ".";
} else {
    $frase_partecipanti .= "nessuno al momento.";
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

// Funzione per ottenere il nome del mese in italiano
function getItalianMonth($month) {
    global $mesi_italiano;
    return $mesi_italiano[$month];
}

$data_inizio = strftime("%d", strtotime($row['data_inizio'])) . ' ' . getItalianMonth(strftime("%B", strtotime($row['data_inizio']))) . strftime(" %Y", strtotime($row['data_inizio']));
$orario_inizio = strftime("%H:%M", strtotime($row['orario_inizio']));

$evento = '<div id="pannello-principale-pe">
    <div class="boxImage-pe">
        <div class="imgEvent-pe">
            <img src="' . $row['url_immagine'] . '" alt="immagine evento">
        </div>
    </div>
    <div class="containerPrincipale-pe">
        <div class="container_sx-pe">
            <h2>' . $row['titolo'] . '</h2>
            <p class="dataEvento-pe"><time datetime="' . $row['data_inizio'] . '">' . $data_inizio . ' alle ore ' . $orario_inizio . '</time></p>
            <p>' . $row['luogo'] . '</p>
        </div>
        <div class="container_dx-pe">
            <button class="sonoInteressato-pe"> Sono Interessato</button>
            <button class="sonoInteressato-pe"> Elimina</button>
        </div>
    </div>
    <div class="containerSecondario-pe">
        <h2><span style="font-weight:bold;">Dettagli</span></h2>
        <div class="accountEvento-pe">
            <img src="../Images/idea.png" alt="Account">
            <p class="accountName-pe">Evento di <span style="font-weight:bold;">' . $row['creatore_id'] . '</span></p>
        </div>
        <div class="personeInteressate-pe">
            <img src="../Images/people_icon_small.png" alt="Immagine persone interessate">
            <p class="peopleInterested-pe">' . $frase_partecipanti . '</p>
        </div>
        <div class="descrizione-pe"><span style="font-weight:bold;">Descrizione: </span>' . $row['descrizione'] . '</div>
    </div>
</div>';

$template = str_replace('{EVENTO}', $evento, $template);
echo $template;

$conn->close();
?>
