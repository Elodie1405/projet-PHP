<?php

require_once '../inc/init.php';

// 1- Vérification si le membre est admin connecté :
if (!isset($_SESSION['membre']) || $_SESSION['membre']['statut'] != 1 ) { 
    header('location:../connexion.php'); 
    exit; 
}

//Suppression d'un produit

if(isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id_avis'])){


    $_GET['id_avis'] = htmlspecialchars($_GET['id_avis'], ENT_QUOTES);

    $resultat = $bdd->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
    $resultat->execute([
        'id_avis' => $_GET['id_avis'],
    ]);

    if($resultat){
        $successMessage .= "L'avis a bien été supprimé. <br>"; 
    }else{
        $errorMessage .= "Erreur - L'avis n'a pas pu être supprimé. <br>";
    }

}



//Modification d'un avis
//debug($_GET);

if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_avis'])){

    $_GET['id_avis'] = htmlspecialchars($_GET['id_avis'], ENT_QUOTES);

    $resultat = $bdd->prepare("SELECT * FROM avis WHERE id_avis = :id_avis");
    $resultat->execute([
        'id_avis' => $_GET['id_avis']
    ]);
	$avis_modifie = $resultat->fetch(PDO::FETCH_ASSOC);
debug($avis_modifie);
}

// 6- Mise à jour des données modifiées en BDD : 

//debug($_POST);
if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_avis'])){

    if (!empty($_POST)) {

    foreach ($_POST as $indice => $valeur) {
		$_POST[$indice] = htmlspecialchars($_POST[$indice], ENT_QUOTES);
    }

    if(empty($errorMessage)){
        $modif = $bdd->prepare(
            "UPDATE avis SET id_salle = :id_salle,commentaire = :commentaire, note = :note WHERE id_avis = :id_avis");
        $modif->execute([
            'id_salle' => $_POST['id_salle'],
            'commentaire' => $_POST['commentaire'],
            'note' => $_POST['note'],
            'id_avis' => $_POST['id_avis'],
        ]);

    if ($modif) {
        $successMessage .= "L'avis a été modifié.<br>"; 
    } else {
        $errorMessage .= 'Erreur lors de la modification<br>';
    }
    }
}
}    


//Liste des salles

$requete_salle = $bdd->query("SELECT * FROM salle");

//Liste des membres

$requete_membre = $bdd->query("SELECT id_membre, email FROM membre");

//Liste des avis



$resultat = $bdd->prepare("SELECT a.id_avis, a.commentaire, a.note, a.date_enregistrement, m.id_membre, m.email, s.id_salle, s.titre 
FROM avis a, produit p, salle s, membre m
WHERE a.id_salle = s.id_salle
AND a.id_membre = m.id_membre ");


$resultat->execute();



$titreDeMaPage = "Gestion des avis";
require_once '../inc/header.php';
?>

<!-- ************ HTML *************** -->

<h1 class="text-center">Gestion des avis</h1>


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




<!-- Tableau recap des avis -->

<table class="table table-striped table-hover table-bordered mt-3">
        <thead>
            <tr>
                <th> ID avis</th>
                <th> ID membre </th>
                <th> Id salle </th>
                <th> Commentaire </th>
                <th> Note </th>
                <th> Date enregistrement </th>
                <th> Actions </th>
            </tr>
        </thead>

        <tbody>

        <?php
                while($avis = $resultat->fetch(PDO ::FETCH_ASSOC)){
                    //debug($avis);
                ?>
                    <tr>
                        <td> <?php echo $avis['id_avis']; ?> </td>
                        <td> <?= $avis['id_membre'] . " - " . $avis['email'] ?> </td>
                        <td> <?= $avis['id_salle'] . " - salle " . $avis['titre'] ?> </td>
                        <td> <?=
                        $avis['id_salle'] . " - ". " Salle " . $avis['titre'] ?></td>
                        <td> <?= $avis['commentaire'] ?> </td>
                        <td> <?= $avis['note'] ?> </td>
                        <td> <?= $avis['date_enregistrement'] ?> </td>
                        <td>
                <a href="gestion_avis.php?action=supprimer&id_avis=<?php echo $avis['id_avis']; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cet avis ?'));">Supprimer |</a>

                
                    <a href="?action=modifier&id_avis=<?= $avis['id_avis'] ?>"> Modifier</a>
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
// FORMULAIRE DE MODIFICATION D'UN AVIS
//debug($avis_modifie);
if (isset($avis_modifie)) {
    ?>
        <hr>
        <hr>
        <h4 class="text-center">Modifiez ici vos données</h4>
        <form action="" method="post" class="col-6 mx-auto">
            <div>
                <div><input type="hidden" name="id_avis" value="<?php echo $avis_modifie['id_avis']; ?>" > 
                </div>
            </div>
    

<label for="id_salle" class="form-label">Salle</label>
<select name="id_salle" id="id_salle" class="form-select">
        <?php   while($salle = $requete->fetch(PDO ::FETCH_ASSOC)){?>
        <option value="<?php if ($avis_modifie['id_salle'] = $salle['id_salle']){ echo $salle['id_salle'];}else {echo 'selected';}  ?>">

        <?php echo $salle['id_salle'] . " - Salle " . $salle['titre'] . $salle['capacite'] . " pers." ?> 
        </option>
        <?php } ?>
</select>

<label for="commentaire" class="form-label">Commentaire</label>
<textarea type="text" name="commentaire" id="commentaire" class="form-control" cols="20" rows="5" ><?php echo $avis_modifie['commentaire'] ?? "" ?></textarea>

<label for="note" class="form-label">Note</label>
        <select name="note" id="note" class="form-select" >
            <option <?php if ($avis_modifie['note'] == 1) echo 'selected'; ?>>1</option>
            <option <?php if ($avis_modifie['note'] == 2) echo 'selected'; ?>>2</option>
            <option <?php if ($avis_modifie['note'] == 3) echo 'selected'; ?>>3</option>
            <option <?php if ($avis_modifie['note'] == 4) echo 'selected'; ?>>4</option>
            <option <?php if ($avis_modifie['note'] == 5) echo 'selected'; ?>>5</option>
    </select>

        
        <br>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary me-5">Enregistrer les modifications</button>
            <a href="gestion_avis.php" class="btn btn-info">Annuler</a>
        </div>

        </form>
    
    <?php
    }

require_once '../inc/footer.php';
?>