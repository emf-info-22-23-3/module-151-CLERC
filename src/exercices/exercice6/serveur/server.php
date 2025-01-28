<?php
require_once("Equipe.php");
require_once("Ctrl.php");
require_once('Wrk.php');
$ctrl = new Ctrl();

if ($_GET['action'] == "equipe") {
	echo json_encode($ctrl->getEquipes());
}
?>