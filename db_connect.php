<!-- Connect to database -->

<?php

	try
	{
		$connection = new PDO("mysql:host=localhost;dbname=casawils_db", "casawils", "casawils");
	}
	catch (PDOException $e)
	{
		print("<p>Error connecting to database</p><br>");
	}

?>