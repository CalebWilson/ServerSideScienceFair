<?php include "admin_check.php" ?>

<title><?php print(ucfirst($action)) ?> Grade</title>
<div class="wrapper">
<div class="main-f">

	<h1><strong><?php print(ucfirst($action)) ?> Grade</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=grade" method="post" >

			<?php print($msg) ?>

			<!-- GradeNum-->
			<label for="GradeNum">Grade Level: </label>
			<input
				type="number"
				min="0"
				id="GradeNum"
				name="GradeNum"
				value="<?php print($this->fields ["GradeNum"]) ?>"
			><br>

			<?php if (isset ($msgs['GradeNum'])) print($msgs['GradeNum']) ?>

			<?php
				
				//preserve selected
				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<!-- Submit -->
			<button type="submit" name="action"
				value="<?php print($action) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Admin.php?view=grade" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
