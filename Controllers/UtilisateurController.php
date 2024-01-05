<?php

namespace App\Controllers;


use App\Core\Form;
use App\Entities\Utilisateur;
use App\Models\UtilisateurModel;
use App\Models\MessagePriveeModel;

class UtilisateurController extends Controller
{
    public function index()
    {
        if (isset($_SESSION['token']) && $_SESSION['statut'] == 'admin') {
            $utilisateurs = new UtilisateurModel();

            $list = $utilisateurs->findAll();

            $this->render('utilisateur/index', ['list' => $list]);
        } else {
            header('Location:index.php');
        }
    }

    public function add()
    {
        // On vérifie si les champs du formulaire sont remplis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $utilisateur = new Utilisateur();

            // On vérifie si un fichier d'image a été sélectionné
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Un fichier d'image a été sélectionné, traitons l'image...

                // Vérifier le type de fichier
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $imageExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (!in_array($imageExtension, $allowedExtensions)) {
                    echo "<Seul les images au format .jpg, .jpeg ou .png sont acceptées.";
                    return;
                }

                // Vérifier la taille du fichier
                $maxFileSize = 2 * 1024 * 1024;
                if ($_FILES['image']['size'] > $maxFileSize) {
                    echo "Votre fichier doit faire moins de 2Mo";
                    return;
                }
                // Récupérer le chemin temporaire de l'image uploadée
                $imageTempPath = $_FILES['image']['tmp_name'];

                // Définir l'emplacement permanent où l'image sera stockée
                $imageName = basename($_FILES['image']['name']);
                $imagePath = "../public/images/" . $imageName;

                // Déplacer l'image du répertoire temporaire vers l'emplacement permanent
                if (move_uploaded_file($imageTempPath, $imagePath)) {
                    // L'upload de l'image s'est effectué avec succès

                    // Assurez-vous de stocker le chemin de l'image dans votre modèle d'utilisateur
                    $utilisateur->setImage($imagePath);
                } else {
                    echo "Une erreur s'est produite avec votre image.<br/>Assurez-vous qu'elle soit au format .jpg, .jpeg ou .pgn et ne pèse pas plus de 2Mo.";
                    return;
                }
            } else {
                $utilisateur->setImage('public/images/default.jpg');
            }

            // On hydrate l'objet utilisateur
            $utilisateur->setPseudo(htmlspecialchars($_POST['pseudo']));
            $utilisateur->setNom(htmlspecialchars($_POST['nom']));
            $utilisateur->setPrenom(htmlspecialchars($_POST['prenom']));
            $utilisateur->setPassword(htmlspecialchars(password_hash($_POST['password'], PASSWORD_BCRYPT)));
            $utilisateur->setEmail(htmlspecialchars($_POST['mail']));
            $utilisateur->setDateInscription(date('Y-m-d'));
            $utilisateur->setStatut();
            $utilisateur->setValide();
            $utilisateur->setBanni();

            // On instancie le modèle "utilisateur"
            $model = new UtilisateurModel();
            $model->create($utilisateur);
        }
        // On instancie la classe Form pour construire le formulaire d'ajout
        $form = new Form();

        // On construit le formulaire d'ajout
        $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("pseudo", "Pseudo", ["class" => "form-label"]);
        $form->addInput("text", "pseudo", ["id" => "pseudo", "class" => "form-control", "placeholder" => "Votre pseudo"]);
        $form->addLabel("nom", "Nom", ["class" => "form-label"]);
        $form->addInput("text", "nom", ["id" => "nom", "class" => "form-control", "placeholder" => "Votre nom"]);
        $form->addLabel("prenom", "Prenom", ["class" => "form-label"]);
        $form->addInput("text", "prenom", ["id" => "prenom", "class" => "form-control", "placeholder" => "Votre prénom"]);
        $form->addLabel("password", "Password", ["class" => "form-label"]);
        $form->addInput("password", "password", ["id" => "password", "class" => "form-control", "placeholder" => "Votre mot de passe"]);
        $form->addLabel("mail", "Mail", ["class" => "form-label"]);
        $form->addInput("email", "mail", ["id" => "mail", "class" => "form-control", "placeholder" => "Votre adresse mail"]);
        $form->addLabel("image", "Image de profil<br/><p class='text-danger'>Jpg, Jpeg ou PNG uniquement</br>Max 2Mo.</p>", ["class" => "mt-4 form-label"]);
        $form->addInput("file", "image", ["id" => "image", "class" => "form-control-file"]);
        $form->addInput("submit", "add", ["value" => "S'inscrire", "class" => "mt-1 btn btn-dark"]);
        $form->endForm();

        // On envoie le formulaire dans la vue add.php
        $this->render("utilisateur/add", ["addForm" => $form->getFormElements()]);
    }

    public function login()
    {
        if (Form::validatePost($_POST, ['pseudo', 'password'])) {
            //on instancie le model Utilisateur
            $connection = new Utilisateur();
            //on l'hydrate
            $connection->setPseudo($_POST['pseudo']);
            $connection->setPassword($_POST['password']);

            $model  = new UtilisateurModel();

            $model->connect($connection);
        }
        //on instancie la classe Form pour construire le formulaire de connection
        $form = new Form();

        //on construit le formulaire d'ajout
        $form->startForm("#", "POST", ["entype" => "multipart/form-data"]);
        $form->addLabel("pseudo", "Pseudo", ["class" => "form-label"]);
        $form->addInput("text", "pseudo", ["id" => "pseudo", "class" => "form-control", "placeholder" => "Pseudo"]);
        $form->addLabel("password", "Mot de passe", ["class" => "form-label"]);
        $form->addInput("password", "password", ["id" => "password", "class" => "form-control", "placeholder" => "Mot de passe"]);
        $form->addInput("submit", "connect", ["value" => "Connexion", "class" => "mt-1 btn btn-dark"]);
        $form->endForm();
        //on envoie le formulaire dans la vue index.php
        $this->render('utilisateur/login', ["loginForm" => $form->getFormElements()]);
    }

    public function logout()
    {
        session_destroy(); // Détruit toutes les données associées à la session
        header('Location:index.php');
        exit();


        $this->render('utilisateur/logout');
    }

    public function profil()
    {
        $utilisateurs = new UtilisateurModel();

        //on stocke dans une variable le return de la methode find()
        $profil = $utilisateurs->find($_SESSION['pseudo']);

        if (Form::validatePost($_POST, ['nom', 'prenom', 'mail'])) {
            $utilisateur = new Utilisateur();

            // Hydrater les propriétés de l'utilisateur
            $utilisateur->setId($profil->id);
            $utilisateur->setPseudo(htmlspecialchars($profil->pseudo));
            $utilisateur->setNom(htmlspecialchars($_POST['nom']));
            $utilisateur->setPrenom(htmlspecialchars($_POST['prenom']));
            $utilisateur->setEmail(htmlspecialchars($_POST['mail']));
            $utilisateur->setDateInscription($profil->date_inscription);
            $utilisateur->setStatut($profil->statut);

            // Mettre à jour l'image de profil uniquement si un nouveau fichier a été sélectionné
            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                // Traiter le fichier d'image uploadé de la même manière que dans la fonction add()
                // Vérifier le type de fichier
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $imageExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (!in_array($imageExtension, $allowedExtensions)) {
                    echo "<Seul les images au format .jpg, .jpeg ou .png sont acceptées.";
                    return;
                }

                // Vérifier la taille du fichier
                $maxFileSize = 2 * 1024 * 1024;
                if ($_FILES['image']['size'] > $maxFileSize) {
                    echo "Votre fichier doit faire moins de 2Mo";
                    return;
                }

                $imageTempPath = $_FILES['image']['tmp_name'];
                $imageName = basename($_FILES['image']['name']);
                $imagePath = "public/images/" . $imageName;

                if (move_uploaded_file($imageTempPath, $imagePath)) {
                    $utilisateur->setImage($imagePath);
                } else {
                    echo "Une erreur s'est produite avec votre image.<br/>Assurez-vous qu'elle soit au format .jpg, .jpeg ou .pgn et ne pèse pas plus de 2Mo.";
                }
            } else {
                // Conserver l'image de profil existante sans modification
                $utilisateur->setImage($profil->image);
            }

            // Instancier le modèle "Utilisateur"
            $model = new UtilisateurModel();
            $model->update($profil->id, $utilisateur);

            // Rediriger l'utilisateur vers la page de profil
            header('Location:index.php?controller=utilisateur&action=profil');
        } else {
            //on affiche un message d'erreur
            $erreur = !empty($_POST) ? "Le formulaire n'a pas été correctement rempli" : "";
        }
        if (isset($_SESSION['token'])) {
            $form = new Form();

            //on construit le formulaire d'ajout
            $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
            $form->addLabel("nom", "Votre nom", ["class" => "form-label"]);
            $form->addInput("text", "nom", ["id" => "nom", "class" => "form-control", "value" => $profil->nom]);
            $form->addLabel("prenom", "Votre prenom", ["class" => "form-label"]);
            $form->addInput("text", "prenom", ["id" => "prenom", "class" => "form-control", "value" => $profil->prenom]);
            $form->addLabel("mail", "Votre adresse email", ["class" => "form-label"]);
            $form->addInput("email", "mail", ["id" => "mail", "class" => "form-control", "value" => $profil->email]);
            $form->addImage("image_profil", $profil->image, ["alt" => "Image de profil"]);
            $form->addLabel("image", "Image de profil", ["class" => "form-label"]);
            $form->addInput("file", "image", ["id" => "image", "class" => "form-control-file"]);
            $form->addInput("submit", "add", ["value" => "Mettre à jour", "class" => "mt-2 btn btn-dark"]);
            $form->endForm();

            $this->render('utilisateur/profil', ['profilForm' => $form->getFormElements()]);
        } else {
            header('Location:index.php');
        }
    }

    public function update()
    {
        $utilisateurs = new UtilisateurModel();


        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $statut = $_POST['statut'];
            $valide = $_POST['valide'];
            $banni = $_POST['banni'];

            $utilisateurs->updateUser($id, $statut, $valide, $banni);
        }
        header("Location: index.php?controller=utilisateur&action=index");
    }

    public function deleteUtilisateur()
    {
        if (isset($_SESSION['token']) && $_SESSION['statut'] == 'admin') {
            // Récupérer l'ID de l'utilisateur à supprimer depuis les paramètres de la requête
            $utilisateur = new Utilisateur();
            $id = $_GET['id'];

            // Effectuer la suppression de l'utilisateur avec l'ID correspondant
            // Assurez-vous d'utiliser les méthodes appropriées de votre modèle ou de votre couche d'accès aux données (DAO)
            $utilisateurModel = new UtilisateurModel();
            $utilisateurModel->deleteUtilisateur($id, $utilisateur);

            // Rediriger vers la page de la liste des utilisateurs après la suppression
            // Assurez-vous d'adapter l'URL en fonction de votre structure d'URL
            header("Location: index.php?controller=utilisateur&action=index");
        } else {
            header('Location:index.php');
        }
    }
}
