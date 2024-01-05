<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\SujetModel;
use App\Models\TopicModel;
use App\Models\UtilisateurModel;

class HomeController extends Controller
{
    public function index()
    {
        $messageModel = new MessageModel();
        $sujetModel = new SujetModel();
        $topics = new TopicModel();
        $utilisateurs = new UtilisateurModel();

        $latestMessages = $messageModel->findLatestMessages(10);

        $top10Sujet = $sujetModel->findTop10TopicsByMessageCount();

        $listeTopic = $topics->findAll();


        // Créez un tableau pour stocker les données des topics avec les pseudos correspondants
        $topicsAvecPseudos = [];

        // Parcourir la liste des topics
        foreach ($listeTopic as $value) {
            $utilisateur = $utilisateurs->findByID($value->id_utilisateur);
            $pseudo = $utilisateur->pseudo; // Utilisez la syntaxe d'objet pour accéder à la propriété "pseudo"

            $topicsAvecPseudos[] = [
                'id' => $value->id,
                'titre' => $value->titre,
                'description' => $value->description,
                'pseudo' => $pseudo
            ];
        }

        $this->render('home/index', [
            'latestMessages' => $latestMessages,
            'top10Sujet' => $top10Sujet,
            'topics' => $topicsAvecPseudos
        ]);
    }
}
