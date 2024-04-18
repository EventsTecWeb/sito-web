<?php
session_start();
require_once 'queries.php';
$template = file_get_contents("../HTML/impoSicu.html");
echo $template;

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
                    echo "Impostazioni di privacy aggiornate con successo.";
                } else {
                    echo "Errore nell'aggiornamento delle impostazioni di privacy: " . $stmt->error;
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
                        echo "Password aggiornata con successo.";
                    } else {
                        echo "Errore nell'aggiornamento della password: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Le password non corrispondono.";
                }
            } elseif (isset($_POST['elimina-account'])) {
                // Effettua l'eliminazione dell'account
                $sql = "DELETE FROM Utenti WHERE utente_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                if ($stmt->execute()) {
                    echo "Account eliminato con successo.";
                    logout();
                    header("Location: ../HTML/index.html"); // Reindirizza alla pagina index
                    exit;
                } else {
                    echo "Errore nell'eliminazione dell'account: " . $stmt->error;
                }
                $stmt->close();
            } elseif (isset($_POST['logout'])) {
                logout();
                header("Location: ../HTML/index.html"); // Reindirizza alla pagina index
                exit;
            }
        } else {
            echo "Utente non trovato.";
        }
    } else {
        echo "Utente non autenticato.";
    }
}
$conn->close();
?>