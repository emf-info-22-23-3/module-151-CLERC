<?php
class Commentaire
{
    private $contenu;
    private $date;
    private $auteur;

    public function __construct(string $contenu, DateTime $date, string $auteur)
    {
        $this->contenu = $contenu;
        $this->date = $date;
        $this->auteur = $auteur;
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