<?php
class Card
{
    private int $id;
    private string $nom;
    private string $categorie;
    private DateTime $dateCreation;
    private ?DateTime $dateEcheance;
    private string $priorite;
    private string $utilisateurOrigine;
    private array $commentaires = [];

    public function __construct(
        int $id,
        string $nom,
        string $categorie,
        DateTime $dateCreation,
        ?DateTime $dateEcheance,
        string $priorite,
        string $utilisateurOrigine,
        array $commentaires = []
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->categorie = $categorie;
        $this->dateCreation = $dateCreation;
        $this->dateEcheance = $dateEcheance;
        $this->priorite = $priorite;
        $this->utilisateurOrigine = $utilisateurOrigine;
        $this->commentaires = $commentaires;
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

    public function getCommentaires(): array
    {
        return $this->commentaires;
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

    // Méthodes pour gérer les commentaires
    public function addCommentaire(Comment $commentaire): void
    {
        $this->commentaires[] = $commentaire;
    }

    public function removeCommentaire(int $index): void
    {
        if (isset($this->commentaires[$index])) {
            array_splice($this->commentaires, $index, 1);
        }
    }

    public function clearCommentaires(): void
    {
        $this->commentaires = [];
    }
}
?>