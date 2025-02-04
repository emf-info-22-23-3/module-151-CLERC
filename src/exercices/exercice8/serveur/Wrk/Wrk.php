<?php

class Wrk
{

    private $conn = null;

    public function __construct()
    {
        $this->conn = DBConfig::getInstance();
    }


    // Fonction pour obtenir toutes les équipes
    public function getEquipes()
    {
        return $this->conn->getEquipes();
    }

    // Fonction pour obtenir les joueurs d'une équipe donnée
    public function getJoueurs($equipeId)
    {
        return $this->conn->getJoueurs($equipeId);
    }
}
?>