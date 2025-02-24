/*
 * @author Lexkalli
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
            <div class="d-flex justify-content-between task-actions">
            <a href="./modify-card.html" class="modify-link"
              data-task-id="${task.id}"
              data-task-name="${task.nom}"
              data-priority="${task.priorite}"
              data-due-date="${task.dateEcheance ? task.dateEcheance : ''}">
              Modifier
            </a>
            <a href="./view-comments.html" class="comments-link" 
              data-task-id="${task.id}">
              Commentaires
            </a>
            <a href="#" class="delete-link"
              data-task-id="${task.id}">
              Supprimer
            </a>
          </div>
                </div>
              </div>
            </div>
            `;
      // Ajoute l'élément dans l'accordéon correspondant
      $("#" + accordionId).append(itemHtml);
    }
  });

  $(".modify-link").on("click", function (e) {
    // Récupérer les informations stockées dans les attributs data du lien cliqué
    let taskId = $(this).data("task-id");
    let taskName = $(this).data("task-name");
    let priority = $(this).data("priority");
    let dueDate = $(this).data("due-date");

    // Stocker ces informations dans le localStorage
    localStorage.setItem("taskId", taskId);
    localStorage.setItem("taskName", taskName);
    localStorage.setItem("priority", priority);
    localStorage.setItem("dueDate", dueDate);
  });

  $(".delete-link").on("click", function (e) {
    e.preventDefault();
    let taskId = $(this).data("task-id");
    if (confirm("Voulez-vous vraiment supprimer cette tâche ?")) {
      deleteTask(taskId, deleteTaskSuccess, deleteTaskError);
    }
  });

  $(".comments-link").on("click", function (e) {
    // Récupérer l'ID stockée dans les attributs data du lien cliqué
    let taskId = $(this).data("task-id");

    // Stocker l'information dans le localStorage
    localStorage.setItem("taskId", taskId);
  });

  // Une fois les tâches chargées, initialiser le drag & drop
  initializeDragAndDrop();
}

/**
 * Callback en cas d'erreur lors du chargement des tâches.
 */
function loadTasksError(request, status, error) {
  if (request.status === 401) {
    alert("Erreur 401: Vous devez être connecté pour accéder aux informations de cette page.");
  } else {
    alert("Erreur lors du chargement des tâches : " + error);
  }
  window.location.href = "../index.html";
}

function deleteTaskSuccess(response) {
  if (response.result) {
    alert("Tâche supprimée avec succès");
    window.location.reload();
    localStorage.removeItem("taskId");
  } else {
    alert("Erreur lors de la suppression de la tâche : " + (response.error || ""));
  }
}

function deleteTaskError(request, textStatus, error) {
  alert("Erreur lors de la suppression de la tâche : " + error);
}

function isLoggedSuccess(response) {
  if (response.result === true) {
    // L'utilisateur est connecté, afficher le contenu et charger les tâches
    $("body").show();
    chargerTasks(loadTasksSuccess, loadTasksError);

    // Afficher le paragraphe contenant le login de l'utilisateur
    var login = sessionStorage.getItem('login');
    if (login) {
      $("#connected-as").html("Connecté avec le login " + login);
    }

    // Attacher le code pour le lien "Déconnexion"
    $('#nav-deconnection').on("click", function (e) {
      logoutUser(function (response) {
        if (response.result) {
          // Enlever le login dans le sessionStorage
          sessionStorage.removeItem('login');
        } else {
          alert("Erreur lors de la déconnexion : " + (response.error || ""));
        }
      });
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

function updateTaskCategorySuccess(response) {
  if (response.result === false) {
    alert("Erreur lors de la mise à jour de la catégorie.");
  }
  window.location.reload();
}

function updateTaskCategoryError(response, textStatus, error) {
  if (request.status === 401) {
    alert("Erreur 401: Vous devez être connecté pour accéder à cette page.");
  } else {
    alert("Erreur lors de la vérification de la session : " + error);
  }
  window.location.href = "../index.html";
}

function initializeDragAndDrop() {
  // Rendre chaque élément d'accordéon draggable
  $(".accordion-item").draggable({
    helper: "clone",
    revert: "invalid",
    cursor: "move"
  });

  // Rendre chaque colonne droppable
  $(".kanban-column").droppable({
    accept: ".accordion-item",
    hoverClass: "drop-hover",
    drop: function (event, ui) {
      // Récupérer la nouvelle catégorie depuis l'attribut data-category de la colonne
      let newCategory = $(this).data("category");
      // Récupérer l'ID de la tâche depuis l'élément (par exemple, depuis le lien "modify-link" qui est dans l'item)
      let taskId = ui.draggable.find(".modify-link").data("taskId");

      // Appeler la fonction pour mettre à jour la catégorie de la tâche dans la BD
      updateTaskCategory(taskId, newCategory, updateTaskCategorySuccess, updateTaskCategoryError);
    }
  });
}

$(document).ready(function () {
  isLogged(isLoggedSuccess, isLoggedError);
});