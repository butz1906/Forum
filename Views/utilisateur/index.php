<?php 
$title = "Forum - liste des utilisateurs" ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item active"><a>Liste des utilisateurs</a></li>
        </ol>
    </nav>
<h2 class='titrePage'>Liste des utilisateurs</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Pseudo</th>
            <th scope="col">Email</th>
                <th scope="col">Date d'inscription</th>
                <th scope="col">Statut</th>
                <th scope="col">Validé</th>
                <th scope="col">Banni</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            //On boucle dans le tableau $list qui contient la liste des utilisateurs
            foreach ($list as $value) {
                echo "<tr>";
                echo "<form method='POST' action='index.php?controller=utilisateur&action=update'>";
                echo "<input type='hidden' name='id' value='" . $value->id . "'>";
                echo "<th scope='row'>" . $value->pseudo . "</th>";
                echo "<td>" . $value->email . "</td>";
                echo "<td>" . date('d-m-Y', strtotime($value->date_inscription)) . "</td>";
                echo "<td>";
                echo "<select name='statut'>";
                echo "<option value='admin'" . ($value->statut == 'admin' ? ' selected' : '') . ">admin</option>";
                echo "<option value='modo'" . ($value->statut == 'modo' ? ' selected' : '') . ">modo</option>";
                echo "<option value='user'" . ($value->statut == 'user' ? ' selected' : '') . ">user</option>";
                echo "</select>";
                echo "</td>";
                echo "<td>";
                echo "<select name='valide'>";
                echo "<option value='1'" . ($value->valide == 1 ? ' selected' : '') . ">Oui</option>";
                echo "<option value='0'" . ($value->valide == 0 ? ' selected' : '') . ">Non</option>";
                echo "</select>";
                echo "</td>";
                echo "<td>";
                echo "<select name='banni'>";
                echo "<option value='1'" . ($value->banni == 1 ? ' selected' : '') . ">Oui</option>";
                echo "<option value='0'" . ($value->banni == 0 ? ' selected' : '') . ">Non</option>";
                echo "</select>";
                echo "</td>";
                echo "<td><button class='updateUser' type='submit' name='update' value='" . $value->id . "'><i class='fa-solid fa-save' style='color: #0f992b;'></i></button></td> ";
                echo "<td><a href='index.php?controller=utilisateur&action=deleteUtilisateur&id=" . $value->id . "' onclick='return confirmDelete()'><i class='fa-solid fa-trash-can' style='color: #990000;'></i></a></td>";
                echo "</form>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>