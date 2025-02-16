<?php
/**
 * @author Lexkalli
 */

class User
{
    private $id;
    private $login;
    private $password; // Doit contenir le mot de passe hashé

    public function __construct(int $id, string $login, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    // Setters (si nécessaire)
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
?>