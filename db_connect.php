<!-- Connect to database -->

<?php

	try
	{
		$connection = new PDO("mysql:host=localhost;dbname=ScienceFair", "server", "server");
	}
	catch (PDOException $e)
	{
		print("<p>Error connecting to database: " . $e->__toString() . "</p><br>");
	}

?>
