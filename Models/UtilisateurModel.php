<?php

namespace App\Models;

use Exception;
use App\Core\DbConnect;
use App\Entities\Utilisateur;
use App\Models\MessagePriveeModel;

class UtilisateurModel extends DbConnect
{
    public function findByID($id)
    {
        $this->request = $this->connection->prepare("SELECT * FROM utilisateur WHERE id = :id");
        $this->request->bindParam(":id", $id);
        $this->request->execute();
        $utilisateur = $this->request->fetch();
        return $utilisateur;
    }

    public function findAll()
    {
        $this->request = "SELECT * FROM utilisateur";
        $result = $this->connection->query($this->request);
        $list = $result->fetchAll();
        return $list;
    }

    public function find($pseudo)
    {
        $this->request = $this->connection->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");
        $this->request->bindParam(":pseudo", $_SESSION['pseudo']);
        $this->request->execute();
        $utilisateur = $this->request->fetch();
        return $utilisateur;
    }

    public function countMessages($utilisateur)
    {
        $this->request = $this->connection->prepare("
SELECT COUNT(*) AS nombre_occurrences
FROM message
WHERE id_utilisateur = :id_utilisateur
");
        $this->request->bindParam(":id_utilisateur", $utilisateur->id);
        $this->request->execute();

        $result = $this->request->fetch();

        return $result;
    }


    public function findByPseudo($pseudo)
    {
        $this->request = $this->connection->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");
        $this->request->bindParam(":pseudo", $pseudo);
        $this->request->execute();
        $result = $this->request->fetch();
        return $result;
    }


    public function connect(Utilisateur $connect)
    {
        $pseudo = $connect->getPseudo();
        $request = $this->connection->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");
        $request->bindParam(":pseudo", $pseudo);
        $request->execute();
        $utilisateur = $request->fetch();
        //si l'utilisateur existe
        if ($utilisateur) {
            //vérification du mot de passe saisi
            if (password_verify($connect->getPassword(), $utilisateur->password)) {
                //on créer la session
                session_regenerate_id();
                $_SESSION['token'] = bin2hex(random_bytes(32));
                $_SESSION['id'] = $utilisateur->id;
                $_SESSION['pseudo'] = $utilisateur->pseudo;
                $_SESSION['statut'] = $utilisateur->statut;
                $_SESSION['valide'] = $utilisateur->valide;
                $_SESSION['banni'] = $utilisateur->banni;
                $id_utilisateur = $_SESSION['pseudo'];
                $messagePriveeModel = new MessagePriveeModel();
                $nouveauMessage = $messagePriveeModel->nouveauMessage($id_utilisateur);
                $_SESSION['nouveauMessage'] = $nouveauMessage;

                // Redirigez l'utilisateur après la connexion
                header('Location: index.php');
                exit;
            } else {
                //on affiche un messsage d'erreur
                echo "<div class='container text-danger'>Le mot de passe est incorrect</div>";
            }
        } else {
            echo "<div class='container text-danger'>L'utilisateur n'existe pas</div>";
        }
    }

    public function create(Utilisateur $utilisateur)
    {
        $pseudo = $utilisateur->getPseudo();
        $nom = $utilisateur->getNom();
        $prenom = $utilisateur->getPrenom();
        $email = $utilisateur->getEmail();
        $date_inscription = $utilisateur->getDateInscription();
        $password = $utilisateur->getPassword();
        $image = $utilisateur->getImage();
        $statut = $utilisateur->getStatut();
        $valide = $utilisateur->getValide();
        $banni = $utilisateur->getBanni();
        $checkPseudoQuery = $this->connection->prepare("SELECT COUNT(*) AS count FROM utilisateur WHERE pseudo = :pseudo");
        $checkPseudoQuery->bindValue(":pseudo", $pseudo);
        $checkPseudoQuery->execute();
        $pseudoCount = $checkPseudoQuery->fetchColumn();
        if ($pseudoCount > 0) {
            echo "<script>alert('Le pseudo existe déjà')</script>";
        }

        $email = $utilisateur->getEmail();
        $checkEmailQuery = $this->connection->prepare("SELECT COUNT(*) AS count FROM utilisateur WHERE email = :email");
        $checkEmailQuery->bindValue(":email", $email);
        $checkEmailQuery->execute();
        $emailCount = $checkEmailQuery->fetchColumn();
        if ($emailCount > 0) {
?><script>
                alert("L'adresse email est déjà utilisé")
            </script><?php
                        return;
                    }

                    $to = $utilisateur->getEmail();
                    $subject = "Inscription";
                    $message = "Bonjour " . $utilisateur->getPrenom() . ",\n\n" .
                        "Votre compte est en attente de validation\n" .
                        "Vous pourrez vous connecter à l'adresse suivante : https://www.cefii-developpements.fr/xavier1252/forum/index.php?controller=utilisateur&action=login\n\n" .
                        "Merci de votre inscription !";
                    $headers = "From: Forum\r\n" .
                        "X-Mailer: PHP/" . phpversion();

                    $newUserPseudo = $utilisateur->getPseudo();
                    $newUserSubject = "Nouvelle inscription sur le site";
                    $adminMessage = "Bonjour,\n\nUne nouvelle inscription a été effectuée sur le site.\n\nPseudo du nouvel inscrit : " . $newUserPseudo . "\n\nMerci.";
                    $adminEmailQuery = $this->connection->prepare("SELECT email FROM utilisateur WHERE statut = 'admin'");
                    $adminEmailQuery->execute();
                    $adminEmails = $adminEmailQuery->fetchAll();
                
                    foreach ($adminEmails as $adminEmail) {
                        $to = $adminEmail->email;
                        $adminMessage = "Bonjour,\n\nUne nouvelle inscription a été effectuée sur le site par l'utilisateur : " . $utilisateur->getPseudo() . "\n\nMerci.";
                        
                        if (mail($to, $newUserSubject, $adminMessage, $headers)) {
                            echo "<div class='container text-success'>Un e-mail a été envoyé aux administrateurs.</div>";
                        } else {
                            echo "<div class='container text-danger'>Une erreur s'est produite lors de l'envoi de l'e-mail à l'administrateur : $adminEmail. Veuillez réessayer.</div>";
                        }
                    }

                    if (mail($to, $subject, $message, $headers)) {
                        $this->request = $this->connection->prepare("INSERT INTO utilisateur VALUES (NULL, :pseudo, :nom, :prenom, :email, :date_inscription, :password, :image, :statut, :valide, :banni)");
                        $this->request->bindParam(":pseudo", $pseudo);
                        $this->request->bindValue(":nom", $nom);
                        $this->request->bindValue(":prenom", $prenom);
                        $this->request->bindValue(":password", $password);
                        $this->request->bindValue(":image", $image);
                        $this->request->bindValue(":email", $email);
                        $this->request->bindValue(":date_inscription", $date_inscription);
                        $this->request->bindValue(":statut", $statut);
                        $this->request->bindValue(":valide", $valide);
                        $this->request->bindValue(":banni", $banni);

                        $this->executeTryCatch();
                        echo "<div class='container text-success'>Un e-mail avec vos informations de connexion a été envoyé.</div>";
                    } else {
                        echo "<div class='container text-danger'>Une erreur s'est produite lors de l'envoi de l'e-mail. Veuillez réessayer.</div>";
                    }
                }

                public function update(int $id, Utilisateur $utilisateur)
                {
                    $this->request = $this->connection->prepare("UPDATE utilisateur
SET pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, date_inscription = :date_inscription, image = :image, statut = :statut
WHERE id = :id");
                    $this->request->bindValue(":id", $utilisateur->getId());
                    $this->request->bindValue(":pseudo", $utilisateur->getPseudo());
                    $this->request->bindValue(":nom", $utilisateur->getNom());
                    $this->request->bindValue(":prenom", $utilisateur->getPrenom());
                    $this->request->bindValue(":email", $utilisateur->getEmail());
                    $this->request->bindValue(":date_inscription", $utilisateur->getDateInscription());
                    $this->request->bindValue(":image", $utilisateur->getimage());
                    $this->request->bindValue(":statut", $utilisateur->getStatut());
                    $this->executeTryCatch();
                }

                public function deleteUtilisateur(int $id, Utilisateur $utilisateur)
                {
                    $this->request = $this->connection->prepare("DELETE FROM utilisateur WHERE id = :id");
                    $this->request->bindValue(":id", $id);
                    $this->executeTryCatch();
                }

                public function updateUser($id, $statut, $valide, $banni)
                {
                    $this->request = $this->connection->prepare("UPDATE utilisateur SET statut = :statut, valide = :valide, banni = :banni WHERE id = :id");
                    $this->request->bindParam(":id", $id);
                    $this->request->bindParam(":statut", $statut);
                    $this->request->bindParam(":valide", $valide);
                    $this->request->bindParam(":banni", $banni);
                    $this->executeTryCatch();
                }

                private function executeTryCatch()
                {
                    try {
                        $this->request->execute();
                    } catch (Exception $e) {
                        die('Erreur : ' . $e->getMessage());
                    }
                    //Ferme le curseur, permettant à la requête d'être de nouveau exécutée
                    $this->request->closeCursor();
                }
            }
