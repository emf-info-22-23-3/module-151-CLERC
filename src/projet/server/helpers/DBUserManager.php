<?php

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
        $sql = "SELECT pk_utilisateur, login, password FROM t_utilisateur WHERE login = ?";
        $result = $db->selectSingleQuery($sql, array($login));
        if ($result) {
            return new User((int) $result['pk_utilisateur'], $result['login'], $result['password']);
        }
        return false;
    }
}
?>