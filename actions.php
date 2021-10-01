<!-- included by administrator.php -->

<?php include "administrator_check.php" ?>

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
					"grade"         => "Grade Levels",
					"project"       => "Projects",
					"category"      => "Project Categories",
					"booth"         => "Booths",
					"judge"         => "Judges",
					"ranking"       => "Project Rankings"
				);

				foreach ($actions as $view => $description)
				{
					print
					('
						<form
							name="' . $view . '"
							action="administrator.php"
							method="get"
						>
							<button
								type="submit"
								name="view"
								value="' . $view . '"
							>
								Manage ' . $description .

							'</button>

						</form>
					');
				}

			?>

		</div>

	</div>
</div>
