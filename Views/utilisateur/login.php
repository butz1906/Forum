<?php $title = "Connexion";
if (!isset($_SESSION['pseudo'])) {
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item active"><a>Connexion</a></li>
        </ol>
    </nav>
    <h2 class='titrePage'>Connexion</h2>
<?php
    echo $loginForm;
}
?>