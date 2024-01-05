<?php

namespace App\Models;

use Exception;
use App\Core\DbConnect;
use App\Entities\Topic;

class TopicModel extends DbConnect
{
    public function findAll()
    {
        $this->request = "SELECT topic.*, utilisateur.pseudo AS utilisateur_pseudo FROM topic
        LEFT JOIN utilisateur ON topic.id_utilisateur = utilisateur.id
        ORDER BY titre";
        $result = $this->connection->query($this->request);
        $list = $result->fetchAll();
        return $list;
    }

    public function find($topic)
    {
        $this->request = $this->connection->prepare("SELECT * FROM topic WHERE id = :id");
        $this->request->bindParam(":id", $_GET['id']);
        $this->request->execute();
        $utilisateur = $this->request->fetch();
        return $utilisateur;
    }

    public function create(Topic $topic)
    {
        $this->request = $this->connection->prepare("INSERT INTO topic VALUES (NULL, :titre, :description, :id_utilisateur)");
        $this->request->bindValue(":titre", $topic->getTitre());
        $this->request->bindValue(":description", $topic->getDescription());
        $this->request->bindValue(":id_utilisateur", $topic->getId_utilisateur());
        $this->executeTryCatch();
    }

    public function update(int $id, Topic $topic)
    {
        $this->request = $this->connection->prepare("UPDATE topic
        SET titre = :titre, description = :description, id_utilisateur = :id_utilisateur WHERE id = :id");
        $this->request->bindValue(":id", $topic->getId());
        $this->request->bindValue(":titre", $topic->getTitre());
        $this->request->bindValue(":description", $topic->getDescription());
        $this->request->bindValue(":id_utilisateur", $topic->getId_utilisateur());
        $this->executeTryCatch();
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
