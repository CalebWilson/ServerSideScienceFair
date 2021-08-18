<!-- Connect to database -->

<?php

	try
	{
		$connection = new PDO("mysql:host=localhost;dbname=ScienceFair", "casawils", "casawils");
	}
	catch (PDOException $e)
	{
		print("<p>Error connecting to database</p><br>");
	}

?>
