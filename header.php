<!-- This file is to be included at the top of the page -->

<!-- connect to database -->
<?php
	require_once "db_connect.php";

	$domain = "http://127.0.0.1:8000/";
	$user_types = array ("Administrator", "Judge");
?>

<!doctype html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="./menu.js"></script>

	<meta
		name="viewport"
		content="width=device-width, minimum-scale=1.0, maximum-scale=1.0"
	/>
</head>

<body>
<div id="container">

	<header>
		<div class="width">

			<img
				src="image.png"
				alt="Image temporarily removed for debugging purposes :)"
				class="img-nav"
			>

			<nav>

				<?php

					//for each user type
					foreach ($user_types as $user_type)
					{
						if (isset ($_SESSION[$user_type]))
							$logged_in = true;

						else
						{ 
							print
							('
								<span>
									<a href="' . $domain . strtolower($user_type) . '.php">
										' . $user_type . ' Login
									</a>
								</span>
							');
						}

					} //end for each user type

					//display logout
					if ($logged_in)
						print('<span><a href="logout.php">Logout</a></span>');
				?>

			</nav>
		</div>

		<div class="clear"></div>

	</header>

<!-- container div and body closed in footer.php -->
