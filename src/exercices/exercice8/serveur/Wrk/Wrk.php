<?php

class Wrk
{
    // Fonction pour obtenir toutes les équipes
    public function getEquipes()
    {
        $conn = DBConfig::getConnection();
        $sql = "SELECT * FROM t_equipe";
        $stmt = $conn->prepare($sql);
        $stmt->execute();  // Exécution de la requête

        $equipes = [];
        // Récupère toutes les lignes sous forme d'un tableau associatif
        while ($row = $stmt->fetch()) {
            $equipes[] = new Equipe($row['PK_equipe'], $row['Nom']);
        }

        // Ferme la connexion
        $conn = null;

        return $equipes;
    }

    // Fonction pour obtenir les joueurs d'une équipe donnée
    public function getJoueurs($equipeId)
    {
        $conn = DBConfig::getConnection();
        $sql = "SELECT * FROM t_joueur WHERE FK_equipe = ?";
        $stmt = $conn->prepare($sql);

        // Lier le paramètre à la requête préparée
        $stmt->bindParam(1, $equipeId, PDO::PARAM_INT);
        $stmt->execute();  // Exécution de la requête

        $joueurs = [];
        // Récupère toutes les lignes sous forme d'un tableau associatif
        while ($row = $stmt->fetch()) {
            $joueurs[] = new Joueur($row['PK_joueur'], $row['Nom'], $row['Points']);
        }

        // Ferme la connexion
        $conn = null;

        return $joueurs;
    }
}
?>