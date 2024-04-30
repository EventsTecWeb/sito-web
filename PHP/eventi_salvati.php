<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    header("Location: ../PHP/404.php");
    exit();
}

require_once 'queries.php';
$eventiSalvati="";
if (isset($_SESSION['user_id'])) {
    $template = file_get_contents("../HTML/eventiSalvati.html");
    $ris = getEventiSalvati($conn, $_SESSION['user_id']);
    if(is_array($ris) && empty($ris)){
        $eventiSalvati='<p class="nessunEventoTrovatoImpo">
                        Non ci sono eventi salvati... per ora!
                        </p>';
    } else if(is_array($ris)) {
        foreach($ris as $evento) {
            $eventiSalvati.='<div class="cardSalvato-es">
            <a class="a_evento" href="../PHP/pageEvent.php?evento='.$evento["evento_id"].'">
                                    <div class="card-body-salvati-es">
                                        <img src="' . $evento["url_immagine"] . '" class="container-immagini-eventi-es" alt="scheda dell&#39;evento '.$evento["titolo"].'">
                                        <div class="NomiLinkSalvati-es">
                                            <h3 aria-hidden="true">' . $evento["titolo"] . '</h3>
                                        </div>
                                        <p class="infoEventoSalvato-es" aria-hidden="true">' . $evento["orario_inizio"] . ' – ' . $evento["luogo"] . '</p>
                                        <p class="genereEventoSalvato-es" aria-hidden="true">' . $evento["categoria"] . '</p>
                                    </div>
                                </a>
                            </div>';
        }
    } else {
        // Gestire il caso in cui $ris non è un array (ad esempio, potrebbe essere false)
    }
} else {
    header("Location: ../php/accessNav.php");
    exit();
}

$template = str_replace('{EVENTI}', $eventiSalvati, $template);
echo $template;
?>