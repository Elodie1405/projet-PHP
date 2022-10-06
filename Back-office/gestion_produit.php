<?php

require_once '../inc/init.php';

// 1- Vérification si le membre est admin connecté :
if (!isset($_SESSION['membre']) || $_SESSION['membre']['statut'] != 1 ) { 
    header('location:../connexion.php'); 
    exit; 
}

//Suppression d'un produit

if(isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id_produit'])){


    $_GET['id_produit'] = htmlspecialchars($_GET['id_produit'], ENT_QUOTES);

    $resultat = $bdd->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $resultat->execute([
        'id_produit' => $_GET['id_produit'],
    ]);

    if($resultat){
        $successMessage .= "Le produit a bien été supprimé. <br>"; 
    }else{
        $errorMessage .= "Erreur - Le produit n'a pas pu être supprimé. <br>";
    }

}

// Ajout d'un produit
if(isset($_GET['action']) && $_GET['action'] == "ajouter"){

    if(!empty($_POST) && $_GET['action'] == "ajouter"){



        if(empty($_POST['date_arrivee']) && $_POST['date_arrivee'] < $_POST['date_depart']) {
            $errorMessage .= "La date d'arrivée est obligatoire <br>";
        }

        if($_POST['date_arrivee'] > $_POST['date_depart']) {
            $errorMessage .= "La date d'arrivée doit être avant la date de départ <br>";
        }

        if(empty($_POST['date_depart'])){
            $errorMessage .= "La date de départ est obligatoire <br>";
        }

        if(empty($_POST['prix']) && is_int($_POST['prix'])){
            $errorMessage .= "Le prix est obligatoire <br>";
        }

        if(empty($errorMessage)){

            $ajout = $bdd->prepare("INSERT INTO produit(id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :date_arrivee, :date_depart, :prix, :etat)");
            $ajout->execute([
                'id_salle' => $_POST['id_salle'],
                'date_arrivee' => $_POST['date_arrivee'],
                'date_depart' => $_POST['date_depart'],
                'prix' => $_POST['prix'],
                'etat' => $_POST['etat'],
            ]);

            if($ajout){
                $successMessage .= "Le produit a bien été créé. <br>"; 
            }else{
                $errorMessage .= "Erreur - Le produit n'a pas pu être créé. <br>";
            }

        }

    }
}


//Modification d'un produit
//debug($_GET);

if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_produit'])){

    $_GET['id_produit'] = htmlspecialchars($_GET['id_produit'], ENT_QUOTES);

    $resultat = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $resultat->execute([
        'id_produit' => $_GET['id_produit']
    ]);
	$produit_modifie = $resultat->fetch(PDO::FETCH_ASSOC);
//debug($produit_modifie);
}

// 6- Mise à jour des données modifiées en BDD : 

//debug($_POST);
if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_produit'])){

    if (!empty($_POST)) {

    foreach ($_POST as $indice => $valeur) {
		$_POST[$indice] = htmlspecialchars($_POST[$indice], ENT_QUOTES);
    }

    if(empty($errorMessage)){
        $modif = $bdd->prepare(
            "UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix, etat = :etat WHERE id_produit = :id_produit");
        $modif->execute([
            'id_salle' => $_POST['id_salle'],
            'date_arrivee' => $_POST['date_arrivee'],
            'date_depart' => $_POST['date_depart'],
            'prix' => $_POST['prix'],
            'etat' => $_POST['etat'],
            'id_produit' => $_POST['id_produit'],
        ]);

    if ($modif) {
        $successMessage .= 'Le produit a été modifié.<br>'; 
    } else {
        $errorMessage .= 'Erreur lors de la modification<br>';
    }
    }
}
}    



//Liste des salles

$requete = $bdd->prepare("SELECT * FROM salle");

$requete->execute();

//Liste des produits



$resultat = $bdd->prepare("SELECT p.id_produit, p.date_arrivee, p.date_depart, p.prix, p.etat, s.id_salle, s.titre, s.photo, s.capacite, s.cp, s.adresse
FROM produit p, salle s
WHERE p.id_salle = s.id_salle ");


$resultat->execute();



$titreDeMaPage = "Gestion des produits";
require_once '../inc/header.php';
?>

<!-- ************ HTML *************** -->

<h1 class="text-center">Gestion des produits</h1>


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




<!-- Tableau recap des produits -->

<table class="table table-striped table-hover table-bordered mt-3">
        <thead>
            <tr>
                <th> ID produit</th>
                <th> date d'arrivée </th>
                <th> date de départ </th>
                <th> Salle </th>
                <th> Prix</th>
                <th> Etat </th>
                <th> Action </th>
            </tr>
        </thead>

        <tbody>

        <?php
                while($produit = $resultat->fetch(PDO ::FETCH_ASSOC)){
                   //debug($produit);
                ?>
                    <tr>
                        <td> <?php echo $produit['id_produit']; ?> </td>
                        <td> <?= $produit['date_arrivee'] ?> </td>
                        <td> <?= $produit['date_depart'] ?> </td>
                        <td> <?=
                        $produit['id_salle'] . " - ". " Salle " . $produit['titre'] . '<br>' ?> 
                        <img src="<?= $produit['photo'] ?>" height=70px width=100px></td>
                        <td> <?= $produit['prix'] . " €"?> </td>
                        <td> <?= $produit['etat'] ?> </td>
                        <td>
                <a href="gestion_produit.php?action=supprimer&id_produit=<?php echo $produit['id_produit']; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ce produit ?'));">Supprimer |</a>

                
                    <a href="?action=modifier&id_produit=<?= $produit['id_produit'] ?>"> Modifier</a>
                </td>   
                    </tr>
                <?php
                }
                ?>
        

        </tbody>

    </table>

    <div>
<a href="gestion_produit.php?action=ajouter"><button class="btn btn-primary">Ajouter un produit</button> </a>
<a href="index.php"><button class="btn btn-secondary ms-5">Retour à l'accueil back office</button> </a>
</div>

<!-- FORMULAIRE AJOUT D'UN PRODUIT -->
    <?php


     
    if(isset($_GET['action']) && $_GET['action'] == "ajouter"){
?>
    <hr>
    <hr>
    <h4 class="text-center">Ajouter un produit</h4>
    <form action="" method="post" class="col-6 mx-auto">

<label for="date_arrivee" class="form-label">Date d'arrivée</label>
<input type="datetime-local" name="date_arrivee" id="date_arrivee" class="form-control" >

<label for="date_depart" class="form-label">Date de départ</label>
<input type="datetime-local" name="date_depart" id="date_depart" class="form-control" >


<label for="id_salle" class="form-label">Salle</label>
<select name="id_salle" id="id_salle" class="form-select">
        <?php   while($salle = $requete->fetch(PDO ::FETCH_ASSOC)){?>
        <option value ="<?php echo $salle['id_salle'];?>">
        <?php echo $salle['id_salle'] . " - Salle " . $salle['titre'] . " - " . $salle['adresse'] . ", " . $salle['cp'] . " " . $salle['ville'] . " - " . $salle['capacite'] . " pers." ?> 
        </option>
        <?php } ?>
</select> 

<label for="prix" class="form-label">Tarif</label>
<input type="text" name="prix" id="prix" class="form-control" >

<label for="etat" class="form-label">Etat</label>
        <select name="etat" id="etat" class="form-select" >
            <option>libre</option>
            <option>reservation</option>
    </select>

        
        <br>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary me-5" id="submit" type="submit">Enregistrer le nouveau produit </button>
            <a href="gestion_produit.php" class="btn btn-info">Annuler</a>
        </div>
    </form>

    <br>
    <br>

<?php
    }



// FORMULAIRE DE MODIFICATION D'UNE SALLE
//debug($produit_modifie);
if (isset($produit_modifie)) {
    ?>
        <hr>
        <hr>
        <h4 class="text-center">Modifiez ici vos données</h4>
        <form action="" method="post" class="col-6 mx-auto">
            <div>
                <div><input type="hidden" name="id_produit" value="<?php echo $produit_modifie['id_produit']; ?>" > 
                </div>
            </div>
    
<label for="date_arrivee" class="form-label">Date d'arrivée</label>
<input type="datetime" name="date_arrivee" id="date_arrivee" class="form-control" value="<?php echo $produit_modifie['date_arrivee'] ?? "" ?>">

<label for="date_depart" class="form-label">Date de départ</label>
<input type="datetime" name="date_depart" id="date_depart" class="form-control" value="<?php echo $produit_modifie['date_depart'] ?? "" ?>">


<label for="id_salle" class="form-label">Salle</label>
<select name="id_salle" id="id_salle" class="form-select">
        <?php   while($salle = $requete->fetch(PDO ::FETCH_ASSOC)){?>
        <option value="<?php if ($produit_modifie['id_salle'] = $salle['id_salle']){ echo $salle['id_salle'];}else {echo 'selected';}  ?>">

        <?php echo $salle['id_salle'] . " - Salle " . $salle['titre'] . " - " . $salle['adresse'] . ", " . $salle['cp'] . " " . $salle['ville'] . " - " . $salle['capacite'] . " pers." ?> 
        </option>
        <?php } ?>
</select>

<label for="prix" class="form-label">Tarif</label>
<input type="text" name="prix" id="prix" class="form-control" value="<?php echo $produit_modifie['prix'] ?? "" ?>">

<label for="etat" class="form-label">Etat</label>
        <select name="etat" id="etat" class="form-select" >
            <option <?php if ($produit_modifie['etat'] == "libre") echo 'selected'; ?>>libre</option>
            <option <?php if ($produit_modifie['etat'] == "reservation") echo 'selected'; ?>>reservation</option>
    </select>

        
        <br>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary me-5">Enregistrer les modifications</button>
            <a href="gestion_produit.php" class="btn btn-info">Annuler</a>
        </div>

        </form>
    
    <?php
    }

require_once '../inc/footer.php';
?>