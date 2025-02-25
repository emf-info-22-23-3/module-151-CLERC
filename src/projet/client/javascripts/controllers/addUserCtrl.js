/*
 * Cette classe permet la gestion de la page d'ajouts d'utilisateurs
 * @author Lexkalli
 */

/**
 * Callback en cas de succès de création d'utilisateur
 * @param {type} response
 */
function createUserSuccess(response) {
    if (response.result) {
        alert("Nouvel utilisateur ajouté");
        window.location.href = "./user-view.html";
    } else {
        alert("Erreur : " + (response.error || "Création impossible"));
    }
}

/**
 * Callback en cas d'erreur de création d'utilisateur
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function createUserError(request, status, error) {
    if (jqXHR.status === 401) {
        alert("Erreur 401: Vous devez être connecté pour créer un utilisateur.");
    } else {
        alert("Erreur lors de la création de l'utilisateur : " + error);
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

        // Message personnalisé lorsque le format n'est pas respecté pour le login, le nom et le prénom
        $("input[name='login'], input[name='name'], input[name='fullname']")
            .on("invalid", function () {
                if (!this.validity.valid) {
                    this.setCustomValidity("Les champs login, nom et prénom doivent uniquement contenir des lettres sans espaces");
                }
            })
            // Retirer le message personnalisé lorsque l'utilisateur modifie les champs respectifs
            .on("input", function () {
                this.setCustomValidity("");
            });

        // Lorsque l'utilisateur clique sur le bouton de soumission du formulaire
        $("#registerForm").on("submit", function (e) {
            e.preventDefault(); // Empêcher la soumission classique du formulaire

            // Récupérer les valeurs du formulaire
            let nameVal = $(this).find("input[name='name']").val();
            let fullnameVal = $(this).find("input[name='fullname']").val();
            let loginVal = $(this).find("input[name='login']").val();
            let passwordVal = $(this).find("input[name='password']").val();

            // Appeler la fonction de service pour créer un utilisateur en passant les callbacks
            createUser(nameVal, fullnameVal, loginVal, passwordVal, createUserSuccess, createUserError);
        });
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