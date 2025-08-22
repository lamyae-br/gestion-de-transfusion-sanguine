<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit;
}

// Import database
include("../connection.php");

// Comptages sécurisés
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');
$patientrow = $database->query("SELECT * FROM patient;");
$doctorrow = $database->query("SELECT * FROM doctor;");
$appointmentrow = $database->query("SELECT * FROM appointment WHERE appodate >= '$today';");
$schedulerow = $database->query("SELECT * FROM schedule WHERE start_date = '$today';");
$demandrow = $database->query("SELECT * FROM demande WHERE date_demande >= '$today';"); // ajouté
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
<title>Dashboard</title>
<style>
.dashbord-tables{animation: transitionIn-Y-over 0.5s;}
.filter-container{animation: transitionIn-Y-bottom 0.5s;}
.sub-table{animation: transitionIn-Y-bottom 0.5s;}
</style>
<style>
/* Menu et profil */
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
    background: #000000ff;
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
    background: #000000ff;
}
.menu-active a {
    color: white !important;
}

/* Header date et bouton */
.date-label {
    font-size:14px;
    color:#777;
    margin:0;
}
.heading-sub12 {
    font-size:16px;
    font-weight:600;
    color:#222;
    margin:2px 0 0 0;
}
.btn-label {
    background-color:#4facfe;
    border:none;
    border-radius:8px;
    padding:6px;
    cursor:pointer;
    transition: all 0.3s ease;
}
.btn-label img {
    display:block;
}
.btn-label:hover {
    background-color:#00f2fe;
    transform: scale(1.05);
}
</style>
<style>
    /* Corps principal */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f8;
    color: #333;
    margin: 0;
    padding: 0;
}
.dash-body {
    padding: 20px;
}

/* Tables */
.sub-table {
    width: 100%;              /* Toujours 100% de la div parent */
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.sub-table th {
    background-color: #000000ff;
    color: white;
    font-weight: 600;
    padding: 12px;
    text-align: center;
    font-size: 14px;
}
.sub-table td {
    padding: 12px;
    text-align: center;
    font-size: 13px;
    color: #333;
}
.sub-table tr:nth-child(even) {
    background-color: #f0f6fb;
}
.sub-table tr:hover {
    background-color: #e0f7fa;
}

/* Scrollable containers */
.abc.scroll {
    overflow-y: auto;
    max-height: 220px;
    padding: 5px 0;
    width: 100%;  /* Occupe toute la largeur de la div parent */
}

/* Boutons principaux */
.btn-primary {
    background-color: #494657ff;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    background-color: #f1eff9ff;
    transform: scale(1.02);
}

/* Responsive */
@media screen and (max-width: 1024px){
    .dashboard-items {
        flex-direction: column;
        text-align: center;
    }
}

    </style>

</head>
<body>

<div class="container">
   <div class="menu">
    <table class="menu-container" border="0">
        <tr>
            <td style="padding:10px" colspan="2">
                <table border="0" class="profile-container">
                    <tr>
                        <td width="30%" style="padding-left:20px">
                            <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                        </td>
                        <td style="padding:0;margin:0;">
                            <p class="profile-title">Administrateur</p>
                            <p class="profile-subtitle">admin@edoc.com</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="../logout.php">
                                <input type="button" value="Déconnexion" class="logout-btn btn-primary-soft btn">
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="menu-row">
            <td class="menu-btn menu-icon-dashbord menu-active menu-icon-dashbord-active">
                <a href="index.php" class="non-style-link-menu non-style-link-menu-active">
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
                    <div><p class="menu-text">session</p></div>
                </a>
            </td>
        </tr>
        <tr class="menu-row">
            <td class="menu-btn menu-icon-appoinment">
                <a href="appointment.php" class="non-style-link-menu">
                    <div><p class="menu-text">demandes</p></div>
                </a>
            </td>
        </tr>
        
    </table>
</div>


    <div class="dash-body" style="margin-top: 15px">
        <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;">
    <tr>
        <td colspan="2" class="nav-bar">
            <form action="doctors.php" method="post" class="header-search">
                <input type="search" name="search" class="input-text header-searchbar" placeholder="Rechercher un médecin par nom ou e-mail" list="doctors">&nbsp;&nbsp;
                <?php
                echo '<datalist id="doctors">';
                $list11 = $database->query("SELECT docname, docemail FROM doctor;");
                while ($row00 = $list11->fetch_assoc()) {
                    $d = htmlspecialchars($row00["docname"]);
                    $c = htmlspecialchars($row00["docemail"]);
                    echo "<option value='$d'>";
                    echo "<option value='$c'>";
                }
                echo '</datalist>';
                ?>
                <input type="submit" value="Rechercher" class="login-btn btn-primary-soft btn" style="padding:10px 25px;">
            </form>
        </td>

        <td width="15%">
            <p style="font-size:14px;color: rgb(119,119,119);padding:0;margin:0;text-align:right;">Date d'aujourd'hui</p>
            <p class="heading-sub12" style="padding:0;margin:0;"><?php echo $today; ?></p>
        </td>
        <td width="10%">
            <button class="btn-label" style="display:flex;justify-content:center;align-items:center;">
                <img src="../img/calendar.svg" width="100%">
            </button>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <center>
                <table style="border:none;width:100%;max-width:1000px;margin:0 auto;">
                    <tr>
                        <td colspan="4">
                            <p style="font-size:20px;font-weight:600;padding-left:12px;margin:0 0 10px 0;">Statistiques</p>
                        </td>
                    </tr>
                    <tr>
                        <!-- Médecins -->
                        <td style="width:33%;padding:10px;">
                            <div style="padding:20px;margin:auto;width:95%;display:flex;justify-content:space-between;align-items:center;background-color:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                                <div>
                                    <div style="font-size:28px;font-weight:bold;color:#4facfe;"><?php echo $doctorrow->num_rows; ?></div><br>
                                    <div style="font-size:14px;color:#555;">Médecins</div>
                                </div>
                                <div style="width:60px;height:60px;background-image:url('../img/icons/doctors-hover.svg');background-size:cover;background-position:center;border-radius:50%;flex-shrink:0;"></div>
                            </div>
                        </td>

                        <!-- Demandes -->
                        <td style="width:33%;padding:10px;">
                            <div style="padding:20px;margin:auto;width:95%;display:flex;justify-content:space-between;align-items:center;background-color:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                                <div>
                                    <div style="font-size:28px;font-weight:bold;color:#ff5c5c;"><?php echo $demandrow->num_rows; ?></div><br>
                                    <div style="font-size:14px;color:#555;">Demandes</div>
                                </div>
                                <div style="width:60px;height:60px;background-image:url('../img/icons/patients-hover.svg');background-size:cover;background-position:center;border-radius:50%;flex-shrink:0;"></div>
                            </div>
                        </td>

                        <!-- Séances du jour -->
                        <td style="width:33%;padding:10px;">
                            <div style="padding:20px;margin:auto;width:95%;display:flex;justify-content:space-between;align-items:center;background-color:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                                <div>
                                    <div style="font-size:28px;font-weight:bold;color:#4caf50;"><?php echo $schedulerow->num_rows; ?></div><br>
                                    <div style="font-size:14px;color:#555;">Séances du jour</div>
                                </div>
                                <div style="width:60px;height:60px;background-image:url('../img/icons/session-iceblue.svg');background-size:cover;background-position:center;border-radius:50%;flex-shrink:0;"></div>
                            </div>
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
<tr width="100%">
    <!-- Dernières Demandes -->
    <td colspan="2" width="100%">
        <center>
            <div style="display:flex; justify-content: space-between; align-items:center; width:90%; margin-left:auto; margin-right:0; margin-bottom:10px;">
                <h3 style="color:#000000; font-family: 'Segoe UI', Tahoma, sans-serif;">Dernières demandes</h3>
                <a href="appointment.php">
                    <button style="padding:6px 12px; background:#b91c1c; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">
                        Voir toutes les demandes
                    </button>
                </a>
            </div>
            <div class="abc scroll" 
                 style="height:200px; margin-bottom:30px; width:90%; margin-left:auto; margin-right:0; 
                        border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); overflow-x:auto; background:#fef2f2;">
                <table width="100%" class="sub-table scrolldown" border="0"
                       style="border-collapse:collapse; width:100%; font-family: 'Segoe UI', sans-serif; font-size:14px;">
                    <thead>
                        <tr style="background:#000000; color:white; text-align:left;">
                            <th style="padding:12px;">Demande ID</th>
                            <th style="padding:12px;">Type de demande</th>
                            <th style="padding:12px;">Doctor</th>
                            <th style="padding:12px;">Service</th>
                            <th style="padding:12px;">Date demande</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
$sqlDemandes = "SELECT d.id, d.type_sang, d.groupe_sanguin, d.quantite_demandee, d.niveau_urgence, 
                       d.service_concerne, d.date_demande, doc.docname
                FROM demande d
                INNER JOIN doctor doc ON d.docid = doc.docid
                ORDER BY d.date_demande DESC
                LIMIT 5"; // Les 5 dernières demandes

$result = $database->query($sqlDemandes);

if ($result->num_rows == 0) {
    echo '<tr><td colspan="5" style="text-align:center; padding:20px;">
            <img src="../img/notfound.svg" width="15%"><br>
            <p style="font-size:16px;color:rgb(100,100,100)">Aucune demande trouvée !</p>
          </td></tr>';
} else {
    $rowIndex = 0;
    while ($row = $result->fetch_assoc()) {
        $bgColor = ($rowIndex % 2 == 0) ? "#fca5a5" : "#fee2e2"; 
        echo '<tr style="background:'.$bgColor.'; transition:0.3s; cursor:pointer;" 
                   onmouseover="this.style.background=\'#f87171\'" 
                   onmouseout="this.style.background=\''.$bgColor.'\'">
                <td style="padding:12px;">'.htmlspecialchars($row["id"]).'</td>
                <td style="padding:12px;">'.htmlspecialchars($row["type_sang"]).'</td>
                <td style="padding:12px;">'.htmlspecialchars(substr($row["docname"],0,25)).'</td>
                <td style="padding:12px;">'.htmlspecialchars(substr($row["service_concerne"],0,25)).'</td>
                <td style="padding:12px;">'.htmlspecialchars(substr($row["date_demande"],0,10)).'</td>
              </tr>';
        $rowIndex++;
    }
}
?>
                    </tbody>
                </table>
            </div>
        </center>
    </td>
</tr>

<tr>
    <!-- Dernières Sessions -->
    <td colspan="2">
        <center>
            <div style="display:flex; justify-content: space-between; align-items:center; width:90%; margin-left:auto; margin-right:0; margin-bottom:10px;">
                <h3 style="color:#000000; font-family: 'Segoe UI', Tahoma, sans-serif;">Dernières sessions</h3>
                <a href="schedule.php">
                    <button style="padding:6px 12px; background:#b91c1c; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">
                        Voir toutes les sessions
                    </button>
                </a>
            </div>
            <div class="abc scroll" 
                 style="height:200px; margin-bottom:30px; width:90%; margin-left:auto; margin-right:0; 
                        border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); overflow-x:auto; background:#fef2f2;">
                <table width="100%" class="sub-table scrolldown" border="0"
                       style="border-collapse:collapse; width:100%; font-family: 'Segoe UI', sans-serif; font-size:14px;">
                   <thead>
      <tr style="background:#000000; color:white; text-align:left;">
        <th style="padding:12px;">Titre de la session</th>
        <th style="padding:12px;">Médecin</th>
        <th style="padding:12px;">Début prévu</th>
        <th style="padding:12px;">Fin prévue</th>
        <th style="padding:12px;">Nombre de demandes</th>
    </tr>
</thead>

                    <tbody>
                        <?php
                        $nextweek = date("Y-m-d", strtotime("+1 week"));
                        $sqlmain = "SELECT s.scheduleid, s.title, d.docname, s.start_date, s.end_date, s.nop
                                    FROM schedule s
                                    INNER JOIN doctor d ON s.docid = d.docid
                                    WHERE s.start_date >= '$today' AND s.start_date <= '$nextweek'
                                    ORDER BY s.start_date DESC
                                    LIMIT 5";
                        $result = $database->query($sqlmain);

                        if ($result->num_rows == 0) {
                            echo '<tr><td colspan="5" style="text-align:center; padding:20px;">
                            <img src="../img/notfound.svg" width="15%"><br>
                            <p style="font-size:16px;color:rgb(100,100,100)">Aucune session trouvée!</p></td></tr>';
                        } else {
                            $rowIndex = 0;
                            while ($row = $result->fetch_assoc()) {
                                $bgColor = ($rowIndex % 2 == 0) ? "#fca5a5" : "#fee2e2"; 
                                echo '<tr style="background:'.$bgColor.'; transition:0.3s; cursor:pointer;" 
                                           onmouseover="this.style.background=\'#f87171\'" 
                                           onmouseout="this.style.background=\''.$bgColor.'\'">
                                    <td style="padding:12px;">'.htmlspecialchars(substr($row["title"],0,30)).'</td>
                                    <td style="padding:12px;">'.htmlspecialchars(substr($row["docname"],0,20)).'</td>
                                    <td style="padding:12px;">'.htmlspecialchars($row["start_date"]).'</td>
                                    <td style="padding:12px;">'.htmlspecialchars($row["end_date"]).'</td>
                                    <td style="padding:12px;">'.htmlspecialchars($row["nop"]).'</td>
                                </tr>';
                                $rowIndex++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </center>
    </td>
</tr>


</body>
</html>
