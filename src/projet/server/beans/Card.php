<?php
class Carte
{
    private $id;
    private $nom;
    private $categorie;
    private $dateCreation;
    private $dateEcheance;
    private $priorite;
    private $utilisateurOrigine;

    public function __construct(
        int $id,
        string $nom,
        string $categorie,
        DateTime $dateCreation,
        ?DateTime $dateEcheance,
        string $priorite,
        string $utilisateurOrigine
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->categorie = $categorie;
        $this->dateCreation = $dateCreation;
        $this->dateEcheance = $dateEcheance;
        $this->priorite = $priorite;
        $this->utilisateurOrigine = $utilisateurOrigine;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    public function getDateEcheance(): ?DateTime
    {
        return $this->dateEcheance;
    }

    public function getPriorite(): string
    {
        return $this->priorite;
    }

    public function getUtilisateurOrigine(): string
    {
        return $this->utilisateurOrigine;
    }

    // Setters
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function setDateEcheance(?DateTime $dateEcheance): void
    {
        $this->dateEcheance = $dateEcheance;
    }

    public function setPriorite(string $priorite): void
    {
        $this->priorite = $priorite;
    }

    public function setUtilisateurOrigine(string $utilisateurOrigine): void
    {
        $this->utilisateurOrigine = $utilisateurOrigine;
    }
}
?>