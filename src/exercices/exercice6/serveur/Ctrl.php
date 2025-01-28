<?php
class Ctrl
{
    public function getEquipes()
    {
        $wrk = new Wrk();
        $equipes = $wrk->getEquipesFromDB();
        return $equipes;
    }
}
?>