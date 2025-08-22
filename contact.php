<?php 
$active = 'contact';
include 'head.php'; 

// Connexion à la base
$conn = mysqli_connect("localhost", "root", "", "blood_donation") or die("Erreur de connexion");

// Traitement du formulaire
$success = $error = '';
if (isset($_POST["send"])) {
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $number = mysqli_real_escape_string($conn, $_POST['contactno']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact_query (query_name, query_mail, query_number, query_message) 
            VALUES ('$name', '$email', '$number', '$message')";

    if (mysqli_query($conn, $sql)) {
        $success = "✅ Votre message a été envoyé avec succès. Nous vous répondrons bientôt.";
    } else {
        $error = "❌ Une erreur est survenue lors de l'envoi. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Contact - CHU Fès</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- JS & jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
body {
    background: #f5f6fa;
    font-family: 'Segoe UI', sans-serif;
}

/* Container page */
#page-container {
    padding: 60px 15px;
}

/* Titres */
.text-gradient {
    background: linear-gradient(45deg, #E53935, #D32F2F);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Formulaire */
.form-card {
    background: #ffffff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    transition: 0.3s;
}
.form-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}
.form-card label {
    font-weight: 600;
    color: #333333;
}
.form-card input, .form-card textarea {
    border-radius: 10px;
    border: 1px solid #ccc;
}
.btn-gradient {
    background: linear-gradient(45deg, #D32F2F, #E53935);
    color: white;
    font-weight: bold;
    border-radius: 30px;
    padding: 12px 30px;
    transition: 0.3s;
}
.btn-gradient:hover {
    background: linear-gradient(45deg, #E53935, #F44336);
}

/* Alertes */
.alert-success, .alert-danger {
    border-radius: 15px;
    font-weight: 500;
}

/* Footer */
footer.info-card {
    background: #222;
    color: #fff;
    padding: 40px 0;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
}
footer.info-card a {
    color: #FFD700;
    text-decoration: none;
}
footer.info-card a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>
<div id="page-container" class="container">
    <h1 class="mb-4 text-center text-gradient">📩 Contact</h1>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $success ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $error ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <h3 class="mb-4 text-center" style="color:#D32F2F;">Envoyez-nous un message</h3>
                <form method="post">
                    <div class="form-group">
                        <label>Nom complet :</label>
                        <input type="text" class="form-control" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label>Numéro de téléphone :</label>
                        <input type="tel" class="form-control" name="contactno" required>
                    </div>
                    <div class="form-group">
                        <label>Adresse e-mail :</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Message :</label>
                        <textarea rows="6" class="form-control" name="message" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="send" class="btn btn-gradient btn-lg">
                            ✉ Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer avec coordonnées -->
<footer class="info-card mt-5">
    <div class="container text-center">
        <p><strong>CHU Fès</strong></p>
        <p>Centre Hospitalier Hrazem, BP:1835 Atlas, Fès, Avenue Hassan II, Fès 30050</p>
        <p>Ouvert 24h/24</p>
        <p>Téléphone : 05356-19053</p>
        <p>Site Web : <a href="#">www.chufes.ma</a></p>
    </div>
</footer>
</body>
</html>
