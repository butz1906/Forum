<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['token'])) {

    if (!empty($listeSujets)) :
        $firstSujet = reset($listeSujets);
        $title = $titreTopic->titre;
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item"><a href='index.php?controller=topic&action=liste'>Forum</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a><?= $titreTopic->titre?></a></li>
        </ol>
    </nav>
        <h2 class='titrePage'><?= $title; ?></h2>

        <?php foreach ($listeSujets as $sujet) : ?>
            <div class="sujet">
                <div class='editTopic'>
                    <h3><a href='index.php?controller=message&action=liste&id=<?= $sujet->id ?>'><?= $sujet->theme; ?></a></h3>
                    <?php if ($_SESSION['statut'] === 'admin' || $_SESSION['statut'] === 'modo') {
                        echo "<a href='index.php?controller=sujet&action=modifier&id=" . $sujet->id . "'><i class='fa-solid fa-pen-to-square' style='color: #0f992b;'></i></a>";
                    } ?>
                </div>
                <div class="editTopic">
                    <?= date('d-m-Y', strtotime($sujet->date)); ?>
                    <p><?= $sujet->nombre_occurrences; ?> message(s)</p>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else : ?>
        <p>Aucun sujet trouvé.</p>
    <?php endif; ?>

    <a class="btn btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off" href='index.php?controller=sujet&action=add'>Ajouter un nouveau sujet</a>
<?php
} else {
    header('Location:index.php');
}
