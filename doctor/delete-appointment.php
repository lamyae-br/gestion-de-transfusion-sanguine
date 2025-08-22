<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un docteur
if(!isset($_SESSION["user"]) || $_SESSION["user"]=="" || $_SESSION['usertype']!='d'){
    header("location: ../login.php");
    exit;
}

// Vérifier si l'ID de la session est passé
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("location: schedule.php?message=InvalidID");
    exit;
}

$session_id = intval($_GET['id']); // Sécuriser l'ID pour éviter les injections

// Connexion à la base de données
include("../connection.php");

// Vérifier si la session appartient au docteur connecté
$useremail = $_SESSION["user"];
$userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];

// Vérifier que la session appartient bien à ce docteur
$check = $database->query("SELECT * FROM schedule WHERE scheduleid=$session_id AND docid=$userid");
if($check->num_rows == 0){
    // Session introuvable ou pas autorisée
    header("location: schedule.php?message=NotAuthorized");
    exit;
}

// Supprimer la session
$delete = $database->query("DELETE FROM schedule WHERE scheduleid=$session_id AND docid=$userid");
if($delete){
    header("location: schedule.php?message=Deleted");
} else {
    header("location: schedule.php?message=ErrorDeleting");
}
exit;
?>
