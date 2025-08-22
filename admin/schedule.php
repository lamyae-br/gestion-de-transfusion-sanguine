<?php
session_start();

// Vérification de session admin
if (!isset($_SESSION["user"]) || $_SESSION["user"] === "" || $_SESSION['usertype'] !== 'a') {
    header("Location: ../login.php");
    exit;
}

// Connexion base de données
require_once("../connection.php");

// Récupérer tous les docteurs
$doctors = [];
$sql = "SELECT * FROM doctor";
$result = $database->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
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
<title>Schedule Manager</title>
<style>
    .popup { animation: transitionIn-Y-bottom 0.5s; }
    .sub-table { animation: transitionIn-Y-bottom 0.5s; }
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


body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 0;
    color: #333;
}

/* --- Titres et sous-titres --- */
.heading-main12, .heading-sub12 {
    font-weight: 600;
    color: #313131;
}
.heading-main12 {
    font-size: 20px;
}
.heading-sub12 {
    font-size: 14px;
    color: #777;
}

/* --- Boutons --- */
.btn, .login-btn {
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
    padding: 8px 16px;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-primary-soft {
    background-color: #e7f1ff;
    color: #007bff;
    padding: 8px 16px;
}

.btn-primary-soft:hover {
    background-color: #cce4ff;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
    padding: 8px 16px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* --- Tableaux --- */
.sub-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.sub-table th, .sub-table td {
    padding: 12px 15px;
    font-size: 14px;
}

.sub-table th {
    background: linear-gradient(90deg, #000000ff, #000000ff);
    color: #fff;
    font-weight: 600;
    text-align: left;
}

.sub-table tr:nth-child(even) {
    background-color: #f7f9fc;
}

.sub-table tr:hover {
    background-color: #e3f2fd;
}

/* --- Inputs et select --- */
input[type="text"], input[type="number"], input[type="date"], select {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 12px;
}

/* --- Scrollable div --- */
.abc.scroll {
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
}

/* --- Popups --- */
.overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.popup {
    background-color: #fff;
    padding: 25px;
    border-radius: 12px;
    width: 450px;
    max-width: 90%;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

.popup h2 {
    margin-top: 0;
    font-size: 22px;
    color: #007bff;
    text-align: center;
}

.popup .close {
    position: absolute;
    top: 15px;
    right: 20px;
    text-decoration: none;
    font-size: 25px;
    color: #555;
    font-weight: bold;
}

.popup .content {
    margin: 15px 0;
    font-size: 15px;
    color: #555;
    text-align: center;
}

/* --- Responsive --- */
@media (max-width: 768px) {
    .sub-table th, .sub-table td {
        padding: 8px;
        font-size: 12px;
    }

    .btn, .login-btn {
        font-size: 12px;
        padding: 6px 12px;
    }

    .popup {
        width: 90%;
        padding: 15px;
    }
}

@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
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
             <?php
$page = basename($_SERVER['PHP_SELF']); // récupère le nom de la page actuelle
?>
<tr class="menu-row">
    <td class="menu-btn menu-icon-dashbord <?php if($page=='index.php'){echo 'menu-active menu-icon-dashbord-active';} ?>">
        <a href="index.php" class="non-style-link-menu" style="display:block; width:100%; padding:10px;">
            <p class="menu-text">Tableau de bord</p>
        </a>
    </td>
</tr>

<tr class="menu-row">
    <td class="menu-btn menu-icon-doctor <?php if($page=='doctors.php'){echo 'menu-active menu-icon-doctor-active';} ?>">
        <a href="doctors.php" class="non-style-link-menu" style="display:block; width:100%; padding:10px;">
            <p class="menu-text">Médecins</p>
        </a>
    </td>
</tr>

<tr class="menu-row">
    <td class="menu-btn menu-icon-schedule <?php if($page=='schedule.php'){echo 'menu-active menu-icon-schedule-active';} ?>">
        <a href="schedule.php" class="non-style-link-menu" style="display:block; width:100%; padding:10px;">
            <p class="menu-text">sessions</p>
        </a>
    </td>
</tr>

<tr class="menu-row">
    <td class="menu-btn menu-icon-appoinment <?php if($page=='appointment.php'){echo 'menu-active menu-icon-appoinment-active';} ?>">
        <a href="appointment.php" class="non-style-link-menu" style="display:block; width:100%; padding:10px;">
            <p class="menu-text">demandes</p>
        </a>
    </td>
</tr>

            </table>
        </div>

    <div class="dash-body">
        <table border="0" width="100%" style="border-spacing:0;margin-top:25px;">
            <tr>
                <td width="13%">
                    <a href="schedule.php">
                        <button class="login-btn btn-primary-soft btn btn-icon-back" 
                                style="padding:11px 0;margin-left:20px;width:125px">Retour</button>
                    </a>
                </td>
                <td>
                    <p style="font-size:23px;padding-left:12px;font-weight:600;">Gestion du planning</p>
                </td>
                <td width="15%">
                    <p style="font-size:14px;color: rgb(119,119,119);padding:0;margin:0;text-align:right;">Date du jour</p>
                    <p class="heading-sub12" style="padding:0;margin:0;"><?php echo date('Y-m-d'); ?></p>
                </td>
                <td width="10%">
                    <button class="btn-label" style="display:flex;justify-content:center;align-items:center;">
                        <img src="../img/calendar.svg" width="100%">
                    </button>
                </td>
            </tr>

            <!-- Programmer une session -->
            <tr>
                <td colspan="4">
                    <div style="display:flex;margin-top:40px;">
                        <div class="heading-main12" style="margin-left:45px;font-size:20px;color:rgb(49,49,49);margin-top:5px;">
                            Programmer une session
                        </div>
                        <button onclick="document.getElementById('sessionForm').style.display='block'" 
                                class="login-btn btn-primary btn button-icon" 
                                style="margin-left:25px;background-image:url('../img/icons/add.svg');">
                            Ajouter une session
                        </button>
                    </div>
                </td>
            </tr>

            <!-- Formulaire d'ajout -->
            <tr id="sessionForm" style="display:none;">
                <td colspan="4">
                    <div style="margin:20px;padding:20px;border:1px solid #ccc;border-radius:8px;background:#f9f9f9;">
                        <h3>Ajouter une nouvelle session</h3>
                        <form method="POST" action="add-session.php">
                            <label>Titre de la session :</label><br>
                            <input type="text" name="title" required><br><br>

                            <label>Sélectionner un médecin :</label><br>
                            <select name="docid" required>
                                <option value="" disabled selected>Choisir un médecin</option>
                                <?php
                                foreach($doctors as $doc){
                                    echo "<option value='{$doc['docid']}'>{$doc['docname']}</option>";
                                }
                                ?>
                            </select><br><br>

                            <label>Nombre de demandes :</label><br>
                            <input type="number" name="nop" min="1" required><br><br>

                            <label>Date de début :</label><br>
                            <input type="date" name="start_date" required><br><br>

                            <label>Date de fin :</label><br>
                            <input type="date" name="end_date" required><br><br>

                            <button type="submit" class="btn btn-primary">Enregistrer la session</button>
                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('sessionForm').style.display='none'">Annuler</button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- Nombre de sessions -->
            <tr>
                <td colspan="4" style="padding-top:10px;width:100%;">
                    <p class="heading-main12" style="margin-left:45px;font-size:18px;color:rgb(49,49,49)">
                        Toutes les sessions (<?php echo $database->query("SELECT * FROM schedule")->num_rows; ?>)
                    </p>
                </td>
            </tr>

            <!-- Tableau des sessions -->
            <tr>
                <td colspan="4" style="padding-top:0;width:100%;">
                    <center>
                        <div class="abc scroll">
                            <table width="93%" class="sub-table scrolldown" border="0">
                                <thead>
                                    <tr>
                                        <th class="table-headin">Médecin</th>
                                        <th class="table-headin">Titre de la session</th>
                                        <th class="table-headin">Date de début</th>
                                        <th class="table-headin">Date de fin</th>
                                        <th class="table-headin">Nombre max de demandes</th>
                                        <th class="table-headin">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $result = $database->query("SELECT s.scheduleid, s.title, s.start_date, s.end_date, s.nop, d.docname 
                                                            FROM schedule s
                                                            JOIN doctor d ON s.docid = d.docid
                                                            ORDER BY s.start_date DESC");

                                if ($result->num_rows == 0) {
                                    echo '<tr><td colspan="6" style="text-align:center;padding:40px 0;">
                                            <img src="../img/notfound.svg" width="25%"><br>
                                            <p class="heading-main12" style="font-size:20px;color:rgb(49,49,49)">
                                                Aucune session planifiée pour le moment !
                                            </p>
                                          </td></tr>';
                                } else {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>
                                                <td>'.htmlspecialchars($row['docname']).'</td>
                                                <td>'.htmlspecialchars($row['title']).'</td>
                                                <td>'.htmlspecialchars($row['start_date']).'</td>
                                                <td>'.htmlspecialchars($row['end_date']).'</td>
                                                <td>'.htmlspecialchars($row['nop']).'</td>
                                                <td>
                                                    <a href="schedule.php?action=drop&id='.$row['scheduleid'].'" class="non-style-link">
                                                        <button class="btn-primary btn" style="padding:5px 10px;">Supprimer</button>
                                                    </a>
                                                </td>
                                              </tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </center>
                </td>
            </tr>

<?php
// Popups pour confirmation
if($_GET){
    $action = $_GET["action"] ?? '';
    $id = $_GET["id"] ?? '';

    if($action=='drop' && !empty($id)){
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Êtes-vous sûr ?</h2>
                    <a class="close" href="schedule.php">&times;</a>
                    <div class="content">
                        Voulez-vous supprimer cette session médicale ? (ID : '.htmlspecialchars($id).')
                    </div>
                    <div style="display:flex;justify-content:center;">
                        <a href="delete-session.php?id='.$id.'" class="non-style-link">
                            <button class="btn-primary btn" style="margin:10px;padding:10px;">Oui</button>
                        </a>
                        &nbsp;&nbsp;&nbsp;
                        <a href="schedule.php" class="non-style-link">
                            <button class="btn-primary btn" style="margin:10px;padding:10px;">Non</button>
                        </a>
                    </div>
                </center>
            </div>
        </div>';
    }
}
?>

        </table>
    </div>
</div>
</body>

</html>
