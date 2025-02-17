/*
 * @author Lexkalli
 */

function modifyTaskSuccess() {
    if (response.result) {
        alert("Tâche modifiée");
        window.location.href = "./user-view.html";
    } else {
        alert("Erreur : " + (response.error || "Modification impossible"));
    }
}

function modifyTaskError() {
    if (jqXHR.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour créer un utilisateur.");
    } else {
        alert("Erreur lors de la création de l'utilisateur : " + errorThrown);
    }
}

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

            $("#updateTaskForm").on("submit", function (e) {
                e.preventDefault(); // Empêcher la soumission classique du formulaire

                // Récupérer les valeurs du formulaire
                var taskNameVal = $(this).find("input[name='taskName']").val();
                var priorityVal = $(this).find("input[name='priority']").val();
                var dueDateVal = $(this).find("input[name='dueDate']").val();
                var newCommentVal = $(this).find("input[name='newComment']").val();

                // Appeler la fonction de service pour créer un utilisateur en passant les callbacks
                modifyTask(taskNameVal, priorityVal, dueDateVal, newCommentVal, modifyTaskSuccess, modifyTaskError);
            });

        } else {
            alert("Erreur lors du chargement de la page : tâche à modifier inconnue");
            window.location.href = "./user-view.html";
        }
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