<?php
session_start();

if(!isset($_SESSION["user"]) || $_SESSION["user"]=="" || $_SESSION['usertype']!='d'){
    header("location: ../login.php");
    exit;
}

$useremail = $_SESSION["user"];

// Importer la base de données
include("../connection.php");

$userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];
$username = $userfetch["docname"];

date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

// Récupérer les sessions du docteur
$sessions = $database->query("SELECT * FROM schedule WHERE docid=$userid");

$useremail = $_SESSION["user"];


// Récupérer l’ID du docteur
$userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];

// Date du jour
$today = date('Y-m-d');

// Vérifier s’il y a au moins une session non expirée
$active_sessions = $database->query("
    SELECT * FROM schedule 
    WHERE docid = $userid 
      AND end_date >= '$today'
");

if($active_sessions->num_rows == 0){
    // Aucune session active ou future → déconnexion automatique
    session_destroy();
    header("location: ../login.php?message=NoActiveSession");
    exit;
}

// Récupérer toutes les sessions du docteur pour affichage
$sessions = $database->query("SELECT * FROM schedule WHERE docid=$userid");
?>

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
<title>My Sessions</title>
</head>
<body>
<div class="container">
    <!-- Menu -->
    <div class="menu">
    <style>
        .profile-container {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            border-radius: 10px;
            padding: 20px; /* augmenté pour plus d'espace */
        }
        .profile-title {
            font-size: 18px; /* légèrement plus grand */
            font-weight: bold;
            margin: 10px 0 0; /* plus de marge */
        }
        .profile-subtitle {
            font-size: 14px; /* plus lisible */
            opacity: 0.9;
            margin: 5px 0 0; /* plus d'espace */
        }
        .logout-btn {
            background: #ff5c5c;
            color: white;
            border: none;
            padding: 10px 16px; /* padding augmenté */
            margin-top: 12px; /* plus d'espace au-dessus */
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px; /* texte légèrement plus grand */
        }
        .logout-btn:hover {
            background: #ff3030;
        }
        .menu-row {
            transition: 0.3s;
        }
        .menu-btn {
            padding: 20px 16px; /* padding vertical et horizontal augmenté */
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
                            <a href="../logout.php"><input type="button" value="Se déconnecter" class="logout-btn btn-primary-soft btn"></a>
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
            <td class="menu-btn menu-icon-session ">
                <a href="appointment.php" class="non-style-link-menu "><div><p class="menu-text">Mes demandes</p></div></a>
            </td>
        </tr>
        <tr class="menu-row">
            <td class="menu-btn  menu-icon-appoinment menu-active menu-icon-appoinment-active">
                <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Mes sessions</p></div></a>
            </td>
        </tr>
        <tr class="menu-row">
            <td class="menu-btn menu-icon-settings">
                <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Paramètres</p></div></a>
            </td>
        </tr>
    </table>
</div>

    <!-- Dashboard Body -->
<div class="dash-body" style="padding:20px; font-family:Arial, sans-serif; background:#f9f9f9;">
    <table border="0" width="100%" style="border-spacing:0;margin-top:25px;">
        <tr>
            <td width="13%">
                <a href="schedule.php">
                    <button style="padding:11px;margin-left:20px;width:125px; background-color:#4fc3f7; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold; transition:0.3s;">
                        Retour
                    </button>
                </a>
            </td>
            <td>
                <p style="font-size:23px;padding-left:12px;font-weight:600;color:#333;">Mes sessions</p>
            </td>
            <td width="15%">
                <p style="font-size:14px;color:#777;margin:0;text-align:right;">Date du jour</p>
                <p style="margin:0;color:#555;font-weight:500;"><?php echo $today; ?></p>
            </td>
            <td width="10%">
                <button style="display:flex;justify-content:center;align-items:center; background:#e0f7fa; border:none; padding:5px; border-radius:8px; cursor:pointer;">
                    <img src="../img/calendar.svg" width="80%">
                </button>
            </td>
        </tr>
    </table>

    <!-- Sessions Table -->
    <div class="sub-table" style="margin-top:20px; overflow-x:auto;">
        <table style="margin:auto; width:90%; border-collapse:collapse; box-shadow:0 4px 12px rgba(0,0,0,0.1); border-radius:10px; overflow:hidden; background:white;">
            <thead>
                <tr style="background:red; color:white; text-align:center; font-weight:bold;">
                    <th style="padding:12px;">Titre</th>
                    <th style="padding:12px;">Date de début</th>
                    <th style="padding:12px;">Date de fin</th>
                    <th style="padding:12px;">Statut</th>
                    <th style="padding:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($sessions->num_rows > 0){
                while($row = $sessions->fetch_assoc()){
                    $start_date = isset($row['start_date']) ? $row['start_date'] : 'N/A';
                    $end_date   = isset($row['end_date']) ? $row['end_date'] : 'N/A';

                    if ($today < $start_date) {
                        $current_status = "<span style='color:orange;font-weight:bold;'>En attente</span>";
                    } elseif ($today >= $start_date && $today <= $end_date) {
                        $current_status = "<span style='color:green;font-weight:bold;'>En cours</span>";
                    } else {
                        $current_status = "<span style='color:red;font-weight:bold;'>Expiré</span>";
                    }

                    // Intégration du lien de suppression avec l'ID
                    echo "<tr style='text-align:center; transition:0.3s;'>
                            <td style='padding:12px; border-bottom:1px solid #eee;'>{$row['title']}</td>
                            <td style='padding:12px; border-bottom:1px solid #eee;'>{$start_date}</td>
                            <td style='padding:12px; border-bottom:1px solid #eee;'>{$end_date}</td>
                            <td style='padding:12px; border-bottom:1px solid #eee;'>{$current_status}</td>
                            <td style='padding:12px; border-bottom:1px solid #eee;'>
                                <a href='delete-session.php?id={$row['scheduleid']}' style='color:#ff5c5c; text-decoration:none; font-weight:bold; transition:0.3s;' onclick=\"return confirm('Voulez-vous vraiment supprimer cette session ?');\">Supprimer</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;padding:15px;color:#777;'>Aucune session trouvée</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Hover effect pour les lignes */
    .sub-table table tbody tr:hover {
        background-color: #f1f9ff;
    }

    /* Hover effect pour le bouton retour */
    .login-btn:hover {
        background-color: #00b7ffff !important;
    }

    /* Hover effect pour les liens action */
    .sub-table table tbody a:hover {
        text-decoration: underline;
        color: #d43f3f;
    }
</style>


</body>
</html>
