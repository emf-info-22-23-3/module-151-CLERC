function createUserSuccess(response) {
    if (response.result) {
        alert("Nouvel utilisateur ajouté");
        window.location.href = "./user-view.html";
    } else {
        alert("Erreur : " + (response.error || "Création impossible"));
    }
}

function createUserError(jqXHR, textStatus, errorThrown) {
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

        // Message personnalisé lorsque le format n'est pas respecté pour le login, le nom et le prénom
        $("input[name='login'], input[name='name'], input[name='fullname']")
            .on("invalid", function () {
                if (!this.validity.valid) {
                    this.setCustomValidity("Les champs login, nom et prénom doivent uniquement contenir des lettres sans espaces");
                }
            })
            // Reset le message personnalisé lorsque l'utilisateur modifie les champs respectifs
            .on("input", function () {
                this.setCustomValidity("");
            });

        $("#registerForm").on("submit", function (e) {
            e.preventDefault(); // Empêcher la soumission classique du formulaire

            // Récupérer les valeurs du formulaire
            var nameVal = $(this).find("input[name='name']").val();
            var fullnameVal = $(this).find("input[name='fullname']").val();
            var loginVal = $(this).find("input[name='login']").val();
            var passwordVal = $(this).find("input[name='password']").val();

            // Appeler la fonction de service pour créer un utilisateur en passant les callbacks
            createUser(nameVal, fullnameVal, loginVal, passwordVal, createUserSuccess, createUserError);
        });
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