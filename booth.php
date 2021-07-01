<!--

	booth.php

	This file will be included in Booth->add ($post) or Booth->edit ($post), where
	$post is a copy array of $_POST.

-->

<title><?php print($action . " ") ?>Booth</title>
<div class="wrapper">
<div class="main-f">
	<h1><strong><?php print($action . " ") ?>Booth</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=booth" method="post" >

			<?php print($msg) ?>
			
			<label for="BoothNum">Booth Number:</label>
			<input
				type="number"
				name="BoothNum"
				id="BoothNum"
				value="<?php print($this->fields['BoothNum']) ?>"
			><br>

			<?php
				if (isset ($msgs['BoothNum']))
					print($msgs['BoothNum']);

				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<button type="submit" name="action"
				value="<?php print($action) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Admin.php?view=booth" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
