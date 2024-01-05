<?php

namespace App\Controllers;

use App\Core\Form;
use App\Entities\Message;
use App\Models\MessageModel;
use App\Models\JaimeModel;
use App\Models\SujetModel;

class MessageController extends Controller
{
    public function liste()
    {
        $messageModel = new MessageModel();
        $titreSujet = new SujetModel();
        $id_sujet = $_GET['id'];
        $messagesParPage = 10; // Nombre de messages à afficher par page
        $totalMessages = $messageModel->countById($id_sujet);
        $totalPages = ceil($totalMessages / $messagesParPage);
        $pageCourante = isset($_GET['page']) ? $_GET['page'] : 1;
        $indiceDepart = ($pageCourante - 1) * $messagesParPage;
        $messageCounts = $messageModel->countMessagesByUser();
        $listeMessages = $messageModel->findById($id_sujet, $indiceDepart, $messagesParPage);
        $sujet = $titreSujet->findSujetById($_GET['id']);
        $title = $sujet->theme . ' - Page ' . $pageCourante;

        $likeCounts = [];

        foreach ($listeMessages as $message) {
            $likeCounts[$message->id] = $messageModel->countLike($message->id);
        }

        if (Form::validatePost($_POST, ['message'])) {
            $message = new Message();

            $id_utilisateur = intval($_SESSION['id']);
            //on l'hydrate
            $message->setMessage(htmlspecialchars($_POST['message']));
            $message->setDate(date('Y-m-d'));
            $message->setEdit();
            $message->setDate_edition(date('Y-m-d'));
            $message->setId_sujet(intval($_GET['id']));
            $message->setId_utilisateur($id_utilisateur);

            //on instancie le model
            $model = new MessageModel();
            $model->reponse($message);

            //on redirige l'utilisateur
            header("Location: " . $_SERVER['REQUEST_URI']);
        }

        $form = new Form;
        $form->startForm("#", "POST", ["id" => "nouveauMessage", "enctype" => "multipart/form-data"]);
        $form->addTextarea("message", "", ["id" => "message", "rows" => "5", "class" => "form-control"]);
        $form->addInput("submit", "repondre", ["value" => "Repondre", "class" => "mt-3 btn btn-primary"]);
        $form->endForm();

        $this->render('message/liste', [
            'listeMessage' => $listeMessages,
            'sujet' => $sujet,
            'title' => $title,
            'likeCounts' => $likeCounts,
            'addForm' => $form->getFormElements(),
            'totalPages' => $totalPages,
            'pageCourante' => $pageCourante,
            'id_sujet' => $id_sujet,
            'messageCounts' => $messageCounts,
        ]);
    }

    public function modifier($id)
    {
        // Récupérer le sujet à modifier depuis le modèle
        $messageModel = new MessageModel();
        $message = $messageModel->findMessageById($id);

        $form = new Form();
        $form->startForm("#", "POST", ["id" => "nouveauMessage", "enctype" => "multipart/form-data"]);
        $form->addTextarea("message", $message->message, ["id" => "message", "rows" => "5", "class" => "form-control"]);
        $form->addInput("submit", "repondre", ["value" => "Repondre", "class" => "mt-3 btn btn-primary"]);
        $form->endForm();

        if (Form::validatePost($_POST, ['message'])) {
            $messageMaj = new Message();
            $messageMaj->setId($id);
            $messageMaj->setMessage(htmlspecialchars($_POST['message']));
            $messageMaj->setEdit('1');
            $messageMaj->setDate_edition(date('Y-m-d'));
            $messageModel->update($messageMaj);

            // Rediriger vers la page de détails du sujet modifié
            header("Location:index.php?controller=message&action=liste&id=" . $message->id_sujet);
            exit;
        }

        // Afficher le formulaire de modification avec les données du sujet et la liste des topics
        $this->render('message/modifier', ['messageForm' => $form->getFormElements()]);
    }

    public function supprimerMessage($id)
    {
        $messageModel = new MessageModel();
        $id = $_GET['id'];

        // Récupérer le message à supprimer
        $message = $messageModel->findMessageById($id);

        // Vérifier si le message a été trouvé
        if ($message) {
            // Récupérer la valeur de la colonne id_sujet du message
            $id_sujet = $message->id_sujet;

            // Supprimer le message de la base de données
            $messageModel->delete($id);

            // Rediriger l'utilisateur vers la page de la liste des messages du sujet après la suppression
            header("Location: index.php?controller=message&action=liste&id=$id_sujet");
            exit();
        } else {
            // Le message n'a pas été trouvé, vous pouvez gérer l'erreur ici
            // Par exemple, afficher un message d'erreur ou rediriger l'utilisateur vers une autre page
            echo "Le message à supprimer n'a pas été trouvé.";
            exit();
        }
    }

    public function like()
    {
        if (isset($_POST['message_id'])) {
            $messageId = intval($_POST['message_id']);
            $userId = intval($_SESSION['id']);

            $likeModel = new JaimeModel();

            // Vérifier si l'utilisateur a déjà liké ce message
            $existingLike = $likeModel->findLike($messageId, $userId);

            if ($existingLike) {
                // L'utilisateur a déjà liké ce message, supprimer le like
                $likeModel->deleteLike($messageId, $userId);
            } else {
                // L'utilisateur n'a pas encore liké ce message, ajouter le like
                $likeModel->addLike($messageId, $userId);
            }

            // Maintenant, mettez à jour le compteur de likes pour ce message
            $likeCount = $likeModel->countLikes($messageId);

            // Retournez la réponse JSON indiquant le nouveau compteur de likes
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'like_count' => $likeCount]);
        } else {
            // En cas de problème, retournez une réponse d'erreur JSON
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
    }
}
