<?php
require_once 'queries.php';
session_start();

$template = file_get_contents('../HTML/infoPersonali.html');

function formatDataNascita($data_nascita) {
    setlocale(LC_TIME, 'it_IT');
    return strftime('%d %B %Y', strtotime($data_nascita));
}


if(isset($_SESSION['username']) || isset($_SESSION['email'])) {
    if(isset($_POST["day"]) and isset($_POST["month"]) and isset($_POST["year"])){
        insertUserDateOfBirth($conn, $_SESSION['user_id'], $_POST["dai"]+$_POST["month"]+$_POST["year"]);
    }
    if(isset($_POST["email"])){
        updateUserEmail($conn, $_SESSION['user_id'], clearInput($_POST["email"]));
        $_SESSION["email"]=$_POST["email"];
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

    $user = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['email'];
    $userData = getUserByMailOrUsername($conn, $user);
    
    if ($userData) {
        $userId = $userData['utente_id'];
        $profile_image_path = getProfilePhoto($conn, $userId) ?: '../Images/people_icon_small.png';
        
        $nomeCompleto = htmlspecialchars($userData['nome']) . ' ' . htmlspecialchars($userData['cognome']);
        $username = htmlspecialchars($userData['username']);
        $email = htmlspecialchars($userData['email']);
        $telefono = htmlspecialchars($userData['telefono']);
        $data_nascita = formatDataNascita($userData['data_nascita']);
        $genere = htmlspecialchars($userData['genere']);
        
        $template = str_replace('{NOME}', $nomeCompleto, $template);
        $template = str_replace('{USERNAME}', $username, $template);
        $template = str_replace('{GENERE}', $genere, $template);
        $template = str_replace('{DATANASCITA}', $data_nascita, $template);
        $template = str_replace('{EMAIL}', $email, $template);
        $template = str_replace('{TELEFONO}', $telefono, $template);
        // Assume there is a placeholder for the profile image path in the template
        $template = str_replace('{PROFILE_IMAGE_PATH}', $profile_image_path, $template);
    }
}
echo $template;
?>
