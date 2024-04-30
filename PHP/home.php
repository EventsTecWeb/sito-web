<?php

session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    header("Location: pagina_non_autorizzata.php");
    exit();
}

require_once 'queries.php';
$template = file_get_contents("../HTML/home.html");

$errore = "";
$results = getEventiByCategoria($conn, "");
$result_last_seen = getEventiByID($conn, getCookieValue("cookiesBanner"));

if ($results!=null) {
    $row = $results->fetch_assoc();
    if ($row) {
        $template = file_get_contents('../HTML/home.html');
        $titolo=$row['titolo'];
        $data_inizio=$row['data_inizio'];
        $luogo = $row['luogo'];
        $costo = $row['costo'];
    } else {
        $error = '<p>Errore! Nessun evento trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
        header('Location:404.html');
    }
} else {
    $error = '<p>Errore! Nessun evento trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
    header('Location:404.html');
}

$evidenza = '<div class="column-home-e">
    <a class="a_evento" href="../PHP/pageEvent.php?evento='.$row["evento_id"].'">
        <div class="container-home-evidenza-e">
            <img src="'.$row["url_immagine"].'" alt="Scheda dell&#39;evento in evidenza: '.$row["titolo"].'">
            <h4 class="descrizioneEventiT-home" aria-hidden="true">'.$row["titolo"].'</h4>
            <p class="descrizioneEventiD-home" aria-hidden="true">'.$row["data_inizio"].' - '.$row["luogo"].'</p>
            <p class="descrizioneEventiD-home" aria-hidden="true">'.$row["categoria"].'</p>
        </div>
    </a>
</div>';

if($result_last_seen!=null){
    $row = $result_last_seen->fetch_assoc();
    if ($row) {
        $template = file_get_contents('../HTML/home.html');
        $titolo=$row['titolo'];
        $data_inizio=$row['data_inizio'];
        $luogo = $row['luogo'];
        $costo = $row['costo'];
    } else {
        $error = '<p>Errore! Nessun evento trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
        header('Location:404.html');
    }
} else {
    $error = '<p>Errore! Nessun evento trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
    header('Location:404.html');
}

$lastSeen = '<div class="column-home-e">
<a class="a_evento" href="../PHP/pageEvent.php?evento='.$row["evento_id"].'">
    <div class="container-home-evidenza-e">
        <img src="'.$row["url_immagine"].'" alt="Scheda dell&#39;ultimo evento salvato: '.$row["titolo"].'">
        <h4 class="descrizioneEventiT-home" aria-hidden="true">'.$row["titolo"].'</h4>
        <p class="descrizioneEventiD-home" aria-hidden="true">'.$row["data_inizio"].' - '.$row["luogo"].'</p>
        <p class="descrizioneEventiD-home" aria-hidden="true">'.$row["categoria"].'</p>
    </div>
</a>
</div>';


$pEventi = getProssimiEventi($conn);

$prossimiEventi="";
if (!is_null($pEventi)) {
    foreach ($pEventi as $evento) {
		$prossimiEventi.=
		'<div class="column-home">
        <a class="a_evento" href="../PHP/pageEvent.php?evento='.$evento["evento_id"].'">
            <div class="container-home-evidenza">
                <img src="'.$evento["url_immagine"].'" alt="scheda dell&#39;evento '.$evento["titolo"].'">
                <h4 class="descrizioneEventiT-home" aria-hidden="true">'.$evento["titolo"].'</h4>
                <p class="descrizioneEventiD-home" aria-hidden="true">'.$evento["data_inizio"].'</p>
                <p class="descrizioneEventiL-home" aria-hidden="true">'.$evento["luogo"].'</p>
                <p class="descrizioneEventiG-home" aria-hidden="true">'.$evento["categoria"].'</p>
            </div>
        </a>
    </div>';
    }
} else {
    echo "Non ci sono eventi futuri.";
}
$template = str_replace('{EVIDENZA}', $evidenza, $template);
$template = str_replace('{LASTSEEN}', $lastSeen, $template);
$template = str_replace('{PROSSIMIEVENTI}', $prossimiEventi, $template);

echo $template;
?>