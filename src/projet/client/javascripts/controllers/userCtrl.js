$(document).ready(function () {
    // Sélectionner le lien "Déconnexion" par son texte ou par son href
    $("a.login-link").filter(function () {
        return $(this).text().trim() === "Déconnexion";
    }).on("click", function (e) {
        e.preventDefault(); // Empêcher le comportement par défaut (href="./visitor-view.html" de l'html)
        logoutUser(
            function (response) {
                // Si la réponse indique que la déconnexion est réussie, rediriger vers visitor-view.html
                if (response.result) {
                    window.location.href = "./visitor-view.html";
                } else {
                    alert("Erreur lors de la déconnexion : " + (response.error || ""));
                }
            },
            function (jqXHR, textStatus, errorThrown) {
                alert("Erreur lors de la déconnexion : " + errorThrown);
            }
        );
    });
});