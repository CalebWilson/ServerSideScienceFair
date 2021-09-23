<?php

	if (session_status() !== PHP_SESSION_ACTIVE)
		session_start();

	//log out of admin account
	unset ($_SESSION ['Administrator']);

	//check whether judge is logged in
	if (isset ($_SESSION ['Judge']))
	{
		require_once "header.php";

		$_GET ['view'] = "score";
		include "view.php";
	}

	//if the judge is not logged in yet, make them log in
	else
	{
		$_SESSION['user_type'] = "Judge";
		include "login.php";
	}

	require_once "footer.php";
?>
