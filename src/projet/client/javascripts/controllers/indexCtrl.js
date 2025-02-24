/*
 * @author Lexkalli
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

    $("#loginForm").on("submit", function (e) {
        e.preventDefault(); // Empêcher la soumission classique du formulaire

        // Récupérer les valeurs du formulaire
        let loginVal = $(this).find("input[name='login']").val();
        let passwordVal = $(this).find("input[name='password']").val();

        loginUser(loginVal, passwordVal, loginSuccess, loginError);
    });
});