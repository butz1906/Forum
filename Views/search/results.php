<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a>Recherche</a></li>
    </ol>
</nav>
<?php
$title = "Résultats de la recherche";
if (!empty($results)) :
?>
    <div class='searchresult'>
        <!-- Div pour les résultats de type 'Topic' -->
        <div class="results-topic">
            <h3>Résultat dans les Topics :</h3>
            <?php foreach ($results as $result) :
                if ($result->result_type === 'Topic') : ?>
                    <div class="topic">
                        <div class='titredescription'>
                            <h2><a class="text-dark" href="index.php?controller=sujet&action=liste&id=<?= $result->result_id ?>"><?= $result->result_title ?></a></h2>
                            <p><?= $result->result_description ?></p>
                        </div>
                    </div>
            <?php endif;
            endforeach; ?>
        </div>

        <!-- Div pour les résultats de type 'Sujet' -->
        <div class="results-sujet">
            <h3>Résultat dans les Sujets :</h3>
            <?php foreach ($results as $result) :
                if ($result->result_type == 'Sujet') : ?>
                    <div class="sujet">
                        <div class='editTopic'>
                            <h3><a href='index.php?controller=message&action=liste&id=<?= $result->result_id ?>'><?= $result->result_title; ?></a></h3>
                        </div>
                    </div>
            <?php endif;
            endforeach; ?>
        </div>

        <!-- Div pour les résultats de type 'Message' -->
        <div class="results-message">
            <h3>Résultat dans les Messages :</h3>
            <?php foreach ($results as $result) :
                if ($result->result_type == 'Message') : ?>
                    <div class="message">
                        <div class="titreSujet">
                            <h6><a href='index.php?controller=message&action=liste&id=<?= $result->result_id_sujet ?>'>Dans le sujet <?= $result->result_description ?></a></h6>
                        </div>
                        <div class="corpsmessage">
                            <?php
                            $pseudo = $result->result_user;
                            ?>
                            <div class='textMessage'>
                                <?= $result->result_title; ?>
                            </div>
                        </div>
                    </div>
            <?php endif;
            endforeach; ?>
        </div>
    </div>

<?php else : ?>
    <p>Aucun résultat trouvé.</p>
<?php endif; ?>