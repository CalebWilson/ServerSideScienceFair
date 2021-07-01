<?php include "admin_check.php" ?>

<title><?php print($action . " ") ?>Project</title>
<div class="wrapper">


<div class="main-f">
	<h1><strong><?php print($action . " ") ?>Project</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=project" method="post">

			<?php print($msg) ?>

			<label for="Booth">Booth Number:</label>
			<select name="Booth" id="Booth">
				<?php
					foreach ($options['booths'] as $booth)
					{
						print ("<option value=" . $booth["BoothID"]);
						if ($booth['BoothID'] === $this->fields['BoothID'])
							print(" selected");
						print (">" .  $booth["BoothNum"] .  "</option>");
					}
				?>
			</select><br>

			<label for="Category">Category:</label>
			<select name="Category" id="#">
				<?php
					foreach ($options['categories'] as $category)
					{
						print ("<option value=" . $category["CategoryID"]);
						if ($category['CategoryID'] === $this->fields['CategoryID'])
							print(" selected");
						print (">" . $category["CategoryName"] . "</option>");
					}
				?>
			</select><br>

			<label for="ProjectNum">Project Number:</label>
			<input
				type="number"
				name="ProjectNum"
				value="<?php print ($this->fields["ProjectNum"]) ?>"
			><br>
			<?php if (isset ($msgs['ProjectNum'])) print ($msgs['ProjectNum']) ?>
			Leave this field blank to auto-generate a new project number.<br>

			<label for="Title">Enter Project Title:</label>
			<input
				type="text"
				name="Title"
				value="<?php print ($this->fields["Title"]) ?>"
			><br>
			<?php if (isset ($msgs['Title'])) print ($msgs['Title']) ?>

			<label for="Abstract">Abstract:</label>
			<textarea
				placeholder="Enter project description"
				name="Abstract"
				class="p-desc"><?php
					print ($this->fields['Abstract'])
			?></textarea>

			<?php
				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<button type="submit" name="action"
				value="<?php print($action) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Admin.php?view=project" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
