<?php
session_start();

$template = file_get_contents("../HTML/impoSicu.html");

$txt_error = "";
$privacy_profile = "";

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    header("Location: ../HTML/index.html");
    exit();
}

require_once 'queries.php';
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
            if (isset($_POST['privacy'])) {
                $privacy = $_POST['privacy'];
                $sql = "UPDATE Utenti SET privacy = ? WHERE utente_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $privacy, $userId);
                if ($stmt->execute()) {
                    $txt_error = "Impostazioni di privacy aggiornate con successo.";
                } else {
                    $txt_error = "Errore nell'aggiornamento delle impostazioni di privacy: " . $stmt->error;

                }
                $stmt->close();
            } elseif (isset($_POST['new-password']) && isset($_POST['repeat-password'])) {
                $newPassword = $_POST['new-password'];
                $repeatPassword = $_POST['repeat-password'];
                if ($newPassword === $repeatPassword) {
                    $sql = "UPDATE Utenti SET password = ? WHERE utente_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $newPassword, $userId);
                    if ($stmt->execute()) {
                        $txt_error = "Password aggiornata con successo.";
                    } else {
                        $txt_error = "Errore nell'aggiornamento della password: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $txt_error = "Le password non corrispondono.";
                }
            } if (isset($_POST['elimina-account'])) {
                $sql_elimina_eventi = "DELETE FROM eventi WHERE creatore_id = ?";
                $stmt_elimina_eventi = $conn->prepare($sql_elimina_eventi);
                $stmt_elimina_eventi->bind_param("i", $userId);
                if ($stmt_elimina_eventi->execute()) {
                    $sql_elimina_utente = "DELETE FROM Utenti WHERE utente_id = ?";
                    $stmt_elimina_utente = $conn->prepare($sql_elimina_utente);
                    $stmt_elimina_utente->bind_param("i", $userId);
                    if ($stmt_elimina_utente->execute()) {
                        $txt_error = "Account eliminato con successo.";
                        logout();
                        exit;
                    } else {
                        $txt_error = "Errore nell'eliminazione dell'account: " . $stmt_elimina_utente->error;
                    }
                    $stmt_elimina_utente->close();
                } else {
                    $txt_error = "Errore nell'eliminazione degli eventi: " . $stmt_elimina_eventi->error;
                }
                $stmt_elimina_eventi->close();
            }
             elseif (isset($_POST['logout'])) { 
                logout();
                exit;
            }
        } else {
            $txt_error = "Utente non trovato.";
        }
    } else {
        $txt_error = "Utente non autenticato.";
    }
}

if (isset($_SESSION['username']) || isset($_SESSION['email'])) {
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['email'];
    $userData = getUserByMailOrUsername($conn, $user);
    if ($userData) {
        $userId = $userData['utente_id'];
        $privacy_profile = $userData['privacy'];
    }
}

if (!isset($privacy_profile)) {
    $privacy_profile = "";
}

$errore = "<p class='impo-error'> $txt_error </p>";

$template = str_replace('{ERROR}', $errore, $template);
$template = str_replace('{PRIVACY}', $privacy_profile, $template);
echo $template;

$conn->close();

?>