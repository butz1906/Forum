<?php

namespace App\Controllers;

use App\Middleware\MessagePriveeMiddleware; // Importez le middleware
use App\Models\MessagePriveeModel;


abstract class Controller
{
    public function render(string $path, array $data = [])
    {

        if (isset($_SESSION['id'])) {
            $middleware = new MessagePriveeMiddleware();
            $middleware->handle();

            // Mettez à jour $_SESSION['nouveauMessage'] en fonction des changements dans la base de données
            $id_utilisateur = $_SESSION['pseudo'];
            $messagePriveeModel = new MessagePriveeModel();
            $nouveauMessage = $messagePriveeModel->nouveauMessage($id_utilisateur);
            $_SESSION['nouveauMessage'] = $nouveauMessage;
        }

        // Permet d'extraire les données récupérées sous forme de variables
        extract($data);

        // On crée le buffer de sortie
        ob_start();

        // Crée le chemin et inclut le fichier de la vue souhaitée
        include dirname(__DIR__) . '/Views/' . $path . '.php';

        // On vide le buffer dans les variables $title et $content
        $content = ob_get_clean();

        // On fabrique le "template"
        include dirname(__DIR__) . '/Views/base.php';
    }
}