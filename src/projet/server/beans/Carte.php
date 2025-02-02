<?php
class Carte
{
    private $id;
    private $nom;
    private $categorie;
    private $dateCreation;
    private $dateEcheance;
    private $priorite;
    private $commentaires;
    private $utilisateurOrigine;

    public function __construct($id, $nom)
    {
        $this->id = $id;
        $this->nom = $nom;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }
}
?>