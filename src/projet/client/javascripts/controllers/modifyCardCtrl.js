/*
 * Cette classe permet la gestion de la page de modification de cartes
 * @author Lexkalli
 */

/**
 * Callback en cas de succès de modification de tâche
 * @param {type} response
 */
function modifyTaskSuccess(response) {
    if (response.result) {
        alert("Tâche modifiée");

        // Supprimer les variables respectives de localStorage
        localStorage.removeItem("taskId");
        localStorage.removeItem("taskName");
        localStorage.removeItem("priority");
        localStorage.removeItem("dueDate");
        window.location.href = "./user-view.html";
    } else {
        alert("Erreur : " + (response.error || "Modification impossible"));
    }
}

/**
 * Callback en cas d'erreur de modification de tâche
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function modifyTaskError(request, status, error) {
    if (request.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour modifier une tâche.");
    } else {
        alert("Erreur lors de la modification de la tâche : " + error);
    }
}

/**
 * Callback en cas de succès de si l'utilisateur est connecté
 * @param {type} response
 */
function isLoggedSuccess(response) {
    if (response.result === true) {
        // L'utilisateur est connecté, afficher le contenu de la page
        $("body").css("display", "flex");

        // Récupérer les valeurs stockées dans le localStorage
        let id = localStorage.getItem("taskId");
        let taskName = localStorage.getItem("taskName");
        let priority = localStorage.getItem("priority");
        let dueDate = localStorage.getItem("dueDate");

        // Regarder tout d'abord si il y a une tâche d'enregistrée dans le localStorage (si l'utilisateur n'est pas venu en tappant le chemin de la page html)
        if (id) {
            // Remplir les champs du formulaire si les données existent
            if (taskName) {
                document.getElementById("taskName").value = taskName;
            }
            if (priority) {
                document.getElementById("priority").value = priority;
            }
            if (dueDate) {
                // dueDate est au format "dd.MM.yyyy"
                let parts = dueDate.split(".");
                if (parts.length === 3) {
                    // Recompose au format "yyyy-MM-dd"
                    let reformattedDate = parts[2] + "-" + parts[1] + "-" + parts[0];
                    document.getElementById("dueDate").value = reformattedDate;
                }
            }

            // Attacher le code pour le lien "Annuler"
            $('#nav-annuler').on("click", function () {
                localStorage.removeItem("taskId");
                localStorage.removeItem("taskName");
                localStorage.removeItem("priority");
                localStorage.removeItem("dueDate");
            });

            // Lorsque l'utilisateur clique sur le bouton de soumission du formulaire
            $("#updateTaskForm").on("submit", function (e) {
                e.preventDefault(); // Empêcher la soumission classique du formulaire

                // Récupérer les valeurs du formulaire
                let taskNameVal = $(this).find("input[name='taskName']").val();
                let priorityVal = $(this).find("select[name='priority']").val();
                let dueDateVal = $(this).find("input[name='dueDate']").val();
                let newCommentVal = $(this).find("textarea[name='newComment']").val();

                // Récupérer l'id de la tâche
                let taskId = localStorage.getItem("taskId");

                // Appeler le service pour tenter de créer un utilisateur
                modifyTask(taskId, taskNameVal, priorityVal, dueDateVal, newCommentVal, modifyTaskSuccess, modifyTaskError);
            });

        } else {
            alert("Erreur lors du chargement de la page : tâche à modifier inconnue");
            window.location.href = "./user-view.html";
        }
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