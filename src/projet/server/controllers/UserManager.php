<?php
// controllers/UserManager.php

require_once(__DIR__ . '/../helpers/DBUserManager.php');
require_once(__DIR__ . '/../beans/User.php');
require_once(__DIR__ . '/../helpers/SessionManager.php');

class UserManager
{
    private $dbUserManager;
    private $sessionManager;

    public function __construct()
    {
        $this->dbUserManager = new DBUserManager();
        $this->sessionManager = new SessionManager();
    }

    /**
     * Vérifie les identifiants de l'utilisateur.
     *
     * @param string $login
     * @param string $password
     * @return User|false Retourne l'objet User si les identifiants sont valides, false sinon.
     */
    public function checkCredentials($login, $password)
    {
        $user = $this->dbUserManager->getUserByLogin($login);
        if ($user) {
            // Vérifier le mot de passe avec password_verify (les mots de passe doivent être hashés en DB)
            if (password_verify($password, $user->getPassword())) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Tente de connecter l'utilisateur.
     *
     * @param string $login
     * @param string $password
     * @return bool true si la connexion est réussie, false sinon.
     */
    public function login($login, $password)
    {
        $user = $this->checkCredentials($login, $password);
        if ($user) {
            // Utilisation de SessionManager pour enregistrer l'utilisateur en session
            $this->sessionManager->login($user);
            return true;
        }
        return false;
    }
}
?>