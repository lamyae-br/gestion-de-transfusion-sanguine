<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Appointments</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
.profile-container {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
    border-radius: 8px;
    padding: 10px;
}
.profile-container {
    background: linear-gradient(135deg, #fe4f4fff, #fe0000ff);
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
    background: #ffebebff;
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
    background: #000000ff;
    transform: translateX(5px);
}
.menu-active {
    background: #020202ff;
}
.menu-active a {
    color: white !important;
}

 </style>
 
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    

    //import database
    include("../connection.php");

    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
    <td style="padding:10px" colspan="2">
        <table border="0" class="profile-container">
            <tr>
                <td width="30%" style="padding-left:20px" >
                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                </td>
                <td style="padding:0px;margin:0px;">
                    <p class="profile-title">Administrateur</p>
                    <p class="profile-subtitle">admin@edoc.com</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="../logout.php">
                        <input type="button" value="Se déconnecter" class="logout-btn btn" 
                               style="background-color:#000000; color:white; border:none; border-radius:6px; padding:8px 16px; cursor:pointer;">
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr class="menu-row">
    <td class="menu-btn menu-icon-dashbord">
        <a href="index.php" class="non-style-link-menu">
            <div><p class="menu-text">Tableau de bord</p></div>
        </a>
    </td>
</tr>

<tr class="menu-row">
    <td class="menu-btn menu-icon-doctor">
        <a href="doctors.php" class="non-style-link-menu">
            <div><p class="menu-text">Médecins</p></div>
        </a>
    </td>
</tr>

<tr class="menu-row">
    <td class="menu-btn menu-icon-schedule">
        <a href="schedule.php" class="non-style-link-menu">
            <div><p class="menu-text">sessions</p></div>
        </a>
    </td>
</tr>

<tr class="menu-row">
    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active">
            <div><p class="menu-text">demandes</p></div>
        </a>
    </td>
</tr>


            </table>
        </div>
        <div class="dash-body">
           <table border="0" width="100%" style="border-spacing:0;margin:0;padding:0;margin-top:25px;font-family:Arial,sans-serif;background-color:#f5f6fa;">
    <tr>
        <td width="13%">
            <a href="appointment.php">
                <button class="login-btn btn-primary-soft btn btn-icon-back" 
                        style="padding:11px;margin-left:20px;width:125px;border:none;border-radius:6px;background-color:#e0e0e0;color:#333;cursor:pointer;font-weight:500;">
                    <font class="tn-in-text">Retour</font>
                </button>
            </a>
        </td>
        <td>
            <p style="font-size:23px;padding-left:12px;font-weight:600;color:#333;">Gestion des rendez-vous</p>
        </td>
        <td width="15%">
            <p style="font-size:14px;color:#777;padding:0;margin:0;text-align:right;">Date du jour</p>
            <p class="heading-sub12" style="padding:0;margin:0;color:#555;font-size:14px;">
                <?php 
                    date_default_timezone_set('Asia/Kolkata');
                    $today = date('Y-m-d');
                    echo $today;
                    $list110 = $database->query("select * from appointment;");
                ?>
            </p>
        </td>
        <td width="10%">
            <button class="btn-label" style="display:flex;justify-content:center;align-items:center;border:none;background:none;">
                <img src="../img/calendar.svg" width="100%">
            </button>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="padding-top:10px;width:100%;">
            <p style="margin-left:45px;font-size:18px;color:#313131;font-weight:600;">Tous les rendez-vous (<?php echo $list110->num_rows; ?>)</p>
        </td>
    </tr>

    <tr width="100%">
        <td colspan="4" style="padding-top:0px;width:100%;">
            <center>
                <table border="0" style="width:95%;background-color:#fff;padding:15px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.1);border-collapse:separate;border-spacing:0;">
                    <tr>
                        <td width="10%"></td>
                        <td width="5%" style="text-align:center;font-weight:600;color:#333;">Date :</td>
                        <td width="30%">
                            <form action="" method="post">
                                <input type="date" name="sheduledate" id="date" 
                                       style="width:95%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;">
                        </td>
                        <td width="5%" style="text-align:center;font-weight:600;color:#333;">Médecin :</td>
                        <td width="30%">
                            <select name="docid" style="width:90%;height:37px;margin:0;padding:6px 10px;border-radius:6px;border:1px solid #ccc;appearance:none;background:url('data:image/svg+xml;utf8,<svg fill=\'%23333\' height=\'24\' viewBox=\'0 0 24 24\' width=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7 10l5 5 5-5z\'/></svg>') no-repeat right 10px center;background-size:12px;">
                                <option value="" disabled selected hidden>Choisir le médecin dans la liste</option>
                                <?php 
                                    $list11 = $database->query("select * from doctor order by docname asc;");
                                    for ($y=0;$y<$list11->num_rows;$y++){
                                        $row00=$list11->fetch_assoc();
                                        $sn=$row00["docname"];
                                        $id00=$row00["docid"];
                                        echo "<option value=".$id00.">$sn</option>";
                                    };
                                ?>
                            </select>
                        </td>
                        <td width="12%">
                            <input type="submit" name="filter" value="Filtrer" 
                                   style="width:100%;padding:15px;border:none;border-radius:6px;background-color:#000000;color:#fff;font-weight:600;cursor:pointer;">
                            </form>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>



                        </center>
                    </td>
                    
                </tr>
                
           <?php


if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit;
}

// Importer la base de données
include("../connection.php");

// Initialiser la requête principale
$sqlmain = "SELECT demande.id, demande.groupe_sanguin, demande.niveau_urgence,
                   demande.quantite_demandee, demande.type_sang,
                   demande.service_concerne, demande.commentaire,
                   demande.fichier_justificatif, demande.date_demande, demande.status,
                   doctor.docname
            FROM demande
            INNER JOIN doctor ON demande.docid = doctor.docid";

// Si formulaire soumis, appliquer les filtres
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filters = array();
    if (!empty($_POST["sheduledate"])) {
        $date = $_POST["sheduledate"];
        $filters[] = "demande.date_demande='$date'";
    }
    if (!empty($_POST["docid"])) {
        $docid = $_POST["docid"];
        $filters[] = "doctor.docid=$docid";
    }

    if (count($filters) > 0) {
        $sqlmain .= " WHERE " . implode(" AND ", $filters);
    }
}

$sqlmain .= " ORDER BY demande.date_demande DESC";

$result = $database->query($sqlmain);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointments</title>
</head>
<body style="font-family:Arial,sans-serif;background-color:#f5f6fa;margin:0;padding:0;">

<div style="width:90%;margin:25px auto;overflow-x:auto;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);background-color:#fff;padding:10px;">
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
            <tr>
               <th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;border-radius:6px 6px 0 0;position:sticky;top:0;">ID</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Nom du médecin</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Groupe sanguin</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Quantité</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Type de demande</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Priorité</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Département</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Date de la demande</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Statut</th>
<th style="padding:12px;background-color:#000000;color:#fff;font-weight:600;text-align:center;">Actions</th>

            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows == 0) {
            echo '<tr>
                    <td colspan="10" style="text-align:center;padding:50px 0;background-color:#f9f9f9;border-radius:0 0 6px 6px;">
                        <img src="../img/notfound.svg" width="25%"><br>
                        <p style="font-size:20px;color:#313131;margin-top:20px;">Aucune demande trouvée !</p>
                        <a href="appointment.php"><button style="padding:12px 25px;border:none;border-radius:6px;background-color:#4CAF50;color:#fff;font-weight:600;cursor:pointer;margin-top:15px;">Afficher toutes les demandes</button></a>
                    </td>
                  </tr>';
        } else {
            for ($x = 0; $x < $result->num_rows; $x++) {
                $row = $result->fetch_assoc();
                $id = $row["id"];
                $groupe_sanguin = $row["groupe_sanguin"];
                $niveau_urgence = $row["niveau_urgence"];
                $quantite = $row["quantite_demandee"];
                $type_sang = $row["type_sang"];
                $service = $row["service_concerne"];
                $commentaire = $row["commentaire"];
                $fichier = $row["fichier_justificatif"];
                $date_demande = $row["date_demande"];
                $status = $row["status"];
                $docname = $row["docname"];
                $bgcolor = ($x % 2 == 0) ? '#fff' : '#f9f9f9';

                echo '<tr style="background-color:' . $bgcolor . ';border-bottom:1px solid #e0e0e0;">
                        <td style="padding:12px;text-align:center;font-weight:600;">' . $id . '</td>
                        <td style="padding:12px;text-align:center;">' . substr($docname, 0, 25) . '</td>
                        <td style="padding:12px;text-align:center;">' . $groupe_sanguin . '</td>
                        <td style="padding:12px;text-align:center;">' . $quantite . '</td>
                        <td style="padding:12px;text-align:center;">' . $type_sang . '</td>
                        <td style="padding:12px;text-align:center;">' . $niveau_urgence . '</td>
                        <td style="padding:12px;text-align:center;">' . $service . '</td>
                        <td style="padding:12px;text-align:center;">' . substr($date_demande, 0, 10) . '</td>
                        <td style="padding:12px;text-align:center;">' . $status . '</td>
                        <td style="padding:12px;text-align:center;">
                            <div style="display:flex;justify-content:center;gap:8px;">
                                <a href="?action=view&id=' . $id . '"><button style="padding:8px 16px;border:none;border-radius:6px;background-color:#2196F3;color:#fff;cursor:pointer;font-weight:600;">Voir</button></a>
                               
                            </div>
                        </td>
                      </tr>';
            }
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php
if (isset($_GET["id"]) && isset($_GET["action"]) && $_GET["action"] === "view") {
    $id = intval($_GET["id"]); // Sécuriser l'ID

    // Récupérer les détails de la demande avec requête préparée
    $sql = "SELECT d.*, doc.docname 
            FROM demande d 
            INNER JOIN doctor doc ON d.docid = doc.docid 
            WHERE d.id = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $demande = $result->fetch_assoc()) {
        ?>
        <style>
            .overlay {
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(0,0,0,0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                padding: 10px;
                box-sizing: border-box;
            }
            .popup {
                background: #fff;
                border-radius: 10px;
                width: 450px;
                max-width: 95%;
                padding: 20px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                position: relative;
                animation: slideDown 0.35s ease-out;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            @keyframes slideDown {
                from { transform: translateY(-40px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .popup .close {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 24px;
                font-weight: bold;
                text-decoration: none;
                color: #555;
                transition: 0.3s;
            }
            .popup .close:hover { color: #f44336; }
            .popup .content p.title {
                font-size: 22px;
                font-weight: 600;
                color: #222;
                text-align: center;
                margin-bottom: 15px;
            }
            .popup table { width: 100%; border-collapse: collapse; font-size: 14px; }
            .popup table td {
                padding: 8px 6px;
                border-bottom: 1px solid #eee;
            }
            .popup table td b {
                color: #333;
                display: inline-block;
                width: 130px;
            }
            .popup a.file-link {
                color: #1a73e8;
                text-decoration: none;
                font-weight: 500;
            }
            .popup a.file-link:hover { text-decoration: underline; }
            .login-btn {
                padding: 8px 18px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                transition: 0.3s;
            }
            .btn-primary-soft { background-color: #f5f5f5; color: #333; }
            .btn-primary-soft:hover { background-color: #e0e0e0; }
            a.non-style-link { text-decoration: none; }
        </style>

        <div class="overlay">
            <div class="popup">
                <a class="close" href="appointment.php">&times;</a>
                <div class="content">
                    <p class="title">Détails de la Demande</p>
                    <table>
                        <tr><td><b>Docteur :</b> <?= htmlspecialchars($demande["docname"]) ?></td></tr>
                        <tr><td><b>Groupe sanguin :</b> <?= htmlspecialchars($demande["groupe_sanguin"]) ?></td></tr>
                        <tr><td><b>Quantité :</b> <?= intval($demande["quantite_demandee"]) ?> poches</td></tr>
                        <tr><td><b>Type de sang :</b> <?= htmlspecialchars($demande["type_sang"]) ?></td></tr>
                        <tr><td><b>Niveau :</b> <?= htmlspecialchars($demande["niveau_urgence"]) ?></td></tr>
                        <tr><td><b>Service :</b> <?= htmlspecialchars($demande["service_concerne"]) ?></td></tr>
                        <tr><td><b>Commentaire :</b> <?= nl2br(htmlspecialchars($demande["commentaire"])) ?></td></tr>
                        <tr>
                            <td><b>Fichier :</b> 
                                <?php if (!empty($demande["fichier_justificatif"])): ?>
                                    <?php 
                                        $fileName = basename($demande["fichier_justificatif"]); 
                                        $filePath = "../doctor/uploads/" . $fileName;
                                    ?>
                                    <?php if (file_exists($filePath)): ?>
                                        <a class="file-link" href="<?= htmlspecialchars($filePath) ?>" target="_blank">Voir / Télécharger</a>
                                    <?php else: ?>
                                        <span style="color:red;">Fichier introuvable</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    Aucun
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr><td><b>Date :</b> <?= htmlspecialchars($demande["date_demande"]) ?></td></tr>
                        <tr><td><b>Statut :</b> <?= htmlspecialchars($demande["status"]) ?></td></tr>
                        <tr>
                            <td>
                                <a href="appointment.php" class="non-style-link">
                                    <input type="button" value="OK" class="login-btn btn-primary-soft">
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<p>Demande introuvable.</p>';
    }
}
?>


</body>
</html>