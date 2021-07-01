<?php include "admin_check.php" ?>

<div class = "wrapper">
<title><?php print($action . " ") ?>School</title>

<div class="main-f">
	<h1><strong><?php print($action . " ") ?>Student</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=student" method="post" >
		
			<?php print($msg) ?>

			<label for="SchoolID">School:</label>
			<select name="SchoolID" id="SchoolID" class="drop-down">
				<?php
					foreach ($options['schools'] as $school)
					{
						print ("<option value=" . $school["SchoolID"]);
						if ($school['SchoolID'] === $this->fields['SchoolID'])
							print (" selected");
						print(">" . $school["SchoolName"] . "</option>");
					}
				?>
			</select><br>

			<label for="FirstName">First Name:</label>
			<input
				type="text"
				id="FirstName"
				name="FirstName"
				value="<?php print($this->fields["FirstName"]) ?>"
			><br>
			<?php if (isset ($msgs['FirstName'])) print ($msgs['FirstName']) ?>

			<label for="MiddleName">Middle Name:</label>
			<input
				type="text"
				id="MiddleName"
				name="MiddleName"
				value="<?php print($this->fields["MiddleName"]) ?>"
			><br>

			<label for="LastName">Last Name:</label>
			<input type="text" id="LastName" name="LastName"
				value="<?php print($this->fields["LastName"]) ?>"
			><br>
			<?php if (isset ($msgs['LastName'])) print ($msgs['LastName']) ?>

			<label for="Gender">Gender:</label>
			<select name="Gender" id="Gender" class="drop-down">
				<option value="Male"
					<?php if ($this->fields['Gender'] == "Male") print (" selected") ?>
				>Male</option>

				<option value="Female"
					<?php if ($this->fields['Gender'] == "Female") print (" selected") ?>
				>Female</option>

				<option value="Other"
					<?php if ($this->fields['Gender'] == "Other") print (" selected")?>
				>Other</option>
			</select><br>

			<label for="ProjectID">Project:</label>
			<select name="ProjectID" id="ProjectID" class="drop-down">
				<?php
					foreach ($options['projects'] as $project)
					{
						print ("<option value=" . $project["ProjectID"]);
						if ($project['ProjectID'] === $this->fields['ProjectID'])
							print (" selected");
						print (">" . $project["Title"] . "</option>");
					}
				?>
			</select><br>

			<label for="Grade" >Grade:</label>
			<select name="Grade" id="Grade" class="drop-down">
				<?php
					foreach ($options['grades'] as $grade)
					{
						print ("<option value=" . $grade["GradeID"]);
						if ($grade['GradeID'] === $this->fields['GradeID'])
							print (" selected");
						print (">" . $grade["GradeNum"] . "</option>");
					}
				?>
			</select> <br>

			<?php
				if (isset($post['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$post['selected'][0] . '">'); 
			?>

			<button type="submit" name="action"
				value="<?php print($action) ?>" class="btn">Submit</button>
         
		</form>

	</div>

	<form action="Admin.php?view=student" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>


