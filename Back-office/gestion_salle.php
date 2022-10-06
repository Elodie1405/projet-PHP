<?php

require_once '../inc/init.php';

// 1- Vérification si le membre est admin connecté :
if (!isset($_SESSION['membre']) || $_SESSION['membre']['statut'] != 1 ) { 
    header('location:../connexion.php'); 
    exit; 
}

// Ajout d'une salle
if(isset($_GET['action']) && $_GET['action'] == "ajouter"){

    if(!empty($_POST) && $_GET['action'] == "ajouter"){
        if(empty($_POST['titre']) || strlen( trim($_POST['titre'])) < 4 || strlen( trim($_POST['titre'])) > 50){
            $errorMessage .= "Le titre de la salle doit contenir entre 4 et 50 caractères <br>";
        }

        if(empty($_POST['description'])) {
            $errorMessage .= "La description est obligatoire <br>";
        }

        if(empty($_POST['pays']) || $_POST['pays'] != "France"){
            $errorMessage .= "Le pays doit être égal à France <br>";
        }

        if(empty($_POST['ville'])){
            $errorMessage .= "La ville est obligatoire <br>";
        }

        if(empty($_POST['adresse'])){
            $errorMessage .= "L'adresse est obligatoire <br>";
        }

        if(empty($_POST['cp'])){
            $errorMessage .= "Le code postal est obligatoire <br>";
        }

        if(empty($_POST['capacite'])){
            $errorMessage .= "La capacite est obligatoire <br>";
        }


        if( !empty($_POST) ){
            $photo = '';
            if(!empty($_FILES['photo']['name'])){
                $photo = 'photo/' .$_FILES['photo']['name'];
                copy($_FILES['photo']['tmp_name'], $photo);
            }
        }


        if(empty($errorMessage)){

            $ajout = $bdd->prepare("INSERT INTO salle(titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
            $ajout->execute([
                'titre' => $_POST['titre'],
                'description' => $_POST['description'],
                'photo' => $photo,
                'pays' => $_POST['pays'],
                'ville' => $_POST['ville'],
                'adresse' => $_POST['adresse'],
                'cp' => $_POST['cp'],
                'capacite' => $_POST['capacite'],
                'categorie' => $_POST['categorie'],
            ]);

            if($ajout){
                $successMessage .= "La salle a bien été créée. <br>"; 
            }else{
                $errorMessage .= "Erreur - La salle n'a pas pu être créée. <br>";
            }

        }

    }
}

//Suppression d'un membre

if(isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id_salle'])){


    $_GET['id_salle'] = htmlspecialchars($_GET['id_salle'], ENT_QUOTES);

    $resultat = $bdd->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
    $resultat->execute([
        'id_salle' => $_GET['id_salle'],
    ]);

    if($resultat){
        $successMessage .= "La salle a bien été supprimé. <br>"; 
    }else{
        $errorMessage .= "Erreur - La salle n'a pas pu être supprimé. <br>";
    }

}

//Modification d'une salle
//debug($_GET);

if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_salle'])){

    $_GET['id_salle'] = htmlspecialchars($_GET['id_salle'], ENT_QUOTES);

    $resultat = $bdd->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $resultat->execute([
        'id_salle' => $_GET['id_salle']
    ]);
	$salle_modifie = $resultat->fetch(PDO::FETCH_ASSOC);
//debug($salle_modifie);
}

// 6- Mise à jour des données modifiées en BDD : 

//debug($_POST);
if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_salle'])){



if( !empty($_POST) ){
    $photo = '';
    if(!empty($_FILES['photo']['name'])){
        $photo = 'photo/' .$_FILES['photo']['name'];
        copy($_FILES['photo']['tmp_name'], $photo);
    }else{
        $photo = $salle_modifie['photo'];
    }
}

    //debug($photo);
    if (!empty($_POST)) {

        if(empty($_POST['titre']) || strlen( trim($_POST['titre'])) < 4 || strlen( trim($_POST['titre'])) > 50){
            $errorMessage .= "Le titre de la salle doit contenir entre 4 et 50 caractères <br>";
        }
        
        if(empty($_POST['pays']) || $_POST['pays'] != "France" || (empty($salle_modifie['pays']))){
            $errorMessage .= "Le pays doit être égal à France <br>";
        }

    foreach ($_POST as $indice => $valeur) {
		$_POST[$indice] = htmlspecialchars($_POST[$indice], ENT_QUOTES);
    }

    if(empty($errorMessage)){
        $modif = $bdd->prepare(
            "UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");
        $modif->execute([
            'titre' => $_POST['titre'],
            'description' => $_POST['description'],
            'photo' => $photo,
            'pays' => $_POST['pays'],
            'ville' => $_POST['ville'],
            'adresse' => $_POST['adresse'],
            'cp' => $_POST['cp'],
            'capacite' => $_POST['capacite'],
            'categorie' => $_POST['categorie'],
            'id_salle' => $_POST['id_salle'],
        ]);

    if ($modif) {
        $successMessage .= 'La salle a été modifiée.<br>'; 
    } else {
        $errorMessage .= 'Erreur lors de la modification<br>';
    }
    }
}
}    
//Liste des salles

$resultat = $bdd->prepare("SELECT * FROM salle");

$resultat->execute();




$titreDeMaPage = "Gestion des salles";
require_once '../inc/header.php';
?>

<!-- ************ HTML *************** -->

<h1 class="text-center">Gestion des salles</h1>


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






<!-- Tableau recap des salles -->

<table class="table table-striped table-hover table-bordered mt-3">
        <thead>
            <tr>
                <th> ID </th>
                <th> Titre </th>
                <th> Description </th>
                <th> Photo </th>
                <th> Pays </th>
                <th> Ville </th>
                <th> Adresse </th>
                <th> CP </th>
                <th> capacité </th>
                <th> categorie </th>
                <th> Action </th>
            </tr>
        </thead>

        <tbody>

        <?php

                while($salle = $resultat->fetch(PDO ::FETCH_ASSOC)){
                
                
                ?>
                    <tr>
                        <td> <?php echo $salle['id_salle'] ?> </td>
                        <td> <?= $salle['titre'] ?> </td>
                        <td> <?= $salle['description'] ?> </td>
                        <td> <img src="<?= $salle['photo'] ?>" height=100px width=100px> </td>
                        <td> <?= $salle['pays'] ?> </td>
                        <td> <?= $salle['ville'] ?> </td>
                        <td> <?= $salle['adresse'] ?> </td>
                        <td> <?= $salle['cp'] ?> </td>
                        <td> <?= $salle['capacite'] ?> </td>
                        <td> <?= $salle['categorie'] ?> </td>
                        <td>
                <a href="gestion_salle.php?action=supprimer&id_salle=<?php echo $salle['id_salle']; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer cette salle ?'));">Supprimer |</a>
                    <a href="?action=modifier&id_salle=<?= $salle['id_salle'] ?>"> Modifier</a>
                </td>   
                    </tr>
                <?php
                }
                ?>
        

        </tbody>

    </table>

    <div>
<a href="gestion_salle.php?action=ajouter"><button class="btn btn-primary">Ajouter une salle</button> </a>
<a href="index.php"><button class="btn btn-secondary ms-5">Retour à l'accueil back office</button> </a>
</div>

<!-- FORMULAIRE AJOUT D'UNE SALLE -->
    <?php
    if(isset($_GET['action']) && $_GET['action'] == "ajouter"){
?>
    <hr>
    <hr>
    <h4 class="text-center">Ajouter une salle</h4>
    <form action="" method="post" class="col-6 mx-auto" enctype="multipart/form-data">

<label for="titre" class="form-label">Titre</label>
<input type="text" name="titre" id="titre" class="form-control" value="<?php echo $_POST['titre'] ?? "" ?>">

<label for="description" class="form-label">Description</label>
<textarea type="text" name="description" id="description" class="form-control" cols="30" rows="5" ><?php echo $_POST['description'] ?? "" ?></textarea>

<label for="photo" class="form-label">Photo</label>
<input type="file" name="photo" id="photo" class="form-control" value="<?php echo $photo ?? "" ?>" >

<label for="pays" class="form-label">Pays</label>
<input type="text" name="pays" id="pays" class="form-control" value="<?php echo $_POST['pays'] ?? "" ?>">


<label for="ville" class="form-label">Ville</label>
<select name="ville" id="ville" class="form-select">
    <option >Paris</option>
    <option >Lyon</option>
    <option >Marseille</option>
</select>

<label for="adresse" class="form-label">Adresse</label>
<textarea type="text" name="adresse" id="adresse" class="form-control" cols="30" rows="5"><?php echo $_POST['adresse'] ?? "" ?></textarea>

<label for="cp" class="form-label">CP</label>
<input type="text" name="cp" id="cp" class="form-control" value="<?php echo $_POST['cp'] ?? "" ?>">

<label for="capacite" class="form-label">capacite</label>
<input type="text" name="capacite" id="capacite" class="form-control" value="<?php echo $_POST['capacite'] ?? "" ?>">

<label for="categorie" class="form-label">Catégorie</label>
        <select name="categorie" id="categorie" class="form-select" value="<?php echo $_POST['categorie'] ?? "" ?>">
            <option>reunion</option>
            <option>bureau</option>
            <option>formation</option>
        </select>
        
        <br>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary me-5">Enregistrer la nouvelle salle </button>
            <a href="gestion_salle.php" class="btn btn-info">Annuler</a>
        </div>
    </form>

    <br>
    <br>

<?php
}



// FORMULAIRE DE MODIFICATION D'UNE SALLE
//debug($salle_modifie);
if (isset($salle_modifie)) {
    ?>
        <hr>
        <hr>
        <h4 class="text-center">Modifiez ici vos données</h4>
        <form action="" method="post" class="col-6 mx-auto" enctype="multipart/form-data">
            <div>
                <div><input type="hidden" name="id_salle" value="<?php echo $salle_modifie['id_salle']; ?>" > 
                </div>
            </div>
    
<label for="titre" class="form-label">Titre</label>
<input type="text" name="titre" id="titre" class="form-control" value="<?php echo $salle_modifie['titre'] ?? "" ?>" >

<label for="description" class="form-label">Description</label>
<textarea type="text" name="description" id="description" class="form-control" cols="30" rows="5" ><?php echo $salle_modifie['description'] ?? "" ?></textarea>

<label for="photo" class="form-label">Photo</label>
<input type="file" name="photo" id="photo" class="form-control" value="<?php echo $salle_modifie['photo'] ?? "" ?>">

<label for="pays" class="form-label">Pays</label>
<input type="text" name="pays" id="pays" class="form-control" value="<?php echo $salle_modifie['pays'] ?? "" ?>" >
</select>

<label for="ville" class="form-label">Ville</label>
<select name="ville" id="ville" class="form-select">
    <option <?php if ($salle_modifie['ville'] == "Paris") echo 'selected'; ?>>Paris</option>
    <option <?php if ($salle_modifie['ville'] == "Lyon") echo 'selected'; ?>>Lyon</option>
    <option <?php if ($salle_modifie['ville'] == "Marseille") echo 'selected'; ?>>Marseille</option>
</select>

<label for="adresse" class="form-label">Adresse</label>
<textarea type="text" name="adresse" id="adresse" class="form-control" cols="30" rows="5" ><?php echo $salle_modifie['adresse'] ?? "" ?></textarea>

<label for="cp" class="form-label">CP</label>
<input type="text" name="cp" id="cp" class="form-control" value="<?php echo $salle_modifie['cp'] ?? "" ?>" >

<label for="capacite" class="form-label">capacite</label>
<input type="text" name="capacite" id="capacite" class="form-control" value="<?php echo $salle_modifie['capacite'] ?? "" ?>" >

<label for="categorie" class="form-label">Catégorie</label>
        <select name="categorie" id="categorie" class="form-select">
            <option <?php if ($salle_modifie['categorie'] == "reunion") echo 'selected'; ?>>reunion</option>
            <option <?php if ($salle_modifie['categorie'] == "bureau") echo 'selected'; ?>>bureau</option>
            <option <?php if ($salle_modifie['categorie'] == "formation") echo 'selected'; ?>>formation</option>
        </select>
        
        <br>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary me-5">Enregistrer les modifications</button>
            <a href="gestion_salle.php" class="btn btn-info">Annuler</a>
        </div>

        </form>
    
    <?php
    }

require_once '../inc/footer.php';
?>