<?php
/**
 * @author Lexkalli
 */

class User
{
    private $id;
    private $name;
    private $fullname;
    private $login;
    private $password;

    public function __construct(int $id, string $name, string $fullname, string $login, string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->fullname = $fullname;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }
}
?>