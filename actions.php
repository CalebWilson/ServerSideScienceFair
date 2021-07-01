<!-- included by Admin.php -->

<?php include "admin_check.php" ?>

<title>Administrative Actions</title>
<div class="wrapper">
	<div class="main-f">
		<h1><strong>Administrative Actions</strong></h1>
		<div class="admin-action">	

			<?php

				$actions = array
				(
					"administrator" => "Administrators",
					"county"        => "Counties",
					"school"        => "Schools",
					"student"       => "Students",
					"project"       => "Projects",
					"category"      => "Project Categories",
					"grade"         => "Project Grades",
					"booth"         => "Booths",
					"judge"         => "Judges",
					"session"       => "Judge Sessions",
					"ranking"       => "Project Rankings"
				);

				foreach ($actions as $view => $description)
				{
					print
					('
						<form
							name="' . $view . '"
							action="Admin.php?view=' . $view . '"
							method="post"
						>
							<button type="submit">Manage ' . $description . '</button>

						</form>
					');
				}

			?>

		</div>

	</div>
</div>
