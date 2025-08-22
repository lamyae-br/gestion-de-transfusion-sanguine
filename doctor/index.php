<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Dashboard</title>
    <style>
        .dashbord-tables, .doctor-header, .filter-container, .sub-table, #anim {
            animation: transitionIn-Y-over 0.5s;
        }
        .btn-primary {
            background-color: #b71c1c;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-primary:hover {
            background-color: #a01515;
        }
    </style>
</head>
<body>
<?php
session_start();

// Vérifier si l'utilisateur est connecté et est docteur
if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit();
}

$useremail = $_SESSION["user"];
include("../connection.php");

// Récupération des infos du docteur connecté
$userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");

// Vérification si le docteur existe
if ($userrow && $userrow->num_rows > 0) {
    $userfetch = $userrow->fetch_assoc();
    $userid    = $userfetch["docid"];
    $username  = $userfetch["docname"];
} else {
    // Si aucun docteur trouvé, déconnexion
    header("location: ../logout.php");
    exit();
}

// Données dashboard pour le docteur connecté

// Les 5 dernières demandes du docteur
$lastDemandes = $database->query("
    SELECT * 
    FROM demande 
    WHERE docid = $userid 
    ORDER BY id DESC 
    LIMIT 5
");

// Statistiques par statut
$demandeValide  = $database->query("SELECT * FROM demande WHERE status='Acceptée' AND docid = $userid");
$demandeRefusee = $database->query("SELECT * FROM demande WHERE status='Refusée' AND docid = $userid");
$demandeAttente = $database->query("SELECT * FROM demande WHERE status='En attente' AND docid = $userid");
?>

<div class="container">

    <!-- MENU LATÉRAL -->
    <div class="menu">
    <table class="menu-container" border="0" style="width:100%; font-family:Arial, sans-serif; border-collapse:collapse; background:#f5f7fa;">
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

    <!-- Profil -->
    <tr>
        <td style="padding:10px" colspan="2">
            <table border="0" class="profile-container">
                <tr>
                    <td width="30%" style="padding-left:10px">
                        <img src="../img/user.png" width="100%" style="border-radius:50%; border:2px solid white;">
                    </td>
                    <td>
                        <p class="profile-title"><?php echo substr($username,0,13); ?>..</p>
                        <p class="profile-subtitle"><?php echo substr($useremail,0,22); ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="../logout.php"><input type="button" value=" Se déconnecter" class="logout-btn"></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Menu -->
    <tr class="menu-row menu-active">
    <td class="menu-btn menu-icon-dashbord">
        <a href="index.php"><p class="menu-text">Tableau de bord</p></a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-appoinment">
        <a href="appointment.php"><p class="menu-text">Mes demandes</p></a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-session">
        <a href="schedule.php"><p class="menu-text">Mes sessions</p></a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-settings">
        <a href="settings.php"><p class="menu-text">Paramètres</p></a>
    </td>
</tr>

</table>

    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="dash-body" style="margin-top: 15px">
        <table border="0" width="100%" style="border-spacing:0;margin:0;padding:0;">
            <!-- HEADER -->
          <tr>
    <td class="nav-bar">
        <p style="font-size:23px;font-weight:600;margin-left:20px;color:red;">Tableau de bord</p>

    </td>
    <td width="25%"></td>
    <td width="15%" style="text-align:right;">
        <style>
            /* Styles intégrés pour la date et le bouton */
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
        <p class="date-label">Today's Date</p>
        <p class="heading-sub12"><?php date_default_timezone_set('Asia/Kolkata'); echo date('Y-m-d'); ?></p>
    </td>
    <td width="10%">
        <button class="btn-label">
            <img src="../img/calendar.svg" width="24" height="24">
        </button>
    </td>
</tr>


            <!-- WELCOME -->
          <tr>
    <td colspan="4">
        <center>
            <style>
                /* Style intégré pour la section Welcome */
                .doctor-header {
                    background: linear-gradient(135deg, #4facfe, #00f2fe);
                    color: white;
                    border-radius: 12px;
                    padding: 25px;
                    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    width: 95%;
                    margin: 10px auto;
                    font-family: 'Arial', sans-serif;
                }
                .doctor-header h3 {
                    font-size: 18px;
                    font-weight: 400;
                    margin: 0 0 5px 0;
                    opacity: 0.9;
                }
                .doctor-header h1 {
                    font-size: 28px;
                    font-weight: 700;
                    margin: 5px 0 15px 0;
                }
                .doctor-header p {
                    font-size: 14px;
                    margin-bottom: 20px;
                    opacity: 0.95;
                }
                .doctor-header .btn {
                    background-color: #ff5c5c;
                    border: none;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 8px;
                    font-size: 14px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    text-decoration: none;
                }
                .doctor-header .btn:hover {
                    background-color: #ff3030;
                    transform: scale(1.05);
                }
            </style>

            <table class="filter-container doctor-header" style="border:none;">
                <tr>
                    <td><h3>Bienvenue !</h3>
<h1><?php echo $username; ?>.</h1>
<p>Ravi de vous avoir parmi nous ! Consultez facilement vos demandes depuis votre espace personnel.</p>
<a href="appointment.php" class="btn">Voir mes demandes</a>

                    </td>
                </tr>
            </table>
        </center>
    </td>
</tr>

            <!-- DERNIÈRES DEMANDES -->
     <tr>
    <td colspan="4">
        <center>
            <style>
                .sub-table {
                    font-size: 14px;
                    border-collapse: collapse;
                    width: 100%;
                }
                .sub-table th, .sub-table td {
                    padding: 8px 12px;
                    text-align: center;
                    border-bottom: 1px solid #ddd;
                }
                .sub-table th {
                    background-color: #0A76D8; /* Header bleu */
                    color: white;
                }

                /* Badges rectangles pour status */
                .status-badge {
                    display: inline-block;
                    padding: 5px 12px;
                    border-radius: 6px;
                    font-weight: bold;
                    color: white;
                    min-width: 80px;
                }
                .status-en-attente { background-color: #ff9800; }  /* jaune */
                .status-acceptee { background-color: #4caf50; }    /* vert */
                .status-refusee { background-color: #f44336; }     /* rouge */

                /* Lien Voir Plus */
                .view-link {
                    color: #0A76D8;
                    text-decoration: none;
                    font-weight: 500;
                }
                .view-link:hover {
                    text-decoration: underline;
                }

                /* Scrollable container */
                .abc.scroll {
                    width: 95%;
                    max-height: 250px;
                    overflow-y: auto;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    margin: 0 auto;
                }

                .btn-primary {
                    padding: 6px 12px;
                    border-radius: 5px;
                    background-color: #b71c1c;
                    color: white;
                    text-decoration: none;
                    font-size: 14px;
                }
            </style>

            <div style="display:flex;justify-content:space-between;align-items:center;width:95%;margin-bottom:10px;">
                <p style="font-size:20px;font-weight:600;margin:0;">Dernières Demandes</p>
                <a href="appointment.php" class="btn-primary">Voir toutes les demandes</a>
            </div>

            <div class="abc scroll">
                <table class="sub-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Groupe</th>
                            <th>Urgence</th>
                            <th>Quantité</th>
                            <th>Type</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Voir Plus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
// Récupérer les 5 dernières demandes du docteur
$lastDemandes = $database->query("SELECT * FROM demande WHERE docid = $userid ORDER BY id DESC LIMIT 5");

// Tableau pour stocker les détails des demandes en JS
$demandeDetails = [];

while ($demande = $lastDemandes->fetch_assoc()) {
    // Associer la couleur au statut exact
    $statusClass = '';
    if ($demande['status'] == 'En attente') $statusClass = 'status-en-attente';
    elseif ($demande['status'] == 'Acceptée') $statusClass = 'status-acceptee';
    elseif ($demande['status'] == 'Refusée') $statusClass = 'status-refusee';

    echo "<tr>
            <td>{$demande['id']}</td>
            <td>{$demande['groupe_sanguin']}</td>
            <td>{$demande['niveau_urgence']}</td>
            <td>{$demande['quantite_demandee']}</td>
            <td>{$demande['type_sang']}</td>
            <td>{$demande['service_concerne']}</td>
            <td><span class='status-badge $statusClass'>{$demande['status']}</span></td>
            <td>{$demande['date_demande']}</td>
            <td><a href='#' class='view-link' onclick='showPopup({$demande['id']})'>Voir Plus</a></td>
          </tr>";

    // Vérifier si un fichier justificatif existe
    $fileHTML = "Aucune";
    if (!empty($demande['fichier_justificatif'])) {
        $filePathRelative = "uploads/" . basename($demande['fichier_justificatif']);
        $absolutePath = __DIR__ . "/uploads/" . basename($demande['fichier_justificatif']);

        if (file_exists($absolutePath)) {
            $ext = strtolower(pathinfo($filePathRelative, PATHINFO_EXTENSION));
            if (in_array($ext, ['png','jpg','jpeg','gif'])) {
                $fileHTML = "<a href='{$filePathRelative}' target='_blank'>
                                <img src='{$filePathRelative}' style='width:50px;height:50px;object-fit:cover;border-radius:5px;' alt='Justificatif'>
                             </a>";
            } else {
                $fileHTML = "<a href='{$filePathRelative}' target='_blank'>📄 Voir le fichier</a>";
            }
        }
    }

    // Stocker les détails dans un tableau JS
    $demandeDetails[$demande['id']] = [
        'id' => $demande['id'],
        'groupe_sanguin' => $demande['groupe_sanguin'],
        'niveau_urgence' => $demande['niveau_urgence'],
        'quantite_demandee' => $demande['quantite_demandee'],
        'type_sang' => $demande['type_sang'],
        'service_concerne' => $demande['service_concerne'],
        'commentaire' => $demande['commentaire'],
        'fichier_justificatif_html' => $fileHTML,
        'date_demande' => $demande['date_demande'],
        'status' => $demande['status']
    ];
}
?>

<!-- Popup -->
<div id="popup" class="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div class="popup" style="background:#fff; padding:20px; border-radius:10px; max-width:500px; width:90%; position:relative;">
        <a class="close" href="#" onclick="hidePopup()" style="position:absolute; top:10px; right:15px; font-size:24px; text-decoration:none; color:#333;">&times;</a>
        <h2 style="text-align:center; margin-bottom:15px;">Détails de la demande</h2>
        <div class="content" id="popupContent" style="text-align:left; padding:10px 0;"></div>
        <div style="text-align:center; margin-top:20px;">
            <button onclick="hidePopup()" style="padding:8px 15px; background:#007bff; color:#fff; border:none; border-radius:5px;">Fermer</button>
        </div>
    </div>
</div>

<script>
// Convertir le tableau PHP en JS
var demandes = <?php echo json_encode($demandeDetails); ?>;

function showPopup(id) {
    var d = demandes[id];
    var content = `
        <p><b>ID :</b> ${d.id}</p>
        <p><b>Groupe sanguin :</b> ${d.groupe_sanguin}</p>
        <p><b>Niveau d'urgence :</b> ${d.niveau_urgence}</p>
        <p><b>Quantité demandée :</b> ${d.quantite_demandee} poches</p>
        <p><b>Type de sang :</b> ${d.type_sang}</p>
        <p><b>Service concerné :</b> ${d.service_concerne}</p>
        <p><b>Commentaire :</b> ${d.commentaire ? d.commentaire : 'Aucun'}</p>
        <p><b>Pièce justificative :</b> ${d.fichier_justificatif_html}</p>
        <p><b>Date de demande :</b> ${d.date_demande}</p>
        <p><b>Status :</b> ${d.status}</p>
    `;
    document.getElementById('popupContent').innerHTML = content;
    document.getElementById('popup').style.display = 'flex';
}

function hidePopup() {
    document.getElementById('popup').style.display = 'none';
}
</script>

                        
    <style>                
.overlay {
    position: fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.6);
    display:flex; justify-content:center; align-items:center;
    z-index:1000;
}
.popup {
    background:#fff; border-radius:10px; width:50%; max-width:600px; padding:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.3); position:relative; font-family:Arial,sans-serif;
}
.popup h2 { margin-top:0; text-align:center; }
.popup .close {
    position:absolute; top:10px; right:15px; font-size:25px; font-weight:bold; color:#333; text-decoration:none;
}
.popup .close:hover { color:red; }
.popup .content p { margin:8px 0; font-size:14px; }
.popup button { padding:8px 20px; border:none; border-radius:6px; cursor:pointer; background-color:#2196F3; color:#fff; font-size:14px; }
.popup button:hover { background-color:#1976D2; }
.status-badge.status-en-attente { background:#ff9800; color:#fff; padding:2px 6px; border-radius:4px; }
.status-badge.status-acceptee { background:#4caf50; color:#fff; padding:2px 6px; border-radius:4px; }
.status-badge.status-refusee { background:#f44336; color:#fff; padding:2px 6px; border-radius:4px; }
</style>

                    </tbody>
                </table>
            </div>
        </center>
    </td>
</tr>


            <!-- STATUS -->
          <tr>
    <td colspan="4">
        <center>
            <style>
                /* Conteneur principal */
                .status-container {
                    border: none;
                    width: 95%;
                    border-spacing: 20px;
                    margin-top: 20px;
                }

                /* Titre */
                .status-header p {
                    font-size: 22px;
                    font-weight: 700;
                    text-align: left;
                    padding-bottom: 12px;
                    border-bottom: 3px solid #eee;
                    margin: 0 0 10px 0;
                    color: #333;
                }

                /* Carte status */
                .status-card {
                    border-radius: 12px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 18px 20px;
                    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                    color: #fff;
                }
                .status-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
                }

                /* Couleurs par statut */
                .status-valid { background: #4caf50; }    /* Vert pour validées */
                .status-refuse { background: #f44336; }   /* Rouge pour refusées */
                .status-attente { background: #ff9800; }  /* Orange pour en attente */

                /* Informations */
                .status-info {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-start;
                }
                .status-count {
                    font-size: 28px;
                    font-weight: 700;
                }
                .status-label {
                    font-size: 14px;
                    opacity: 0.9;
                }

                /* Icône */
                .status-icon {
                    width: 50px;
                    height: 50px;
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center;
                }

                /* Responsive */
                @media screen and (max-width: 768px) {
                    .status-container {
                        width: 100%;
                    }
                    .status-card {
                        flex-direction: column;
                        align-items: center;
                        text-align: center;
                        padding: 15px;
                    }
                    .status-info {
                        align-items: center;
                        margin-bottom: 10px;
                    }
                }
            </style>

           <table class="status-container">
    <tr>
        <td colspan="3">
            <div class="status-header">
                <p>Status des Demandes</p>
            </div>
        </td>
    </tr>
    <tr>
        <!-- Validées -->
        <td>
            <div class="status-card status-valid">
                <div class="status-info">
                    <div class="status-count"><?php echo $demandeValide->num_rows; ?></div>
                    <div class="status-label">Validées</div>
                </div>
                <div class="status-icon" style="background-image:url('../img/icons/check.svg');"></div>
            </div>
        </td>

        <!-- Refusées -->
        <td>
            <div class="status-card status-refuse">
                <div class="status-info">
                    <div class="status-count"><?php echo $demandeRefusee->num_rows; ?></div>
                    <div class="status-label">Refusées</div>
                </div>
                <div class="status-icon" style="background-image:url('../img/icons/cancel.svg');"></div>
            </div>
        </td>

        <!-- En attente -->
        <td>
            <div class="status-card status-attente">
                <div class="status-info">
                    <div class="status-count"><?php echo $demandeAttente->num_rows; ?></div>
                    <div class="status-label">En Attente</div>
                </div>
                <div class="status-icon" style="background-image:url('../img/icons/wait.svg');"></div>
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

        </table>
    </div>
</div>
</body>
</html>
