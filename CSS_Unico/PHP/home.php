<?php
use DB\DBAccess;

$template = "home.html";
$query = "SELECT titolo, orario_inizio, orario_fine, luogo, costo, categoria, url_immagine FROM Eventi WHERE orario_inizio > NOW()";

$error = "";
$connection = new DBAccess();

if ($connection->openConnection()) {
    $results = $connection->executeSelect($query);
    if ($results) {
        $content = '';
        foreach ($results as $result) {
            $content .= '<div class="row">
                            <div class="column">
                                <div class="container">
                                    <img src="' . $result["url_immagine"] . '" alt="music event">
                                    <h4 class="descrizioneEventiT">' . $result["titolo"] . '</h4>
                                    <time class="descrizioneEventiD" datetime="' . $result["orario_inizio"] . '">' . $result["orario_inizio"] . ' - ' . $result["luogo"] . '</time>
                                    <p class="descrizioneEventiD">' . $result["costo"] . '</p>
                                </div>
                            </div>
                        </div>';
        }
        $connection->closeConnection();
    } else {
        // Nessun evento trovato, reindirizza alla pagina 404
        header('Location:404.html');
        exit; // Assicura che lo script termini dopo il reindirizzamento
    }
} else {
    // Errore di connessione al database
    $error = '<p>Errore! Non è stato possibile connettersi al database. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
}
?>