<?php

namespace App\Controllers;

use App\Core\Form;
use App\Entities\Sujet;
use App\Models\SujetModel;
use App\Models\TopicModel;

class SujetController extends Controller
{
    public function liste()
    {
        $sujetModel = new SujetModel();
        $titre = new TopicModel();
        $id_topic = $_GET['id'];

        $titreTopic = $titre->find($id_topic);
        $listeSujets = $sujetModel->findById($id_topic);

        foreach ($listeSujets as $sujet) {
            $nombre_occurrences = $sujetModel->countMessage($sujet);
            $sujet->nombre_occurrences = $nombre_occurrences->nombre_occurrences;
        }

        $this->render('sujet/liste', [
            'listeSujets' => $listeSujets,
            'titreTopic' => $titreTopic
        ]);
    }

    public function add()
    {
        //on controle si les champs du formulaire sont remplis
        if (Form::validatePost($_POST, ['theme', 'id_topic'])) {
            $sujet = new Sujet();

            //on l'hydrate
            $sujet->setTheme(htmlspecialchars($_POST['theme']));
            $sujet->setDate(date('Y-m-d'));
            $sujet->setId_topic($_POST['id_topic']);
            $id_topic = $sujet->setId_topic($_POST['id_topic']);


            //on instancie le modèle "SujetModel"
            $model = new SujetModel();
            $model->create($sujet);

            //on redirige l'utilisateur vers la liste des sujets
            header("Location: index.php?controller=sujet&action=liste&id=" . $sujet->getId_topic());
            exit();
        } else {
            //on affiche un message d'erreur
            $erreur = !empty($_POST) ? "Le formulaire n'a pas été correctement rempli" : "";
        }


        //on instancie la classe Form pour construire le formulaire d'ajout
        $form = new Form();

        //on construit le formulaire d'ajout
        $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("theme", "Sujet", ["class" => "form-label"]);
        $form->addInput("text", "theme", ["id" => "theme", "class" => "form-control", "placeholder" => "Sujet"]);

        // Récupérer les options pour le champ select (id_topic)
        $topics = new TopicModel();
        $topicsListe = $topics->findAll();
        $options = [];
        foreach ($topicsListe as $topic) {
            $options[$topic->id] = $topic->titre;
        }

        $form->addSelect("id_topic", $options, [], ["id" => "topic", "class" => "mt-2 form-control"]);

        $form->addInput("submit", "add", ["value" => "Ajouter", "class" => "mt-1 btn btn-dark"]);
        $form->endForm();

        //on envoie le formulaire dans la vue add.php
        $this->render('sujet/add', ["addForm" => $form->getFormElements()]);
    }

    public function modifier($id)
    {
        // Récupérer le sujet à modifier depuis le modèle
        $sujetModel = new SujetModel();
        $sujet = $sujetModel->findSujetById($id);
        $topics = new TopicModel();

        $form = new Form();
        $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("theme", "Theme", ["class" => "mt-2 form-label"]);
        $form->addInput("text", "theme", ["id" => "theme", "class" => "form-control", "value" => $sujet->theme, "readonly" => "readonly"]);
        $form->addInput("date", "date", ["id" => "date", "class" => "form-control", "value" => $sujet->date, "hidden" => "hidden"]);

        // Récupérer les options pour le champ select (id_topic)
        $options = [];
        $topicsListe = $topics->findAll();
        foreach ($topicsListe as $topic) {
            $options[$topic->id] = $topic->titre;
        }

        $form->addSelect("id_topic", $options, [], ["id" => "id_topic", "class" => "mt-2 form-control"]);

        $form->addInput("submit", "update", ["value" => "Déplacer", "class" => "mt-1 btn btn-dark"]);
        $form->endForm();

        if (Form::validatePost($_POST, ['id_topic'])) {
            $sujetMaj = new Sujet();

            $sujetMaj->setId($_GET['id']);
            $sujetMaj->setTheme(htmlspecialchars($_POST['theme']));
            $sujetMaj->setDate($_POST['date']);
            $sujetMaj->setId_topic($_POST['id_topic']);
            $sujetModel->update($sujetMaj);

            // Rediriger vers la page de détails du sujet modifié
            header('Location: index.php?controller=sujet&action=liste&id=' . $_POST["id_topic"] . '');
            exit;
        }

        // Afficher le formulaire de modification avec les données du sujet et la liste des topics
        $this->render('sujet/modifier', ['sujetForm' => $form->getFormElements()]);
    }
}
