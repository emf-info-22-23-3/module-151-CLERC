<?php

// Quand même vérifier si une session n'existe pas déjà afin de ne pas générer d'erreurs ou d'avertissements 
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
        // Vider le tableau des variables de la session
        $_SESSION = array();

        // Détruire la session
        session_destroy();
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