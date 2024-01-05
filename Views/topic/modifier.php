<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0) {

    $title = "Forum - Modifier un topic";
?>
    <h2 class='titrePage'>Modifier un topic</h2>
<?php echo $topicForm;
} else {
    $title = "Forum - Accueil";
}
