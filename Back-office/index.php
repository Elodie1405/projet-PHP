<?php

require_once '../inc/init.php';
//debug($_SESSION);
// 1- Vérification si le membre est admin connecté :
    if (!isset($_SESSION['membre']) || $_SESSION['membre']['statut'] != 1 ) { 
        header('location:../connexion.php'); 
        exit; 
    }




$titreDeMaPage = "Back office - Accueil";
require_once '../inc/header.php';
?>

<h1 class="text-center">BACK OFFICE - Accueil</h1>


<div class="d-flex justify-content-around mt-5">
<div style="height: 120px; width: 200px; background :darkgray; border: 1px black solid; padding: 20px; text-align: center;">
<a style="text-decoration: none; color:black; font-size: 22px;" href="gestion_membre.php">Gestion des membres</a>    
</div>
<div style="height: 120px; width: 200px; background :darkgray; border: 1px black solid; padding: 20px; text-align: center;"><a style="text-decoration: none; color:black; font-size: 22px;" href="gestion_salle.php">Gestion des salles</a>    
</div>
<div style="height: 120px; width: 200px; background :darkgray; border: 1px black solid; padding: 20px; text-align: center;"><a style="text-decoration: none; color:black; font-size: 22px;" href="gestion_produit.php">Gestion des produits</a>   
</div>
<div style="height: 120px; width: 200px; background :darkgray; border: 1px black solid; padding: 20px; text-align: center;"><a style="text-decoration: none; color:black; font-size: 22px;" href="gestion_avis.php">Gestion des <br> avis</a>   
</div>
<div style="height: 120px; width: 200px; background :darkgray; border: 1px black solid; padding: 20px; text-align: center;"><a style="text-decoration: none; color:black; font-size: 22px;" href="gestion_commande.php">Gestion des commandes</a>
</div>
<div style="height: 120px; width: 200px; background :darkgray; border: 1px black solid; padding: 20px; text-align: center;"><a style="text-decoration: none; color:black; font-size: 22px;" href="statistiques.php">Statistiques</a>   </div>
</div>


<?php
require_once '../inc/footer.php';
?>