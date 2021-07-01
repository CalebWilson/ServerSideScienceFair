<?php include "admin_check.php" ?>

<title><?php print($action . " ") ?>Session</title>
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print($action . " ") ?>Session</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=session" method="post" >

			<?php print($msg) ?>

			<!-- SessionNum -->
			<label for="SessionNum">Session number:</label>
			<input type="number" id="SessionNum" name="SessionNum"
				value=<?php print ($this->fields["SessionNum"]) ?>><br>
			<?php if (isset ($msgs['SessionNum'])) print ($msgs['SessionNum']) ?>
			If this field is left blank, a session number will be auto-generated.<br>

			<!-- StartTime -->
			<label for="StartTime">Session start time:</label>
			<input type="datetime-local" id="StartTime" name="StartTime"
				value="<?php print ($this->fields["StartTime"]) ?>"><br>
			<?php if (isset ($msgs['StartTime'])) print ($msgs['StartTime']) ?>

			<!-- EndTime -->
			<label for="EndTime">Session end time:</label>
			<input type="datetime-local" id="EndTime" name="EndTime"
				value="<?php print ($this->fields["EndTime"]) ?>"><br>
			<?php if (isset ($msgs['EndTime'])) print ($msgs['EndTime']) ?>

			<?php
				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<!-- This isn't ideal but it works for now -->
			Please enter times as YYYY-MM-DD HH:MM:SS.<br><br>

			<button type="submit" name="action"
				value="<?php print($action) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Admin.php?view=session" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
