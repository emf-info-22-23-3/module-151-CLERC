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

    public function updateTask($originalTaskName, $taskName, $priority, $dueDate, $comment, $userId)
    {
        $isUpdated = $this->dbCardManager->updateTask($originalTaskName, $taskName, $priority, $dueDate);

        $isCommentAdded = false;

        // Si un objet Comment est fourni, tenter de l'ajouter
        if ($comment instanceof Comment) {
            $isCommentAdded = $this->dbCardManager->addComment($taskName, $comment, $userId);
        }

        // Retourne true si l'update ou l'ajout du commentaire a réussi
        return $isUpdated || $isCommentAdded;
    }

    public function addTask($taskName, $priority, $dueDate, $comment, $userId)
    {
        $categorie = "todo";
        $isAdded = $this->dbCardManager->addTask($taskName, $dueDate, $categorie, $priority, $userId);

        $isCommentAdded = false;

        // Si un objet Comment est fourni, tenter de l'ajouter
        if ($comment instanceof Comment) {
            $isCommentAdded = $this->dbCardManager->addComment($taskName, $comment, $userId);
        }

        // Retourne true si l'update ou l'ajout du commentaire a réussi
        return $isAdded || $isCommentAdded;
    }

    public function deleteTask($taskName)
    {
        // Supprimer tous les commentaires associés à la tâche
        $this->dbCardManager->deleteComments($taskName);

        // Supprimer la tâche elle-même
        $isTaskDeleted = $this->dbCardManager->deleteTask($taskName);

        return $isTaskDeleted;
    }

}
?>