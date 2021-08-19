<?php

	$project_records = $connection->query
	("
		select distinct Title, BoothNum
		from Schedule, Project, Booth
		where
			Schedule.ProjectID = Project.ProjectID and
			Project.BoothID    = Booth.BoothID
		order by BoothNum
	");
	$projects = $project_records->fetchAll (PDO::FETCH_ASSOC);

	$judging_records = $connection->query
	("
		select
			Schedule.SessionID as SessionID, StartTime, EndTime,
			Booth.BoothNum as BoothNum,
			Judge.Title as Title, FirstName, LastName
		from Schedule, Session, Judge, Project, Booth
		where
			Schedule.SessionID = Session.SessionID and
			Schedule.JudgeID   = Judge.JudgeID     and
			Schedule.ProjectID = Project.ProjectID and
			Project.BoothID    = Booth.BoothID
		order by StartTime, BoothNum
	");

	$judgings = $judging_records->fetchAll (PDO::FETCH_ASSOC);

	$cur_session = $judgings[0]['SessionID'];
	$cur_booth   = $judgings[0]['BoothNum'];

?>

<div class="wrapper">
<div class="full-schedule">

<h1>Schedule</h1>

<table class="schedule-table">

	<thead>
		<th class="schedule-cell"></th>

		<?php

			foreach ($projects as $project)
			{
				print
				(
					'<th class="schedule-cell">Booth ' . $project['BoothNum'] .
						':<br>' .  $project['Title'] .
					'</th>'
				);
			}
		?>

	</th>
	</thead>

	<tbody>

		<tr>
			<td class="schedule-cell">

			<?php

				foreach ($judgings as $judging)
				{
					//if row over
					if ($judging['SessionID'] != $cur_session)
					{
						//end cell, update current booth
						//end row, update current session
						//begin new row
						//begin new cell
					}

					//if cell over
					else if ($judging['BoothNum'] != $cur_booth)
					{
						//end cell, update current booth
						//begin new cell
					}

					//print judge name
				}


			?>

			</td>
		</tr>
	

</table>

</div>
</div>
