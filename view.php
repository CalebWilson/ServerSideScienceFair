<?php

	//initialize concrete Entity
	$classname = ucfirst($_GET['view']);
	include $classname . ".php";
	$entity = new $classname ($connection);

	$records = $entity->display ($_POST);

?>
