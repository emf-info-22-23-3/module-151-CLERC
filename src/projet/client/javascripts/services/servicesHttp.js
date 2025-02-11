/*
 * Couche de services HTTP pour charger les tâches.
 */

var BASE_URL = "http://localhost:8080/projet/server/main.php";

/**
 * Fonction permettant de charger les tâches.
 * @param {function} successCallback - Fonction appelée en cas de succès.
 * @param {function} errorCallback - Fonction appelée en cas d'erreur.
 */
function chargerTasks(successCallback, errorCallback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: BASE_URL,
        data: { action: "getTasks" },
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
 * @param {string} fullname - Le prénom (ou nom complet) de l'utilisateur.
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