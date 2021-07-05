<?php
	//will unset and redirect to home page
	session_start();
	session_destroy();
	unset($_SESSION['Judge']);
	unset($_SESSION['Administrator']);
	header("location: Admin.php");
?>
