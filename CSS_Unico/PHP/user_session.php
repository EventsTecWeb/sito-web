<?php
function gestisciAccesso($conn) {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $permessi = getPermessiByUsername($conn, $_SESSION['mail']);
        if ($permessi == true) {
            $accedi_stringa = "<a href='admin.php'>Area per gli amministratori di Events</a>";
        } else {
            $accedi_stringa = "<a href='profilo.php'>Area personale utente Events</a>";
        }
    } else {
        $accedi_stringa = '<a href="accedi.php">Accedi</a>';
    }
    return $accedi_stringa;
}
?>