<?php

require_once 'inc/init.php';


// Liste des produits

$requete = $bdd->prepare("SELECT p.id_produit, p.date_arrivee, p.date_depart, p.prix, p.etat, s.id_salle, s.titre, s.description, s.photo, s.pays, s.ville, s.capacite, s.cp, s.adresse, s.categorie
FROM produit p, salle s
WHERE p.id_salle = s.id_salle ");

$requete->execute();

// Liste des notes 

$resultat = $bdd-> query("SELECT note, id_salle FROM avis");


// Liste des selections

$resultat2 = $bdd-> query("SELECT DISTINCT ville FROM salle GROUP BY ville");
$resultat3 = $bdd-> query("SELECT DISTINCT categorie FROM salle GROUP BY categorie");
$resultat4 = $bdd-> query("SELECT DISTINCT capacite FROM salle GROUP BY capacite");

$resultat5 = $bdd-> query("SELECT DISTINCT prix FROM produit GROUP BY prix");
$resultat6 = $bdd-> query("SELECT DISTINCT date_arrivee FROM produit GROUP BY date_arrivee");
$resultat7 = $bdd-> query("SELECT DISTINCT date_depart FROM produit GROUP BY date_depart");



$titreDeMaPage = "Accueil";
require_once 'inc/header.php';
?> 

<!-- ************** HTML ***************** -->

<div class="container-fluid">
    <div class="row row-cols-3">
        <div class="col-md-2">
    
            <label for="categorie" class="form-label ms-3">Catégorie</label>
                <select name="categorie" id="categorie" class="form-select ms-3">
                <?php while($categorie = $resultat3->fetch(PDO ::FETCH_ASSOC)){?>
                    <option value="<?php echo $categorie['categorie'];  ?>">
                    <?php echo $categorie['categorie']; }?> 
                    </option>
                </select>
            <br>
            <br>
            <label for="ville" class="form-label ms-3">Ville</label>
                <select name="ville" id="ville" class="form-select ms-3">
                <?php while($salle = $resultat2->fetch(PDO ::FETCH_ASSOC)){?>
                    <option value="<?php echo $salle['ville'];  ?>">
                    <?php echo $salle['ville']; ?> 
                    <?php } ?>
                    </option>
                </select>
                <br>
                <br>
            <label for="capacite" class="form-label ms-3">Capacite</label>
                <select name="capacite" id="capacite" class="form-select ms-3">
                <?php while($capacite = $resultat4->fetch(PDO ::FETCH_ASSOC)){?>
                    <option value="<?php echo $capacite['capacite'];  ?>">
                    <?php echo $capacite['capacite']; ?> 
                    <?php } ?>
                    </option>
                </select>
                <br>
                <br>
            <label for="prix" class="form-label ms-3">Prix</label>
                <select name="prix" id="prix" class="form-select ms-3">
                <?php while($prix = $resultat5->fetch(PDO ::FETCH_ASSOC)){?>
                    <option value="<?php echo $prix['prix'];  ?>">
                    <?php echo $prix['prix']; ?> 
                    <?php } ?>
                    </option>
                </select>
                <br>
                <br>
            <label for="date_arrivee" class="form-label ms-3">Date d'arrivée</label>
                <select name="date_arrivee" id="date_arrivee" class="form-select ms-3">
                <?php while($date = $resultat6->fetch(PDO ::FETCH_ASSOC)){?>
                    <option value="<?php echo $date['date_arrivee'];  ?>">
                    <?php echo $date['date_arrivee']; ?> 
                    <?php } ?>
                    </option>
                </select>
                <br>
                <br>
            <label for="date_depart" class="form-label ms-3">Date de départ </label>
                <select name="date_depart" id="date_depart" class="form-select ms-3">
                <?php while($date1 = $resultat7->fetch(PDO ::FETCH_ASSOC)){?>
                    <option value="<?php echo $date1['date_depart'];  ?>">
                    <?php echo $date1['date_depart']; ?> 
                    <?php } ?>
                    </option>
                </select>
                <br>
                <br>
                </div>
                <div class="col-md-1">
                </div>   

        <div class="col-md-3">
            <div class="card">
            <?php while($liste = $requete->fetch(PDO ::FETCH_ASSOC)){?>
            <img src="<?php echo "./Back-office/" . $liste['photo']; ?>" class="card-img-top" height="300px">
                <div class="card-body">
                <h5 class="card-title">
                <?php  
                if ($liste['categorie'] == "reunion"){
                echo "Salle " .  $liste['titre'];
                }elseif($liste['categorie'] == "bureau") { echo "Bureau " . $liste['titre'];
                }elseif($liste['categorie'] == "formation") { echo "Salle de formation " . $liste['titre'];
                }?></h5>
                <p class="card-text"><?= $liste['description'] ?></p>
                <p class="card-text"><?= $liste['ville'] ?></p>
                <p class="card-text"><?= $liste['date_arrivee'] . " au " . $liste['date_depart'] ?></p>
                <a href="/projet-PHP/fiche_produit.php">En savoir plus</a>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>




<?php
require_once 'inc/footer.php';
?>