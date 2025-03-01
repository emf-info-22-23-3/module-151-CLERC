/*
 * @author Lexkalli
 */

let BASE_URL = "https://clercd.emf-informatique.ch/151_ManageKANBAN/projet/server/main.php";

/**
 * Envoie une requête AJAX pour connecter charger les tâches.
 * 
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function chargerTasks(successCallback, errorCallback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: BASE_URL + "?action=getTasks",
        data: {},
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour connecter un utilisateur.
 *
 * @param {string} login - Le login de l'utilisateur.
 * @param {string} password - Le mot de passe de l'utilisateur.
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function loginUser(login, password, successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=login",
        data: { login: login, password: password },
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour déconnecter l'utilisateur.
 *
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function logoutUser(successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=logout",
        data: {},
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour créer un utilisateur.
 *
 * @param {string} name - Le nom de l'utilisateur.
 * @param {string} fullname - Le prénom de l'utilisateur.
 * @param {string} login - Le login de l'utilisateur.
 * @param {string} password - Le mot de passe de l'utilisateur.
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function createUser(name, fullname, login, password, successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=createUser",
        data: { name: name, fullname: fullname, login: login, password: password },
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour vérifier si la session est connectée.
 * 
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function isLogged(successCallback, errorCallback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: BASE_URL + "?action=isLogged",
        data: {},
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour modifier une tâche.
 *
 * @param {string} taskId - L'ID unique de la tâche.
 * @param {string} taskName - Le nom de la tâche.
 * @param {string} priority - La priorité de la tâche.
 * @param {string} dueDate - La date d'échéance de la tâche (peut être null).
 * @param {string} newComment - Le nouveau commentaire (peut être null).
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function modifyTask(taskId, taskName, priority, dueDate, newComment, successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=updateTask",
        data: { taskId: taskId, taskName: taskName, priority: priority, dueDate: dueDate, newComment: newComment },
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour créer une tâche.
 *
 * @param {string} taskName - Le nom de la tâche.
 * @param {string} priority - La priorité de la tâche.
 * @param {string} dueDate - La date d'échéance de la tâche (peut être null).
 * @param {string} newComment - Le nouveau commentaire (peut être null).
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function addTask(taskName, priority, dueDate, newComment, successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=addTask",
        data: { taskName: taskName, priority: priority, dueDate: dueDate, newComment: newComment },
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour supprimer une tâche.
 *
 * @param {string} taskId - L'ID unique de la tâche à supprimer.
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function deleteTask(taskId, successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=deleteTask",
        data: { taskId: taskId },
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX retournant les commentaires d'une tâche spécifique.
 * 
 * @param {string} taskId - L'ID unique de la tâche.
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function chargerCommentaires(taskId, successCallback, errorCallback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: BASE_URL + "?action=getComments",
        data: { taskId: taskId },
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour supprimer un commentaire.
 * 
 * @param {string} commentId - L'ID unique du commentaire à supprimer.
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function deleteComment(commentId, successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=deleteComment",
        data: { commentId: commentId },
        success: successCallback,
        error: errorCallback
    });
}

/**
 * Envoie une requête AJAX pour mettre à jour la catégorie d'une tâche.
 *
 * @param {number} taskId - L'ID unique de la tâche.
 * @param {string} newCategory - La nouvelle catégorie (ex: "todo", "inprogress", etc.).
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function updateTaskCategory(taskId, newCategory, successCallback, errorCallback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: BASE_URL + "?action=updateCategory",
        data: { taskId: taskId, newCategory: newCategory },
        success: successCallback,
        error: errorCallback
    });
}