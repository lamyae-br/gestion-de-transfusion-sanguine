<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un docteur
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit();
}

$useremail = $_SESSION["user"];

// Importer la base de données
include("../connection.php");

// Récupération des infos du docteur connecté
$userrow = $database->query("SELECT * FROM doctor WHERE docemail = '$useremail'");

// Vérifier si le docteur existe
if ($userrow && $userrow->num_rows > 0) {
    $userfetch = $userrow->fetch_assoc();
    $userid    = $userfetch["docid"];
    $username  = $userfetch["docname"];
} else {
    // Si aucun docteur trouvé, déconnexion
    header("location: ../logout.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/animations.css">  
<link rel="stylesheet" href="../css/main.css">  
<link rel="stylesheet" href="../css/admin.css">
<title>Blood Requests</title>
<style>
.popup{ animation: transitionIn-Y-bottom 0.5s; }
.sub-table{ animation: transitionIn-Y-bottom 0.5s; }
</style>
<style>
/* Overlay (fond noir transparent) */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Fenêtre popup */
.popup {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    width: 500px;
    max-width: 90%;
    box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
    position: relative;
    animation: fadeInDown 0.4s ease;
}

/* Bouton de fermeture (X) */
.popup .close {
    position: absolute;
    top: 10px;
    right: 15px;
    text-decoration: none;
    font-size: 24px;
    color: #333;
    font-weight: bold;
    transition: 0.2s;
}
.popup .close:hover {
    color: red;
}

/* Contenu */
.popup .content {
    margin-top: 15px;
    font-size: 15px;
    line-height: 1.6;
    color: #444;
}

/* Animation d’apparition */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Bouton Fermer */
.btn {
    background: var(--primarycolor, #0A76D8);
    border: none;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    transition: 0.3s;
}
.btn:hover {
    background: var(--primarycolorhover, #006dd3);
}

</style>
</head>
<body>

<div class="container">
    <div class="container">
   <div class="menu">
    <style>
        .profile-container {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            border-radius: 8px;
            padding: 10px;
        }
        .profile-title {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0 0;
        }
        .profile-subtitle {
            font-size: 12px;
            opacity: 0.9;
            margin: 0;
        }
        .logout-btn {
            background: #ff5c5c;
            color: white;
            border: none;
            padding: 6px 12px;
            margin-top: 8px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        .logout-btn:hover {
            background: #ff3030;
        }
        .menu-row {
            transition: 0.3s;
        }
        .menu-btn {
            padding: 12px;
            cursor: pointer;
            font-weight: bold;
            text-align: left;
        }
        .menu-btn a {
            color: #333;
            text-decoration: none;
            display: block;
            width: 100%;
        }
        .menu-row:hover {
            background: #e0f7fa;
            transform: translateX(5px);
        }
        .menu-active {
            background: #4fc3f7;
        }
        .menu-active a {
            color: white !important;
        }
    </style>

    <table class="menu-container" border="0">
        <tr>
            <td style="padding:10px" colspan="2">
                <table border="0" class="profile-container">
                    <tr>
                        <td width="30%" style="padding-left:20px">
                            <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                        </td>
                        <td style="padding:0px;margin:0px;">
                            <p class="profile-title"><?php echo substr($username,0,13) ?>..</p>
                            <p class="profile-subtitle"><?php echo substr($useremail,0,22) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="../logout.php"><input type="button" value="se deconnecter" class="logout-btn btn-primary-soft btn"></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="menu-row">
    <td class="menu-btn menu-icon-dashbord">
        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Tableau de bord</p></div></a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Mes demandes</p></div></a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-session">
        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Mes sessions</p></div></a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-settings">
        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Paramètres</p></div></a>
    </td>
</tr>

    </table>
</div>

    <div class="dash-body">
        <style>
/* Container du header */
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    font-family: 'Inter', sans-serif;
    margin-bottom: 20px;
}

/* Bouton Back moderne */
.btn-back {
    padding: 10px 20px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.btn-back:hover {
    background: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

/* Titre principal */
.header-title {
    font-size: 26px;
    font-weight: 700;
    color: #222;
    margin: 0 20px;
}

/* Date d’aujourd’hui */
.header-date {
    text-align: right;
}
.header-date p:first-child {
    font-size: 13px;
    color: #888;
    margin: 0;
}
.header-date p:last-child {
    font-size: 16px;
    font-weight: 600;
    margin: 4px 0 0;
    color: #333;
}

/* Responsive */
@media (max-width: 768px){
    .header-container {
        flex-direction: column;
        align-items: flex-start;
    }
    .header-title {
        margin: 15px 0;
    }
    .header-date {
        text-align: left;
        margin-top: 5px;
    }
    .btn-back {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>

<!-- Header -->
<div class="header-container">
    <button class="btn-back" onclick="location.href='appointment.php'">Retour</button>
<h2 class="header-title">Gestion des demandes de sang</h2>
<div class="header-date">
    <p>Date du jour</p>
    <p><?php date_default_timezone_set('Asia/Kolkata'); echo date('Y-m-d'); ?></p>
</div>

</div>


            <!-- Filtres -->
           <style>
/* Formulaire filtre */
.filter-container {
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin: 20px 0;
    width: 95%;
}

.filter-container-items {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: 0.3s;
}
.filter-container-items:focus {
    border-color: #4facfe;
    box-shadow: 0 0 5px rgba(79,172,254,0.5);
    outline: none;
}

.btn-filter {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    transition: 0.3s;
    padding: 12px;
    width: 100%;
}
.btn-filter:hover {
    background: linear-gradient(135deg, #00f2fe, #4facfe);
}

/* Texte centrés dans le formulaire */
.filter-container td {
    vertical-align: middle;
    padding: 5px 10px;
    font-family: 'Inter', sans-serif;
}
</style>

<tr>
    <td colspan="4">
        <form method="post">
            <center>
                <table class="filter-container" border="0">
                    <tr>
                        <td width="5%"></td>
<td width="8%" style="text-align:center;">Date :</td>
<td width="20%">
    <input type="date" name="date_demande" class="input-text filter-container-items" style="width:95%;">
</td>
<td width="10%" style="text-align:center;">Groupe sanguin :</td>
<td width="15%">
    <select name="bloodgroup" class="input-text filter-container-items" style="width:100%;">
        <option value="">Tous</option>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
    </select>
</td>
<td width="10%" style="text-align:center;">Statut :</td>
<td width="15%">
    <select name="status" class="input-text filter-container-items" style="width:100%;">
        <option value="">Tous</option>
        <option value="En attente">En attente</option>
        <option value="Acceptée">Acceptée</option>
        <option value="Refusée">Refusée</option>
    </select>
</td>
<td width="10%">
    <input type="submit" name="filter" value=" Filtrer" class="btn-filter">
</td>

                    </tr>
                </table>
            </center>
        </form>
    </td>
</tr>
<style>
/* Container du filtre */
.filter-container {
    width: 90%;
    background: #e0f7fa; /* Bleu pastel doux */
    border-radius: 12px;
    padding: 10px 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08); /* Ombre douce */
    font-size: 14px;
    border-collapse: separate;
    border-spacing: 8px;
}

/* Cellules */
.filter-container td {
    padding: 6px 10px;
    vertical-align: middle;
}

/* Labels (Date, Blood Group, Status) */
.filter-container td[style*="text-align:center;"] {
    font-weight: 600;
    color: #333;
}

/* Champs input et select */
.filter-container .input-text {
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 6px 8px;
    font-size: 13px;
    transition: 0.2s;
    outline: none;
    width: 100%;
}

.filter-container .input-text:focus {
    border-color: #4facfe;
    box-shadow: 0 0 6px rgba(79,172,254,0.4);
}

/* Bouton filtrer */
.btn-filter {
    background: #4facfe; 
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
    font-weight: 600;
    padding: 7px 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-filter:hover {
    background: linear-gradient(135deg, #00f2fe, #4facfe);
}
</style>


           <?php
// Toujours filtrer par le docteur connecté
$where = ["docid = " . intval($userid)]; // sécurité : intval pour docid

// Vérifier si le formulaire de filtre est soumis
if (isset($_POST['filter'])) {
    if (!empty($_POST['date_demande'])) {
        $date = $database->real_escape_string($_POST['date_demande']);
        $where[] = "DATE(date_demande) = '$date'";
    }
    if (!empty($_POST['bloodgroup'])) {
        $bloodgroup = $database->real_escape_string($_POST['bloodgroup']);
        $where[] = "groupe_sanguin = '$bloodgroup'";
    }
    if (!empty($_POST['status'])) {
        $status = $database->real_escape_string($_POST['status']);
        $where[] = "status = '$status'";
    }
}

// Construire la requête finale
$query = "SELECT * FROM demande WHERE " . implode(" AND ", $where) . " ORDER BY date_demande DESC";

// Exécuter la requête
$result = $database->query($query);

// Vérifier si la requête a réussi
if (!$result) {
    die("Erreur SQL : " . $database->error);
}
?>



            <!-- Tableau des demandes -->
             <!-- Bouton Nouvelle demande -->
<tr>
    <td colspan="8" style="padding:10px 0;">
        <div style="text-align:right; padding-right:30px;">
            <a href="new_request.php">
                <button class="btn-new-request">+ Nouvelle demande</button>
            </a>
        </div>
    </td>
</tr>

<style>
/* Style du bouton Nouvelle demande */
.btn-new-request {
    background: linear-gradient(135deg, #2200ffff, #0011ffff); /* Dégradé bleu clair → turquoise */
    color: white;
    font-weight: 600;
    padding: 10px 20px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: 0.3s;
}

.btn-new-request:hover {
    background: linear-gradient(135deg, #00f2fe, #4facfe); /* Inverse le dégradé au survol */
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}
</style>

            <tr>
                <td colspan="8">
                    <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                            <tr>
                                <th>Groupe Sanguin</th>
                                <th>Niveau Urgence</th>
                                <th>Quantité Demandée</th>
                                <th>Type de Sang</th>
                                <th>Service Concerné</th>
                                <th>Date de la Demande</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                       <?php
if($result->num_rows==0){
    echo '<tr><td colspan="8" style="text-align:center;padding:20px;">
        <img src="../img/notfound.svg" width="25%">
        <p style="font-size:20px;color:rgb(49,49,49)">Aucune demande trouvée !</p>
        </td></tr>';
} else {
    while($row = $result->fetch_assoc()){
       echo '<tr>
    <td>'.$row["groupe_sanguin"].'</td>
    <td>'.$row["niveau_urgence"].'</td>
    <td style="text-align:center;font-weight:500;color:var(--btnnicetext);">'.$row["quantite_demandee"].'</td>
    <td>'.$row["type_sang"].'</td>
    <td>'.$row["service_concerne"].'</td>
    <td>'.$row["date_demande"].'</td>
    <td>';

        $status = strtolower($row["status"]);
        if($status == "en attente"){
            echo '<span class="status-badge status-enattente">'.$row["status"].'</span>';
        } elseif($status == "validée" || $status == "acceptee"){
            echo '<span class="status-badge status-acceptee">'.$row["status"].'</span>';
        } elseif($status == "refusée" || $status == "refusee"){
            echo '<span class="status-badge status-refusee">'.$row["status"].'</span>';
        } else {
            echo $row["status"];
        }

echo '</td>
    <td>
        <div style="display:flex;justify-content:center;">
            <a href="appointment.php?action=view&id='.$row['id'].'" class="non-style-link">
                <button class="btn-primary btn" style="margin:5px;padding:5px;">Voir plus</button>
            </a>
        </div>
    </td>
</tr>';

    }
    
}
?>
<style>
/* --- Style pour les badges de status --- */
.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    color: white;
    font-weight: bold;
    font-size: 13px;
    display: inline-block;
    text-align: center;
}

/* Couleurs selon le status */
.status-enattente { background-color: orange; }
.status-acceptee { background-color: green; }
.status-refusee { background-color: red; }
</style>


                        </tbody>
                        </table>
                        </div>
                    </center>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php
// Si on clique sur "Voir plus"
if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Sélectionner la demande uniquement si elle appartient au docteur connecté
    $demandeQuery = $database->query(
        "SELECT * FROM demande WHERE id = $id AND docid = " . intval($userid)
    );
    $demande = $demandeQuery->fetch_assoc();

    if ($demande) {
        // Vérifie si le fichier justificatif existe
        $fileHTML = "Aucune";
        if (!empty($demande["fichier_justificatif"])) {
            $filename = basename($demande["fichier_justificatif"]);
            $filePath = __DIR__ . "/uploads/" . $filename;
            $filePathRelative = "uploads/" . $filename;

            if (file_exists($filePath)) {
                $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                if (in_array($ext, ['png','jpg','jpeg','gif'])) {
                    // Afficher l'image
                    $fileHTML = "<a href='{$filePathRelative}' target='_blank'>
                                    <img src='{$filePathRelative}' style='width:100px;height:100px;object-fit:cover;border-radius:5px;' alt='Justificatif'>
                                 </a>";
                } else {
                    // Afficher le lien vers le fichier
                    $fileHTML = "<a href='{$filePathRelative}' target='_blank'>📄 Voir le fichier</a>";
                }
            }
        }

        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Détails de la demande</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content" style="text-align:left; padding:15px;">
                        <p><b>ID :</b> ' . htmlspecialchars($demande["id"]) . '</p>
                        <p><b>Groupe sanguin :</b> ' . htmlspecialchars($demande["groupe_sanguin"]) . '</p>
                        <p><b>Niveau d\'urgence :</b> ' . htmlspecialchars($demande["niveau_urgence"]) . '</p>
                        <p><b>Quantité demandée :</b> ' . htmlspecialchars($demande["quantite_demandee"]) . ' poches</p>
                        <p><b>Type de sang :</b> ' . htmlspecialchars($demande["type_sang"]) . '</p>
                        <p><b>Service concerné :</b> ' . htmlspecialchars($demande["service_concerne"]) . '</p>
                        <p><b>Commentaire :</b> ' . ($demande["commentaire"] ? nl2br(htmlspecialchars($demande["commentaire"])) : "Aucun") . '</p>
                        <p><b>Pièce justificative :</b> ' . $fileHTML . '</p>
                        <p><b>Date de demande :</b> ' . htmlspecialchars($demande["date_demande"]) . '</p>
                        <p><b>Status :</b> ' . htmlspecialchars($demande["status"]) . '</p>
                    </div>
                    <div style="margin-top:20px;">
                        <a href="appointment.php"><button class="btn-primary btn">Fermer</button></a>
                    </div>
                </center>
            </div>
        </div>';
    } else {
        // Si la demande n'existe pas ou n'appartient pas au docteur
        echo '<script>
                alert("Cette demande n\'existe pas ou n\'est pas accessible."); 
                window.location.href="appointment.php";
              </script>';
    }
}
?>


<style>
/* === Tableau des demandes === */
.sub-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Inter', sans-serif;
    margin-top: 10px;
}

.sub-table th, .sub-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e0e0e0;
    text-align: center;
    font-size: 14px;
    transition: background 0.3s;
}

.sub-table th {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
    font-weight: bold;
    border-radius: 8px;
}

.sub-table tr:hover {
    background: #f0f9ff;
}

/* Scroll horizontal si trop large */
.abc.scroll {
    overflow-x: auto;
    max-width: 100%;
}

/* Boutons Voir plus */
.btn-primary {
    background: #4fc3f7;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    cursor: pointer;
    font-size: 13px;
    transition: 0.3s;
}

.btn-primary:hover {
    background: #00b0f0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* --- Popup overlay --- */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

/* --- Popup container --- */
.popup {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border-radius: 12px;
    width: 450px;
    max-width: 95%;
    padding: 20px;
    color: white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    animation: fadeIn 0.4s ease-in-out;
    text-align: center;
    position: relative;
}

/* --- Bouton de fermeture --- */
.popup .close {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 24px;
    font-weight: bold;
    text-decoration: none;
    color: white;
    transition: 0.3s;
}

.popup .close:hover {
    color: #ff5c5c;
}

/* --- Contenu texte popup --- */
.popup .content {
    text-align: left;
    font-size: 14px;
    line-height: 1.6;
}

.popup .content p {
    margin: 8px 0;
}

/* --- Bouton Fermer popup --- */
.popup .btn-primary {
    background: #ff5c5c;
    color: white;
    border: none;
    padding: 8px 16px;
    margin-top: 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.popup .btn-primary:hover {
    background: #ff3030;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* --- Animation apparition popup --- */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}

/* Image "Aucune demande" */
.sub-table img {
    display: block;
    margin: 0 auto;
}

/* Texte message Aucune demande */
.sub-table p {
    font-size: 18px;
    color: rgb(49,49,49);
    text-align: center;
}

/* Scroll horizontal joli */
.scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
/* --- Style pour les badges de status --- */
.status-badge {
    padding: 5px 12px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    font-size: 13px;
    display: inline-block;
    text-align: center;
    min-width: 80px;
}

/* Couleurs selon le status */
.status-enattente { background-color: orange; }
.status-acceptee { background-color: green; }
.status-refusee { background-color: red; }
.sub-table th, 
.sub-table td {
    padding: 6px 8px;   /* réduit l’espace interne */
    font-size: 14px;    /* texte plus compact */
    line-height: 1.2;   /* réduit l’espace vertical */
}

.sub-table tr {
    height: 32px;       /* hauteur max de chaque ligne */
}

/* Style badges statut */
.status-badge {
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-enattente { background-color: orange; color: white; }
.status-acceptee { background-color: green; color: white; }
.status-refusee { background-color: red; color: white; }
.sub-table tr {
    height: 28px;
}
</style>




</body>
</html>
