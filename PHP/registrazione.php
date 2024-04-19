<?php
require_once 'queries.php';
session_start();

$registration_feedback = '';
$template = file_get_contents('../HTML/regNav.html');

// Controllo dei campi obbligatori
$required_fields = ['username', 'password', 'email', 'password_confirm', 'nome', 'cognome'];
$error_count = 0;
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $registration_feedback .= "<p class='signin-error'>Il campo $field è obbligatorio</p>";
        $error_count++;
    }
}

if (isset($_POST['password'], $_POST['password_confirm'])) {
    // Le chiavi 'password' e 'password_confirm' esistono nell'array $_POST

    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Controllo se le password coincidono
    if ($password !== $password_confirm) {
        $registration_feedback .= "<p class='signin-error'>Le password non corrispondono</p>";
        $error_count++;
    }
} else {
    // Se una delle chiavi manca nell'array $_POST, aggiungi un messaggio di errore
    $registration_feedback .= "<p class='signin-error'>Campi password mancanti nel modulo</p>";
    $error_count++;
}

if ($error_count === 0) {
    $username = clearInput($_POST['username']);
    $password = clearInput($_POST['password']);
    $email = clearInput($_POST['email']);
    $nome = clearInput($_POST['nome']);
    $cognome = clearInput($_POST['cognome']);

    // Verifica se esistono già utenti con lo stesso username o email
    $existing_user_by_username = getUserByMailOrUsername($conn, $username);
    $existing_user_by_email = getUserByMailOrUsername($conn, $email);

    if ($existing_user_by_username) {
        $registration_feedback .= "<p class='signin-error'>Questo username è gia stato utilizzato all'interno del nostro sito</p>";
        $template = str_replace('{ERRORE}', $registration_feedback, $template);
    } elseif ($existing_user_by_email) {
        $registration_feedback .= "<p class='signin-error'>Questa password è già stata utilizzata all'interno del nostro sito</p>";
        $template = str_replace('{ERRORE}', $registration_feedback, $template);
    } else {
        // Effettua la registrazione
        $res=effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password,"Femmina");
        if($res==""){
            $res = getUserByMailOrUsername($conn, $username);
            if($res===false){
                header('Location: 404.php');
                exit();
            }else{
                $_SESSION['user_id'] = $res['utente_id'];
                header('Location: ../php/accessNav.php');
            }
        }else{
            header('Location: 404.php');
            exit();
        }
    }
}else{
    $template = str_replace('{ERRORE}', $registration_feedback, $template);
}

echo $template;

$conn->close();

?>