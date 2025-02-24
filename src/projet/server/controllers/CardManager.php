<?php
/**
 *  * Classe permettant la gestion des cartes (et commentaires).
 * 
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

    public function updateTask($taskId, $taskName, $priority, $dueDate, $comment, $userId)
    {
        // La date d'échéance peut être vide
        if (empty(trim($dueDate))) {
            $dueDate = null;
        }

        return $this->dbCardManager->updateTask(
            $taskId,
            $taskName,
            $priority,
            $dueDate,
            $userId,
            $comment
        );
    }

    public function addTask($taskName, $priority, $dueDate, $comment, $userId)
    {
        $categorie = "todo";
        $dateCreation = (new DateTime())->format("Y-m-d");

        if (empty(trim($dueDate))) {
            $dueDate = null;
        }

        return $this->dbCardManager->addTask(
            $taskName,
            $dueDate,
            $categorie,
            $priority,
            $userId,
            $dateCreation,
            $comment
        );
    }

    public function deleteTask($taskId)
    {
        return $this->dbCardManager->deleteTask($taskId);
    }

    /**
     * Récupère les commentaires d'une tâche à partir de son identifiant.
     *
     * @param string $taskId L'ID de la tâche.
     * @return array|false Un tableau de commentaires (chaque commentaire contient 'contenu', 'date' et 'auteur') ou false en cas d'erreur.
     */
    public function getComments($taskId)
    {
        return $this->dbCardManager->getComments($taskId);
    }

    public function deleteComment($commentId)
    {
        return $this->dbCardManager->deleteComment($commentId);
    }

    public function updateCategory($taskId, $newCategory)
    {
        return $this->dbCardManager->updateCategory($taskId, $newCategory);
    }


}
?>