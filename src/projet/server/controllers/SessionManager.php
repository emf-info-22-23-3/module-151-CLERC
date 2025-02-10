<?php

// Démarrer la session s'il n'est pas déjà démarré
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SessionManager
{
    /**
     * Enregistre l'utilisateur en session.
     *
     * @param User $user L'objet utilisateur qui vient d'être authentifié.
     */
    public function login($user)
    {
        // Par exemple, nous stockons le mot de passe hashé et quelques informations utiles
        $_SESSION['logged'] = $user->getPassword();
        $_SESSION['user'] = array(
            "id" => $user->getId(),
            "login" => $user->getLogin()
        );
    }

    /**
     * Déconnecte l'utilisateur en supprimant les informations de session.
     */
    public function logout()
    {
        unset($_SESSION['logged']);
        unset($_SESSION['user']);
    }

    /**
     * Vérifie si l'utilisateur est connecté.
     *
     * @return bool
     */
    public function isLogged()
    {
        return isset($_SESSION['logged']);
    }
}
?>