<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['token'])) {
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a>Messagerie privée</a></li>
        </ol>
    </nav>
<h2 class='titrePage'>Messagerie</h2>
<?php
$title = "Messagerie";
?>    <ul class="list-group">
        <?php
        foreach ($listeMessagePrivee as $message) : ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href='index.php?controller=messagePrivee&action=echange&id=<?= $message->id_utilisateur ?>&pseudo=<?= $message->utilisateur_pseudo ?>'> <?= $message->utilisateur_pseudo; ?> </a>
                <?php
                if ($message->lu == 0) {
                ?>
                    <span class="badge badge-primary badge-pill">new</span>
                <?php } ?>
            </li>
    <?php
        endforeach;
    ?> </ul>
    <?php } ?>