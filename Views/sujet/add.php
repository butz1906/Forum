<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['token'])) {

?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item"><a href='index.php?controller=topic&action=liste'>Forum</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a>Nouveau sujet</a></li>
        </ol>
    </nav>
    <?php
    $title = "Forum - Nouveau sujet";
    ?>
    <h2 class='titrePage'>Nouveau sujet</h2>
<?php
    echo $addForm;
} else {
    $title = "Forum - Accueil";
}
