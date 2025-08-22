<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit();
}
$useremail = $_SESSION["user"];
include("../connection.php");

$userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");
if ($userrow && $userrow->num_rows > 0) {
    $userfetch = $userrow->fetch_assoc();
    $userid    = $userfetch["docid"];
    $username  = $userfetch["docname"];
} else {
    header("location: ../logout.php");
    exit();
}

$compatibilite = [
    "O-"  => ["O-"],
    "O+"  => ["O-", "O+"],
    "A-"  => ["O-", "A-"],
    "A+"  => ["O-", "O+", "A-", "A+"],
    "B-"  => ["O-", "B-"],
    "B+"  => ["O-", "O+", "B-", "B+"],
    "AB-" => ["O-", "A-", "B-", "AB-"],
    "AB+" => ["O-", "O+", "A-", "A+", "B-", "B+", "AB-", "AB+"],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Nouvelle Demande</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* Styles globaux */
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #ffeaea, #fff);
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Carte principale */
.card {
    background: #fff;
    width: 650px;
    border-radius: 20px;
    padding: 40px 50px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
}

/* Titre */
h2 {
    text-align: center;
    color: #d32f2f;
    font-size: 28px;
    margin-bottom: 30px;
    font-weight: bold;
    letter-spacing: 1px;
}

/* Barre de progression */
.progress-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    position: relative;
}
.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-weight: 600;
    color: #bbb;
    font-size: 14px;
    position: relative;
    flex: 1;
}
.step::after {
    content: "";
    position: absolute;
    top: 18px;
    right: -50%;
    height: 4px;
    background: #eee;
    width: 100%;
    z-index: -1;
    border-radius: 2px;
}
.step:last-child::after {
    display: none;
}
.step.active {
    color: #d32f2f;
}
.step .circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    font-weight: bold;
    transition: 0.3s;
    color: #fff;
}
.step.active .circle {
    background: linear-gradient(135deg, #e53935, #b71c1c);
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(229, 57, 53, 0.4);
}

/* Sections du formulaire */
form section {
    margin-bottom: 25px;
    padding: 20px;
    border-radius: 15px;
    background: linear-gradient(90deg, #fff9f9, #ffeaea);
    border: 1px solid #f5c2c2;
}
form section h3 {
    margin-top: 0;
    font-size: 18px;
    color: #b71c1c;
    border-bottom: 2px solid #e53935;
    padding-bottom: 6px;
    margin-bottom: 15px;
}

/* Labels et champs */
label {
    font-weight: 600;
    margin-top: 10px;
    display: block;
    color: #444;
}
input, select, textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid #ddd;
    margin-top: 5px;
    font-size: 15px;
    transition: 0.3s;
}
input:focus, select:focus, textarea:focus {
    border-color: #e53935;
    box-shadow: 0 0 6px rgba(229, 57, 53, 0.3);
    outline: none;
}

/* Bouton */
.btn {
    width: 100%;
    background: linear-gradient(45deg, #e53935, #d32f2f);
    color: #fff;
    padding: 15px;
    font-size: 16px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    letter-spacing: 0.5px;
}
.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
}

/* Lien PDF */


.pdf-link {
    display: block;
    text-align: center;
    margin: 15px 0;
    font-weight: 600;
    color: #1976d2; /* bleu type "lien" */
    text-decoration: none;
    transition: 0.3s;
}
.pdf-link i {
    margin-right: 6px;
    color: #1976d2;
}
.pdf-link:hover {
    color: #0d47a1; /* bleu plus foncé au survol */
    text-decoration: underline;
}


/* Résumé */
.summary-box {
    background: #fff5f5;
    padding: 20px;
    border-radius: 15px;
    margin-top: 20px;
    font-size: 15px;
    border: 1px solid #f5c2c2;
}
.summary-box p {
    margin: 6px 0;
}

</style>
<style>
/* Conteneur de progression */
.progress-container {
    display: flex;
    justify-content: space-between;
    margin: 20px 0 30px;
}
.step {
    text-align: center;
    flex: 1;
    font-size: 14px;
    color: #777;
}
.step.active {
    font-weight: bold;
    color: #1976d2;
}
.circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    margin: 0 auto 5px;
    background: #ddd;
    line-height: 28px;
    color: #fff;
    font-size: 13px;
}
.step.active .circle {
    background: #1976d2;
}

/* Formulaire */
form {
    background: #fdfdfd;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-width: 700px;
    margin: auto;
}
section {
    margin-bottom: 25px;
}
h3 {
    margin-bottom: 15px;
    color: #1976d2;
    font-size: 18px;
    border-bottom: 2px solid #1976d2;
    padding-bottom: 5px;
}
label {
    display: block;
    margin: 12px 0 6px;
    font-weight: 600;
}
input, select, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 10px;
    transition: border-color 0.3s;
}
input:focus, select:focus, textarea:focus {
    border-color: #1976d2;
    outline: none;
}

/* Bouton */
.btn {
    display: inline-block;
    background: #1976d2;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.3s;
}
.btn:hover {
    background: #0d47a1;
}
</style>
<style>
/* Barre de progression */
.progress-container {
    display: flex;
    justify-content: space-between;
    margin: 20px 0 30px;
}
.step {
    text-align: center;
    flex: 1;
    font-size: 14px;
    color: #777;
}
.step.active {
    font-weight: bold;
    color: #1976d2;
}
.circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    margin: 0 auto 5px;
    background: #ddd;
    line-height: 28px;
    color: #fff;
    font-size: 13px;
}
.step.active .circle {
    background: #1976d2;
}

/* Résumé */
.summary-box {
    background: #f9f9ff;
    border: 1px solid #cfd8dc;
    border-radius: 12px;
    padding: 25px;
    margin: 20px auto;
    max-width: 700px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}
.summary-box h3 {
    color: #1976d2;
    font-size: 20px;
    margin-bottom: 20px;
}
.summary-box p {
    margin: 10px 0;
    font-size: 15px;
    color: #333;
}
.summary-box b {
    color: #1976d2;
}

/* Lien PDF ou fichier */
.summary-box a {
    display: inline-block;
    color: #1976d2;
    text-decoration: none;
    font-weight: bold;
    margin-top: 10px;
}
.summary-box a:hover {
    text-decoration: underline;
}

/* Bouton confirmation */
.btn {
    display: block;
    margin: 25px auto 0;
    background: #1976d2;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.3s;
}
.btn:hover {
    background: #0d47a1;
}
</style>


</head>
<body>
<div class="card">
<h2><i class="fas fa-hand-holding-medical"></i> Nouvelle Demande</h2>

<?php if(!isset($_POST['groupe_patient']) && !isset($_POST['confirmation'])): ?>
<!-- ETAPE 1 -->
<div class="progress-container">
    <div class="step active"><div class="circle">1</div>Choix</div>
    <div class="step"><div class="circle">2</div>Demande</div>
    <div class="step"><div class="circle">3</div>Résumé</div>
</div>
<form action="" method="POST">
<section>
<h3>Informations patient</h3>
<p style="color:#555; font-size:14px; margin-bottom:15px;">
        Veuillez sélectionner le <b>groupe sanguin du patient</b>.  
        Cette information est essentielle pour déterminer les donneurs compatibles.  
       
        
    </p>
<label for="groupe_patient"><i class="fas fa-tint"></i> Groupe sanguin :</label>
<select name="groupe_patient" id="groupe_patient" required>
<option value="">-- Choisir --</option>
<option value="O+">O+</option><option value="O-">O-</option>
<option value="A+">A+</option><option value="A-">A-</option>
<option value="B+">B+</option><option value="B-">B-</option>
<option value="AB+">AB+</option><option value="AB-">AB-</option>
</select>
</section>
<!-- ✅ Phrase explicative -->

        
        <a href="Document sans titre.pdf" target="_blank" style="color:#e53935; text-decoration:none; font-weight:bold;" class="pdf-link">
            <i class="fas fa-file-pdf"></i> Besoin d’aide ? Consultez le guide explicatif
        </a>
 
<button type="submit" class="btn">Continuer <i class="fas fa-arrow-right"></i></button>
</form>
<?php exit(); endif; ?>


<?php 
// ÉTAPE 2 : Formulaire de demande
if(isset($_POST['groupe_patient']) && !isset($_POST['confirmation'])):
$groupe_patient = $_POST['groupe_patient'];
$compatibles = $compatibilite[$groupe_patient]; 
?>
<div class="progress-container">
    <div class="step active"><div class="circle">1</div>Choix</div>
    <div class="step active"><div class="circle">2</div>Demande</div>
    <div class="step"><div class="circle">3</div>Résumé</div>
</div>
<form  method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="confirmation" value="1">
<input type="hidden" name="docid" value="<?php echo $userid; ?>">
<input type="hidden" name="groupe_patient" value="<?php echo $groupe_patient; ?>">

<section>
<h3>Détails du patient</h3>
<p><b>Groupe sanguin :</b> <?php echo $groupe_patient; ?></p>
<label for="groupe_sanguin"><i class="fas fa-hand-holding-droplet"></i> Groupe compatible :</label>
<select name="groupe_sanguin" required>
<?php foreach($compatibles as $g): ?>
<option value="<?php echo $g; ?>"><?php echo $g; ?></option>
<?php endforeach; ?>
</select>
</section>

<section>
<h3>Détails de la demande</h3>
<label>Niveau d'urgence :</label>
<select name="niveau_urgence" required>
<option value="">-- Choisir --</option>
<option value="Faible">Faible</option><option value="Moyenne">Moyenne</option><option value="Élevée">Élevée</option>
</select>
<label>Quantité (poches) :</label>
<input type="number" name="quantite_demandee" min="1" required>
<label for="type_sang">Type de sang :</label>
<select name="type_sang" id="type_sang" required>
    <option value="">-- Sélectionner --</option>
    <option value="Sang total">Sang total</option>
    <option value="Plasma">Plasma</option>
    <option value="Plaquettes">Plaquettes</option>
    <option value="Globules rouges">Globules rouges</option>
    <option value="Concentré de plaquettes">Concentré de plaquettes</option>
    <option value="Concentré de globules blancs">Concentré de globules blancs</option>
</select>

<label>Fichier justificatif :</label>
<input type="file" name="fichier_justificatif" accept=".pdf,.jpg,.jpeg,.png">
</section>

<section>
<h3>Informations supplémentaires</h3>
<label>Service concerné :</label>
<input type="text" name="service_concerne" maxlength="100" required>
<label>Commentaire :</label>
<textarea name="commentaire" maxlength="500"></textarea>
</section>

<button type="submit" class="btn">Continuer vers résumé <i class="fas fa-arrow-right"></i></button>
</form>
<?php exit(); endif; ?>

<?php
// connexion à la base de données

// Initialisation de la variable message succès
$message_success = "";

// Récupération des données POST
$groupe_patient = $_POST['groupe_patient'] ?? '';
$groupe_sanguin = $_POST['groupe_sanguin'] ?? '';
$niveau_urgence = $_POST['niveau_urgence'] ?? '';
$quantite_demandee = $_POST['quantite_demandee'] ?? 0;
$type_sang = $_POST['type_sang'] ?? '';
$service_concerne = $_POST['service_concerne'] ?? '';
$commentaire = $_POST['commentaire'] ?? '';
$docid = $_POST['docid'] ?? 0;

// Gestion du fichier justificatif
$filePathRelative = $_POST['fichier_justificatif'] ?? '';
if(isset($_FILES['fichier_justificatif']) && $_FILES['fichier_justificatif']['error'] == 0){
    $uploadsDir = __DIR__ . "/uploads/";
    if(!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

    $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($_FILES['fichier_justificatif']['name']));
    $destination = $uploadsDir . $fileName;
    if(move_uploaded_file($_FILES['fichier_justificatif']['tmp_name'], $destination)){
        $filePathRelative = "uploads/" . $fileName;
    }
}

// Si l’utilisateur clique sur le bouton "Confirmer et envoyer"
if(isset($_POST['confirmation']) && $_POST['confirmation'] == 1){
    $stmt = $database->prepare("INSERT INTO demande 
        (groupe_sanguin, niveau_urgence, quantite_demandee, type_sang, fichier_justificatif, service_concerne, commentaire, docid) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissssi", $groupe_sanguin, $niveau_urgence, $quantite_demandee, $type_sang, $filePathRelative, $service_concerne, $commentaire, $docid);
    $stmt->execute();
    $stmt->close();

    $message_success = "✅ Demande enregistrée avec succès !";
}
?>

<div class="progress-container">
    <div class="step active"><div class="circle">1</div>Choix</div>
    <div class="step active"><div class="circle">2</div>Demande</div>
    <div class="step active"><div class="circle">3</div>Résumé</div>
</div>

<div class="summary-box">
    <h3><i class="fas fa-clipboard-check"></i> Résumé de la demande</h3>

    <!-- Message succès -->
    <?php if(!empty($message_success)): ?>
        <p style="padding:10px; background:#d4edda; color:#155724; border-radius:5px; font-weight:bold;">
            <?= $message_success ?>
        
    <?php endif; ?>

    <p><b>Groupe patient :</b> <?=htmlspecialchars($groupe_patient)?></p>
    <p><b>Groupe compatible :</b> <?=htmlspecialchars($groupe_sanguin)?></p>
    <p><b>Niveau d'urgence :</b> <?=htmlspecialchars($niveau_urgence)?></p>
    <p><b>Quantité demandée :</b> <?=htmlspecialchars($quantite_demandee)?> poches</p>
    <p><b>Type de sang :</b> <?=htmlspecialchars($type_sang)?></p>
    <p><b>Service concerné :</b> <?=htmlspecialchars($service_concerne)?></p>
    <p><b>Commentaire :</b> <?=nl2br(htmlspecialchars($commentaire))?></p>

    <?php if(!empty($filePathRelative)): ?>
        <p style="text-align:center; margin-top:10px;">
            <a href="<?= $filePathRelative ?>" target="_blank" style="color:blue; font-weight:bold; text-decoration:underline;">
                📄 Voir le fichier justificatif
            </a>
        </p>
    <?php endif; ?>

    <!-- Bouton de confirmation, visible seulement si la demande n'a pas encore été envoyée -->
    <?php if(empty($message_success)): ?>
        <form action="" method="post" enctype="multipart/form-data">
            <?php foreach($_POST as $k=>$v): if($k!="confirmation"): ?>
                <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
            <?php endif; endforeach; ?>
            <input type="hidden" name="confirmation" value="1">
            <button type="submit" class="btn">Confirmer et envoyer <i class="fas fa-paper-plane"></i></button>
        </form>
        
    <?php endif; ?>
   
       <!-- Bouton redirection tableau de bord -->
<!-- Boutons côte à côte -->
<div style="text-align:center; margin-top:15px;">
    <a href="index.php" 
       style="background:#007bff; 
              color:white; 
              padding:6px 14px; 
              border-radius:4px; 
              text-decoration:none; 
              font-size:13px; 
              display:inline-block; 
              margin-right:8px;">
        <i class="fas fa-home"></i> Tableau de bord
    </a>

    <a href="new_request.php" 
       style="background:#28a745; 
              color:white; 
              padding:6px 14px; 
              border-radius:4px; 
              text-decoration:none; 
              font-size:13px; 
              display:inline-block;">
        <i class="fas fa-plus-circle"></i> Nouvelle demande
    </a>
</div>


</div>


</body>
</html>
