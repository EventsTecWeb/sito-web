<?php
session_start();

// Verifica se l'utente ha effettuato il login
if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    // Reindirizza l'utente alla pagina di accesso non autorizzato (pagina X)
    header("Location: ../HTML/index.html");
    exit(); // Assicura che il codice successivo non venga eseguito
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
            } if (isset($_POST['elimina-account'])) {
                // Effettua l'eliminazione degli eventi associati all'account
                $sql_elimina_eventi = "DELETE FROM eventi WHERE creatore_id = ?";
                $stmt_elimina_eventi = $conn->prepare($sql_elimina_eventi); // Prepara lo statement per l'eliminazione degli eventi
                $stmt_elimina_eventi->bind_param("i", $userId); // Bind dei parametri
                if ($stmt_elimina_eventi->execute()) { // Esegui la query per eliminare gli eventi
                    // Se l'eliminazione degli eventi ha successo, procedi con l'eliminazione dell'account
                    $sql_elimina_utente = "DELETE FROM Utenti WHERE utente_id = ?";
                    $stmt_elimina_utente = $conn->prepare($sql_elimina_utente); // Prepara lo statement per l'eliminazione dell'account
                    $stmt_elimina_utente->bind_param("i", $userId); // Bind dei parametri
                    if ($stmt_elimina_utente->execute()) { // Esegui la query per eliminare l'account
                        echo "Account eliminato con successo.";
                        logout();
                        exit;
                    } else {
                        echo "Errore nell'eliminazione dell'account: " . $stmt_elimina_utente->error;
                    }
                    $stmt_elimina_utente->close(); // Chiudi lo statement per l'eliminazione dell'account
                } else {
                    echo "Errore nell'eliminazione degli eventi: " . $stmt_elimina_eventi->error;
                }
                $stmt_elimina_eventi->close(); // Chiudi lo statement per l'eliminazione degli eventi
            }
             elseif (isset($_POST['logout'])) { 
                logout();
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


include "../HTML/impoSicu.html";
?>