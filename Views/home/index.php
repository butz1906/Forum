<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a>Accueil</a></li>
    </ol>
</nav>
<?php
$title = "Forum - Accueil";
?>
<h2 class='titrePage'>Accueil</h2>
<?php if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0) { ?>
    <div class='accueil'>
        <aside>
            <article class='listetopic'>
                <h3>Listes des topics</h3>
                <?php foreach ($topics as $topic) : ?>
                    <div class="topic">
                        <h2><a class="text-dark" href="index.php?controller=sujet&action=liste&id=<?= $topic['id'] ?>"><?= $topic['titre'] ?></a></h2>
                        <p><?= $topic['description'] ?></p>
                    </div>
                <?php endforeach; ?>
            </article>
            <article class='hottopic'>
                <h3>Sujets populaires :</h3>
                <?php foreach ($top10Sujet as $sujet) { ?>
                    <div>
                        <a href="index.php?controller=message&action=liste&id=<?php echo $sujet->id; ?>">
                            <?php echo '<strong>' . $sujet->theme . '</strong><br> dans <em>' . $sujet->topic_title . '</em>' ?>
                        </a>

                    </div>
                <?php } ?>
            </article>
        </aside>
        <!-- Sujet avec plus de 10 messages ces 15 derniers jours -->
            <article class='lastmessage'>
                <h3>Derniers messages :</h3>
                <?php foreach ($latestMessages as $message) { ?>
                    <div>
                        <em> Sujet : <a href="index.php?controller=message&action=liste&id=<?php echo $message->id_sujet; ?>">
                                <?php echo $message->topic_title; ?>
                            </a></em><br>
                        <strong><?php echo $message->username . " dit : " ?></strong>
                        <?php echo $message->message; ?>
                    </div>
                <?php } ?>
            </article>
    </div>
<?php }
?>