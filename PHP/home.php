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

if ($results!=null) {
    $row = $results->fetch_assoc();
    if ($row) {
        $template = file_get_contents('../HTML/home.html');
        $titolo=$row['titolo'];
        $data_inizio=$row['data_inizio'];
        $luogo = $row['luogo'];
        $costo = $row['costo'];
    } else {
        $error = '<p>Errore! Nessun <span lang="en">evento</span> trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
        header('Location:404.html');
    }
} else {
    $error = '<p>Errore! Nessun <span lang="en">evento</span> trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
    header('Location:404.html');
}

$evidenza=' <div class="column-home-e">
<a class="a_evento" href="../PHP/pageEvent.php?evento='.$row["evento_id"].'" alt="premi per accedere alla pagina dell&#39evento">
                    <div class="container-home-evidenza-e">
                    <img src="'.$row["url_immagine"].'" alt="music event">
                    <h4 class="descrizioneEventiT-home">'.$row["titolo"].'</h4>
                        <time class="descrizioneEventiD-home" datetime="'.$row["data_inizio"].' - '.$row["luogo"].'</time>
                        <p class="descrizioneEventiD-home">'.$row["categoria"].'</p>
                    </div>
                </a>
            </div>';


$pEventi = getProssimiEventi($conn);

$prossimiEventi="";
if (!is_null($pEventi)) {
    foreach ($pEventi as $evento) {
		$prossimiEventi.=
		'<div class="column-home">
        <a class="a_evento" href="../PHP/pageEvent.php?evento='.$evento["evento_id"].'" alt="premi per accedere alla pagina dell&#39evento">
                <div class="container-home-evidenza">
                    <img src="'.$evento["url_immagine"].'" alt="music event">
                    <h4 class="descrizioneEventiT-home">'.$evento["titolo"].'</h4>
                    <time class="descrizioneEventiD-home" datetime="2023-12-11 21:00">'.$evento["data_inizio"].'</time>
                    <p class="descrizioneEventiL-home">'.$evento["luogo"].'</p>
                    <p class="descrizioneEventiG-home">'.$evento["categoria"].'</p>
                </div>
            </a>
		</div>';
    }
} else {
    echo "Non ci sono eventi futuri.";
}
$template = str_replace('{EVIDENZA}', $evidenza, $template);
$template = str_replace('{PROSSIMIEVENTI}', $prossimiEventi, $template);

echo $template;
?>