<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
    <li class="breadcrumb-item active"><a>Nouveau topic</a></li>
</ol>
</nav>
<?php
$title = "Forum - Nouveau topic";
?>
<h2 class='titrePage'>Nouveau topic</h2>
<?php
echo $addForm;
