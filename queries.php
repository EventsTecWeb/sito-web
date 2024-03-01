<?php
require_once 'database_con.php';

function clearInput($value) {
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
    return $value;
}

function getEventi($conn) {
    //Mi creo la query per selezionare tutti i campi della tabella eventi
    $sql = "SELECT * FROM Eventi";
    $result = $conn->query($sql);
    // Verifico se la query è stata eseguita correttamente
    if (!$result) {
        header('Location: ../php/500.php');
        exit();
    }
    return $result;
}

function getEventiByTitolo($conn, $titolo)
{
    // Preparo la per selezionare gli eventi con un determinato titolo
    $queryEventi = "SELECT * FROM Eventi WHERE titolo LIKE ?";
    $stmt = $conn->prepare($queryEventi);

    $titolo_like = "%" . $titolo . "%"; 
    $stmt->bind_param("s", $titolo_like);

    $stmt->execute();

    $resultEventi = $stmt->get_result();

    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi;
    } else {
        header('Location: ../php/404.php');
        exit;
    }
}

function getEventiByCategoria($conn, $categoria)
{
    // Mi preparo la query per selezionare gli eventi con una determinata categoria
    $queryEventi = "SELECT * FROM Eventi WHERE categoria = ?";
    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("s", $categoria);

    $stmt->execute();

    $resultEventi = $stmt->get_result();


    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi;
    } else {
        header('Location: ../php/404.php');
        exit;
    }
}

function getEventiByIdQuery($conn, $idEvento)
{
    // Mi perparo la query per selezionare un evento dato il suo ID
    $queryEventi = "SELECT * FROM Eventi WHERE evento_id = ?";
    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("i", $idEvento);

    $stmt->execute();

    $resultEvento = $stmt->get_result();

    $stmt->close();

    if ($resultEvento && $resultEvento->num_rows > 0) {
        return $resultEvento->fetch_assoc();
    } else {
        header('Location: ../php/404.php');
        exit;
    }
}

function getUserByMailOrUsername($conn, $user){
    $query = "SELECT * 
                FROM Utenti 
                WHERE username = ? OR email = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $user , $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        header('Location: ../php/500.php');
        exit();
    }

    if($result->num_rows == 0){
        return null;
    }
    $stmt->close();
    return $result->fetch_assoc();

}

function getCategorie($conn){
    $querySql = "SELECT categoria FROM Eventi";
    $resultCategoria = $conn->prepare($querySql);

    if(!$resultCategoria){
        header('Location: ../php/500.php');
        exit();
    }
    return $result;
}

function getPermessiByUsername($conn, $user){
    $query = "SELECT U.permessi 
            FROM Utenti AS U 
            WHERE U.email = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header('Location: ../php/500.php');
        exit();
    }

    $row = $result->fetch_assoc();
    $stmt->close();

    return (bool) $row['permessi'];
}



function effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password){
    $sql = "INSERT INTO Utente (email, username, nome, cognome, permessi, password) VALUES (?, ?, ?, ?, 0, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $email, $username, $nome, $cognome, $password);
    $stmt->execute();
    $stmt->close();
}


