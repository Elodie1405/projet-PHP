<?php

require_once '../inc/init.php';
//debug($_SESSION['membre']);
// 1- Vérification si le membre est admin connecté :
if (!isset($_SESSION['membre']) || $_SESSION['membre']['statut'] != 1 ) { 
    header('location:../connexion.php'); 
    exit; 
}


// Ajout d'un membre
if(isset($_GET['action']) && $_GET['action'] == "ajouter"){

    if(!empty($_POST) && $_GET['action'] == "ajouter"){
        if(empty($_POST['pseudo']) || strlen( trim($_POST['pseudo'])) < 4 || strlen( trim($_POST['pseudo'])) > 20){
            $errorMessage .= "Le pseudo doit contenir entre 4 et 20 caractères <br>";
        }

        if(empty($_POST['mdp']) || strlen( trim($_POST['mdp'])) < 4 || strlen( trim($_POST['mdp'])) > 60){
            $errorMessage .= "Le mot de passe doit contenir entre 4 et 60 caractères <br>";
        }

        if(empty($_POST['nom'])){
            $errorMessage .= "Le nom est obligatoire <br>";
        }

        if(empty($_POST['prenom'])){
            $errorMessage .= "Le prénom est obligatoire <br>";
        }


        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errorMessage .= "L'email n'est pas valide <br>";
        }

        if(empty($errorMessage)){

            $ajout = $bdd->prepare("INSERT INTO membre(pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, :date_enregistrement)");
            $ajout->execute([
                'pseudo' => $_POST['pseudo'],
                'mdp' => $_POST['mdp'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'civilite' => $_POST['civilite'],
                'statut' => $_POST['statut'],
                'date_enregistrement' => date("Y-m-d H:i:s"),
            ]);

            if($ajout){
                $successMessage .= "Le membre a bien été crée. <br>"; 
            }else{
                $errorMessage .= "Erreur - Le membre n'a pas pu être crée. <br>";
            }

        }
    }
}













//Suppression d'un membre

if(isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id_membre'])){

	$_GET['id_membre'] = htmlspecialchars( $_GET['id_membre']);

    $resultat = $bdd->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $resultat->execute([
        'id_membre' => $_GET['id_membre'],
    ]);

    if($resultat){
        $successMessage .= "Le membre a bien été supprimé. <br>"; 
    }else{
        $errorMessage .= "Erreur - Le membre n'a pas pu être supprimé. <br>";
    }
}


//Modification d'un membre
//debug($_GET);
if(isset($_GET['action']) && $_GET['action'] == "modifier" && isset($_GET['id_membre'])){

    $resultat = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $resultat->execute([
        'id_membre' => $_GET['id_membre']
    ]);
	$membre_modifie = $resultat->fetch(PDO::FETCH_ASSOC);
    //debug($membre_modifie);
}

// 6- Mise à jour des données modifiées en BDD : 

//debug($_POST);

if (!empty($_POST) && $_GET['action'] == "modifier" && isset($_GET['id_membre'])) { 
    foreach ($_POST as $indice => $valeur) {
		$_POST[$indice] = htmlspecialchars($_POST[$indice], ENT_QUOTES);
    }

    //debug($_POST);
        $requete = $bdd->prepare(
            "UPDATE membre SET nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");
        $requete->execute([
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'civilite' => $_POST['civilite'],
            'statut' => $_POST['statut'],
            'id_membre' => $_POST['id_membre'],
        ]);


    if ($requete) {
        $successMessage .= 'Le membre a été modifié.<br>';
    } else {
        $errorMessage .= 'Erreur lors de la modification<br>';
    }

}    

//Liste des membres

$resultat = $bdd->query("SELECT id_membre, pseudo, nom, prenom, email, civilite, statut, date_enregistrement FROM membre");

$membres = $resultat->fetchAll(PDO::FETCH_ASSOC);



$titreDeMaPage = "Gestion des membres";
require_once '../inc/header.php';
?>

<!-- ************ HTML *************** -->

<h1 class="text-center">Gestion des membres</h1>


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






<!-- Tableau recap des membres -->

<table class="table table-striped table-hover table-bordered mt-3">
        <thead>
            <tr>
                <th> ID Membre</th>
                <th> Pseudo </th>
                <th> Nom </th>
                <th> Prénom </th>
                <th> Email </th>
                <th> Civilite </th>
                <th> Statut </th>
                <th> Date enregistrement </th>
                <th> Action </th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($membres as $membre) {  
      //debug($membre);?>
            <tr> 

                <?php foreach ($membre as $information) { ?>
                
                    <td> <?= $information ?> </td>
                    <?php
                } 

               // debug($membre);
                ?> 
                
                <td>
                <a href="gestion_membre.php?action=supprimer&id_membre=<?php echo $membre['id_membre']; ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ce membre ?'));">Supprimer |</a>
                    <a href="?action=modifier&id_membre=<?= $membre['id_membre'] ?>"> Modifier</a>
                </td>   
            </tr>
            <?php } ?>  

        </tbody>

    </table>

    <div>
<a href="gestion_membre.php?action=ajouter"><button class="btn btn-primary">Ajouter un membre</button></a>
<a href="index.php"><button class="btn btn-secondary ms-5">Retour à l'accueil back office</button> </a>
</div>


<!-- FORMULAIRE AJOUT D'UN MEMBRE -->
    <?php
    if(isset($_GET['action']) && $_GET['action'] == "ajouter"){
?>
    <hr>
    <hr>
    <h4 class="text-center">Ajouter un membre</h4>
    <form action="" method="post" class="col-6 mx-auto">

<label for="pseudo" class="form-label">Pseudo</label>
<input type="text" name="pseudo" id="pseudo" class="form-control">

<label for="mdp" class="form-label">Mot de passe</label>
<input type="password" name="mdp" id="mdp" class="form-control">

<label for="nom" class="form-label">Nom</label>
<input type="text" name="nom" id="nom" class="form-control" >

<label for="prenom" class="form-label">Prénom</label>
<input type="text" name="prenom" id="prenom" class="form-control" >

<label for="email" class="form-label">Email</label>
<input type="email" name="email" id="email" class="form-control" >

<label for="civilite" class="form-label">Civilite</label>
<select name="civilite" id="civilite" class="form-control">
    <option value ="m">Homme </option>
    <option value="f">Femme</option>
</select>

<label for="statut" class="form-label">Statut</label>
    <select name="statut" id="statut" class="form-select">
        <option value ="0"> Utilisateur </option>
        <option value="1">Administrateur</option>
    </select>
        
        <br>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary">Enregistrer le nouveau membre</button>
            <a href="gestion_membre.php" class="btn btn-info ms-3">Annuler</a>
        </div>
    </form>

    <br>
    <br>

<?php
}

// FORMULAIRE DE MODIFICATION D'UN MEMBRE

if (isset($membre_modifie)) {
?>
    <hr>
    <hr>
    <h4 class="text-center">Modifiez ici vos données</h4>
    <form action="" method="post" class="col-6 mx-auto ">
        <div>
            <div><input type="hidden" name="id_membre" value="<?php echo $membre_modifie['id_membre']; ?>" > 
            </div>
        </div>

        <label for="nom" class="form-label">Nom</label>
        <input type="text" name="nom" id="nom" value="<?php echo $membre_modifie['nom']; ?>" class="form-control">
        
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" value="<?php echo $membre_modifie['prenom']; ?>" class="form-control">
    
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" id="email" value="<?php echo $membre_modifie['email']; ?>" class="form-control">

        <label for="civilite" class="form-label">Civilite</label>
        <select name="civilite" id="civilite" class="form-select">
            <option value="m">Homme</option>
            <option value="f" <?php if ($membre_modifie['civilite'] == 1) echo 'selected'; ?> >Femme</option>
        </select>
    
    
        
        <label for="statut" class="form-label">Statut</label>
        <select name="statut" id="statut" class="form-select">
            <option value="0">Utilisateur</option>
            <option value="1" <?php if ($membre_modifie['statut'] == 1) echo 'selected'; ?> >Administrateur</option>
        </select>
        
        <br>
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary">Valider les modifications</button>    
        </div>
    </form>

<?php
}
require_once '../inc/footer.php';
?>