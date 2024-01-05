<?php 
$title = "Forum - Déplacer un sujet";
if (isset($_SESSION['token'])) {
echo $sujetForm;
}
else {
    $title = "Forum - Accueil";
}