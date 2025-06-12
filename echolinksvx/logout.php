<?php
include 'include/config.php'; 

session_start();

// If login is not required, simply redirect to index.php
// Si le login n'est pas requis, rediriger simplement vers index.php
if (!$CONFIG['login_required']) {
    header("Location: index.php");
    exit();
}

// Otherwise, log the user out
// Sinon, déconnecter l'utilisateur
session_destroy();
header("Location: login.php");
exit();
?>
