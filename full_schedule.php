<?php

	$project_records = $connection->query
	("
		select Title, BoothNum
		from Schedule, Project, Booth
		where
			Schedule.ProjectID = Project.ProjectID and
			Project.BoothID    = Booth.BoothID
	");
	$projects = $project_records->fetchAll (PDO::FETCH_ASSOC);

	$sess

?>
<div class="wrapper">
<div class="full-schedule">

<h1>Schedule</h1>

<table class="schedule-table">

	<th>
		<td class="schedule-cell"></td>

		<?php

			foreach ($projects as $project)
			{
				print
				(
					'<td class="schedule-cell">Booth ' . $project['BoothNum'] .
						':<br>' .  $project['Title'] .
					'</td>'
				);
			}
		?>

	</th>

	<?php

		//foreach ($

	?>
	

</table>

</div>
</div>
