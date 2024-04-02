<?php
session_start();
require_once 'queries.php';
$eventiCreati = "";

if (isset($_SESSION['user_id'])) {
    $template = file_get_contents("../HTML/profiloEv.html");
    $ris = getEventiCreati($conn, $_SESSION['user_id']);
    if($ris == false){
        $eventiCreati = '<div class="cardSalvato-es" id="event4Salvato-es">
                        non ci sono eventi creati
                        </div>';
    } else {
        while($evento = $ris->fetch_assoc()){
            $eventiCreati .= '<div class="cardSalvato-es" id="event2Salvato-es">
                                <div class="card-body-salvati-es">
                                <a href="link_per_natale_a_roma.html" class="NomiLinkSalvati-es">
                                    <h3>'.$evento["titolo"].'</h3>
                                </a>
                                <p class="infoEventoSalvato-es">'.$evento["orario_inizio"].' – '.$evento["luogo"].'</p>
                                <span class="genereEventoSalvato-es">'.$evento["categoria"].'</span>
                                <a href="" class="container-immagini-eventi-es">
                                    <img src="' . $evento["url_immagine"] . '" class="container-immagini-eventi-es">
                                </a>
                                </div>
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
