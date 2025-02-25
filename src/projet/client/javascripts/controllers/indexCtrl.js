/*
 * Cette classe permet la gestion de la page d'accueil
 * @author Lexkalli
 */

/**
 * Callback en cas de succès d'authentification de l'utilisateur
 * @param {type} response
 */
function loginSuccess(response) {
    if (response.result) {
        // Stocker le login dans le sessionStorage
        sessionStorage.setItem('login', response.login);
        window.location.href = "./views/user-view.html";
    } else {
        alert("Identifiants incorrects !");
        window.location.href = ""; // Refresh la page
    }
}

/**
 * Callback en cas d'erreur d'authentification de l'utilisateur
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function loginError(request, status, error) {
    alert("Erreur lors de la connexion : " + error);
    window.location.href = ""; // Refresh la page
}

$(document).ready(function () {
    $("body").css("display", "flex");

    // Message personnalisé lorsque le format n'est pas respecté pour le login
    $("input[name='login']")
        .on("invalid", function () {
            if (!this.validity.valid) {
                this.setCustomValidity("Votre login doit uniquement contenir des lettres sans espaces");
            }
        })
        // Reset le message personnalisé lorsque l'utilisateur modifie le champ
        .on("input", function () {
            this.setCustomValidity("");
        });

    // Lorsque l'utilisateur clique sur le bouton de soumission du formulaire
    $("#loginForm").on("submit", function (e) {
        e.preventDefault(); // Empêcher la soumission classique du formulaire

        // Récupérer les valeurs du formulaire
        let loginVal = $(this).find("input[name='login']").val();
        let passwordVal = $(this).find("input[name='password']").val();

        // Appele le service pour tenter de connecter l'utilisateur avec ses identifiants
        loginUser(loginVal, passwordVal, loginSuccess, loginError);
    });
});