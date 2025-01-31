<?php

class Ctrl
{

    public function getEquipesXML()
    {
        $wrk = new Wrk();
        $equipes = $wrk->getEquipes();

        header("Content-Type: text/xml; charset=utf-8");
        echo '<equipes>';
        foreach ($equipes as $equipe) {
            echo '<equipe>';
            echo '<id>' . $equipe->getId() . '</id>';
            echo '<nom>' . $equipe->getNom() . '</nom>';
            echo '</equipe>';
        }
        echo '</equipes>';
    }

    public function getJoueursXML($equipeId)
    {
        $wrk = new Wrk();
        $joueurs = $wrk->getJoueurs($equipeId);

        header("Content-Type: text/xml; charset=utf-8");
        echo '<joueurs>';
        foreach ($joueurs as $joueur) {
            echo '<joueur>';
            echo '<id>' . $joueur->getId() . '</id>';
            echo '<nom>' . $joueur->getNom() . '</nom>';
            echo '<points>' . $joueur->getPoints() . '</points>';
            echo '</joueur>';
        }
        echo '</joueurs>';
    }
}
?>