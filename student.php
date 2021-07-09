<!--

	student.php

	This file will be included in Student->add ($post) or Student->edit ($post),
	where $post is a copy array of $_POST.

-->

<?php include "admin_check.php" ?>

<div class = "wrapper">
<title><?php print(ucfirst($action)) ?> Student</title>

<div class="main-f">
	<h1><strong><?php print($action . " ") ?>Student</strong></h1>
	<div class="form-s">
		<form action="Admin.php?view=student" method="post" >
		
			<?php print($msg) ?>

			<!-- SchoolID -->
			<label for="SchoolID">School:</label>
			<select name="SchoolID" id="SchoolID" >

				<!-- blank option -->
				<option value=""></option>

				<!-- actual options -->
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

			<?php if (isset ($msgs['SchoolID'])) print ($msgs['SchoolID']) ?>

			<!-- FirstName -->
			<label for="FirstName">First Name:</label>
			<input
				type="text"
				id="FirstName"
				name="FirstName"
				value="<?php print($this->fields["FirstName"]) ?>"
			><br>
			<?php if (isset ($msgs['FirstName'])) print ($msgs['FirstName']) ?>

			<!-- MiddleName -->
			<label for="MiddleName">Middle Name:</label>
			<input
				type="text"
				id="MiddleName"
				name="MiddleName"
				value="<?php print($this->fields["MiddleName"]) ?>"
			><br>

			<!-- LastName -->
			<label for="LastName">Last Name:</label>
			<input type="text" id="LastName" name="LastName"
				value="<?php print($this->fields["LastName"]) ?>"
			><br>
			<?php if (isset ($msgs['LastName'])) print ($msgs['LastName']) ?>

			<!-- Gender -->
			<label for="Gender">Gender:</label>
			<select name="Gender" id="Gender" >
	
				<!-- blank option -->
				<option value=""></option>

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

			<?php if (isset ($msgs['LastName'])) print ($msgs['LastName']) ?>

			<!-- ProjectID -->
			<label for="ProjectID">Project:</label>
			<select name="ProjectID" id="ProjectID" >

				<!-- blank option -->
				<option value=""></option>

				<!-- valid options -->
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

			<?php if (isset ($msgs['ProjectID'])) print ($msgs['ProjectID']) ?>

			<!-- GradeID -->
			<label for="GradeID" >Grade:</label>
			<select name="GradeID" id="GradeID" >

				<!-- blank option -->
				<option value=""></option>

				<!-- valid options -->
				<?php
					foreach ($options ['grades'] as $grade)
					{
						print ("<option value=" . $grade ['GradeID']);
						if ($grade ['GradeID'] === $this->fields ['GradeID'])
							print (" selected");
						print (">" . $grade["GradeNum"] . "</option>");
					}
				?>

			</select> <br>

			<?php if (isset ($msgs['GradeID'])) print ($msgs['GradeID']) ?>

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

	<form action="Admin.php?view=student" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>


