<?php

namespace App\Models;

use Exception;
use App\Core\DbConnect;
use App\Entities\Message;

class MessageModel extends DbConnect
{
    public function findById($id_sujet, $indiceDepart, $messagesParPage)
    {
        $this->request = $this->connection->prepare("SELECT message.*, sujet.theme, utilisateur.pseudo, utilisateur.image, sujet.id_topic, topic.titre AS topic_titre
        FROM message 
        JOIN sujet ON message.id_sujet = sujet.id
        JOIN utilisateur ON message.id_utilisateur = utilisateur.id
        JOIN topic ON sujet.id_topic = topic.id
        WHERE message.id_sujet = :id
            ORDER BY message.id ASC
        LIMIT :indiceDepart, :messagesParPage        
        ");
    
        $this->request->bindParam(":id", $id_sujet);
        $this->request->bindParam(":indiceDepart", $indiceDepart, \PDO::PARAM_INT);
        $this->request->bindParam(":messagesParPage", $messagesParPage, \PDO::PARAM_INT);
        $this->request->execute();
        
        $messages = $this->request->fetchAll();
        
        return $messages;
    }

    public function countById($id_sujet)
    {
        $this->request = $this->connection->prepare("SELECT COUNT(*) AS total FROM message WHERE id_sujet = :id");
        $this->request->bindParam(":id", $id_sujet);
        $this->request->execute();
        $result = $this->request->fetch();
        $totalMessages = $result->total;
        return $totalMessages;
    }

    public function reponse(Message $message)
    {
        if (empty($message->getMessage())) {
            echo "<div class='container text-danger'>Votre réponse ne peut être vide</div>";
            return;
        } else {
            $this->request = $this->connection->prepare("INSERT INTO message VALUES (NULL, :message, :date, :edit, :date_edition, :id_sujet, :id_utilisateur)");
            $this->request->bindValue(":message", $message->getMessage());
            $this->request->bindValue(":date", $message->getDate());
            $this->request->bindValue(":edit", $message->getEdit());
            $this->request->bindValue(":date_edition", $message->getDate_edition());
            $this->request->bindValue(":id_sujet", $message->getId_sujet());
            $this->request->bindValue(":id_utilisateur", $message->getId_utilisateur());
            $this->executeTryCatch();
        }
    }

    public function findMessageById($id)
    {
        $this->request = $this->connection->prepare("SELECT * FROM message WHERE id = :id");
        $this->request->bindParam(":id", $id);
        $this->request->execute();
        $message = $this->request->fetch();
        return $message;
    }

    public function update(Message $message)
    {
        $this->request = $this->connection->prepare("UPDATE message SET message = :message, edit = :edit, date_edition = :date_edition WHERE id = :id");
        $id = $message->getId();
        $messageContent = $message->getMessage();
        $messageEdit = $message->getEdit();
        $messageDate_edition = $message->getDate_edition();

        $this->request->bindParam(":id", $id);
        $this->request->bindParam(":message", $messageContent);
        $this->request->bindParam(":edit", $messageEdit);
        $this->request->bindParam(":date_edition", $messageDate_edition);

        $this->executeTryCatch();
    }

    public function findLatestMessages($limit)
    {
        $this->request = $this->connection->prepare("
            SELECT 
                m.*, 
                u.pseudo AS username,
                s.theme AS topic_title
            FROM 
                message m
            INNER JOIN 
                utilisateur u ON m.id_utilisateur = u.id
            INNER JOIN
                sujet s ON m.id_sujet = s.id
            ORDER BY 
                m.date DESC
            LIMIT :limit
        ");
        $this->request->bindParam(":limit", $limit, \PDO::PARAM_INT);
        $this->request->execute();
        $messages = $this->request->fetchAll();
        return $messages;
    }

    public function delete($id)
    {
        $this->request = $this->connection->prepare("DELETE FROM message WHERE id = :id");
        $this->request->bindParam(":id", $id);
        $this->executeTryCatch();
    }

    public function countMessagesByUser()
    {
        $this->request = $this->connection->prepare("
        SELECT id_utilisateur, COUNT(*) as count
        FROM message
        GROUP BY id_utilisateur
    ");
        $this->request->execute();
        $messageCounts = $this->request->fetchAll(\PDO::FETCH_ASSOC);
        return $messageCounts;
    }

    public function countLike($messageId)
    {
        $this->request = $this->connection->prepare("SELECT COUNT(id_message) AS like_count FROM jaime WHERE id_message = :messageId");
        $this->request->bindParam(':messageId', $messageId);
        $this->request->execute();
        $likeCount = $this->request->fetch();
        return $likeCount->like_count;
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
