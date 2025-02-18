<?php
/**
 * @author Lexkalli
 */

class DBCardManager
{
    /**
     * Récupère toutes les tâches depuis la base de données.
     *
     * @return array Tableau d'objets Card
     */
    public function getAllTasks()
    {
        // Récupération de l'instance PDO via le singleton DBConnection
        $db = DBConnection::getInstance();

        // Requête SQL avec jointure pour obtenir le login de l'utilisateur
        $sql = "SELECT 
                    t.pk_tache, 
                    t.nom, 
                    t.categorie, 
                    t.date_creation, 
                    t.date_echeance, 
                    t.priorite, 
                    u.nom AS utilisateurOrigineNom, 
                    u.prenom AS utilisateurOriginePrenom 
                FROM t_tache t
                LEFT JOIN t_utilisateur u ON t.fk_utilisateur_tache = u.pk_utilisateur";

        // Exécution de la requête
        $result = $db->selectQuery($sql, array());

        $tasks = array();
        foreach ($result as $row) {
            $tasks[] = new Card(
                (int) $row['pk_tache'],
                $row['nom'],
                $row['categorie'],
                new DateTime($row['date_creation']),
                !empty($row['date_echeance']) ? new DateTime($row['date_echeance']) : null,
                $row['priorite'],
                $row['utilisateurOrigineNom'],
                $row['utilisateurOriginePrenom']
            );
        }
        return $tasks;
    }

    /**
     * Met à jour une tâche identifiée par son nom actuel (originalTaskName).
     *
     * @param string $originalTaskName Le nom actuel de la tâche.
     * @param string $taskName Le nouveau nom de la tâche.
     * @param string $priority La nouvelle priorité.
     * @param string|null $dueDate La nouvelle date d'échéance (format "yyyy-MM-dd" ou null).
     * @return bool true si la mise à jour a réussi, false sinon.
     */
    public function updateTask($originalTaskName, $taskName, $priority, $dueDate)
    {
        $db = DBConnection::getInstance();

        if (empty(trim($dueDate))) {
            $dueDate = null;
        }

        $sql = "UPDATE t_tache SET nom = ?, priorite = ?, date_echeance = ? WHERE nom = ?";
        $params = array($taskName, $priority, $dueDate, $originalTaskName);
        $rowCount = $db->executeQuery($sql, $params);
        return $rowCount > 0;
    }

    /**
     * Met à jour une tâche identifiée par son nom actuel (originalTaskName).
     *
     * @param string $taskName Le nouveau nom de la tâche.
     * @param string $priority La nouvelle priorité.
     * @param string|null $dueDate La nouvelle date d'échéance (format "yyyy-MM-dd" ou null).
     * @return bool true si la mise à jour a réussi, false sinon.
     */
    public function addTask($taskName, $dueDate, $categorie, $priority, $userId)
    {
        $db = DBConnection::getInstance();

        $dateCreation = (new DateTime())->format("Y-m-d");
        if (empty(trim($dueDate))) {
            $dueDate = null;
        }

        $sql = "INSERT INTO t_tache (nom, date_creation, date_echeance, categorie, priorite, fk_utilisateur_tache) VALUES (?, ?, ?, ?, ?, ?)";
        $params = array($taskName, $dateCreation, $dueDate, $categorie, $priority, $userId);
        $rowCount = $db->executeQuery($sql, $params);
        return $rowCount > 0;
    }

    /**
     * Ajoute un commentaire associé à une tâche identifiée par son nom (originalTaskName).
     *
     * @param string $taskName Le nom actuel de la tâche.
     * @param Comment $comment L'objet Comment à ajouter.
     * @return bool true si l'ajout du commentaire a réussi, false sinon.
     */
    public function addComment($taskName, Comment $comment, int $userId)
    {
        $db = DBConnection::getInstance();
        // Récupérer l'identifiant de la tâche à partir de son nom
        $sqlTask = "SELECT pk_tache FROM t_tache WHERE nom = ?";
        $row = $db->selectSingleQuery($sqlTask, array($taskName));
        if (!$row) {
            return false;
        }
        $taskId = $row['pk_tache'];

        $sql = "INSERT INTO t_commentaire (commentaire, date_creation, fk_utilisateur_commentaire, fk_tache) VALUES (?, ?, ?, ?)";
        $dateStr = $comment->getDate()->format("Y-m-d");
        $params = array($comment->getContenu(), $dateStr, $userId, $taskId);
        $rowCount = $db->executeQuery($sql, $params);
        return ($rowCount > 0);
    }

    public function deleteComments($taskName)
    {
        $db = DBConnection::getInstance();
        // Récupérer l'identifiant de la tâche à partir de son nom
        $sqlTask = "SELECT pk_tache FROM t_tache WHERE nom = ?";
        $row = $db->selectSingleQuery($sqlTask, array($taskName));
        if (!$row) {
            // Si la tâche n'existe pas, on considère qu'il n'y a rien à supprimer
            return true;
        }
        $taskId = $row['pk_tache'];

        // Supprimer tous les commentaires associés à cette tâche
        $sql = "DELETE FROM t_commentaire WHERE fk_tache = ?";
        // On n'a pas besoin de vérifier le rowCount ici : 0 est acceptable s'il n'y a aucun commentaire
        $db->executeQuery($sql, array($taskId));
        return true;
    }

    public function deleteTask($taskName)
    {
        $db = DBConnection::getInstance();
        $sql = "DELETE FROM t_tache WHERE nom = ?";
        $rowCount = $db->executeQuery($sql, array($taskName));
        return $rowCount > 0;
    }

    /**
     * Récupère les commentaires associés à une tâche.
     *
     * @param string $taskId L'ID de la tâche.
     * @return array|false Un tableau contenant les commentaires ou false en cas d'erreur.
     */
    public function getComments($taskId)
    {
        $db = DBConnection::getInstance();
        $sql = "SELECT 
                c.pk_commentaire,
                c.commentaire, 
                c.date_creation, 
                u.nom AS auteurNom, 
                u.prenom AS auteurPrenom
            FROM t_commentaire c
            LEFT JOIN t_utilisateur u ON c.fk_utilisateur_commentaire = u.pk_utilisateur
            WHERE c.fk_tache = ?";
        $results = $db->selectQuery($sql, array($taskId));
        if ($results === false) {
            return false;
        }

        $comments = array();
        foreach ($results as $row) {
            // Créer un objet Comment à partir des données récupérées
            $auteur = $row['auteurNom'] . " " . $row['auteurPrenom'];
            $commentObj = new Comment($row['commentaire'], new DateTime($row['date_creation']), $auteur);
            $commentObj->setId($row['pk_commentaire']);
            $comments[] = $commentObj;
        }
        return $comments;
    }

    public function deleteComment($commentId)
    {
        $db = DBConnection::getInstance();
        $sql = "DELETE FROM t_commentaire WHERE pk_commentaire = ?";
        $rowCount = $db->executeQuery($sql, array($commentId));
        return ($rowCount > 0);
    }

}
?>