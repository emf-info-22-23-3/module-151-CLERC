<?php
require_once("./beans/Card.php");
require_once("./beans/Comment.php");
require_once("./beans/User.php");
require_once("./controllers/CardManager.php");
require_once("./controllers/UserManager.php");
require_once("./helpers/DBConnection.php");
require_once("./helpers/DBCardManager.php");
require_once("./helpers/DBUserManager.php");
require_once("./controllers/SessionManager.php");

// Vérifier que le paramètre 'action' soit là
$action = isset($_GET['action']) ? $_GET['action'] : "";

switch ($action) {
    case "getTasks":
        $cardManager = new CardManager();
        $tasks = $cardManager->getAllTasks();

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

    case "login":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifier que les identifiants sont bien fournis
            if (
                !isset($_POST['login']) || !isset($_POST['password']) ||
                empty($_POST['login']) || empty($_POST['password'])
            ) {
                echo json_encode(array("result" => false, "error" => "Identifiants incomplets"));
                break;
            }

            // Récupérer les identifiants envoyés en POST
            $login = $_POST['login'];
            $password = $_POST['password'];

            // Utiliser UserManager pour la connexion, qui appellera SessionManager en interne
            $userManager = new UserManager();
            if ($userManager->login($login, $password)) {
                echo json_encode(array("result" => true));
            } else {
                echo json_encode(array("result" => false, "error" => "Identifiants incorrects"));
            }
        }
        break;

    case "logout":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userManager = new UserManager();
            if ($userManager->logout()) {
                echo json_encode(array("result" => true));
            } else {
                echo json_encode(array("result" => false, "error" => "Impossible de déconnecter l'utilisateur car il n'est pas loggé."));
            }
        }
        break;

    case "createUser":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifier que les données sont bien fournis
            if (
                !isset($_POST['name']) || !isset($_POST['fullname']) || !isset($_POST['login']) || !isset($_POST['password']) ||
                empty($_POST['name']) || empty($_POST['fullname'] || empty($_POST['login']) || empty($_POST['password']))
            ) {
                echo json_encode(array("result" => false, "error" => "Un ou plusieurs champs ne sont pas renseignés."));
                break;
            }

            // Récupérer les identifiants envoyés en POST
            $name = $_POST['name'];
            $fullname = $_POST['fullname'];
            $login = $_POST['login'];
            $password = $_POST['password'];

            $userManager = new UserManager();
            if ($userManager->newUser($name, $fullname, $login, $password)) {
                echo json_encode(array("result" => true));
            } else {
                echo json_encode(array("result" => false, "error" => "Vous n'êtes pas connecté OU un utilisateur existant contient déjà ce login."));
            }
        }
        break;

    default:
        echo json_encode(array("error" => "Action non spécifiée ou inconnue"));
        break;
}
?>