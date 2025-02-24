<?php
require_once("./beans/Card.php");
require_once("./beans/Comment.php");
require_once("./beans/User.php");
require_once("./controllers/CardManager.php");
require_once("./controllers/UserManager.php");
require_once("./controllers/SessionManager.php");
require_once("./helpers/DBConnection.php");
require_once("./helpers/DBCardManager.php");
require_once("./helpers/DBUserManager.php");
require_once('./helpers/DBConfig.php');
require_once("./helpers/SecretPepper.php");

// Vérifier que le paramètre 'action' soit là
$action = isset($_GET['action']) ? $_GET['action'] : "";

switch ($action) {

    // Récupérer toutes les tâches de la BD
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

    // Tenter d'authentifier le visiteur
    case "login":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $userManager = new UserManager();
            if (!$userManager->isLogged()) {

                // Vérifier que les identifiants sont bien fournis
                if (
                    !isset($_POST['login']) || !isset($_POST['password']) ||
                    empty(trim($_POST['login'])) || empty(trim($_POST['password']))
                ) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Identifiants incomplets"));
                    break;
                }

                // Récupérer les identifiants envoyés en POST
                $login = trim($_POST['login']);
                $password = $_POST['password']; // Ne pas utiliser trim car un mot de passe doit être précis (l'espace au tout début peut potentiellement compter)

                // Vérifier que $login ne contienne que des lettres sans espaces
                if (!preg_match('/^[A-Za-z]+$/', $login)) {
                    echo json_encode(array("result" => false, "error" => "Le champ login ne doit contenir que des lettres sans espaces."));
                    break;
                }

                if ($userManager->login($login, $password)) {
                    echo json_encode(array("result" => true, "login" => $login));
                } else {
                    echo json_encode(array("result" => false, "error" => "Identifiants incorrects"));
                }
            } else {
                echo json_encode(array("result" => false, "error" => "Vous êtes déjà connecté"));
            }
        }
        break;

    // Tente de déconnecter l'utilisateur
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

    // Tente de créer un nouvel utilisateur dans la BD
    case "createUser":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données sont bien fournis
                if (
                    !isset($_POST['name']) || !isset($_POST['fullname']) || !isset($_POST['login']) || !isset($_POST['password']) ||
                    empty(trim($_POST['name'])) || empty(trim($_POST['fullname'])) || empty(trim($_POST['login'])) || empty(trim($_POST['password']))
                ) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Un ou plusieurs champs ne sont pas renseignés."));
                    break;
                }

                // Récupérer les identifiants envoyés en POST
                $name = trim($_POST['name']);
                $fullname = trim($_POST['fullname']);
                $login = trim($_POST['login']);
                $password = $_POST['password']; // Ne pas utiliser trim car un mot de passe doit être précis (l'espace au tout début peut potentiellement compter)

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
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Vérifie si l'utilisateur est connecté ou non
    case "isLogged":
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {
                echo json_encode(array("result" => true));
            } else {
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Tente de mettre à jour une tâche à l'aide de son ID et de ses informations potentiellement modifiées à la BD
    case "updateTask":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                if (!isset($_POST['taskId']) || empty(trim($_POST['taskId']))) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Erreur lors de la récupération de l'id de la tâche."));
                    break;
                }

                // Vérifier que les données obligatoires sont bien fournis
                if (
                    !isset($_POST['taskName']) || !isset($_POST['priority']) ||
                    empty(trim($_POST['taskName'])) || empty(trim($_POST['priority']))
                ) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Les champs nom et priorité doivent être renseignés."));
                    break;
                }

                // Récupérer les données envoyées en POST
                $taskName = trim($_POST['taskName']);
                $priority = trim($_POST['priority']);
                $dueDate = isset($_POST['dueDate']) ? trim($_POST['dueDate']) : null;
                $newCommentText = isset($_POST['newComment']) ? trim($_POST['newComment']) : null;
                $taskId = trim($_POST["taskId"]);

                // Vérifie que la priorité est bien une de ces 4
                $allowedPriorities = array("basse", "moyenne", "haute", "urgente");
                if (!in_array(strtolower($priority), $allowedPriorities)) {
                    echo json_encode(array("result" => false, "error" => "La priorité doit être 'basse', 'moyenne', 'haute' ou 'urgente'."));
                    break;
                }

                // Détermine s'il y a un commentaire à ajouter
                $comment = null;
                if (!empty($newCommentText)) {
                    $author = $userManager->getAuthor();
                    $comment = new Comment($newCommentText, new DateTime(), $author);
                }

                $cardManager = new CardManager();
                $userId = $userManager->getAuthorId();
                $isUpdated = $cardManager->updateTask(
                    $taskId,
                    $taskName,
                    $priority,
                    $dueDate,
                    $comment,
                    $userId
                );

                if ($isUpdated) {
                    echo json_encode(array('result' => true));
                } else {
                    echo json_encode(array("error" => "Erreur lors de la modification de la tâche. Avez-vous modifié une donnée ?"));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Tente d'ajouter une nouvelle tâche à la BD
    case "addTask":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données obligatoires sont bien fournis
                if (
                    !isset($_POST['taskName']) || !isset($_POST['priority']) ||
                    empty(trim($_POST['taskName'])) || empty(trim($_POST['priority']))
                ) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Les champs nom et priorité doivent être renseignés."));
                    break;
                }

                // Récupérer les données envoyées en POST
                $taskName = trim($_POST['taskName']);
                $priority = trim($_POST['priority']);
                $dueDate = isset($_POST['dueDate']) ? trim($_POST['dueDate']) : null;
                $newCommentText = isset($_POST['newComment']) ? trim($_POST['newComment']) : null;

                // Vérifie que le nom de la tâche ne contient pas de guillemets simples et doubles
                if (preg_match('/["\']/', $taskName)) {
                    echo json_encode(array("result" => false, "error" => "Les guillemets ne sont pas autorisés dans le nom de la tâche."));
                    break;
                }

                // Vérifie que la priorité est bien une de ces 4
                $allowedPriorities = array("basse", "moyenne", "haute", "urgente");
                if (!in_array(strtolower($priority), $allowedPriorities)) {
                    echo json_encode(array("result" => false, "error" => "La priorité doit être 'basse', 'moyenne', 'haute' ou 'urgente'."));
                    break;
                }

                // Détermine s'il y a un commentaire à ajouter
                $comment = null;
                if (!empty($newCommentText)) {
                    $author = $userManager->getAuthor();
                    $comment = new Comment($newCommentText, new DateTime(), $author);
                }

                $cardManager = new CardManager();
                $userId = $userManager->getAuthorId();
                $isAdded = $cardManager->addTask(
                    $taskName,
                    $priority,
                    $dueDate,
                    $comment,
                    $userId
                );

                if ($isAdded) {
                    echo json_encode(array('result' => true));
                } else {
                    echo json_encode(array("error" => "Erreur lors de l'ajout de la tâche."));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Tente de supprimer une tâche à l'aide de son ID. Tous les commentaires associés à la tâche seront aussi supprimés.
    case "deleteTask":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données obligatoires sont bien fournis
                if (!isset($_POST['taskId']) || empty(trim($_POST['taskId']))) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Erreur lors de la récupération de l'ID de la tâche."));
                    break;
                }

                // Récupérer les données envoyées en POST
                $taskId = trim($_POST['taskId']);

                $cardManager = new CardManager();
                $isDeleted = $cardManager->deleteTask($taskId);

                if ($isDeleted) {
                    echo json_encode(array('result' => true));
                } else {
                    echo json_encode(array("result" => false, "error" => "Erreur lors de la suppression de la tâche."));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Récupère tous les commentaires d'une tâche spécifique
    case "getComments":
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données obligatoires sont bien fournis
                if (!isset($_GET['taskId']) || empty(trim($_GET['taskId']))) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Identifiant de tâche manquant."));
                    break;
                }

                // Récupérer les données envoyées en POST
                $taskId = trim($_GET['taskId']);
                $cardManager = new CardManager();
                $comments = $cardManager->getComments($taskId);

                $commentsArray = array();
                foreach ($comments as $comment) {
                    $commentsArray[] = array(
                        "id" => $comment->getId(),
                        "contenu" => $comment->getContenu(),
                        "date" => $comment->getDate()->format("d.m.Y"),
                        "auteur" => $comment->getAuteur()
                    );
                }
                echo json_encode($commentsArray);
            } else {
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Tente de supprimer un commentaire spécifique à l'aide de son ID
    case "deleteComment":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données obligatoires sont bien fournis
                if (!isset($_POST['commentId']) || empty(trim($_POST['commentId']))) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Identifiant du commentaire manquant."));
                    break;
                }

                // Récupérer les données envoyées en POST
                $commentId = trim($_POST['commentId']);
                $cardManager = new CardManager();
                $isDeleted = $cardManager->deleteComment($commentId);

                if ($isDeleted) {
                    echo json_encode(array("result" => true));
                } else {
                    echo json_encode(array("result" => false, "error" => "Erreur lors de la suppression du commentaire."));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Tente de mettre à jour la catégorie de la tâche
    case "updateCategory":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userManager = new UserManager();
            if ($userManager->isLogged()) {

                // Vérifier que les données obligatoires sont bien fournis
                if (!isset($_POST['taskId']) || empty(trim($_POST['taskId']))) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Identifiant de tâche manquant"));
                    break;
                }
                if (!isset($_POST['newCategory']) || empty(trim($_POST['newCategory']))) {
                    http_response_code(400);
                    echo json_encode(array("result" => false, "error" => "Nouvelle catégorie manquante."));
                    break;
                }

                // Récupérer les données envoyées en POST
                $taskId = trim($_POST['taskId']);
                $newCategory = trim($_POST['newCategory']);

                $cardManager = new CardManager();
                $isUpdated = $cardManager->updateCategory($taskId, $newCategory);

                if ($isUpdated) {
                    echo json_encode(array("result" => true));
                } else {
                    echo json_encode(array("result" => false, "error" => "Erreur lors de la mise à jour de la catégorie."));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("result" => false, "error" => "Unauthorized"));
            }
        }
        break;

    // Par défaut, si l'action n'est pas reconnue, on lui répond avec une erreur
    default:
        http_response_code(400);
        echo json_encode(array("error" => "Action non spécifiée ou inconnue"));
        break;
}
?>