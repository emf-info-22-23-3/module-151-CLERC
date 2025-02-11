$(document).ready(function () {
    $("#registerForm").on("submit", function (e) {
        e.preventDefault(); // Empêcher la soumission classique

        // Récupérer les valeurs du formulaire
        var nameVal = $(this).find("input[name='name']").val();
        var fullnameVal = $(this).find("input[name='fullname']").val();
        var loginVal = $(this).find("input[name='login']").val();
        var passwordVal = $(this).find("input[name='password']").val();

        // Appeler la fonction de service pour créer un utilisateur
        createUser(nameVal, fullnameVal, loginVal, passwordVal,
            function (response) {
                // En cas de succès
                if (response.result) {
                    alert("Nouvel utilisateur ajouté");
                    window.location.href = "./user-view.html";
                } else {
                    alert("Erreur : " + (response.error || "Création impossible"));
                }
            },
            function (jqXHR, textStatus, errorThrown) {
                alert("Erreur lors de la création de l'utilisateur : " + errorThrown);
            }
        );
    });
});