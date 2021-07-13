<!--

	county.php

	This file will be included in County->add ($post) or County->edit ($post),
	where $post is a copy array of $_POST.

-->

<?php include "admin_check.php" ?>

<title><?php print (ucfirst($action)) ?> County</title>

<div class="wrapper">
	<div class="main-f">
		<h1><strong><?php print (ucfirst($action)) ?> County</strong></h1>
		<div class="form-s">
			<form action="Admin.php?view=county" method="post">

				<?php print ($msg) ?>

				<!-- CountyName -->
				<label for="CountyName">County Name:</label>
				<input
					type="text"
					name="CountyName"
					value="<?php print($this->fields['CountyName']) ?>"
				><br>

				<?php
					if (isset ($this->msgs['CountyName']))
						print ($this->msgs['CountyName'])
				?>

				<?php

					//preserve selected
					if (isset($post['selected'][0]))
						print ('<input type="hidden" name="selected[]" value="' .
							$post['selected'][0] . '">'); 
				?>

				<!-- Submit -->
				<button
					type="submit"
					name="action"
					value="<?php print ($action) ?>"
					class="btn"
				>Submit</button>

			</form>

		</div>

		<form action="Admin.php?view=county" method="post" class="back-btn">
			<button type="submit">Back</button>
		</form>
					
	</div>
</div>
