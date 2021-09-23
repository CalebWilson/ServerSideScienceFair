<?php

	if ($_SESSION['user_type'] == "Judge")
		include "judge_check.php";

	else include "admin_check.php";

	//initialize concrete Entity
	$classname = ucfirst($_GET['view']);
	include $classname . ".php";
	$entity = new $classname ($connection);

	//initialize potential error message
	$msg = "";

	//if submitted
	if (isset($_POST['action']))
	{
		//do the desired action on the desired records of the entity
		$action = $_POST['action'];
		$msg = $entity->$action($_POST);

	} //end if submitted

	//get records
	$records = $entity->display_data();

?>

<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print ($entity->title) ?></strong></h1>
	<?php print("<p>". $msg . "</p>") ?>
	<div class="form-s">
	<?php
		//show buttons to upload csv
		print($entity->upload_button());
	?>

	<form action="Admin.php?view=<?php print ($_GET['view']) ?>" method="post">
		<div class="data-box">
		
			<script>

				function isChecked(elem)
				{
					elem.parentNode.style.background =
						(elem.checked) ? '#ffa500' : 'none';
				}

			</script>

			<?php
			//show records
			//e.g. <input type="checkbox" name="selected" value=1 checked>Caleb Wilson</input><br>

				foreach ($records as $record)
				{
					print
					('
						<label id="data">
						<input 
						    id="change"
							type="checkbox"
							name="selected[]"
							class="check-d";
							onchange="isChecked(this)"
							value=' . $record['ID']
					);


					//preserve checkedness
					if (isset ($_POST['selected']))
					{
						if (in_array($record['ID'], $_POST['selected']))
							print (" checked");
					}

					print
					('
						>  ' .
							$record['selection'] . '
						</input></label></><br>
					');
				}	
			?>

		</div>
		<?php
			print($entity->buttons());
/*
			if ($show_buttons)
			{
*/
				if ($_GET['view'] == 'judge')
					print
					('
					<div class="view-b">
						<button type="submit" name="action" value="Checkin" class="btn">Check in</button>
						<button type="submit" name="action" value="Checkout" class="btn">Check out</button>
					</div>
					');
	?>
	</form>

	<form action="Admin.php?view=actions" method="get" class="back-btn">
		<button type="submit">Back</button>
	</form>
</div>
</div>
</div>
