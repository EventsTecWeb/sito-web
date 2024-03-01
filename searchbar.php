<?php
/*da riguardare*/
require_once '../../queries/queries.php';
session_start();
include 'user_session.php';
$accedi_stringa = gestisciAccesso($conn);

$datalist_options = '';
$evento_trovato = null;
$categorie_options = '';


if(isset($_GET['evento_titolo'])) {
    $ricerca_evento_titolo = clearInput($_GET['evento_titolo']);
    $evento_trovato = getEventoByTitolo($conn, $ricerca_evento_titolo);
    $conn->close();
}


if(isset($_GET['categoria_evento'])) {
    $categoria_evento_selezionata = clearInput($_GET['categoria_evento']);
    $result_eventi = getEventiByCategoria($conn, $categoria_evento_selezionata);
} else {
    $result_eventi = getEventi($conn);
}

// Popolo le opzioni per il datalist
while ($row = $result_eventi->fetch_assoc()) {
    $evento_titolo = $row['titolo'];
    $datalist_options .= "<option value='$evento_titolo'>";
}

$result_categorie = getCategorie($conn);
while ($row = $result_categorie->fetch_assoc()) {
    $categoria = $row['categoria'];
    $categorie_options .= "<option value='$categoria'>$categoria</option>";
}

if ($evento_trovato) {
    $evento_id = $evento_trovato['evento_id'];
    header("Location: info_evento.php?evento=$evento_id");
    exit();
}


$barra_ricerca_eventi_html = file_get_contents('../html/barra_ricerca_eventi.html'); 


$barra_ricerca_eventi_html = str_replace('{ACCEDI}', $accedi_stringa, $barra_ricerca_eventi_html);
$barra_ricerca_eventi_html = str_replace('{RICERCA_EVENTO_TITOLO}', $ricerca_evento_titolo ?? '', $barra_ricerca_eventi_html);
$barra_ricerca_eventi_html = str_replace('{DATALIST-OPTIONS}', $datalist_options, $barra_ricerca_eventi_html);
$barra_ricerca_eventi_html = str_replace('{CATEGORIE-OPTIONS}', $categorie_options, $barra_ricerca_eventi_html);

echo $barra_ricerca_eventi_html; /*da sviluppare questo codice*/

?>
