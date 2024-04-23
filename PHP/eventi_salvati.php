<?php
session_start();

// Verifica se l'utente ha effettuato il login
if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    // Reindirizza l'utente alla pagina di accesso non autorizzato (pagina X)
    header("Location: pagina_non_autorizzata.php");
    exit(); // Assicura che il codice successivo non venga eseguito
}

require_once 'queries.php';
$eventiSalvati="";
if (isset($_SESSION['user_id'])) {
    $template = file_get_contents("../HTML/eventiSalvati.html");
    $ris = getEventiSalvati($conn, $_SESSION['user_id']);
    if($ris==false){
        $eventiSalvati='<p class="nessunEventoTrovatoImpo">
                        Non ci sono eventi salvati... per ora!
                        </p>';
    }else{
        while($evento = $ris->fetch_assoc()){
            $eventiSalvati.='<div class="cardSalvato-es" id="event4Salvato-es">
            <a class="a_evento" href="../PHP/pageEvent.php?evento='.$evento["evento_id"].'" alt="premi per accedere alla pagina dell&#39evento">
                                    <div class="card-body-salvati-es">
                                        <img src="' . $evento["url_immagine"] . '" class="container-immagini-eventi-es">
                                        <div class="NomiLinkSalvati-es">
                                            <h3>' . $evento["titolo"] . '</h3>
                                        </div>
                                        <p class="infoEventoSalvato-es">' . $evento["orario_inizio"] . ' – ' . $evento["luogo"] . '</p>
                                        <p class="genereEventoSalvato-es">' . $evento["categoria"] . '</p>
                                    </div>
                                </a>
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
