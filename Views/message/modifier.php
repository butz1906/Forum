<?php
if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0 && $_SESSION['token']) {
    $title = "Forum - Editer un message";
    echo $messageForm;
} else {
    $title = "Forum - Accueil";
}
