<?php
session_start();
include("../connection.php");

if(!isset($_SESSION['user']) || $_SESSION['usertype'] != 'd'){
    header("location: ../login.php");
    exit();
}

// Récupération des données
$docid = $_POST['docid'] ?? 0;
$groupe_sanguin = $_POST['groupe_sanguin'] ?? '';
$niveau_urgence = $_POST['niveau_urgence'] ?? '';
$quantite_demandee = $_POST['quantite_demandee'] ?? 0;
$type_sang = $_POST['type_sang'] ?? '';
$service_concerne = $_POST['service_concerne'] ?? '';
$commentaire = $_POST['commentaire'] ?? '';

// Gestion du fichier justificatif
$fichier_justificatif = $_POST['fichier_justificatif'] ?? '';
if(isset($_FILES['fichier_justificatif']) && $_FILES['fichier_justificatif']['error'] == 0){
    $uploads_dir = __DIR__ . '/uploads/';
    if(!is_dir($uploads_dir)) mkdir($uploads_dir, 0777, true);

    $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($_FILES['fichier_justificatif']['name']));
    $destination = $uploads_dir . $filename;
    if(move_uploaded_file($_FILES['fichier_justificatif']['tmp_name'], $destination)){
        $fichier_justificatif = $filename;
    }
}

// Si le formulaire de confirmation est soumis
if(isset($_POST['confirmation']) && $_POST['confirmation'] == 1){
    // Préparer l'INSERT
    $stmt = $database->prepare("INSERT INTO demande 
        (groupe_sanguin, niveau_urgence, quantite_demandee, type_sang, fichier_justificatif, service_concerne, commentaire, docid) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissssi", $groupe_sanguin, $niveau_urgence, $quantite_demandee, $type_sang, $fichier_justificatif, $service_concerne, $commentaire, $docid);
    $stmt->execute();
    $stmt->close();
    $database->close();

    echo "<p style='color:green;'>✅ Demande enregistrée avec succès !</p>";
    echo "<a href='index.php'>Retour au tableau de bord</a>";
    exit;
}

// Sinon : afficher le résumé et demander confirmation
?>
<div class="card">
<h2>Résumé de la demande</h2>
<table>
    <tr><td>Groupe sanguin</td><td><?=htmlspecialchars($groupe_sanguin)?></td></tr>
    <tr><td>Niveau d'urgence</td><td><?=htmlspecialchars($niveau_urgence)?></td></tr>
    <tr><td>Quantité</td><td><?=htmlspecialchars($quantite_demandee)?> poches</td></tr>
    <tr><td>Type de sang</td><td><?=htmlspecialchars($type_sang)?></td></tr>
    <tr><td>Service concerné</td><td><?=htmlspecialchars($service_concerne)?></td></tr>
    <tr><td>Commentaire</td><td><?=nl2br(htmlspecialchars($commentaire))?></td></tr>
    <?php if($fichier_justificatif): ?>
        <tr><td>Fichier justificatif</td>
        <td><a href="uploads/<?=$fichier_justificatif?>" target="_blank">Voir le fichier</a></td></tr>
    <?php endif; ?>
</table>

<form method="post">
    <input type="hidden" name="docid" value="<?=htmlspecialchars($docid)?>">
    <input type="hidden" name="groupe_sanguin" value="<?=htmlspecialchars($groupe_sanguin)?>">
    <input type="hidden" name="niveau_urgence" value="<?=htmlspecialchars($niveau_urgence)?>">
    <input type="hidden" name="quantite_demandee" value="<?=htmlspecialchars($quantite_demandee)?>">
    <input type="hidden" name="type_sang" value="<?=htmlspecialchars($type_sang)?>">
    <input type="hidden" name="service_concerne" value="<?=htmlspecialchars($service_concerne)?>">
    <input type="hidden" name="commentaire" value="<?=htmlspecialchars($commentaire)?>">
    <input type="hidden" name="fichier_justificatif" value="<?=htmlspecialchars($fichier_justificatif)?>">
    <input type="hidden" name="confirmation" value="1">
    <button type="submit">✅ Confirmer et envoyer</button>
</form>
