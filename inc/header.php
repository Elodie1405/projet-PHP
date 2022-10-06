<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titreDeMaPage ?></title>

    <!-- CSS BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>

<header>

<nav class="navbar navbar-expand-lg navbar-light bg-info">
  <div class="container-fluid">
    <a class="navbar-brand" href="/PHP/projet-PHP/index.php">Lokisalle</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            Espace membre
  </button>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a href="/PHP/projet-PHP/inscription.php" class="dropdown-item" >Inscription</a></li>
            <li><a class="dropdown-item" href="/PHP/projet-PHP/connexion.php">Connexion</a></li>
            <li><a class="dropdown-item" href="/PHP/projet-PHP/profil.php">Profil</a></li>

            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/PHP/projet-PHP/Back-office/index.php">Back office</a></li>
          </ul>
          <li class="nav-item">
          <a class="nav-link" aria-current="page" href="/PHP/projet-PHP/index.php">Accueil</a>
        </li>
          <li class="nav-item">
          <a class="nav-link" aria-current="page" href="#">Qui sommes-nous ?</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-dark" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>


</header>

