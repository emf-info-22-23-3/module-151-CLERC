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

    $("#loginForm").on("submit", function (e) {
        e.preventDefault(); // Empêcher la soumission classique du formulaire

        // Récupérer les valeurs du formulaire
        var loginVal = $(this).find("input[name='login']").val();
        var passwordVal = $(this).find("input[name='password']").val();

        loginUser(loginVal, passwordVal, loginSuccess, loginError);
    });
});