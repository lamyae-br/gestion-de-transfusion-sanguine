<?php
session_start();
include("../connection.php"); // connexion à la base

// Vérifier si un docteur est connecté
if(isset($_SESSION['user'], $_SESSION['usertype'], $_SESSION['docid']) && $_SESSION['usertype'] === 'd') {

    $docid = $_SESSION['docid']; // ID du docteur
    $today = date('Y-m-d');

    // Mettre à jour la dernière session active du docteur
    $sql = "UPDATE schedule 
            SET end_date = ?, status = 'finished' 
            WHERE docid = ? AND status = 'active' 
            ORDER BY start_date DESC 
            LIMIT 1";

    if($stmt = $database->prepare($sql)) {
        $stmt->bind_param("si", $today, $docid);
        $stmt->execute();
        $stmt->close();
    }
}

// Supprimer toutes les données de session
$_SESSION = [];

// Supprimer le cookie de session si existant
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Détruire la session
session_destroy();

// Redirection vers la page de login avec paramètre logout
header('Location: login.php?action=logout');
exit;
?>
