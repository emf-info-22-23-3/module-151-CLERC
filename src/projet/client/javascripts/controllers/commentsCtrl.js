/*
 * Cette classe permet la gestion de la page de vue de commentaires
 * @author Lexkalli
 */

/**
 * Callback en cas de succès de suppression de commentaire
 * @param {type} response
 */
function deleteCommentSuccess(response) {
    if (response.result) {
        alert("Commentaire supprimé avec succès");
        window.location.reload();
    } else {
        alert("Erreur lors de la suppression du commentaire : " + (response.error || "Impossible de supprimer le commentaire"));
    }
}

/**
 * Callback en cas d'erreur de suppression de commentaire
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function deleteCommentError(request, status, error) {
    alert("Erreur lors de la suppression du commentaire : " + error);
}

/**
 * Callback en cas de succès de récupération des commentaires
 * @param {type} comments
 */
function loadCommentsSuccess(comments) {
    let container = $("#commentsContainer");
    container.empty();

    // Si aucun commentaire n'a été trouvé pour cette tâche
    if (comments.length === 0) {
        container.append("<p>Aucun commentaire n'a été trouvé.</p>");

        // Sinon pour chaque commentaire, le mettre en forme et l'ajouter au HTML
    } else {
        comments.forEach(function (comment) {
            let formattedContent = comment.contenu.replace(/\n/g, '<br>'); // Créer les retours à la ligne là où il y en a
            let commentHtml = `
                <div class="comment">
                    <div class="comment-author">${comment.auteur}</div>
                    <div class="comment-date">${comment.date}</div>
                    <div class="comment-content">${formattedContent}</div>
                    <a href="#" class="delete-comment-link" data-comment-id="${comment.id}">Supprimer le commentaire</a>
                </div>
            `;
            container.append(commentHtml);
        });

        // Attacher l'événement pour la suppression des commentaires
        $(".delete-comment-link").on("click", function (e) {
            e.preventDefault();
            let commentId = $(this).data("comment-id");
            if (confirm("Voulez-vous vraiment supprimer ce commentaire ?")) {
                deleteComment(commentId, deleteCommentSuccess, deleteCommentError);
            }
        });
    }
}

/**
 * Callback en cas d'erreur de récupération des commentaires
 */
function loadCommentsError() {
    if (request.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour consulter les commentaires d'une tâche.");
    } else {
        alert("Erreur lors de la récupération des commentaires : " + error);
    }
}

/**
 * Callback en cas de succès de si l'utilisateur est connecté
 * @param {type} response
 */
function isLoggedSuccess(response) {
    if (response.result === true) {
        // L'utilisateur est connecté, afficher le contenu de la page
        $("body").show();

        // Récupérer l'ID de la carte stockée dans le localStorage
        $taskId = localStorage.getItem("taskId");

        // Attacher le code pour le lien "Retour"
        $('#nav-retour').on("click", function () {
            localStorage.removeItem("taskId");
        });

        // Appeler le service afin de charger les commentaires de la tâche
        chargerCommentaires($taskId, loadCommentsSuccess, loadCommentsError);
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