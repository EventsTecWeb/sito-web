<?php
/*da riguardare*/
require_once 'queries.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: 500.php');
    exit();
}

$permissions = getPermissionsByUsername($conn, $_SESSION['email']);
if ($permissions !== true) {
    header('Location: 500.php');
    exit();
}

$page_content = file_get_contents('../html/admin.html'); /*manca admin.html*/

echo $page_content;
$conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['logged_in'] = false;
    session_unset();
    session_destroy();
    header('Location: accedi.php');
    exit();
}

?>
