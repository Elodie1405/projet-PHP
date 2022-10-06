<?php

require_once 'inc/init.php';

if(!empty($_POST)){

    // Echapper les caractères speciaux
    // $key = ['pseudo] par exemple
    // $value = ce qui va être écrit dans le formulaire dans chaque input
    foreach($_POST as $key => $value){
    $_POST[$key] = htmlspecialchars($_POST[$key], ENT_QUOTES);
    }

    //Vérifications des informations du formulaire
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

        $requete = $bdd->prepare("INSERT INTO membre(pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, :date_enregistrement)");
        $requete->execute([
            'pseudo' => $_POST['pseudo'],
            'mdp' => $_POST['mdp'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'civilite' => $_POST['civilite'],
            'statut' => 0,
            'date_enregistrement' => date("Y-m-d H:i:s"),
        ]);
    
        $_SESSION['message'] = "Bonjour $_POST[nom], vous êtes inscrit. Vous pouvez maintenant vous connecter."; 
        
        header("location:connexion.php"); 

        exit; 
    }

}





$titreDeMaPage = "Inscription";
require_once 'inc/header.php';
?>

<!-- HTML -->

<h1 class="text-center">S'inscrire</h1>

    <?php if(!empty($errorMessage)){ ?>
        <div class="alert alert-danger text-center col-5 mx-auto">
            <?= $errorMessage ?>
        </div>
    <?php } ?> 


<form action="" method="post" class="col-6 mx-auto">
<label for="pseudo" class="form-label">Pseudo</label>
<input type="text" name="pseudo" id="pseudo" class="form-control" value="<?php echo $_POST['pseudo'] ?? "" ?>">

<label for="mdp" class="form-label">Mot de passe</label>
<input type="password" name="mdp" id="mdp" class="form-control">

<label for="nom" class="form-label">Nom</label>
<input type="text" name="nom" id="nom" class="form-control" value="<?php echo $_POST['nom'] ?? "" ?>">

<label for="prenom" class="form-label">Prénom</label>
<input type="text" name="prenom" id="prenom" class="form-control" value="<?php echo $_POST['prenom'] ?? "" ?>">

<label for="email" class="form-label">Email</label>
<input type="email" name="email" id="email" class="form-control" value="<?php echo $_POST['email'] ?? "" ?>">

<label for="civilite" class="form-label">Civilite</label>
<select name="civilite" id="civilite" class="form-control">
    <option value="m">Homme</option>
    <option value="f">Femme</option>
</select>


<div class="d-flex justify-content-center mt-3">
    <button class="btn btn-primary">Inscription</button>
</div>

</form>




<!-- FOOTER -->
<?php
require_once 'inc/footer.php';
?>