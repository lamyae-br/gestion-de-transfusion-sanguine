<?php
session_start();

// Vérification si l'utilisateur est admin
if (!isset($_SESSION["user"]) || $_SESSION["user"] === "" || $_SESSION['usertype'] !== 'a') {
    header("location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("../connection.php");

    // Récupération et validation des données
    $title     = trim($_POST["title"]);
    $docid     = intval($_POST["docid"]);
    $nop       = intval($_POST["nop"]);
    $start_date = $_POST["start_date"];
    $end_date   = $_POST["end_date"];

    if ($title !== "" && $docid > 0 && $nop > 0 && $start_date !== "" && $end_date !== "") {
        // Préparation de la requête pour éviter l'injection SQL
        $stmt = $database->prepare("INSERT INTO schedule (docid, title, start_date, end_date, nop) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $docid, $title, $start_date, $end_date, $nop);

        if ($stmt->execute()) {
            // Succès
            header("location: schedule.php?action=session-added&title=" . urlencode($title));
            exit;
        } else {
            // Erreur SQL
            $errorMsg = $stmt->error;
            header("location: schedule.php?action=error&msg=" . urlencode($errorMsg));
            exit;
        }

        $stmt->close();
    } else {
        // Champs invalides
        header("location: schedule.php?action=error&msg=" . urlencode("Veuillez remplir tous les champs correctement."));
        exit;
    }
}
?>
