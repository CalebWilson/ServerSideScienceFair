<!--

	school.php

	This file will be included in School->add ($post) or School->edit ($post),
	where $post is a copy array of $_POST.

-->

<?php include "admin_check.php" ?>

<title><?php print(ucfirst($action)) ?> School</title>
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print(ucfirst($action)) ?> School</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=school" method="post">

			<?php print($msg) ?>

			<!-- SchoolName -->
			<div class="label">
				<label for="SchoolName">School Name:</label>
				<input
					type="text"
					name="SchoolName"
					value="<?php print ($this->fields["SchoolName"]) ?>"
				><br>
			</div>
			<?php if (isset ($msgs['SchoolName'])) print ($msgs['SchoolName']) ?>

			<!-- CountyID -->
			<label for="CountyID">County: </label>
			<select name="CountyID" id="#">

				<!-- blank option -->
				<option value=""></option>

				<!-- actual options -->
				<?php
					foreach ($options as $county)
					{
						print ("<option value=" . $county["CountyID"]);
						if ($county['CountyID'] === $this->fields['CountyID'])
							print(" selected");
						print (">" . $county["CountyName"] . "</option>");
					}
				?>

			</select><br>

			<?php if (isset ($msgs['CountyID'])) print ($msgs['CountyID']) ?>

			<!-- clear selected -->
			<?php
				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<!-- Submit -->
			<button type="submit" name="action"
				value="<?php print($action) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Admin.php?view=school" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
