<!-- This file is to be included at the top of the page -->

<?php
	try
	{
		$connection = new PDO("mysql:host=localhost;dbname=casawils_db", "casawils", "casawils");
	}
	catch (PDOException $e)
	{
		print("<p>Error connecting to database</p><br>");
	}

	session_start();
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

				<img src="image.png" alt="i" class="img-nav">
			<nav>
				<span>
					<a href="http://corsair.cs.iupui.edu:24601/home.php">Home</a>
				</span>

					<?php

						if (isset($_SESSION ['Judge']))
						{
							echo
							'<span>
								<a href="http://corsair.cs.iupui.edu:24601/schedule.php">
									Schedule
								</a>
							</span>';
						}

						else
						{ 
							echo
							'<span>
								<a href="http://corsair.cs.iupui.edu:24601/Judge.php">
									Judge Login
								</a>
							</span>';
						}

						if (!isset($_SESSION ['Administrator']))
						{ 
							echo
							'<span>
								<a href="http://corsair.cs.iupui.edu:24601/Admin.php">
									Admin Login
								</a>
							</span>';
						}

					?>

					<span>
						<a href="http://corsair.cs.iupui.edu:24601/register.php">
							Register
						</a>
					</span>

					<span><a href="#">Contact</a></span>

					<?php

						if (isset($_SESSION ['Judge']) ||
							isset($_SESSION ['Administrator']))
						{ 
							echo '<span><a href="logout.php">logout</a></span>';
						}
					?>
			</nav>
		</div>

		<div class="clear"></div>

	</header>

<!-- container div and body closed in footer.php -->
