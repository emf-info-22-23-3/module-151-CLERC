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

    public function updateTask($taskId, $taskName, $priority, $dueDate, $comment, $userId)
    {
        $isTaskModified = $this->dbCardManager->updateTask($taskId, $taskName, $priority, $dueDate);

        $isCommentAdded = false;
        // Si un objet Comment est fourni, tenter de l'ajouter
        if ($comment instanceof Comment) {
            $isCommentAdded = $this->dbCardManager->addComment($taskId, $comment, $userId);
        }

        // Retourne true si l'update ou l'ajout du commentaire a réussi
        return $isTaskModified || $isCommentAdded;
    }

    public function addTask($taskName, $priority, $dueDate, $comment, $userId)
    {
        $categorie = "todo";
        $taskId = $this->dbCardManager->addTask($taskName, $dueDate, $categorie, $priority, $userId);
        $isTaskAdded = ($taskId !== false);

        $isCommentAdded = false;
        // Si un objet Comment est fourni, tenter de l'ajouter
        if ($isTaskAdded && $comment instanceof Comment) {
            $isCommentAdded = $this->dbCardManager->addComment($taskId, $comment, $userId);
        }

        // Retourne true si l'update ou l'ajout du commentaire a réussi
        return $isTaskAdded || $isCommentAdded;
    }

    public function deleteTask($taskId)
    {
        // Supprimer tous les commentaires associés à la tâche
        $this->dbCardManager->deleteComments($taskId);

        // Supprimer la tâche elle-même
        $isTaskDeleted = $this->dbCardManager->deleteTask($taskId);

        return $isTaskDeleted;
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

}
?>