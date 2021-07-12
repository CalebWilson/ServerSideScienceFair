<!--

	category.php

	This file will be included in Category->add ($post) or Category->edit ($post),
	where $post is a copy array of $_POST.

-->


<?php include "admin_check.php" ?>

<title><?php print(ucfirst($action)) ?> Category</title>
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print(ucfirst($action)) ?> Category</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=category" method="post" >

			<?php print($msg) ?>

			<!-- CategoryName -->
			<div class="label">
				<label for="CategoryName">Category Name:</label>
				<input
					type="text"
					name="CategoryName"
					value="<?php print ($this->fields["CategoryName"])?>"
				><br>
			</div>

			<?php if (isset ($msgs['CategoryName'])) print($msgs['CategoryName']) ?>

			<?php

				//preserve selected
				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<button type="submit" name="action"
				value="<?php print($action) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Admin.php?view=category" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
