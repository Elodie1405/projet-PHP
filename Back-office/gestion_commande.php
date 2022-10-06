<?php

require_once '../inc/init.php';

// 1- Vérification si le membre est admin connecté :
if (!isset($_SESSION['membre']) || $_SESSION['membre']['statut'] != 1 ) { 
    header('location:../connexion.php'); 
    exit; 
}

//Suppression d'une commande

if(isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id_commande'])){


    $_GET['id_commande'] = htmlspecialchars($_GET['id_commande'], ENT_QUOTES);

    $resultat = $bdd->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
    $resultat->execute([
        'id_commande' => $_GET['id_commande'],
    ]);

    if($resultat){
        $successMessage .= "La commande a bien été supprimée. <br>"; 
    }else{
        $errorMessage .= "Erreur - La commande n'a pas pu être supprimée. <br>";
    }

} 


//Liste des salles

$requete_salle = $bdd->query("SELECT id_salle, titre FROM salle");

//Liste des membres

$requete_membre = $bdd->query("SELECT id_membre, email FROM membre");

//Liste des produits

$requete_produit = $bdd->query("SELECT id_produit, id_salle, date_arrivee, date_depart, prix FROM produit");

//Liste des commandes

$resultat = $bdd->prepare("SELECT c.id_commande, c.date_enregistrement, m.id_membre, m.email, p.id_produit, s.id_salle, s.titre, p.date_arrivee, p.date_depart, p.prix
FROM commande c, produit p, salle s, membre m
WHERE c.id_produit = p.id_produit
AND c.id_membre = m.id_membre 
AND p.id_salle = s.id_salle");


$resultat->execute();



$titreDeMaPage = "Gestion des commandes";
require_once '../inc/header.php';
?>

<!-- ************ HTML *************** -->

<h1 class="text-center">Gestion des commandes</h1>


<?php if (!empty($errorMessage)) { ?>
    <div class="alert alert-danger text-center col-6 mx-auto">
        <?= $errorMessage ?>
    </div>
<?php } ?>

<?php if (!empty($successMessage)) { ?>
    <div class="alert alert-success text-center col-6 mx-auto">
        <?= $successMessage ?>
    </div>
<?php } ?>




<!-- Tableau recap des commandes -->

<table class="table table-striped table-hover table-bordered mt-3">
        <thead>
            <tr>
                <th> ID commande</th>
                <th> ID membre </th>
                <th> Id produit</th>
                <th> Prix </th>
                <th> Date enregistrement </th>
                <th> Actions </th>
            </tr>
        </thead>

        <tbody>

        <?php
                while($commande = $resultat->fetch(PDO ::FETCH_ASSOC)){
                    //debug($commande);
                ?>
                    <tr>
                        <td> <?php echo $commande['id_commande']; ?> </td>
                        <td> <?= $commande['id_membre'] . " - " . $commande['email'] ?> </td>
                        <td> <?= $commande['id_produit'] . " - salle " . $commande['titre'] . '<br>' . $commande['date_arrivee'] . " au " . $commande['date_depart']?> </td>
                        <td> <?=
                        $commande['prix'] . " € " ?></td>
                        <td> <?= $commande['date-enregistrement'] ?> </td>
                        
                        <td>
                <a href="gestion_commande.php?action=supprimer&id_commande=<?php echo $commande['id_commande']; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette commande ?'));">Supprimer |</a>
                </td>   
                    </tr>
                <?php
                }
                ?>
        

        </tbody>

    </table>

<div>
<a href="index.php"><button class="btn btn-secondary ms-5">Retour à l'accueil back office</button> </a>
</div>

<?php
require_once '../inc/footer.php';
?>