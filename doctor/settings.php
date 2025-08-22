<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        


    <title>Settings</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-X  0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
    
    
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];
    $username=$userfetch["docname"];


    //echo $userid;
    //echo $username;
    
    ?>
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
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
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
    <td class="menu-btn menu-icon-appoinment">
        <a href="appointment.php" class="non-style-link-menu">
            <div><p class="menu-text">Mes demandes</p></div>
        </a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-session">
        <a href="schedule.php" class="non-style-link-menu">
            <div><p class="menu-text">Mes sessions</p></div>
        </a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-settings menu-active menu-icon-settings-active">
        <a href="settings.php" class="non-style-link-menu non-style-link-menu-active">
            <div><p class="menu-text">Paramètres</p></div>
        </a>
    </td>
</tr>

            </table>
        </div>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
                       <tr style="background:#f8f9fa;">
    <!-- Bouton Retour -->
    <td width="13%">
        <a href="settings.php">
            <button style="
                padding:10px 18px;
                margin-left:20px;
                width:130px;
                background: linear-gradient(135deg, #4facfe, #00f2fe);
                color:white;
                border:none;
                border-radius:8px;
                font-weight:600;
                font-size:14px;
                cursor:pointer;
                box-shadow:0 4px 6px rgba(0,0,0,0.1);
                transition:0.3s;
            " 
            onmouseover="this.style.background='linear-gradient(135deg, #3a95f6, #00d4ff)';" 
            onmouseout="this.style.background='linear-gradient(135deg, #4facfe, #00f2fe)';">
                Retour
            </button>
        </a>
    </td>

    <!-- Titre -->
    <td>
        <p style="
            font-size:24px;
            font-weight:700;
            color:#222;
            margin-left:15px;
            margin-bottom:0;
        ">Paramètres</p>
    </td>

    <!-- Date du jour -->
    <td width="15%">
        <p style="
            font-size:13px;
            color:#999;
            margin:0;
            text-align:right;
        ">Date du jour</p>
        <p style="
            font-size:14px;
            font-weight:500;
            color:#555;
            text-align:right;
            margin:2px 0 0 0;
        ">
            <?php 
            date_default_timezone_set('Asia/Kolkata');
            echo date('d-m-Y');
            ?>
        </p>
    </td>

    <!-- Bouton Calendrier -->
    <td width="10%">
        <button style="
            display:flex;
            justify-content:center;
            align-items:center;
            background:#e0f7fa;
            border:none;
            border-radius:8px;
            padding:6px;
            cursor:pointer;
            transition:0.3s;
            box-shadow:0 2px 4px rgba(0,0,0,0.1);
        "
        onmouseover="this.style.background='#b2ebf2';" 
        onmouseout="this.style.background='#e0f7fa';">
            <img src="../img/calendar.svg" width="70%">
        </button>
    </td>
</tr>

                <tr>
    <td colspan="4">
        <center>
            <table class="filter-container" style="border:none;" border="0">
                <tr>
                    <td colspan="4">
                        <p style="font-size: 20px">&nbsp;</p>
                    </td>
                </tr>

                <!-- Paramètres du compte -->
                <tr>
                    <td style="width: 25%;">
                        <a href="?action=edit&id=<?php echo $userid ?>&error=0" class="non-style-link" style="text-decoration:none;">
                            <div style="padding:20px; margin:auto; width:95%; display:flex; align-items:center; background:#e0f7fa; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); transition:0.3s;">
                                <div style="width:50px; height:50px; background-image: url('../img/icons/doctors-hover.svg'); background-size:cover; background-position:center; border-radius:10px; margin-right:15px;"></div>
                                <div>
                                    <div style="font-size:18px; font-weight:bold; color:#333;">Paramètres du compte</div>
                                    <div style="font-size:14px; color:#555;">Modifiez vos informations personnelles et changez votre mot de passe</div>
                                </div>
                            </div>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td colspan="4"><p style="font-size: 5px">&nbsp;</p></td>
                </tr>

                <!-- Voir les détails du compte -->
                <tr>
                    <td style="width: 25%;">
                        <a href="?action=view&id=<?php echo $userid ?>" class="non-style-link" style="text-decoration:none;">
                            <div style="padding:20px; margin:auto; width:95%; display:flex; align-items:center; background:#e0f7fa; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); transition:0.3s;">
                                <div style="width:50px; height:50px; background-image: url('../img/icons/view-iceblue.svg'); background-size:cover; background-position:center; border-radius:10px; margin-right:15px;"></div>
                                <div>
                                    <div style="font-size:18px; font-weight:bold; color:#333;">Voir les détails du compte</div>
                                    <div style="font-size:14px; color:#555;">Consultez les informations personnelles de votre compte</div>
                                </div>
                            </div>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td colspan="4"><p style="font-size: 5px">&nbsp;</p></td>
                </tr>

                <!-- Supprimer le compte -->
                <tr>
                    <td style="width: 25%;">
                        <a href="?action=drop&id=<?php echo $userid.'&name='.$username ?>" class="non-style-link" style="text-decoration:none;">
                            <div style="padding:20px; margin:auto; width:95%; display:flex; align-items:center; background:#ffe5e5; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); transition:0.3s;">
                                <div style="width:50px; height:50px; background-image: url('../img/icons/patients-hover.svg'); background-size:cover; background-position:center; border-radius:10px; margin-right:15px;"></div>
                                <div>
                                    <div style="font-size:18px; font-weight:bold; color:#ff5050;">Supprimer le compte</div>
                                    <div style="font-size:14px; color:#555;">Cette action supprimera définitivement votre compte</div>
                                </div>
                            </div>
                        </a>
                    </td>
                </tr>
            </table>
        </center>
    </td>
</tr>

            
            </table>
        </div>
    </div>
    <?php 
if($_GET){
    
    $id=$_GET["id"];
    $action=$_GET["action"];
    if($action=='drop'){
        $nameget=$_GET["name"];
        echo '
        <div id="popup1" class="overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000;">
                <div class="popup" style="background:#fff;border-radius:12px;max-width:500px;margin:80px auto;padding:25px;text-align:center;box-shadow:0 5px 15px rgba(0,0,0,0.3);">
                <center>
                    <h2 style="color:#e74c3c;font-size:22px;">Êtes-vous sûr ?</h2>
                    <a class="close" href="settings.php" style="position:absolute;top:15px;right:20px;font-size:24px;color:#555;text-decoration:none;">&times;</a>
                    <div class="content" style="margin:15px 0;color:#333;font-size:16px;">
                        Voulez-vous vraiment supprimer ce compte ?<br><b>('.substr($nameget,0,40).')</b>
                    </div>
                    <div style="display:flex;justify-content:center;gap:20px;margin-top:20px;">
                        <a href="delete-doctor.php?id='.$id.'" class="non-style-link">
                            <button class="btn-primary btn" style="background:#e74c3c;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">Oui</button>
                        </a>
                        <a href="settings.php" class="non-style-link">
                            <button class="btn-primary btn" style="background:#3498db;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">Non</button>
                        </a>
                    </div>
                </center>
        </div>
        </div>
        ';
    }elseif($action=='view'){
        $sqlmain= "select * from doctor where docid='$id'";
        $result= $database->query($sqlmain);
        $row=$result->fetch_assoc();
        $name=$row["docname"];
        $email=$row["docemail"];
        $spe=$row["specialties"];
        
        $spcil_res= $database->query("select sname from specialties where id='$spe'");
        $spcil_array= $spcil_res->fetch_assoc();
        $spcil_name=$spcil_array["sname"];
        $nic=$row['docnic'];
        $tele=$row['doctel'];
        echo '
        <div id="popup1" class="overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000;">
                <div class="popup" style="background:#fff;border-radius:12px;max-width:600px;margin:60px auto;padding:25px;box-shadow:0 5px 15px rgba(0,0,0,0.3);">
                <center>
                    <a class="close" href="settings.php" style="position:absolute;top:15px;right:20px;font-size:24px;color:#555;text-decoration:none;">&times;</a>
                    <p style="font-size:22px;font-weight:600;color:#2c3e50;">Détails du compte</p>
                    <br>
                    <table width="90%" style="text-align:left;font-size:16px;color:#333;">
                        <tr><td><b>Nom :</b></td><td>'.$name.'</td></tr>
                        <tr><td><b>Email :</b></td><td>'.$email.'</td></tr>
                        <tr><td><b>CIN :</b></td><td>'.$nic.'</td></tr>
                        <tr><td><b>Téléphone :</b></td><td>'.$tele.'</td></tr>
                        <tr><td><b>Spécialité :</b></td><td>'.$spcil_name.'</td></tr>
                    </table>
                    <br>
                    <a href="settings.php"><button style="background:#3498db;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">OK</button></a>
                </center>
        </div>
        </div>
        ';
    }elseif($action=='edit'){
        $sqlmain= "select * from doctor where docid='$id'";
        $result= $database->query($sqlmain);
        $row=$result->fetch_assoc();
        $name=$row["docname"];
        $email=$row["docemail"];
        $spe=$row["specialties"];
        
        $spcil_res= $database->query("select sname from specialties where id='$spe'");
        $spcil_array= $spcil_res->fetch_assoc();
        $spcil_name=$spcil_array["sname"];
        $nic=$row['docnic'];
        $tele=$row['doctel'];

        $error_1=$_GET["error"];
            $errorlist= array(
                '1'=>'<label style="color:#e74c3c;text-align:center;">Un compte existe déjà avec cet email.</label>',
                '2'=>'<label style="color:#e74c3c;text-align:center;">Erreur de confirmation du mot de passe.</label>',
                '3'=>'<label style="color:#e74c3c;text-align:center;"></label>',
                '4'=>"",
                '0'=>'',
            );

        if($error_1!='4'){
                echo '
                <div id="popup1" class="overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000;">
                        <div class="popup" style="background:#fff;border-radius:12px;max-width:700px;margin:40px auto;padding:30px;box-shadow:0 5px 15px rgba(0,0,0,0.3);">
                        <center>
                            <a class="close" href="settings.php" style="position:absolute;top:15px;right:20px;font-size:24px;color:#555;text-decoration:none;">&times;</a> 
                            <p style="font-size:22px;font-weight:600;color:#2c3e50;">Modifier les informations du médecin</p>
                            '.$errorlist[$error_1].'
                            <form action="edit-doc.php" method="POST" style="margin-top:20px;text-align:left;">
                                <input type="hidden" value="'.$id.'" name="id00">
                                <label>Email :</label>
                                <input type="hidden" name="oldemail" value="'.$email.'" >
                                <input type="email" name="email" value="'.$email.'" required style="width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:6px;">
                                
                                <label>Nom :</label>
                                <input type="text" name="name" value="'.$name.'" required style="width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:6px;">
                                
                                <label>CIN :</label>
                                <input type="text" name="nic" value="'.$nic.'" required style="width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:6px;">

                                <label>Téléphone :</label>
                                <input type="tel" name="Tele" value="'.$tele.'" required style="width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:6px;">

                                <label>Spécialité : (Actuelle : '.$spcil_name.')</label>
                                <select name="spec" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;margin:5px 0;">';
                                    
                                $list11 = $database->query("select  * from  specialties;");
                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $sn=$row00["sname"];
                                    $id00=$row00["id"];
                                    echo "<option value=".$id00.">$sn</option>";
                                };
                                    
                echo     '</select>

                                <label>Mot de passe :</label>
                                <input type="password" name="password" required style="width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:6px;">
                                
                                <label>Confirmer mot de passe :</label>
                                <input type="password" name="cpassword" required style="width:100%;padding:10px;margin:5px 0;border:1px solid #ccc;border-radius:6px;">
                                
                                <div style="margin-top:20px;text-align:center;">
                                    <input type="reset" value="Réinitialiser" style="background:#95a5a6;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">
                                    <input type="submit" value="Enregistrer" style="background:#2ecc71;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;margin-left:15px;">
                                </div>
                            </form>
                        </center>
                </div>
                </div>
                ';
        }else{
            echo '
                <div id="popup1" class="overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000;">
                        <div class="popup" style="background:#fff;border-radius:12px;max-width:500px;margin:80px auto;padding:25px;text-align:center;box-shadow:0 5px 15px rgba(0,0,0,0.3);">
                        <center>
                            <h2 style="color:#27ae60;">Modifications enregistrées avec succès !</h2>
                            <a class="close" href="settings.php" style="position:absolute;top:15px;right:20px;font-size:24px;color:#555;text-decoration:none;">&times;</a>
                            <div class="content" style="margin:15px 0;color:#333;">
                                Si vous avez modifié votre email, veuillez vous déconnecter et vous reconnecter avec votre nouvel email.
                            </div>
                            <div style="display:flex;justify-content:center;gap:20px;margin-top:20px;">
                                <a href="settings.php" class="non-style-link"><button style="background:#3498db;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">OK</button></a>
                                <a href="../logout.php" class="non-style-link"><button style="background:#e67e22;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">Se déconnecter</button></a>
                            </div>
                        </center>
                </div>
                </div>
    ';
        }; 
    }

}
?>


</body>
</html>