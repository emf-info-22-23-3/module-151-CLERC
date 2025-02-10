<?php
require_once("./beans/Card.php");
require_once("./beans/Comment.php");
require_once("./beans/User.php");
require_once("./controllers/CardManager.php");
require_once("./controllers/UserManager.php");
require_once("./helpers/DBConnection.php");
require_once("./helpers/DBCardManager.php");
require_once("./helpers/DBUserManager.php");

// Vérifier le paramètre 'action'
$action = isset($_GET['action']) ? $_GET['action'] : "";

switch ($action) {
    case "getTasks":
        $cardManager = new CardManager();
        $tasks = $cardManager->getAllTasks();

        // Convertir les objets Card en tableau associatif pour l'encodage JSON
        $tasksArray = array();
        foreach ($tasks as $task) {
            $tasksArray[] = array(
                "id" => $task->getId(),
                "nom" => $task->getNom(),
                "categorie" => $task->getCategorie(),
                "dateCreation" => $task->getDateCreation()->format("d.m.Y"),
                "dateEcheance" => $task->getDateEcheance() ? $task->getDateEcheance()->format("d.m.Y") : null,
                "priorite" => $task->getPriorite(),
                "utilisateurOrigine" => $task->getUtilisateurOrigine()
            );
        }

        echo json_encode($tasksArray);
        break;

    // Vous pouvez ajouter d'autres cas ici pour d'autres actions (création, modification, etc.)

    default:
        echo json_encode(array("error" => "Action non spécifiée ou inconnue"));
        break;
}
?>