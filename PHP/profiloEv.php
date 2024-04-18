<?php
session_start();
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
            $eventiCreati .= '<div class="cardCreato-ev" id="event2Salvato-es">
                                <div class="card-body-creati-ev">
                                <a href="link_per_natale_a_roma.html" class="NomiLinkCreati-ev">
                                    <h3>'.$evento["titolo"].'</h3>
                                </a>
                                <p class="infoEventoCreato-ev">'.$evento["data_inizio"].'</p>
                                <p class="infoEventoCreato-ev">'.$evento["orario_inizio"].' – '.$evento["luogo"].'</p>
                                <span class="genereEventoCreato-ev">'.$evento["categoria"].'</span>
                                <a href="" class="container-immagini-eventi-ev">
                                    <img src="' . $evento["url_immagine"] . '" class="container-immagini-eventi-ev">
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
