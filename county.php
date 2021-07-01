<?php

	include "admin_check.php";

	$county = array();
	if ($_POST['action'] == "Edit")
	{
		$record_set = $connection->query
		("
			select CountyName
			from County
			where CountyID = " . $_POST['selected'][0]
		);
		$county = $record_set->fetch (PDO::FETCH_ASSOC);
	}
	elseif ($_POST['action'] == "Add")
	{
		$county = array ("CountyName" => "");
	}

?>

<title><?php print($_POST['action'] . " ") ?>County</title>
<div class="wrapper">

<div class="wrapper">
<div class="main-f">
	<h1><strong><?php print($_POST['action'] . " ") ?>County</strong></h1>
	<div class="form-s">
		<form action="Administrator.php?view=county" method="post">

			<label for="county">County Name:</label>
			<input
				type="text"
				name="county"
				value="<?php print($county["CountyName"]) ?>"
			><br>

			<button type="submit"class="btn">Submit</button>

		</form>
	</div>

	<form action="Administrator.php?view=county" method="post">
		<button type="submit" class="back-btn">Back</button>
	</form>
            
</div>
</div>
