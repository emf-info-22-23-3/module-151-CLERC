<?php
/**
 * @author Lexkalli
 */

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
        $_SESSION['id'] = $user->getId();
        $_SESSION['name'] = $user->getName();
        $_SESSION['fullname'] = $user->getFullname();
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
        return isset($_SESSION['id']);
    }

    /**
     * Retourne le nom et le prénom de l'utilisateur de la session.
     *
     * @return string
     */
    public function getAuthor()
    {
        $name = $_SESSION['name'];
        $fullname = $_SESSION['fullname'];

        return $name . $fullname;
    }

    /**
     * Retourne l'id de l'utilisateur de la session.
     *
     * @return int
     */
    public function getId()
    {
        $id = $_SESSION['id'];

        return $id;
    }
}
?>