<?php

require_once 'inc/init.php';



if(!empty($_SESSION['membre']['id_membre'])) {



$requete = $bdd->prepare("SELECT pseudo, nom, prenom, email, civilite FROM membre WHERE id_membre = :id_membre");

$requete->execute([
    'id_membre' => $_SESSION['membre']['id_membre']
]);


}

if(!empty($_SESSION['membre']['id_membre'])){



$resultat = $bdd->prepare("SELECT * FROM commande WHERE id_membre = :id_membre");

$resultat->execute([
    'id_membre' => $_SESSION['membre']['id_membre']
]);

}


$titreDeMaPage = "Profil";
require_once 'inc/header.php';
?>


<h1 class="text-center">Profil</h1>

<?php
if(!empty($_SESSION['membre']['id_membre'])){

echo '<p class="centre">Bonjour <strong>' . $_SESSION['membre']['pseudo'] . '</strong></p>';

echo '<div class="cadre"><h3> Voici vos informations </h3>';

echo '<p> votre nom est: ' . $_SESSION['membre']['nom'] . '<br>';
echo '<p> votre prenom est: ' . $_SESSION['membre']['prenom'] . '<br>';
echo '<p> votre email est: ' . $_SESSION['membre']['email'] . '<br>';
echo '<p> votre civilite est: ' . $_SESSION['membre']['civilite'] . '<br>';
echo '<p> votre statut est: ' . $_SESSION['membre']['statut'] . '<br>';
}


?>







<h3>Mes commandes</h3>

<table>
<tr>
    <th>ID commande</th>
    <th>ID membre</th>
    <th>ID produit</th>
    <th>Date enregistrement</th>
</tr>

<?php 
while($commande = $resultat->fetch(PDO::FETCH_ASSOC)){
    echo '<tr>';
        echo '<td>' . $commande['id_commande'] . '</td>';
        echo '<td>' . $commande['id_membre'] . '</td>';
        echo '<td>' . $commande['id_produit'] . '</td>';
        echo '<td>' . $commande['date_enregistrement'] . '</td>';
    echo '</tr>';
}
?>
</table>


<?php
require_once 'inc/footer.php';
?>
