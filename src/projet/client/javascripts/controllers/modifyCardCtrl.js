/*
 * @author Lexkalli
 */

$(document).ready(function () {
    // Récupérer les valeurs stockées dans le localStorage
    let id = localStorage.getItem("taskId");
    let taskName = localStorage.getItem("taskName");
    let priority = localStorage.getItem("priority");
    let dueDate = localStorage.getItem("dueDate");

    // Remplir les champs du formulaire si les données existent
    if (taskName) {
        document.getElementById("taskName").value = taskName;
    }
    if (priority) {
        document.getElementById("priority").value = priority;
    }
    if (dueDate) {
        document.getElementById("dueDate").value = dueDate;
    }

    // Attacher le code pour le lien "Annuler"
    $('#nav-annuler').on("click", function () {
        localStorage.removeItem("taskId");
        localStorage.removeItem("taskName");
        localStorage.removeItem("priority");
        localStorage.removeItem("dueDate");
    });
});