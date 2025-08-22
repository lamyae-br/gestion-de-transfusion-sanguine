<?php
session_start();
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";
date_default_timezone_set('Asia/Kolkata');
$_SESSION["date"] = date('Y-m-d');

// Import database
include("connection.php");

$error = '<label class="form-label">&nbsp;</label>';

if ($_POST) {
    $email = $_POST['useremail'];
    $password = $_POST['userpassword'];

    $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
    if ($result->num_rows == 1) {
        $utype = $result->fetch_assoc()['usertype'];
        if ($utype == 'p') {
            $checker = $database->query("SELECT * FROM patient WHERE pemail='$email' AND ppassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'p';
                header('location: patient/index.php');
            } else {
                $error = '<label class="form-label error">Wrong credentials: Invalid email or password</label>';
            }
        } elseif ($utype == 'a') {
            $checker = $database->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'a';
                header('location: admin/index.php');
            } else {
                $error = '<label class="form-label error">Wrong credentials: Invalid email or password</label>';
            }
        } elseif ($utype == 'd') {
    $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
    if ($checker->num_rows == 1) {
        // Récupérer l'ID du docteur
        $doctor = $checker->fetch_assoc();
        $userid = $doctor['docid'];
        $today = date('Y-m-d');

        // Vérifier s'il y a au moins une session active/future
        $active_sessions = $database->query("
            SELECT * FROM schedule 
            WHERE docid = $userid 
              AND end_date >= '$today'
        ");

        if($active_sessions->num_rows == 0){
            // Pas de session valide → empêcher la connexion
            $error = '<label class="form-label error">You cannot login because you have no active sessions. Please wait for the admin to add a session.</label>';
        } else {
            // Au moins une session valide → créer la session
            $_SESSION['user'] = $email;
            $_SESSION['usertype'] = 'd';
            header('location: doctor/index.php');
            exit;
        }
    } else {
        $error = '<label class="form-label error">Wrong credentials: Invalid email or password</label>';
    }
}

    } else {
        $error = '<label class="form-label error">No account found for this email.</label>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

* { margin:0; padding:0; box-sizing:border-box; }
body, input { font-family: "Poppins", sans-serif; }

:root {
    --white-color:#fff;
    --gray-color:#333;
    --input-bg-color:#e0e0e0;
    --placeholder-color:#666;
    --login-btn-color:#b30000;
    --on-hover-login-btn:#ff4d4d;
    --signup-btn-color:#666;
    --on-hover-signup-btn:#888;
}

body {
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    background: linear-gradient(-45deg, #b30000, #999, #ff4d4d, #666);
    background-size:400% 400%;
    animation: gradientBG 15s ease infinite;
}

@keyframes gradientBG{
0%{background-position:0% 50%}
50%{background-position:100% 50%}
100%{background-position:0% 50%}
}

.container {
    position:relative;
    width:100%;
    max-width:400px;
    background:#444;
    border-radius:12px;
    padding:40px 30px;
    box-shadow:0 10px 25px rgba(179,0,0,0.3);
}

.header-text {
    font-size:28px;
    font-weight:700;
    color:#ff4d4d;
    text-align:center;
    margin-bottom:10px;
}

.sub-text { font-size:14px; color:#ddd; text-align:center; margin-bottom:25px; }

.input-field { 
    width:100%; 
    background:var(--input-bg-color); 
    border-radius:55px; 
    height:55px; 
    margin:10px 0; 
    padding:0 1rem; 
    display:flex; 
    align-items:center; 
}

.input-field input { 
    width:100%; 
    border:none; 
    outline:none; 
    background:none; 
    font-size:1.1rem; 
    font-weight:500; 
    color:#000; 
}

.input-field input::placeholder { color:var(--placeholder-color); }

.login-btn, .signup-btn { 
    width:100%; 
    padding:14px 0; 
    border:none; 
    border-radius:50px; 
    font-weight:600; 
    cursor:pointer; 
    transition:0.3s; 
    margin-top:10px; 
}

.login-btn { 
    background:var(--login-btn-color); 
    color:#fff; 
}

.login-btn:hover { 
    background:var(--on-hover-login-btn); 
    transform:scale(1.03); 
}

.signup-btn { 
    background:var(--signup-btn-color); 
    color:#fff; 
}

.signup-btn:hover { 
    background:var(--on-hover-signup-btn); 
    transform:scale(1.03); 
}

.form-label { 
    display:block; 
    margin-bottom:10px; 
    font-weight:500; 
    color:#fff; 
}

.form-label.error { 
    color:#ff3e3e; 
    text-align:center; 
    margin-bottom:15px; 
}

.hover-link1 { 
    color:#ff4d4d; 
    text-decoration:none; 
    font-weight:600; 
}

.hover-link1:hover { 
    text-decoration:underline; 
    opacity:0.9; 
}

.forgot-container {
    text-align:center;
    margin-top:25px;
}
</style>
</head>
<body>
<div class="container">
    <p class="header-text">Bienvenue !</p>
    <p class="sub-text">Connectez-vous avec vos identifiants pour continuer</p>
    <?php echo $error; ?>
    <form method="POST">
        <div class="input-field">
            <input type="email" name="useremail" placeholder="Adresse e-mail" required>
        </div>
        <div class="input-field">
            <input type="password" name="userpassword" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="login-btn">Se connecter</button>
    </form>
    <div class="forgot-container">
        <a href="forgot-password.php" class="hover-link1">Mot de passe oublié ?</a>
    </div>
</div>

    
</div>
</body>
</html>
