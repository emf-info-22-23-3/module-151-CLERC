<?php
/**
 * Classe permettant la gestion des cartes (et commentaires) au niveau de la BD.
 * 
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
        $db = DBConnection::getInstance();

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

        $result = $db->selectQuery($sql, array());

        // Retourner un tableau de Card avec les informations récupérées
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
     * Met à jour une tâche et potentiellement un commentaire dans la BD.
     *
     * @param string $taskId L'ID de la tâche.
     * @param string $taskName Le nouveau nom de la tâche.
     * @param string $priority La nouvelle priorité.
     * @param string|null $dueDate La nouvelle date d'échéance (format "yyyy-MM-dd" ou null).
     * @param string $userId l'ID de l'utilisateur du commentaire
     * @param string $comment Le nouveau commentaire à ajouter (peut être null s'il n'y en a pas)
     * @return bool true si la mise à jour ou l'ajout du commentaire a réussi, false sinon.
     */
    public function updateTask($taskId, $taskName, $priority, $dueDate, $userId, $comment)
    {
        $db = DBConnection::getInstance();
        try {
            $db->startTransaction();

            $sql = "UPDATE t_tache SET nom = ?, priorite = ?, date_echeance = ? WHERE pk_tache = ?";
            $params = array($taskName, $priority, $dueDate, $taskId);
            $rowCount = $db->executeQuery($sql, $params);

            // Vérifier s'il y a un commentaire à ajouter ou non
            $isCommentInserted = false;
            if ($comment instanceof Comment) {
                $sql = "INSERT INTO t_commentaire (commentaire, date_creation, fk_utilisateur_commentaire, fk_tache) VALUES (?, ?, ?, ?)";
                $dateStr = $comment->getDate()->format("Y-m-d");
                $params = array($comment->getContenu(), $dateStr, $userId, $taskId);
                $isCommentInserted = $db->executeQuery($sql, $params);
            }

            $db->commitTransaction();
            // Option A : retourner true uniquement si l'UPDATE a affecté au moins une ligne
            // Option B : retourner true si l'UPDATE ou l'insertion du commentaire a réussi
            return ($rowCount > 0) || $isCommentInserted;

        } catch (Exception $e) {
            $db->rollbackTransaction();
            return false;
        }
    }

    /**
     * Ajoute une tâche et potentiellement un commentaire à la BD
     *
     * @param string $taskName Le nouveau nom de la tâche.
     * @param string|null $dueDate La nouvelle date d'échéance (format "yyyy-MM-dd" ou null).
     * @param string $categorie La catégorie où la tâche va être ajoutée.
     * @param string $priority La nouvelle priorité.
     * @param string $userId L'ID de l'utilisateur.
     * @param string $dateCreation La date de création de la tâche.
     * @param string $comment Le nouveau commentaire à ajouter (peut être null)
     * @return bool true si l'ajout a réussi, false sinon.
     */
    public function addTask($taskName, $dueDate, $categorie, $priority, $userId, $dateCreation, $comment)
    {
        $db = DBConnection::getInstance();
        try {
            $db->startTransaction();

            $sql = "INSERT INTO t_tache (nom, date_creation, date_echeance, categorie, priorite, fk_utilisateur_tache) VALUES (?, ?, ?, ?, ?, ?)";
            $params = array($taskName, $dateCreation, $dueDate, $categorie, $priority, $userId);
            $rowCount = $db->executeQuery($sql, $params);

            if ($rowCount <= 0) {
                throw new Exception("Échec de l'insertion de la tâche");
            }
            // Récupérer l'ID de la dernière tâche ajoutée
            $lastId = $db->getLastId("t_tache");

            // Vérifier s'il y a un commentaire à ajouter ou non
            if ($comment instanceof Comment) {
                $sql = "INSERT INTO t_commentaire (commentaire, date_creation, fk_utilisateur_commentaire, fk_tache) VALUES (?, ?, ?, ?)";
                $dateStr = $comment->getDate()->format("Y-m-d");
                $params = array($comment->getContenu(), $dateStr, $userId, $lastId);
                $db->executeQuery($sql, $params);
            }

            $db->commitTransaction();
            return ($rowCount > 0);

        } catch (Exception $e) {
            $db->rollbackTransaction();
            return false;
        }
    }

    /**
     * Supprime un commentaire de la BD
     *
     * @param string $commentId L'ID du commentaire.
     * @return bool true si la suppression a réussi, false sinon.
     */
    public function deleteComment($commentId)
    {
        $db = DBConnection::getInstance();
        $sql = "DELETE FROM t_commentaire WHERE pk_commentaire = ?";
        $rowCount = $db->executeQuery($sql, array($commentId));
        return ($rowCount > 0);
    }

    /**
     * Supprime une tâche et tous les commentaires associés à celle-ci de la BD
     *
     * @param string $taskId L'ID de la tâche.
     * @return bool true si la suppression a réussi, false sinon.
     */
    public function deleteTask($taskId)
    {
        $db = DBConnection::getInstance();
        try {
            $db->startTransaction();

            // Supprimer tous les commentaires associés à cette tâche
            $sql = "DELETE FROM t_commentaire WHERE fk_tache = ?";
            $db->executeQuery($sql, array($taskId));

            // Supprimer la tâche
            $sql = "DELETE FROM t_tache WHERE pk_tache = ?";
            $rowCount = $db->executeQuery($sql, array($taskId));

            $db->commitTransaction();
            return $rowCount > 0;

        } catch (\Exception $e) {
            $db->rollbackTransaction();
            return false;
        }
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

    /**
     * Met à jour la catégorie d'une tâche dans la BD
     *
     * @param string $taskId L'ID de la tâche.
     * @param string $newCategory La catégorie souhaitée pour la tâche.
     * @return bool true si la modification a réussi, false sinon.
     */
    public function updateCategory($taskId, $newCategory)
    {
        $db = DBConnection::getInstance();
        $sql = "UPDATE t_tache SET categorie = ? WHERE pk_tache = ?";
        $params = array($newCategory, $taskId);
        $rowCount = $db->executeQuery($sql, $params);
        return $rowCount > 0;
    }

}
?>