<?php
/**
 * @author Lexkalli
 */

class CardManager
{

    private $dbCardManager;

    public function __construct()
    {
        $this->dbCardManager = new DBCardManager();
    }

    /**
     * Récupère toutes les tâches via le DBCardManager.
     *
     * @return array Tableau d'objets Card
     */
    public function getAllTasks()
    {
        return $this->dbCardManager->getAllTasks();
    }
}
?>