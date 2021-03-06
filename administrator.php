<!--

	administrator.php

	Route administrators.

-->

<?php

	if (session_status() !== PHP_SESSION_ACTIVE)
		session_start();

	//log out of judge account
	unset ($_SESSION ['Judge']);

	//check whether admin is logged in
	if (isset ($_SESSION ['Administrator']))
	{
		require_once "header.php";

		//display correct view
		if (isset ($_GET ['view']))
		{
			if ($_GET ['view'] == "actions")
				include "actions.php";
			else
				include "view.php";
		}
		else
			include "actions.php";
	}

	//if the admin is not logged in yet, make them log in
	else
	{
		$_SESSION ['user_type'] = "Administrator";
		include "login.php";
	}

	require_once "footer.php";
?>
