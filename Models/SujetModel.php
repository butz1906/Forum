<?php

namespace App\Models;

use Exception;
use App\Core\DbConnect;
use App\Entities\Sujet;

class SujetModel extends DbConnect
{
    public function findById($id)
    {
        $this->request = $this->connection->prepare("SELECT * FROM sujet WHERE id_topic = :id ORDER BY theme ASC");
        $this->request->bindParam(":id", $id);
        $this->request->execute();
        $sujets = $this->request->fetchAll();
        return $sujets;
    }

    public function countMessage($sujet)
    {
        $this->request = $this->connection->prepare("
        SELECT COUNT(*) AS nombre_occurrences
        FROM message
        WHERE id_sujet = :id_sujet
        ");
        $this->request->bindParam(":id_sujet", $sujet->id);
        $this->request->execute();

        $result = $this->request->fetch();

        return $result;
    }

    public function create($sujet)
    {
        $this->request = $this->connection->prepare("INSERT INTO sujet VALUES (NULL, :theme, :date, :id_topic)");
        $this->request->bindValue(":theme", $sujet->getTheme());
        $this->request->bindValue(":date", $sujet->getDate());
        $this->request->bindValue(":id_topic", $sujet->getId_topic());
        $this->executeTryCatch();
    }

    public function findSujetById($id)
    {
        $this->request = $this->connection->prepare("SELECT sujet.*, topic.titre FROM sujet JOIN topic ON sujet.id_topic = topic.id WHERE sujet.id = :id");
        $this->request->bindParam(":id", $id);
        $this->request->execute();
        $sujet = $this->request->fetch();
        return $sujet;
    }

    public function update(Sujet $sujet)
    {
        $this->request = $this->connection->prepare("UPDATE sujet SET id_topic = :id_topic WHERE id = :id");
        $id = $sujet->getId();
        $id_topic = $sujet->getId_topic();
        $this->request->bindParam(":id", $id);
        $this->request->bindParam(":id_topic", $id_topic);
        $this->executeTryCatch();
    }

    public function findTop10TopicsByMessageCount()
    {
        $this->request = $this->connection->prepare("
            SELECT 
                s.*,
                t.titre AS topic_title,
                COUNT(m.id) AS message_count
            FROM 
                sujet s
            LEFT JOIN 
                message m ON s.id = m.id_sujet
            LEFT JOIN
                topic t ON s.id_topic = t.id
            GROUP BY 
                s.id
            ORDER BY 
                message_count DESC
            LIMIT 10
        ");
        $this->request->execute();
        $topics = $this->request->fetchAll();
        return $topics;
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
