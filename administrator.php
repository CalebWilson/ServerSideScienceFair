<!--

	administrator.php

	This file will be included in Administrator->add ($post) or
	Administrator->view ($post), where $post is a copy array of $_POST.

-->

<?php require_once "header.php" ?>

<title><?php print(ucfirst($action)) ?> Administrator</title>

<div class="wrapper">
<div class="main-f">
	<h1><strong><?php print(ucfirst($action)) ?> Administrator</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=administrator" method="post">

			<?php print($msg) ?>

			<!-- FirstName -->
			<label for="FirstName">First Name:</label>
			<input type="text" id="FirstName" name="FirstName"
				value=<?php print($this->fields["FirstName"]) ?>><br>
			<?php if (isset ($msgs['FirstName'])) print ($msgs['FirstName']) ?>

			<!-- MiddleName -->
			<label for="MiddleName">Middle Name:</label>
			<input type="text" id="MiddleName" name="MiddleName"
				value=<?php print($this->fields["MiddleName"]) ?>><br>

			<!-- LastName -->
			<label for="LastName">Last Name:</label>
			<input type="text" id="LastName" name="LastName"
				value=<?php print($this->fields["LastName"]) ?>><br>
			<?php if (isset ($msgs['LastName'])) print ($msgs['LastName']) ?>
		
			<!-- Email -->
			<label for="Email">Email:</label>
			<input type="text" id="Email" name="Email"
				value=<?php print($this->fields["Email"]) ?>><br>
			<?php if (isset ($msgs['Email'])) print ($msgs['Email']) ?>

			<!-- Username -->
			<label for="Username">Username:</label>
			<input type="text" id="Username" name="Username"
				value=<?php print($this->fields["Username"]) ?>><br>
			<?php if (isset ($msgs['Username'])) print ($msgs['Username']) ?>
			
			<!-- Password -->
			<label for="Password">
			<?php if ($action == "edit") print("New ") ?>Password:</label>

			<input type="Password" id="Password" name="Password"
				<?php
					if (isset($this->fields['Password']))
						print(' value="' . $this->fields['Password'] . '"')
				?>><br>
			<?php if (isset ($msgs['Password'])) print ($msgs['Password']) ?>

			<!-- Confirm Password -->
			<label for="pass_conf">Confirm
			<?php if ($action == "Edit") print("New ") ?>Password:</label>

			<input type="password" id="pass_conf" name="pass_conf"
				<?php if (isset($this->fields['pass_conf']))
					print(' value="' . $this->fields['pass_conf'] . '"')?>
			><br>
			<?php if (isset ($msgs['pass_conf'])) print ($msgs['pass_conf']) ?>

			<!-- AuthorityLevel -->
			<label for="AuthorityLevel">Authority Level:</label>
			<select name="AuthorityLevel" id="AuthorityLevel" class="drop-down">
			<option value=2
				<?php if ($this->fields["AuthorityLevel"] == "2")
					print (" selected") ?>>Normal Admin</option>
			<option value=1
				<?php if ($this->fields["AuthorityLevel"] == "1")
					print (" selected") ?>>Super Admin</option>
			</select><br>

			<?php
				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<?php
				if ($this->get_authority() === "1")
				{
					print
					('
						<button
							type="submit" name="action"
							value="' . $action . '"
							class="btn"
						>' .  $action .
						' </button>
					');
				}
			?>

		</form>

	</div>

	<form action="Admin.php?view=administrator" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
