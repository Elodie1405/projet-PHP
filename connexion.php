<?php

require_once 'inc/init.php';

//deconnexion

if(isset($_GET['action']) && $_GET['action'] == "deconnexion"){
    

    $successMessage = "Vous êtes déconnecté, au revoir " . $_SESSION['membre']['prenom'] . "<br>";
    unset($_SESSION['membre']);
    session_destroy();
}

// formulaire de connexion


if(!empty($_POST)){ 
    if(empty($_POST['pseudo']) || empty($_POST['mdp']) ){
        $errorMessage = "Les identifiants sont obligatoire ! <br>";
    }

    if (empty($errorMessage)) { 
        $_POST['pseudo'] = htmlspecialchars($_POST['pseudo'], ENT_QUOTES);
        $_POST['mdp'] = htmlspecialchars($_POST['mdp'], ENT_QUOTES); 
    

        $resultat = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo AND mdp = :mdp ");

        $resultat->execute([
            'pseudo' => $_POST['pseudo'],
            'mdp' => $_POST['mdp']
        ]);

        $membre = $resultat->fetch(PDO::FETCH_ASSOC); 

//debug($membre);

        if(!empty($membre) || $_SESSION['membre']['statut'] = 0){
            
            $_SESSION['membre'] = $membre;

            $successMessage .= "Vous êtes bien connecté !<br>";

        }else {

            $errorMessage .= "Erreur au niveau des identifiants ! <br>";
        }
    }
}


$titreDeMaPage = "Connexion";
require_once 'inc/header.php';
?>

<h1 class="text-center">Se connecter</h1>

<?php if(isset($_SESSION['message'])){ ?>
    <div class="alert alert-success col-6 mx-auto text-center">
        <?= $_SESSION['message'] ?>
    </div>
<?php 
    unset($_SESSION['message']); 
} ?>


<?php if(!empty($successMessage)) { ?>

<div class="alert alert-success text-center mx-auto col-5">
    <?= $successMessage ?>

</div>

<?php } ?>

<?php if(!empty($errorMessage)) { ?>

<div class="alert alert-danger text-center mx-auto col-5">
<?= $errorMessage ?>
</div>

<?php } 

if (!isset($_SESSION['membre'])) : 
?>

<form action="connexion.php" method="post" class="col-6 mx-auto">

<label for="pseudo" class="form-label">Pseudo</label>
<input type="text" id="pseudo" name="pseudo" class="form-control mt-3" placeholder="Votre pseudo">

<label for="mdp" class="form-label">Mot de Passe</label>
<input type="password" id="mdp" name="mdp" class="form-control mt-3" placeholder="Votre mot de passe">

<div class="d-flex justify-content-center mt-3">
<button class="btn btn-primary" id="submit">Connexion</button>
</div>



</form>

<?php
endif;
?>

<div class="text-center mt-3">
    <a href="connexion.php?action=deconnexion" class="btn btn-danger">Déconnexion</a>
    </div>


    
   

    


<?php


require_once 'inc/footer.php';
?>