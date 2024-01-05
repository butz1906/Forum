<?php

namespace App\Entities;

class MessagePrivee
{
    private $id;
    private $message;
    private $date;
    private $lu;
    private $destinataire;
    private $id_utilisateur;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     * 
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     * 
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     * 
     * @return self
     */
    public function setDate($date)
    {
        $this->date = date('Y-m-d');

        return $this;
    }

    /**
     * Get the value of lu
     */
    public function getLu()
    {
        return $this->lu;
    }

    /**
     * Set the value of lu
     * 
     * @return self
     */
    public function setLu($lu = 0)
    {
        $this->lu = $lu;

        return $this;
    }

    /**
     * Get the value of destinataire
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    /**
     * Set the value of destinataire
     * 
     * @return self
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

        /**
     * Get the value of id_utilisateur
     */
    public function getId_utilisateur()
    {
        return $this->id_utilisateur;
    }

    /**
     * Set the value of id_utilisateur
     * 
     * @return self
     */
    public function setId_utilisateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }
}