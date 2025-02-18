function loadCommentsSuccess(comments) {
    let container = $("#commentsContainer");
    container.empty();

    comments.forEach(function (comment) {
        let formattedContent = comment.contenu.replace(/\n/g, '<br>'); // Créer les retours à la ligne là où il y en a
        let commentHtml = `
                <div class="comment">
                    <div class="comment-author">${comment.auteur}</div>
                    <div class="comment-date">${comment.date}</div>
                    <div class="comment-content">${formattedContent}</div>
                </div>
            `;
        container.append(commentHtml);
    });
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