<?php

namespace App\Models;

use Exception;
use App\Core\DbConnect;
use App\Entities\MessagePrivee;

class MessagePriveeModel extends DbConnect
{
    public function find($pseudo)
    {
        $this->request = $this->connection->prepare("
            SELECT m.*, u.pseudo AS utilisateur_pseudo, 
                (CASE WHEN MIN(m.lu) = 0 THEN 0 ELSE 1 END) AS lu
            FROM message_privee AS m
            JOIN utilisateur AS u ON m.id_utilisateur = u.id
            WHERE m.destinataire = :destinataire
            GROUP BY utilisateur_pseudo
        ");
        $this->request->bindParam(":destinataire", $pseudo);
        $this->request->execute();
        $result = $this->request->fetchAll();
        return $result;
    }
    

    public function findById($id_utilisateur, $pseudo_envoi)
    {
        $this->request = $this->connection->prepare("
            SELECT m.*, u.pseudo AS utilisateur_pseudo
            FROM message_privee AS m
            JOIN utilisateur AS u ON m.id_utilisateur = u.id
            WHERE ((m.id_utilisateur = :current_user_id AND m.destinataire = :pseudo_envoi)
            OR (m.id_utilisateur = :id_utilisateur AND m.destinataire = :pseudo_session))

            SELECT message_privee.*, utilisateur.pseudo 
            FROM message_privee 
            JOIN utilisateur ON message_privee.id_utilisateur = utilisateur.id 
            WHERE ((message_privee.id_utilisateur = :current_user_id AND message_privee.destinataire = :pseudo_envoi) OR (message_privee.id_utilisateur = 1 AND message_privee.destinataire = 'carole'));
        ");
        $this->request->bindParam(":id_utilisateur", $id_utilisateur);
        $this->request->bindParam(":pseudo_envoi", $pseudo_envoi);
        $this->request->bindParam(":current_user_id", $_SESSION['id']);
        $this->request->bindParam(":pseudo_session", $_SESSION['pseudo']);
        $this->request->execute();
        $result = $this->request->fetchAll();

        return $result;
    }

    public function nouveauMessage($id_utilisateur)
    {
        $this->request = $this->connection->prepare("
            SELECT COUNT(*) AS count_unread
            FROM message_privee
            WHERE destinataire = :id_utilisateur AND lu = 0
            ");
        $this->request->bindParam(":id_utilisateur", $id_utilisateur);
        $this->request->execute();
        $result = $this->request->fetch();

        if ($result->count_unread > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLu($id_utilisateur)
    {
        $this->request = $this->connection->prepare("
                    UPDATE message_privee
                    SET lu = 1
                    WHERE id_utilisateur = :id_utilisateur
                ");
        $this->request->bindParam(":id_utilisateur", $id_utilisateur);
        $this->executeTryCatch();
    }

    public function nouveauMessagePrivee(MessagePrivee $message)
    {
        if (empty($message->getMessage())) {
            echo "<div class='container text-danger'>Votre réponse ne peut être vide</div>";
            return;
        } else {
            $this->request = $this->connection->prepare("INSERT INTO message_privee VALUES (NULL, :message, :date, :lu, :destinataire, :id_utilisateur)");
            $this->request->bindValue(":message", $message->getMessage());
            $this->request->bindValue(":date", $message->getDate());
            $this->request->bindValue(":lu", $message->getLu());
            $this->request->bindValue(":destinataire", $message->getDestinataire());
            $this->request->bindValue(":id_utilisateur", $message->getId_utilisateur());
            $this->executeTryCatch();

            // Récupérer l'adresse e-mail du destinataire à partir de la table "utilisateur"
            $destinataire = $message->getDestinataire();
            $emailDestinataire = $this->getEmailDestinataire($destinataire);
            if ($emailDestinataire !== false) {
                // Envoi de l'e-mail au destinataire
                $sujet = "Nouveau message privé de " . $_SESSION['pseudo'];
                $messageEmail = "Bonjour " . $destinataire . ",\n\nVous avez reçu un nouveau message privé de " . $_SESSION['pseudo'] . " :\n\n" . $message->getMessage() . "\n\nCliquez ici pour accéder au site : https://www.cefii-developpements.fr/xavier1252/forum/index.php";

                mail($emailDestinataire, $sujet, $messageEmail);
            } else {
                // Gérer le cas où l'adresse e-mail du destinataire n'a pas été trouvée
                echo "<div class='container text-danger'>L'adresse e-mail du destinataire n'a pas été trouvée</div>";
            }
        }
    }

    private function getEmailDestinataire($destinataire)
    {
        $this->request = $this->connection->prepare("SELECT email FROM utilisateur WHERE pseudo = :destinataire");
        $this->request->bindParam(":destinataire", $destinataire);
        $this->request->execute();
        $result = $this->request->fetchAll();

        // Vérifier si l'adresse e-mail a été trouvée et retourner sa valeur
        if ($result && isset($result[0]->email)) {
            return $result[0]->email;
            header("Location: " . $_SERVER['REQUEST_URI']);
        }

        return false; // Retourner false si l'adresse e-mail n'a pas été trouvée
    }


    private function executeTryCatch()
    {
        try {
            $this->request->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        //Ferme le curseur, permettant à la requête d'être de nouveau exécutée
        $this->request->closeCursor();
    }
}
