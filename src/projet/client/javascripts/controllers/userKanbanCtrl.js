/*
 * Contrôleur de la vue "visitor-view.html"
 *
 * @author Olivier Neuhaus
 * @version 1.0 / 20-SEP-2013
 */

/**
 * Méthode appelée lors du retour avec succès du résultat des équipes
 * @param {type} data
 * @param {type} text
 * @param {type} jqXHR
 */
function loadTasksSuccess(tasks) {
  // Pour chaque tâche reçue, on crée un élément d'accordéon et on l'injecte dans la bonne colonne.
  tasks.forEach(function (task) {
    // Détermine l'ID de l'accordéon en fonction de la catégorie
    let accordionId = "";
    let categorie = task.categorie.toLowerCase();
    if (categorie === "todo") {
      accordionId = "accordionToDo";
    } else if (categorie === "inprogress") {
      accordionId = "accordionInProgress";
    } else if (categorie === "totest") {
      accordionId = "accordionToTest";
    } else if (categorie === "validated") {
      accordionId = "accordionValidated";
    }

    if (accordionId !== "") {
      // Utilise l'ID de la tâche pour créer des identifiants uniques pour les éléments collapse
      let collapseId = "collapse" + task.id;
      let headingId = "heading" + task.id;

      // Crée le code HTML pour l'élément de l'accordéon
      let itemHtml = `
            <div class="accordion-item">
              <h2 class="accordion-header" id="${headingId}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                  ${task.nom}
                </button>
              </h2>
              <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="${headingId}"
                data-bs-parent="#${accordionId}">
                <div class="accordion-body">
                  <p><strong>Date de création:</strong> ${task.dateCreation}</p>
                  <p><strong>Date d'échéance:</strong> ${task.dateEcheance ? task.dateEcheance : "-"}</p>
                  <p><strong>Priorité:</strong> ${task.priorite}</p>
                  <p><strong>Créée par:</strong> ${task.utilisateurOrigine}</p>
                </div>
              </div>
            </div>
            `;
      // Ajoute l'élément dans l'accordéon correspondant
      $("#" + accordionId).append(itemHtml);
    }
  });
}

/**
 * Callback en cas d'erreur lors du chargement des tâches.
 */
function loadTasksError(request, status, error) {
  alert("Erreur lors du chargement des tâches : " + error);
}

/**
 * Fonction d'initialisation à exécuter lorsque la page est chargée.
 */
$(document).ready(function () {
  isLogged(function (response) {
    if (response.result === true) { // Vérifier si l'utilisateur est connecté

      chargerTasks(loadTasksSuccess, loadTasksError);
      $("body").show(); // Afficher la page

      $("a.login-link").filter(function () {
        return $(this).text().trim() === "Déconnexion";
      }).on("click", function (e) {
        e.preventDefault(); // Empêcher le comportement par défaut (href="./visitor-view.html" de l'html)
        logoutUser(
          function (response) {
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
    } else {
      // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
      alert("Cette page est uniquement pour les utilisateurs connectés.\nRetour vers la page d'accueil.");
      window.location.href = "../index.html";
    }
  }, function (jqXHR, textStatus, errorThrown) {
    alert("Erreur lors de la vérification de la session : " + errorThrown);
  });
});