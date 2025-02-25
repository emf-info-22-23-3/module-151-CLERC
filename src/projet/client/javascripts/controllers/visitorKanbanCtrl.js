/*
 * Cette classe permet la gestion de la page de vue visiteur
 * @author Lexkalli
 */

/**
 * Callback appelé lors du retour avec succès du résultat des équipes
 * @param {type} tasks
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
 * @param {type} request
 * @param {type} status
 * @param {type} error
 */
function loadTasksError(request, status, error) {
  alert("Erreur lors du chargement des tâches : " + error || "Connexion à la BD impossible.");
}

$(document).ready(function () {
  // Appel du service pour charger les tâches
  chargerTasks(loadTasksSuccess, loadTasksError);
  $("body").show(); // Afficher la page
});