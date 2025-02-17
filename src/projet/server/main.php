<?php
require_once("./beans/Card.php");
require_once("./beans/Comment.php");
require_once("./beans/User.php");
require_once("./controllers/CardManager.php");
require_once("./controllers/UserManager.php");
require_once("./helpers/DBConnection.php");
require_once("./helpers/DBCardManager.php");
require_once("./helpers/DBUserManager.php");
require_once('./helpers/DBConfig.php');
require_once("./controllers/SessionManager.php");
require_once("./helpers/SecretPepper.php");

// Vérifier que le paramètre 'action' soit là
$action = isset($_GET['action']) ? $_GET['action'] : "";

switch ($action) {
    case "getTasks":
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

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
        }
        break;

    case "login":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Vérifier que les identifiants sont bien fournis
            if (
                !isset($_POST['login']) || !isset($_POST['password']) ||
                empty(trim($_POST['login'])) || empty(trim($_POST['password']))
            ) {
                echo json_encode(array("result" => false, "error" => "Identifiants incomplets"));
                break;
            }

            // Récupérer les identifiants envoyés en POST
            $login = $_POST['login'];
            $password = $_POST['password'];

            // Vérifier que $login ne contienne que des lettres sans espaces
            if (!preg_match('/^[A-Za-z]+$/', $login)) {
                echo json_encode(array("result" => false, "error" => "Le champ login ne doit contenir que des lettres sans espaces."));
                break;
            }

            // Utiliser UserManager pour la connexion, qui appellera SessionManager en interne
            $userManager = new UserManager();
            if ($userManager->login($login, $password)) {
                echo json_encode(array("result" => true, "login" => $login));
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

            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données sont bien fournis
                if (
                    !isset($_POST['name']) || !isset($_POST['fullname']) || !isset($_POST['login']) || !isset($_POST['password']) ||
                    empty(trim($_POST['name'])) || empty(trim($_POST['fullname'])) || empty(trim($_POST['login'])) || empty(trim($_POST['password']))
                ) {
                    echo json_encode(array("result" => false, "error" => "Un ou plusieurs champs ne sont pas renseignés."));
                    break;
                }

                // Récupérer les identifiants envoyés en POST
                $name = $_POST['name'];
                $fullname = $_POST['fullname'];
                $login = $_POST['login'];
                $password = $_POST['password'];

                // Vérifier que $login, $name et $fullname ne contiennent que des lettres sans espaces
                if (!preg_match('/^[A-Za-z]+$/', $login) || !preg_match('/^[A-Za-z]+$/', $name) || !preg_match('/^[A-Za-z]+$/', $fullname)) {
                    echo json_encode(array("result" => false, "error" => "Les champs login, nom et prénom ne doivent contenir que des lettres sans espaces."));
                    break;
                }

                $userManager = new UserManager();
                if ($userManager->newUser($name, $fullname, $login, $password)) {
                    echo json_encode(array("result" => true));
                } else {
                    echo json_encode(array("result" => false, "error" => "La base de données contient déjà un utilisateur avec ce login."));
                }
            } else {
                // Renvoyer un code HTTP 401 Unauthorized et un message JSON
                header('HTTP/1.1 401 Unauthorized');
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    case "isLogged":
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {
                echo json_encode(array("result" => true));
            } else {
                // Renvoyer un code HTTP 401 Unauthorized et un message JSON
                header('HTTP/1.1 401 Unauthorized');
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    case "updateTask":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données obligatoires sont bien fournis
                if (
                    !isset($_POST['taskName']) || !isset($_POST['priority']) ||
                    empty(trim($_POST['taskName'])) || empty(trim($_POST['priority']))
                ) {
                    echo json_encode(array("result" => false, "error" => "Les champs nom et priorité doivent être renseignés."));
                    break;
                }

                if (!isset($_POST['dueDate']) || !isset($_POST['newComment'])) {
                    echo json_encode(array("result" => false, "error" => "Champs manquants dans la requête."));
                    break;
                }

                // Récupérer les identifiants envoyés en POST
                $taskName = $_POST['taskName'];
                $priority = $_POST['priority'];
                $dueDate = $_POST['dueDate'];
                $newComment = $_POST['newComment'];

                $comment = null;
                if (!empty($newComment)) {
                    $author = $userManager->getAuthor();
                    $comment = new Comment($newComment, new DateTime(), $author);
                }

                $cardManager = new CardManager();
                $isUpdated = $cardManager->updateTask($taskName, $priority, $dueDate, $comment);

                if ($isUpdated) {
                    echo json_encode(array('result' => true));
                } else {
                    echo json_encode(array("error" => "Erreur lors de la modification de la tâche"));
                }
            }
        }
        break;

    default:
        echo json_encode(array("error" => "Action non spécifiée ou inconnue"));
        break;
}
?>