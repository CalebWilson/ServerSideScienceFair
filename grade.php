<?php

	include "admin_check.php";

	//initialize grade
	$grade = array();

	//make an array to keep track of potential error messages
	$msgs = array();
	$msg  = "";

	//if data has been submitted, prefill with that data
	if (isset($_POST['GradeNum']))
	{
		//copy POST data
		$grade = array('GradeNum' => $_POST['GradeNum']);

		$msgs = array('GradeNum' => "");

		/* GradeNum Uniqueness */
		$unique_check = "";
		if ($_POST['action'] == "Edit")
			$unique_check = " AND GradeID != " . $_POST['selected'][0];

		$query = $connection->prepare
		("
			select count(*) as 'count'
			from Grade
			where GradeNum = ?" .
			$unique_check
		);
		$query->execute(array($grade['GradeNum']));
		$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

		if ($count !== "0")
		{
			$msgs['GradeNum'] =
				'<font color="red">Grade ' .  $grade['GradeNum'] . " already exists.";
		}

		//if good input
		if ($msgs['GradeNum'] == "")
		{
			if ($_POST['action'] == "Add")
			{
				$query = $connection->prepare
				("
					insert into
						Grade (GradeNum)
						values (?)
				");
			}

			elseif ($_POST['action'] == "Edit")
			{
				//append ID to grade
				$grade["ID"] = $_POST['selected'][0];
				$query = $connection->prepare
				("
					update Grade
					set GradeNum = ?
					where GradeID = ?
				");
			}

			//execute the sql statement
			$query->execute(array_values($grade));

			//confirmation message
			$msg = '<font color="green">Successfully ';
			
			if ($_POST['action'] == "Add")
				$msg = $msg . "added ";
			elseif ($_POST['action'] == "Edit")
				$msg = $msg . "modified ";

			$msg = $msg . "Grade " . $grade['GradeNum'] . ".</font><br>";

			//clear fields for next entry
			if ($_POST['action'] == "Add")
				$grade = array_fill_keys (array_keys($grade), "");

		}//end if good input

		//if bad input
		else $msg = "";

	} //end if data submitted 

	elseif ($_POST['action'] == "Edit")
	{
		$record_set = $connection->query
		("
			select GradeNum 
			from Grade
			where GradeID = " . $_POST['selected'][0]
		);
		$grade = $record_set->fetch (PDO::FETCH_ASSOC);
	}
	elseif ($_POST['action'] == "Add")
	{
		$grade = array ("GradeNum" => "");
	}

?>

<title><?php print($_POST['action'] . " ") ?>Grade</title>
<div class="wrapper">
<div class="main-f">
	<h1><strong><?php print($_POST['action'] . " ") ?>Grade</strong></h1>
	<div class="form-s">
		<form action="Administrator.php?view=grade" method="post" >

			<?php print($msg) ?>

			<label for="GradeNum">Grade Level: </label>
			<input
				type="number"
				min="0"
				id="GradeNum"
				name="GradeNum"
				value="<?php print($grade["GradeNum"]) ?>"
			><br>

			<?php
				if (isset ($msgs['GradeNum']))
					print($msgs['GradeNum']);

				if (isset($_POST['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$_POST['selected'][0] . '">'); 
			?>

			<button type="submit" name="action"
				value="<?php print($_POST['action']) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Administrator.php?view=grade" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
