<?php
/**
 * Classe objet d'un commentaire
 * 
 * @author Lexkalli
 */

class Comment
{
    private $id;
    private $contenu;
    private $date;
    private $auteur;

    public function __construct(string $contenu, DateTime $date, string $auteur)
    {
        $this->contenu = $contenu;
        $this->date = $date;
        $this->auteur = $auteur;
    }

    // Setters
    public function setId($id): void
    {
        $this->id = $id;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }
}
?>