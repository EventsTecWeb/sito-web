<?php
session_start();
require_once 'queries.php';
$eventiSalvati="";
if (isset($_SESSION['user_id'])) {
    $template = file_get_contents("../HTML/eventiSalvati.html");
    $ris = getEventiSalvati($conn, $_SESSION['user_id']);
    if($ris==false){
        $eventiSalvati='<div class="cardSalvato-es" id="event4Salvato-es">
                        non ci sono eventi salvati
                        </div>';
    }else{
        while($evento = $ris->fetch_assoc()){
            $eventiSalvati.='<div class="cardSalvato-es" id="event4Salvato-es">
                                <div class="card-body-salvati-es">
                                    <a href="link_per_natale_a_roma.html" class="NomiLinkSalvati-es">
                                        <h3>' . $evento["titolo"] . '</h3>
                                    </a>
                                    <p class="infoEventoSalvato-es">' . $evento["orario_inizio"] . ' – ' . $evento["luogo"] . '</p>
                                    <p class="genereEventoSalvato-es">' . $evento["categoria"] . '</p>
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



$template = str_replace('{EVENTI}', $eventiSalvati, $template);
echo $template;
?>
