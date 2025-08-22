<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Système de Gestion des Demandes de Sang - CHU Fès</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- JS & jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    background: linear-gradient(to right, #000000ff, #000000ff);
    color: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}
.header img { height: 55px; width: auto; }
.header .logo-text { font-size: 26px; font-weight: bold; margin-left: 12px; }
.header .menu a {
    color: white;
    margin-left: 20px;
    font-weight: 500;
    transition: 0.3s;
    text-decoration: none;
    font-size: 16px;
}
.header .menu a:hover { color: #FFD700; }

/* Carousel */
.carousel-inner img { width: 100%; height: 450px; object-fit: cover; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }

/* Sections */
.section {
    padding: 40px 20px;
    margin-top: 30px;
    border-radius: 15px;
    background-color: #f8f9fa;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.section h2 {
    margin-bottom: 20px;
    color: #B71C1C;
    font-weight: bold;
}

/* Lists */
.list-group-item {
    border: none;
    padding-left: 0;
    padding-right: 0;
}
.list-title { font-weight: bold; color: #E91E63; }

/* Button */
.btn-donor {
    background: linear-gradient(45deg, #FF5722, #E91E63);
    color: white;
    font-weight: bold;
    border-radius: 25px;
    transition: 0.3s;
    padding: 12px 25px;
}
.btn-donor:hover { background: linear-gradient(45deg, #E91E63, #FF5722); }

/* Footer */
#footer {
    width: 100%;
    background-color:#111;
    color:white;
    text-align: center;
    padding: 25px 0;
    margin-top: 50px;
    font-size: 14px;
}

/* Responsive */
@media(max-width:768px){
    .header { flex-direction: column; text-align: center; }
    .header .menu a { display: block; margin: 8px 0; }
}
</style>
</head>

<body>
<!-- Header -->
<div class="header">
    <div class="d-flex align-items-center">
        <img src="img/Capture_d_écran_2025-08-03_003358-removebg-preview.png" alt="Logo CHU Fès">
        <span class="logo-text">🩸 CHU Fès</span>
    </div>
    <div class="menu">
        <a href="index.html">Accueil</a>
        <a href="apropos.php">À propos</a>
        <a href="contact.php">Contact</a>
    </div>
</div>

<!-- Carousel -->
<div class="container mt-4">
    <div id="carouselDemandes" class="carousel slide" data-ride="carousel">
        <ul class="carousel-indicators">
            <li data-target="#carouselDemandes" data-slide-to="0" class="active"></li>
            <li data-target="#carouselDemandes" data-slide-to="1"></li>
            <li data-target="#carouselDemandes" data-slide-to="2"></li>
        </ul>
        <div class="carousel-inner">
            <div class="carousel-item active"><img src="Capture d’écran 2025-08-22 125203.png" alt="Présentation"></div>
            <div class="carousel-item"><img src="Capture d’écran 2025-08-22 122034.png" alt="Étapes de la demande"></div>
            <div class="carousel-item"><img src="Capture d’écran 2025-08-22 131054.png" alt="Urgences et délais"></div>
        </div>
        <a class="carousel-control-prev" href="#carouselDemandes" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
        <a class="carousel-control-next" href="#carouselDemandes" data-slide="next"><span class="carousel-control-next-icon"></span></a>
    </div>
</div>




<!-- Informations sur les groupes sanguins -->
<div class="container" style="padding:40px 20px; margin-top:30px; border-radius:15px; background-color:#f8f9fa; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom:20px; font-size:28px; font-weight:bold; color:#B71C1C;">Informations sur les Groupes Sanguins</h2>
    <div class="row">
        <div class="col-lg-6" style="display:flex; align-items:center;">
            <p style="font-size:16px; line-height:1.6; color:#333;">
                Le sang est classé en différents groupes selon la présence ou l'absence d'antigènes. 
                Les principaux groupes sanguins sont A, B, AB et O, chacun pouvant être Rh positif ou Rh négatif.
            </p>
        </div>
        <div class="col-lg-6" style="display:flex; justify-content:center; align-items:center;">
            <img class="img-fluid rounded" src="Capture d’écran 2025-08-22 132942.png" alt="Don de sang" style="border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.3); max-width:100%; height:auto;">
        </div>
    </div>
</div>


<!-- Section Devenir Demandeur -->
<div class="container" style="padding:50px 20px; margin-top:40px; text-align:center; background: linear-gradient(135deg, #FFEBEE, #FFCDD2); border-radius:20px; box-shadow:0 6px 20px rgba(0,0,0,0.2);">
    <h2 style="font-size:30px; font-weight:bold; color:#C62828; margin-bottom:20px;">Devenir Demandeur de Sang</h2>
    <p style="font-size:18px; color:#B71C1C; line-height:1.6; margin-bottom:30px;">
        Vous avez besoin de sang ou souhaitez enregistrer une demande pour un patient au CHU Fès ? Remplissez le formulaire et notre équipe vous contactera rapidement.
    </p>
    
    <a href="login.php" 
       style="display:inline-block; padding:15px 35px; font-size:18px; font-weight:bold; color:white; background: linear-gradient(45deg, #D32F2F, #F44336); border-radius:30px; text-decoration:none; transition:0.3s; box-shadow:0 4px 15px rgba(0,0,0,0.3);">
       Enregistrer une Demande
    </a>
</div>



<div id="footer">
    <b>COPYRIGHT © 2025<br>
    CHU Fès - Gestion des Demandes de Sang<br>
    TOUS DROITS RÉSERVÉS.</b>
</div>

</body>
</html>
