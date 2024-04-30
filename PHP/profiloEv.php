<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    header("Location: ../HTML/index.html");
    exit();
}

require_once 'queries.php';
$eventiCreati = "";

if (isset($_SESSION['user_id'])) {
    $template = file_get_contents("../HTML/ProfiloEv.html");
    $ris = getEventiCreati($conn, $_SESSION['user_id']);
    if($ris == false){
        $eventiCreati = '<p class="nessunEventoTrovatoImpo">
                        Non ci sono eventi creati... per ora!
                        </p>';
    } else {
        while($evento = $ris->fetch_assoc()){
            $eventiCreati .= '<div class="cardCreato-ev">
                                <div class="card-body-creati-ev">
                                <a class="a_evento" href="../PHP/pageEvent.php?evento='.$evento["evento_id"].'">
                                    <img src="' . $evento["url_immagine"] . '" class="container-immagini-eventi-ev" alt="scheda dell&#39;evento '.$evento["titolo"].'">
                                    <div class="NomiLinkCreati-ev" aria-hidden="true">
                                        <h3>'.$evento["titolo"].'</h3>
                                    </div>
                                    <p class="infoEventoCreato-ev" aria-hidden="true">'.$evento["data_inizio"].'</p>
                                    <p class="infoEventoCreato-ev" aria-hidden="true">'.$evento["orario_inizio"].' – '.$evento["luogo"].'</p>
                                    <span class="genereEventoCreato-ev" aria-hidden="true">'.$evento["categoria"].'</span>
                                    </div>
                                </a>
                            </div>';
        }
    }
} else {
    header("Location: ../php/accessNav.php");
    exit();
}

$template = str_replace('{EVENTICREATI}', $eventiCreati, $template);

echo $template;

?>
