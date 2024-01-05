<?php

namespace App\Models;

use Exception;
use App\Core\DbConnect;

class SearchModel extends DbConnect
{
    public function search($searchQuery)
    {
        // Requête SQL pour rechercher dans les trois tables
        $this->request = $this->connection->prepare("
            (SELECT id AS result_id, titre AS result_title, description AS result_description, NULL AS result_date, 'Topic' AS result_type, NULL as result_user, NULL as result_id_sujet FROM topic WHERE titre LIKE :searchQuery OR description LIKE :searchQuery)
            UNION
            (SELECT id AS result_id, theme AS result_title, NULL AS result_description, date AS result_date, 'Sujet' AS result_type, NULL as result_user, NULL as result_id_sujet FROM sujet WHERE theme LIKE :searchQuery)
            UNION
            (SELECT m.id AS result_id, m.message AS result_title, s.theme AS result_description, m.date AS result_date, 'Message' AS result_type, u.pseudo AS result_user, id_sujet as result_id_sujet FROM message m
            INNER JOIN sujet s ON m.id_sujet = s.id
            INNER JOIN utilisateur u ON m.id_utilisateur = u.id
            WHERE m.message LIKE :searchQuery)
        ");
    
        // Liez les paramètres
        $searchParam = '%' . $searchQuery . '%';
        $this->request->bindParam(':searchQuery', $searchParam, \PDO::PARAM_STR);
    
        // Exécutez la requête
        $this->request->execute();
    
        // Récupérez les résultats
        $searchResults = $this->request->fetchAll();
    
        return $searchResults;
    }
    
}
