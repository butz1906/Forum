<?php
if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0 && $_SESSION['token']) {
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a>Messagerie privée</a></li>
        </ol>
    </nav>
    <h2 class='titrePage'>Discussion privée avec <?= $_GET['pseudo'] ?></h2>
    <?php
    foreach ($echangePrivee as $message) :
        $title = "Echange avec " . $_GET['pseudo'];
    ?>
        <!-- Chat message content -->
        <div class="chat <?= ($message->id_utilisateur == $_SESSION['id']) ? 'sent' : 'received' ?>">
            <!-- Message details -->
            <p><em><?= $message->utilisateur_pseudo; ?> dit :</em></p>
            <p><?= $message->message; ?></p>
            <p class='datemessagerie'><?= date('d-m-Y', strtotime($message->date_envoi)); ?></p>
            <?php if ($message->lu == 1) {
            ?><div class='lu'>
                    <i class="fa-solid fa-check" style="color: #20511f;"></i>
                </div>
            <?php
            }
            ?>
        </div>
    <?php endforeach;
    ?>
    <div id="bottom"></div>
<?php
    echo $addForm;
} else {
    $title = "Forum - Accueil";
}
