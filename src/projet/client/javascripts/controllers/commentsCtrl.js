function deleteCommentSuccess(response) {
    if (response.result) {
        alert("Commentaire supprimé avec succès");
        window.location.reload();
    } else {
        alert("Erreur lors de la suppression du commentaire : " + (response.error || "Impossible de supprimer le commentaire"));
    }
}

function deleteCommentError(jqXHR, textStatus, errorThrown) {
    alert("Erreur lors de la suppression du commentaire : " + errorThrown);
}

function loadCommentsSuccess(comments) {
    let container = $("#commentsContainer");
    container.empty();

    if (comments.length === 0) {
        container.append("<p>Aucun commentaire n'a été trouvé.</p>");
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

function loadCommentsError() {
    if (request.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour consulter les commentaires d'une tâche.");
    } else {
        alert("Erreur lors de la récupération des commentaires : " + error);
    }
}

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

        chargerCommentaires($taskId, loadCommentsSuccess, loadCommentsError);
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