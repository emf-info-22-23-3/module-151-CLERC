<?php
include_once 'Ctrl.php';
include_once 'Wrk.php';
include_once 'db_config.php';
include_once 'Equipe.php';
include_once 'Joueur.php';

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