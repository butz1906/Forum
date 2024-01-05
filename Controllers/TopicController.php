<?php

namespace App\Controllers;

use App\Core\Form;
use App\Entities\Topic;
use App\Models\TopicModel;
use App\Models\UtilisateurModel;


class TopicController extends Controller
{
    public function liste()
    {
        $topics = new TopicModel();

        $listeTopic = $topics->findAll();

        // Transmettez les données à la vue pour les afficher
        $this->render('topic/liste', ['topics' => $listeTopic]);
    }

    public function add()
    {

        if (isset($_SESSION['token'])) {

            //on controle si les champs du formulaires sont remplis
            if (Form::validatePost($_POST, ['titre', 'description'])) {
                $topic = new Topic();

                $id_utilisateur = intval($_SESSION['id']);
                //on l'hydrate
                $topic->setTitre(htmlspecialchars($_POST['titre']));
                $topic->setDescription(htmlspecialchars($_POST['description']));
                $topic->setId_utilisateur($id_utilisateur);


                //on instancie le model "creation"
                $model = new TopicModel();
                $model->create($topic);

                //on redirige l'utilisateur vers la liste des category
                header("Location:index.php?controller=topic&action=liste");
            } else {
                //on affiche un messsage d'erreur
                $erreur = !empty($_POST) ? "Le formulaire n'a pas été correctement rempli" : "";
            }


            //on instancie la classe Form pour construire le formulaire d'ajout
            $form = new Form();

            //on construit le formulaire d'ajout
            $form->startForm("#", "POST", ["entype" => "multipart/form-data"]);
            $form->addLabel("titre", "Titre", ["class" => "form-label"]);
            $form->addInput("text", "titre", ["id" => "titre", "class" => "form-control", "placeholder" => "Titre"]);
            $form->addLabel("description", "Description", ["class" => "form-label"]);
            $form->addTextarea("description", "", ["id" => "description", "class" => "form-control"]);
            $form->addInput("submit", "add", ["value" => "Ajouter", "class" => "mt-1 btn btn-dark"]);
            $form->endForm();

            //on envoie le formulaire dans la vue add.php
            $this->render('topic/add', ["addForm" => $form->getFormElements()]);
        } else {
            header('Location:index.php');
        }
    }


    public function modifier()
    {
        $topics = new TopicModel();
        $id = $_GET['id'];
        // On stocke dans une variable le retour de la méthode find()
        $titretopic = $topics->find($id);
        $utilisateurs = new UtilisateurModel();

        $form = new Form();

        // On construit le formulaire d'ajout
        $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("titre", "Titre", ["class" => "mt-2 form-label"]);
        $form->addInput("text", "titre", ["id" => "titre", "class" => "form-control", "value" => $titretopic->titre]);
        $form->addLabel("description", "Description", ["class" => "mt-2 form-label"]);
        $form->addTextarea("description", $titretopic->description, ["id" => "description", "class" => "form-control"]);

        // Récupérer les options pour le champ select (id_utilisateur)
        $options = [];
        $utilisateursListe = $utilisateurs->findAll();
        foreach ($utilisateursListe as $utilisateur) {
            $options[$utilisateur->id] = $utilisateur->pseudo;
        }

        $form->addSelect("id_utilisateur", $options, [], ["id" => "utilisateur", "class" => "mt-2 form-control"]);

        $form->addInput("submit", "add", ["value" => "Modifier", "class" => "mt-1 btn btn-dark"]);
        $form->endForm();

        if (Form::validatePost($_POST, ['titre', 'description', 'id_utilisateur'])) {
            $topic = new Topic();

            // On l'hydrate
            $topic->setId($titretopic->id);
            $topic->setTitre(htmlspecialchars($_POST['titre']));
            $topic->setDescription(htmlspecialchars($_POST['description']));
            $topic->setId_utilisateur($_POST['id_utilisateur']);


            // On récupère l'ID de l'utilisateur à partir du pseudo sélectionné
            $pseudo = $_POST['id_utilisateur'];
            $utilisateur = $utilisateurs->findByPseudo($pseudo);
            // On instancie le modèle "topic"
            $model = new TopicModel();
            $model->update($titretopic->id, $topic);
            header("Location: index.php?controller=topic&action=liste");
        }

        $this->render('topic/modifier', ['topicForm' => $form->getFormElements()]);
    }
}
