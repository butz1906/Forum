<?php

namespace App\Models;

use Exception;
use App\Core\DbConnect;
use App\Entities\Jaime;

class JaimeModel extends DbConnect
{
    public function findLike($messageId, $userId)
    {
        $this->request = $this->connection->prepare("SELECT * FROM jaime WHERE id_message = :messageId AND id_utilisateur = :userId");
        $this->request->bindParam(':messageId', $messageId);
        $this->request->bindParam(':userId', $userId);
        $this->request->execute();
        return $this->request->fetch();
    }

    public function addLike($messageId, $userId)
    {
        $this->request = $this->connection->prepare("INSERT INTO jaime (id_message, id_utilisateur) VALUES (:messageId, :userId)");
        $this->request->bindParam(':messageId', $messageId);
        $this->request->bindParam(':userId', $userId);
        $this->request->execute();
    }

    public function deleteLike($messageId, $userId)
    {
        $this->request = $this->connection->prepare("DELETE FROM jaime WHERE id_message = :messageId AND id_utilisateur = :userId");
        $this->request->bindParam(':messageId', $messageId);
        $this->request->bindParam(':userId', $userId);
        $this->request->execute();
    }

    public function countLikes($messageId)
    {
        $this->request = $this->connection->prepare("SELECT COUNT(*) AS like_count FROM jaime WHERE id_message = :messageId");
        $this->request->bindParam(':messageId', $messageId);
        $this->request->execute();
        $result = $this->request->fetch();
        return $result->like_count;
    }
}
