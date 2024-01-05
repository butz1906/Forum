<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['pseudo']) && $_SESSION['valide'] == 1 && $_SESSION['banni'] == 0 && $_SESSION['token']) {
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='index.php'>Accueil</a></li>
            <li class="breadcrumb-item"><a href='index.php?controller=topic&action=liste'>Forum</a></li>
            <li class="breadcrumb-item"><a href='index.php?controller=sujet&action=liste&id=<?= $sujet->id_topic ?>'><?= $sujet->titre ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><a><?= $sujet->theme; ?></a></li>
        </ol>
    </nav>
    <h2 class='titrePage'><?= $title; ?></h2>
    <?php
    if (empty($listeMessage)) {
        echo "<p>Aucun message.</p>";
    } else {
        $messageCount = 0; // Initialisation du compteur

        foreach ($listeMessage as $message) {
            $messageCount++;
    ?>
            <div class="message">
                <div class='posthead'>
                    <span class='postdate'><i class="fa-regular fa-note-sticky"></i> <?= date('d-m-Y', strtotime($message->date)); ?></span>
                    <span class='postcount'>#<?= $messageCount ?></span>
                </div>
                <div class="corpsmessage">
                    <?php
                    $pseudo = isset($message->pseudo) ? $message->pseudo : 'Utilisateur inconnu';
                    $idUtilisateur = $message->id_utilisateur;
                    ?>
                    <div class='profilMessage'>
                        <?php
                        if ($_SESSION['pseudo'] == $pseudo) {
                        ?> <h4><?= $pseudo; ?></h4>
                            <a style='padding-bottom: .5em; font-size: x-small; color:#333' href='index.php?controller=utilisateur&action=profil'>Votre profil</a>
                        <?php
                        } else { ?>
                            <h4><?= $pseudo; ?></h4>
                            <a style='padding-bottom: .5em; font-size: x-small; color:#333' href='index.php?controller=messagePrivee&action=echange&id=<?= $message->id_utilisateur ?>&pseudo=<?= $pseudo; ?>'>Envoyer un MP</a>
                        <?php } ?>
                        <div class="image-profil">
                            <img src="<?php echo $message->image; ?>" alt="Image de profil">
                        </div>
                        <?php
                        $userMessageCount = 0;
                        foreach ($messageCounts as $count) {
                            if ($count['id_utilisateur'] == $message->id_utilisateur) {
                                $userMessageCount = $count['count'];
                                break;
                            }
                        }
                        // Afficher le compteur
                        echo "<p>Post(s) : $userMessageCount</p>";

                        ?>
                    </div>
                    <div class='textMessage'>
                        <?= $message->message; ?>
                        <div class='edit'>
                            <div class='button'>
                                <?php
                                if ($_SESSION['id'] == $message->id_utilisateur) {
                                    echo '<a href="index.php?controller=message&action=modifier&id=' . $message->id . '" class="btn btn-secondary">éditer</a>';
                                }
                                if (isset($_SESSION['statut']) && $_SESSION['statut'] === 'admin') {
                                    echo '<a href="index.php?controller=message&action=supprimerMessage&id=' . $message->id . '" onclick="return deleteMessage()" class="buttonDelete">X</a>';
                                }
                                ?>
                            </div>
                            <?php
                            if ($message->edit == 1) {
                                echo '<div class="dateEdit">Message edité le : ';
                                echo date('d-m-Y', strtotime($message->date_edition)) . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="jaime">
                    <button class="btn btn-outline like-button" id="like-button-<?= $message->id ?>" data-message-id="<?= $message->id ?>">J'aime</button>
                    <div class='count'><span class="like-count-<?= $message->id ?>"><?= $likeCounts[$message->id] ?></span> <i class="fa-regular fa-thumbs-up"></i></div>
                </div>
            </div>

<?php
        }
        if ($totalPages > 1) {
            // Afficher la pagination uniquement si le nombre total de pages est supérieur à 1
            echo '<div class="pagination">';

            if ($pageCourante > 1) {
                // Lien pour aller à la première page si on n'est pas déjà sur la première page
                echo '<a href="index.php?controller=message&action=liste&id=' . $id_sujet . '&page=1">Première page</a>';

                // Lien pour aller à la page précédente si on n'est pas sur la première page
                echo '<a href="index.php?controller=message&action=liste&id=' . $id_sujet . '&page=' . ($pageCourante - 1) . '">Précédent</a>';
            }

            // Affichage des liens de pages
            for ($page = 1; $page <= $totalPages; $page++) {
                if ($page == $pageCourante) {
                    echo '<span class="current">' . $page . '</span>';
                } else {
                    echo '<a href="index.php?controller=message&action=liste&id=' . $id_sujet . '&page=' . $page . '">' . $page . '</a>';
                }
            }

            if ($pageCourante < $totalPages) {
                // Lien pour aller à la page suivante si on n'est pas sur la dernière page
                echo '<a href="index.php?controller=message&action=liste&id=' . $id_sujet . '&page=' . ($pageCourante + 1) . '">Suivant</a>';
                // Lien pour aller à la dernière page
                echo '<a href="index.php?controller=message&action=liste&id=' . $id_sujet . '&page=' . $totalPages . '">Dernière page</a>';
            }

            echo '</div>';
        }
    }

    echo $addForm;
} else {
    $title = "Forum - Accueil";
} ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const likeButtons = document.querySelectorAll('.like-button');
        likeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const messageId = button.getAttribute('data-message-id');
                likeMessage(messageId);
            });
        });
    });

    function likeMessage(messageId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?controller=message&action=like', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Mettre à jour le compteur de "j'aime" côté client
                    const likeCountSpan = document.querySelector('.like-count-' + messageId + '');
                    const currentLikes = parseInt(likeCountSpan.textContent);
                    likeCountSpan.textContent = response.like_count; // Mise à jour avec la nouvelle valeur

                    // Mettre à jour le nombre de "j'aime" en fonction de la réponse du serveur
                    if (response.action === 'like') {
                        likeCountSpan.textContent = currentLikes + 1;
                    } else if (response.action === 'unlike') {
                        likeCountSpan.textContent = currentLikes - 1;
                    }
                } else {
                    // Gérer l'erreur si nécessaire
                }
                console.error('Erreur lors de l\'analyse de la réponse JSON :', error);
            }
        };
        xhr.send('message_id=' + messageId);
    }
</script>