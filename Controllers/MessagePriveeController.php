<?php

namespace App\Controllers;

use App\Core\Form;
use App\Entities\MessagePrivee;
use App\Models\MessagePriveeModel;

class MessagePriveeController extends Controller
{
    public function liste()
    {
        $messagePrivee = new MessagePriveeModel();
        $pseudo = $_SESSION['pseudo'];
        $listeMessagePrivee = $messagePrivee->find($pseudo);

        $this->render('messagePrivee/liste', ['listeMessagePrivee' => $listeMessagePrivee]);
    }

    public function echange($id_utilisateur)
    {
        $echange = new MessagePriveeModel();
        $id_utilisateur = $_GET['id'];
        $pseudo_envoi = $_GET['pseudo'];
        $echangePrivee = $echange->findById($id_utilisateur, $pseudo_envoi);
        
        $echange->updateLu($id_utilisateur);

        if (Form::validatePost($_POST, ['message'])) {
            $message = new MessagePrivee();

            $id_utilisateur = intval($_SESSION['id']);
            //on l'hydrate
            $message->setMessage(htmlspecialchars($_POST['message']));
            $message->setDate(date('Y-m-d'));
            $message->setLu();
            $message->setDestinataire($_GET['pseudo']);
            $message->setId_utilisateur($id_utilisateur);

            //on instancie le model
            $model = new MessagePriveeModel();
            $model->nouveauMessagePrivee($message);

            header("Location: " . $_SERVER['REQUEST_URI']);

        } else {
            //on affiche un messsage d'erreur
            $erreur = !empty($_POST) ? "Le formulaire n'a pas été correctement rempli" : "";
        }
        $form = new Form;
        $form->startForm("#", "POST", ["id" => "nouveauMessage", "enctype" => "multipart/form-data"]);
        $form->addTextarea("message", "", ["id" => "message", "rows" => "5", "class" => "form-control"]);
        $form->addInput("submit", "repondre", ["value" => "Repondre", "class" => "mt-3 btn btn-primary"]);
        $form->endForm();

        $this->render('messagePrivee/echange', [
            'echangePrivee' => $echangePrivee,
            'addForm' => $form->getFormElements(),
        ]);
    }
}
