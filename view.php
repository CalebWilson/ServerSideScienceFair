<?php

	//initialize concrete Entity
	$classname = ucfirst($_GET['view']);
	include $classname . ".php";
	$entity = new $classname ($connection);

	$records = $entity->display ($_POST);

?>

<!--
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php //print ($entity->title) ?></strong></h1>
	<?php //print("<p>". $msg . "</p>") ?>
	<div class="form-s">

	<?php //print($entity->display_data_header()) ?>

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

				/*
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
				*/
			?>

		</div>

		<?php
//			print($entity->buttons());
	?>
	</form>

	<?php //print ($entity->back_button()) ?>

</div>
</div>
</div>
-->
