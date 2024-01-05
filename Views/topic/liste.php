<?php

if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0) {
    $title = "Forum - liste des topics";
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item active"><a>Forum</a></li>
        </ol>
    </nav>
    <h2 class='titrePage'>Liste des topics</h2>
    <?php foreach ($topics as $topic) : ?>
        <div class="topic">
            <div class='titredescription'>
                <h2><a class="text-dark" href="index.php?controller=sujet&action=liste&id=<?= $topic->id ?>"><?= $topic->titre ?>

                    </a></h2>
                <p><?= $topic->description ?></p>
            </div>
            <div class="editTopic">
                <p><u>Auteur</u> : <?= $topic->utilisateur_pseudo ?></p>
                <?php if ($_SESSION['statut'] === 'admin') {
                    echo "<a href='index.php?controller=topic&action=modifier&id=" . $topic->id . "'><i class='fa-solid fa-pen-to-square' style='color: #0f992b;'></i></a>";
                } ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php } ?>
<?php if (isset($_SESSION['statut']) && $_SESSION['statut'] == 'admin') { ?>
    <a class="btn btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off" href='index.php?controller=topic&action=add'>Ajouter un nouveau topic</a>
<?php
} else {
    $title = "Forum - Accueil";
}
