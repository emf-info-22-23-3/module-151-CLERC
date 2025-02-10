$(document).ready(function(){
    $("#loginForm").on("submit", function(e){
        e.preventDefault(); // Empêcher la soumission classique du formulaire

        // Récupérer les valeurs du formulaire
        var loginVal = $(this).find("input[name='login']").val();
        var passwordVal = $(this).find("input[name='password']").val();

        // Appeler la fonction du service pour se connecter
        loginUser(loginVal, passwordVal, function(response){
            // En cas de succès, si response.result est true, rediriger
            if(response.result){
                // Connexion réussie : redirection vers la page visitor
                window.location.href = "./views/visitor-view.html";
            } else {
                alert("Identifiants incorrects !");
            }
        }, function(jqXHR, textStatus, errorThrown){
            alert("Erreur lors de la connexion : " + errorThrown);
        });
    });
});
