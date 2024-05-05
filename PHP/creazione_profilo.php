<?php

require_once 'queries.php';
session_start();

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$email = clearInput($_POST['mail']);
$username = clearInput($_POST['username']);
$nome = clearInput($_POST['nome']);
$cognome = clearInput($_POST['cognome']);
$password = clearInput($_POST['password']);


$result = effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password);
if($result===false){
	echo "<p>dai inseriti sbagliati</p>";
}else{
	header("Location: ../PHP/home.php");
}

$conn->close();
?>