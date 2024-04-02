<?php
require_once 'queries.php';
session_start();
include 'user_session.php';
$accedi_stringa = gestisciAccesso($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION['username']) || isset($_SESSION['email'])) {
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['email'];
    $userData = getUserByMailOrUsername($conn, $user); 
    if ($userData) {
        $userId = $userData['utente_id'];
        $profile_image_path = getProfilePhoto($conn, $userId);
        if (!$profile_image_path) {
            $profile_image_path = '../Images/people_icon_small.png'; 
        } 
        // Ottenere le informazioni dell'utente
        $userData = getUserByUsernameOrEmail($conn, $_SESSION['username']);
        $nome = $userData['nome'] . ' ' . $userData['cognome'];
        $username = $userData['username'];
        $email = $userData['email'];
        $telefono = $userData['telefono'];
        $data_nascita = $userData['data_nascita'];
        $genere = $userData['genere'];

        // Funzione per formattare la data di nascita
        function formatDataNascita($data_nascita) {
            return date('d F Y', strtotime($data_nascita));
        }
     }
    }
}   
?>