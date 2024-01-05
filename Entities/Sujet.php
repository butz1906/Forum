<?php

namespace App\Entities;

class Sujet
{
    private $id;
    private $theme;
    private $date;
    private $id_topic;


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
     * Get the value of theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set the value of theme
     * 
     * @return self
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

        /**
     * Get the value of date
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * Set the value of date
     * 
     * @return self
     */
    public function setDate($date){
        $this->date = date('Y-m-d');

        return $this;
    }

    /**
     * Get the value of id_topic
     */
    public function getId_topic()
    {
        return $this->id_topic;
    }

    /**
     * Set the value of id_topic
     * 
     * @return self
     */
    public function setId_topic($id_topic)
    {
        $this->id_topic = $id_topic;

        return $this;
    }
}
