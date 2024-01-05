<?php
namespace App\Entities;

class Utilisateur {
    private $id;
    private $pseudo;
    private $nom;
    private $prenom;
    private $email;
    private $date_inscription;
    private $password;
    private $image;
    private $statut;
    private $valide;
    private$banni;

    /**
     * Get the value of id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set the value of id
     * 
     * @return self
     */
    public function setId($id){
        $this->id = $id;

        return $this;
    }

        /**
     * Get the value of pseudo
     */
    public function getPseudo(){
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     * 
     * @return self
     */
    public function setPseudo($pseudo){
        $this->pseudo = $pseudo;

        return $this;
    }

        /**
     * Get the value of nom
     */
    public function getNom(){
        return $this->nom;
    }

    /**
     * Set the value of nom
     * 
     * @return self
     */
    public function setNom($nom){
        $this->nom = $nom;

        return $this;
    }

        /**
     * Get the value of prenom
     */
    public function getPrenom(){
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     * 
     * @return self
     */
    public function setPrenom($prenom){
        $this->prenom = $prenom;

        return $this;
    }

        /**
     * Get the value of email
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * Set the value of email
     * 
     * @return self
     */
    public function setEmail($email){
        $this->email = $email;

        return $this;
    }

        /**
     * Get the value of date_inscription
     */
    public function getDateInscription(){
        return $this->date_inscription;
    }

    /**
     * Set the value of date_inscription
     * 
     * @return self
     */
    public function setDateInscription($date_inscription){
        $this->date_inscription = date('Y-m-d');

        return $this;
    }

        /**
     * Get the value of password
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Set the value of password
     * 
     * @return self
     */
    public function setPassword($password){
        $this->password = $password;

        return $this;
    }

            /**
     * Get the value of image
     */
    public function getImage(){
        return $this->image;
    }

    /**
     * Set the value of image
     * 
     * @return self
     */
    public function setImage($image){
        $adjustedImagePath = str_replace('../', '', $image);

        $this->image = $adjustedImagePath;

        return $this;
    }

        /**
     * Get the value of statut
     */
    public function getStatut(){
        return $this->statut;
    }

    /**
     * Set the value of statut
     * 
     * @return self
     */
    public function setStatut($statut = 'user'){
        $this->statut = $statut;
    
        return $this;
    }

            /**
     * Get the value of valide
     */
    public function getValide(){
        return $this->valide;
    }

    /**
     * Set the value of valide
     * 
     * @return self
     */
    public function setValide($valide = 0){
        $this->valide = $valide;
    
        return $this;
    }

                /**
     * Get the value of banni
     */
    public function getBanni(){
        return $this->banni;
    }

    /**
     * Set the value of banni
     * 
     * @return self
     */
    public function setBanni($banni = 0){
        $this->banni = $banni;
    
        return $this;
    }
}
