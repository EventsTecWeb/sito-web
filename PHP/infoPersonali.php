<?php
require_once 'queries.php';
session_start();

$template = file_get_contents('../HTML/infoPersonali.html');

function formatDataNascita($data_nascita) {
    // Restituisce la data nel formato originale 'Y-m-d'
    return $data_nascita;
}

if(isset($_POST["day"]) and isset($_POST["month"]) and isset($_POST["year"])){
    $day = $_POST["day"];
    $month = $_POST["month"];
    $year = $_POST["year"];
    $dateOfBirth = $year . '-' . $month . '-' . $day;
}

if(isset($_SESSION['username']) || isset($_SESSION['email'])) {
    if(isset($_POST["day"]) and isset($_POST["month"]) and isset($_POST["year"])){
        insertUserDateOfBirth($conn, $_SESSION['user_id'], $dateOfBirth);
    }
    if(isset($_POST["email"])){
        // Ottieni la nuova email dall'input del modulo
        $newEmail = clearInput($_POST["email"]);
        
        // Chiama la funzione per aggiornare l'email dell'utente nel database
        updateUserEmail($conn, $_SESSION['user_id'], $newEmail);
        
        // Aggiorna anche l'email nella sessione per mantenerla coerente
        $_SESSION["email"] = $newEmail;
    }
    if(isset($_POST["telefono"])){
        updateUserPhone($conn, $_SESSION['user_id'], clearInput($_POST["telefono"]));
    }
    if(isset($_POST["genere"])){
        updateUserGender($conn, $_SESSION['user_id'], clearInput($_POST["genere"]));
    }
    if(isset($_POST["username"])){
        updateUserUsername($conn, $_SESSION['user_id'], clearInput($_POST["username"]));
    }
    if(isset($_POST["nome"]) && isset($_POST["cognome"])) {
        $nome = clearInput($_POST["nome"]);
        $cognome = clearInput($_POST["cognome"]);
        
        if(!empty($nome) && !empty($cognome)) {
            updateUserName($conn, $_SESSION['user_id'], $nome, $cognome);
        }
    }
    

    $user = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['email'];
    $userData = getUserByMailOrUsername($conn, $user);
    
    if ($userData) {
        $userId = $userData['utente_id'];
        $profile_image_path = getProfilePhoto($conn, $userId) ?: '../Images/people_icon_small.png';
        
        $nomeCompleto = htmlspecialchars($userData['nome']) . ' ' . htmlspecialchars($userData['cognome']);
        $username = htmlspecialchars($userData['username']);
        $email = htmlspecialchars($userData['email']);
        $telefono = htmlspecialchars($userData['telefono']);
        // $data_nascita = formatDataNascita($userData['data_nascita']);
        $genere = htmlspecialchars($userData['genere']);
        $data_nascita = formatDataNascita($userData['data_nascita']);

        $template = str_replace('{DATANASCITA}', $data_nascita, $template);
        $template = str_replace('{NOME}', $nomeCompleto, $template);
        $template = str_replace('{USERNAME}', $username, $template);
        $template = str_replace('{GENERE}', $genere, $template);
        // $template = str_replace('{DATANASCITA}', $data_nascita, $template);
        $template = str_replace('{EMAIL}', $email, $template);
        $template = str_replace('{TELEFONO}', $telefono, $template);
        // Assume there is a placeholder for the profile image path in the template
        $template = str_replace('{PROFILE_IMAGE_PATH}', $profile_image_path, $template);
    }
}
echo $template;
?>
