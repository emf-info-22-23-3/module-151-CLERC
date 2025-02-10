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