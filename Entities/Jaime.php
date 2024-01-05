<?php

namespace App\Entities;

class Sujet
{
    private $id;
    private $id_utilisateur;
    private $id_message;


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

    /**
     * Get the value of id_message
     */
    public function getId_message()
    {
        return $this->id_message;
    }

    /**
     * Set the value of id_message
     * 
     * @return self
     */
    public function setId_message($id_message)
    {
        $this->id_message = $id_message;

        return $this;
    }
}
