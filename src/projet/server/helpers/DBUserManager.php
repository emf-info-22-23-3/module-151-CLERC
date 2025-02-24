<?php
/**
 * Classe permettant la gestion des utilisateurs au niveau de la BD.
 * 
 * @author Lexkalli
 */

class DBUserManager
{
    /**
     * Récupère un utilisateur par son login.
     *
     * @param string $login
     * @return User|false
     */
    public function getUserByLogin($login)
    {
        $db = DBConnection::getInstance();
        $sql = "SELECT pk_utilisateur, nom, prenom, login, password FROM t_utilisateur WHERE login = ?";
        $result = $db->selectSingleQuery($sql, array($login));
        if ($result) {
            return new User(
                (int) $result['pk_utilisateur'],
                $result['nom'],
                $result['prenom'],
                $result['login'],
                $result['password'],
            );
        }
        return false;
    }

    /**
     * Ajoute un utilisateur à la BD, si aucun autre n'a le même login.
     *
     * @param string $name
     * @param string $fullname
     * @param string $login
     * @param string $password
     * @return User|false
     */
    public function addUser($name, $fullname, $login, $password)
    {
        $db = DBConnection::getInstance();

        // Vérifier si un utilisateur avec le même login existe déjà
        $existing = $db->selectSingleQuery("SELECT pk_utilisateur FROM t_utilisateur WHERE login = ?", array($login));
        if ($existing) {
            return false;
        }

        // Hasher le mot de passe pour le stocker de manière sécurisée
        $pepper = SecretPepper::getSecretPepper();
        $hashedPassword = password_hash($pepper . $password, PASSWORD_DEFAULT);

        // Préparer et exécuter la requête d'insertion
        $sql = "INSERT INTO t_utilisateur (nom, prenom, login, password) VALUES (?, ?, ?, ?)";
        $rowCount = $db->executeQuery($sql, array($name, $fullname, $login, $hashedPassword));

        if ($rowCount > 0) {
            $userId = $db->getLastId("t_utilisateur");

            // Exécuter une requête pour vérifier que l'ajout a complètement fonctionné, puis retourner un nouveau User
            $userData = $db->selectSingleQuery("SELECT pk_utilisateur, nom, prenom, login, password FROM t_utilisateur WHERE pk_utilisateur = ?", array($userId));
            if ($userData) {
                return new User(
                    (int) $userData['pk_utilisateur'],
                    $userData['nom'],
                    $userData['prenom'],
                    $userData['login'],
                    $userData['password'],
                );
            }
        }
        return false;
    }
}
?>