<?php

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Affichage dynamique de la variable $title -->
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
    <script src="https://kit.fontawesome.com/9876c79344.js" crossorigin="anonymous"></script>
</head>
<div class="container">
    <header class="text-center">
        <h1 id="bandeauTitre" class='bg-dark text-light'>FORUM</h1>
    </header>
    <nav id="menu" class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class='navbar-brand' href='#'>Menu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span id="custom-toggler-icon" class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul style="width: 100%;" class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <?php if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=utilisateur&action=profil">Mon profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=topic&action=liste">Liste des topics</a>
                        </li>
                        <li style="display: flex" class="nav-item">
                            <?php if ($_SESSION['nouveauMessage'] == true) { ?>
                                <a class="nouveauMessage nav-link" href="index.php?controller=messagePrivee&action=liste">Nouveau(x) message(s)</a>
                            <?php
                            } else { ?>
                                <a class="nav-link" href="index.php?controller=messagePrivee&action=liste">Messagerie Privée</a>
                            <?php } ?>
                        </li>
                        <?php
                        if (isset($_SESSION['statut']) && $_SESSION['statut'] === 'admin') { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?controller=utilisateur&action=index">Administrateur</a>
                            </li>
                        <?php } ?>
                </ul>
                <div class="input-group" style="justify-content: flex-end;">
                    <form style="display:flex; flex-direction:row" class="form-inline my-2 my-lg-0" action="index.php?controller=search&action=results" method="post">
                        <div class="input-group-prepend">
                            <input style="border-radius: 0.5em 0 0 0.5em;" class="input-group-text" type="text" name="q" placeholder="Rechercher...">
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-outline" type="submit"><i class='fa-solid fa-magnifying-glass'></i></button>
                        </div>
                    </form>
                </div>
            <?php } ?>
            </div>
        </div>
    </nav>
    <main>
        <?= $content; ?>
        <?php
        if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0) {
        ?>
            <div class='logout'>Bienvenue, <?= $_SESSION['pseudo'] ?>.
                <form action="index.php?controller=utilisateur&action=logout" method="POST">
                    <input class="btn btn-alert" type="submit" name="logout" value="Se déconnecter">
                </form>
            </div>
        <?php
        } elseif (isset($_SESSION['banni']) && $_SESSION['banni'] == 1) {
        ?>
            <div class='logout'>Votre compte est banni.</div>
            <form action="index.php?controller=utilisateur&action=logout" method="POST">
                <input class="btn btn-alert" type="submit" name="logout" value="Se déconnecter">
            </form>
        <?php
        } elseif (isset($_SESSION['valide']) && $_SESSION['valide'] == 0) {
        ?>
            <div class='logout'>Votre compte est en attente de validation.</div>
            <form action="index.php?controller=utilisateur&action=logout" method="POST">
                <input class="btn btn-alert" type="submit" name="logout" value="Se déconnecter">
            </form>
        <?php
        } else {
        ?>
            <p>Pour vous inscrire, c'est par <a href="index.php?controller=utilisateur&action=add">ICI</a></p>
            <p>Pour vous connecter, c'est par <a href="index.php?controller=utilisateur&action=login">LÀ</a></p>
        <?php
        }
        ?>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>