<?php

class Wrk
{

    public function getEquipes()
    {
        $conn = DBConfig::getConnection();
        $sql = "SELECT * FROM t_equipe";
        $result = $conn->query($sql);

        $equipes = [];
        while ($row = $result->fetch_assoc()) {
            $equipes[] = new Equipe($row['PK_equipe'], $row['Nom']);
        }
        $conn->close();

        return $equipes;
    }

    public function getJoueurs($equipeId)
    {
        $conn = DBConfig::getConnection();
        $sql = "SELECT * FROM t_joueur WHERE FK_equipe = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $equipeId);
        $stmt->execute();
        $result = $stmt->get_result();

        $joueurs = [];
        while ($row = $result->fetch_assoc()) {
            $joueurs[] = new Joueur($row['PK_joueur'], $row['Nom'], $row['Points']);
        }
        $conn->close();

        return $joueurs;
    }
}
?>