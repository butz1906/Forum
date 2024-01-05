<?php

namespace App\Controllers;

use App\Models\SearchModel;

class SearchController extends Controller
{
    public function results()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["q"])) {
                $searchQuery = $_POST["q"];
                $filters = isset($_POST["filter"]) ? $_POST["filter"] : array();

                // Effectuez la recherche et récupérez les résultats
                $searchModel = new SearchModel();
                $results = $searchModel->search($searchQuery, $filters);

                // Passez les résultats à la vue pour les afficher
                $this->render('search/results', ['results' => $results]);
            }
        }
    }
}
