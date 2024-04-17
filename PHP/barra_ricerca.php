<?php
require_once 'queries.php';
session_start();
include 'user_session.php';
$accedi_stringa = gestisciAccesso($conn);

$result_events = getEventi($conn);
$datalist_risultati = '';
while ($row = $result_film->fetch_assoc()) {
    $titolo_evento = $row['titolo'];
    $datalist_risultati .= "<option value='$titolo_evento'>";
}

if(isset($_GET['cerca_evento'])) {
    $evento = clearInput($_GET['cerca_evento']);
    $result = getFilmByName($conn, $evento);
    $conn->close();
}

if ($evento === '') {
    $result = null;
}

if ($result) {
    $evento = $result['evento_id'];
    header("Location: pageEvent.php?evento=$evento");
}
?>