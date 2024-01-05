<?php

namespace App\Entities;

class Message
{
    private $id;
    private $message;
    private $date;
    private $edit;
    private $date_edition;
    private $id_sujet;
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
     * Get the value of edit
     */
    public function getEdit()
    {
        return $this->edit;
    }

    /**
     * Set the value of edit
     * 
     * @return self
     */
    public function setEdit($edit = 0)
    {
        $this->edit = $edit;

        return $this;
    }

    /**
     * Get the value of date_edition
     */
    public function getDate_edition()
    {
        return $this->date_edition;
    }

    /**
     * Set the value of date_edition
     * 
     * @return self
     */
    public function setDate_edition($date_edition)
    {
        $this->date_edition = date('Y-m-d');

        return $this;
    }

    /**
     * Get the value of id_sujet
     */
    public function getId_sujet()
    {
        return $this->id_sujet;
    }

    /**
     * Set the value of id_sujet
     * 
     * @return self
     */
    public function setId_sujet($id_sujet)
    {
        $this->id_sujet = $id_sujet;

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