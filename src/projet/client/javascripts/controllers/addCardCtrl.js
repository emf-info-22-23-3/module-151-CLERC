/*
 * @author Lexkalli
 */

function addTaskSuccess(response) {
    if (response.result) {
        alert("Tâche ajoutée");
        window.location.href = "./user-view.html";
    } else {
        alert("Erreur : " + (response.error || "Ajout impossible"));
    }
}

function addTaskError(request, status, error) {
    if (request.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour ajouter une tâche.");
    } else {
        alert("Erreur lors de l'ajout de la tâche : " + error);
    }
}

function isLoggedSuccess(response) {
    if (response.result === true) {
        // L'utilisateur est connecté, afficher le contenu de la page
        $("body").css("display", "flex");

        $("#addTaskForm").on("submit", function (e) {
            e.preventDefault(); // Empêcher la soumission classique du formulaire

            // Récupérer les valeurs du formulaire
            var taskNameVal = $(this).find("input[name='taskName']").val();
            var priorityVal = $(this).find("select[name='priority']").val();
            var dueDateVal = $(this).find("input[name='dueDate']").val();
            var newCommentVal = $(this).find("textarea[name='newComment']").val();

            // Vérifier que le nom de la tâche ne contient pas de guillemets simples ou doubles
            if (/["']/.test(taskNameVal)) {
                alert("Les guillemets ne sont pas autorisés dans le nom de la tâche.");
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