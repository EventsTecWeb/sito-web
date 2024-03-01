<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['privacy'])) {
        $privacy = $_POST['privacy'];
        // Aggiorna la privacy dell'utente nel database
        $sql = "UPDATE Utenti SET privacy = '$privacy' WHERE utente_id = 1"; // Supponendo che l'utente attuale sia l'utente con ID 1
        if ($conn->query($sql) === TRUE) {
            echo "Impostazioni di privacy aggiornate con successo.";
        } else {
            echo "Errore nell'aggiornamento delle impostazioni di privacy: " . $conn->error;
        }
    } elseif (isset($_POST['new-password']) && isset($_POST['repeat-password'])) {
        $newPassword = $_POST['new-password'];
        $repeatPassword = $_POST['repeat-password'];
        // Verifica se le password corrispondono e poi aggiorna la password dell'utente nel database
        if ($newPassword === $repeatPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE Utenti SET password = '$hashedPassword' WHERE utente_id = 1"; // Supponendo che l'utente attuale sia l'utente con ID 1
            if ($conn->query($sql) === TRUE) {
                echo "Password aggiornata con successo.";
            } else {
                echo "Errore nell'aggiornamento della password: " . $conn->error;
            }
        } else {
            echo "Le password non corrispondono.";
        }
    } elseif (isset($_POST['elimina-account'])) {
        // Gestisco l'eliminazione dell'account
        $sql = "DELETE FROM Utenti WHERE utente_id = 1"; // Supponendo che l'utente attuale sia l'utente con ID 1
        if ($conn->query($sql) === TRUE) {
            echo "Account eliminato con successo.";
            // Effettua il logout dell'utente o reindirizzalo alla pagina di login
        } else {
            echo "Errore nell'eliminazione dell'account: " . $conn->error;
        }
    }
}
$conn->close();
?>
