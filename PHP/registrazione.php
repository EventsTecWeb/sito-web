<?php
require_once 'queries.php';
session_start();

$registration_feedback = '';
$template = file_get_contents('../HTML/regNav.html');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error_count = 1;
    $required_fields = ['username', 'password', 'email', 'password_confirm', 'nome', 'cognome'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $registration_feedback .= "<p class='signin-error' tabindex='0'>Il campo $field è obbligatorio</p>";
            $error_count++;
        }
    }
    if ($error_count === 1) {
        if (!validateName($_POST['nome'])) {
            $registration_feedback .= "<p class='signin-error' tabindex='0'>Il nome contiene caratteri non validi</p>";
            $error_count++;
        }
        if (!validateName($_POST['cognome'])) {
            $registration_feedback .= "<p class='signin-error' tabindex='0'>Il cognome contiene caratteri non validi</p>";
            $error_count++;
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $registration_feedback .= "<p class='signin-error' tabindex='0'>L'email non è valida</p>";
            $error_count++;
        }
    }
    if ($error_count === 1) {
        if (isset($_POST['password'], $_POST['password_confirm'])) {
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            if ($password !== $password_confirm) {
                $registration_feedback .= "<p class='signin-error' tabindex='0'>Le password non corrispondono</p>";
                $error_count++;
            }
        } else {
            $registration_feedback .= "<p class='signin-error' tabindex='0'>Devi compilare entrambi i campi password</p>";
            $error_count++;
        }
    }
    if ($error_count === 1) {
        $username = clearInput($_POST['username']);
        $password = clearInput($_POST['password']);
        $email = clearInput($_POST['email']);
        $nome = clearInput($_POST['nome']);
        $cognome = clearInput($_POST['cognome']);
        $existing_user_by_username = getUserByMailOrUsername($conn, $username);
        $existing_user_by_email = getUserByMailOrUsername($conn, $email);
        if ($existing_user_by_username) {
            $registration_feedback .= "<p class='signin-error' tabindex='0'>Questo username è già stato utilizzato all'interno del nostro sito</p>";
            $error_count++;
        } 
        if ($existing_user_by_email) {
            $registration_feedback .= "<p class='signin-error' tabindex='0'>Questa email è già stata utilizzata all'interno del nostro sito</p>";
            $error_count++;
        } 
        if ($error_count === 1) {
            $res = effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password, "Altro", "Pubblico");
            if ($res === null) {
                $res = getUserByMailOrUsername($conn, $username);
                if ($res === false) {
                    header('Location: 404.php');
                    exit();
                } else {
                    header('Location: ../php/accessNav.php');
                    exit();
                }
            } else {
                $registration_feedback .= "<p class='signin-error' tabindex='0'>$res</p>";
                $error_count++;
            }
        }
    }
}

if (!empty($registration_feedback)) {
    $template = str_replace('{ERRORE}', $registration_feedback, $template);
} else {
    $template = str_replace('{ERRORE}', '', $template);
}

echo $template;
$conn->close();
?>
