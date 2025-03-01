/*
 * Cette classe permet la gestion de la page d'ajouts de cartes
 * @author Lexkalli
 */

/**
 * Callback Fonction en cas de succès d'ajout de carte
 * @param {type} response
 */
function addTaskSuccess(response) {
    if (response.result) {
        alert("Tâche ajoutée");
        window.location.href = "./user-view.html";
    } else {
        alert("Erreur : " + (response.error || "Ajout impossible"));
    }
}

/**
 * Callback en cas d'erreur d'ajout de carte
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function addTaskError(request, status, error) {
    if (request.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour ajouter une tâche.");
    } else {
        alert("Erreur lors de l'ajout de la tâche : " + error);
    }
}

/**
 * Callback en cas de succès de si l'utilisateur est connecté
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function isLoggedSuccess(response) {
    if (response.result === true) {
        // L'utilisateur est connecté, afficher le contenu de la page
        $("body").css("display", "flex");

        $("#addTaskForm").on("submit", function (e) {
            e.preventDefault(); // Empêcher la soumission classique du formulaire

            // Récupérer les valeurs du formulaire
            let taskNameVal = $(this).find("input[name='taskName']").val();
            let priorityVal = $(this).find("select[name='priority']").val();
            let dueDateVal = $(this).find("input[name='dueDate']").val();
            let newCommentVal = $(this).find("textarea[name='newComment']").val();

            // Vérifier que le nom de la tâche ne contient pas de guillemets doubles
            if (/["]/.test(taskNameVal)) {
                alert("Les guillemets doubles ne sont pas autorisés dans le nom de la tâche.");
                return;
            }

            // Appeler la fonction de service pour créer un utilisateur en passant les callbacks
            addTask(taskNameVal, priorityVal, dueDateVal, newCommentVal, addTaskSuccess, addTaskError);
        });
    } else {
        alert("Erreur lors du chargement de la page : tâche à modifier inconnue");
        window.location.href = "./user-view.html";
    }
}

/**
 * Callback en cas d'erreur de si l'utilisateur est connecté
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function isLoggedError(request, status, error) {
    if (request.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour accéder à cette page.");
    } else {
        alert("Erreur lors de la vérification de la session : " + error);
    }
    window.location.href = "../index.html";
}

$(document).ready(function () {
    isLogged(isLoggedSuccess, isLoggedError);
});