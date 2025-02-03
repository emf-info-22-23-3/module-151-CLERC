<?php
require_once './Ctrl/Ctrl.php';
require_once './Wrk/Wrk.php';
require_once './Helpers/db_config.php';
require_once './Beans/Equipe.php';
require_once './Beans/Joueur.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

$ctrl = new Ctrl();

if ($action == 'equipe') {
	$ctrl->getEquipesXML();
} elseif ($action == 'joueur' && isset($_GET['equipeId'])) {
	$equipeId = $_GET['equipeId'];
	$ctrl->getJoueursXML($equipeId);
} else {
	echo "Action non supportée.";
}
?>