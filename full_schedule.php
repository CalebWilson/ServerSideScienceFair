<?php

	$project_records = $connection->query
	("
		select distinct Title, BoothNum
		from Project, Booth
		where Project.BoothID    = Booth.BoothID
		order by BoothNum
	");
	$projects = $project_records->fetchAll (PDO::FETCH_ASSOC);

/*
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
*/

	$judging_records = $connection->query ("select * from FullSchedule_vw");
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
			<td class="schedule-cell"> <?php
				print($judgings[0]['StartTime'] .  " - " .  $judgings[0]['EndTime']);
			?> </td>
			
			<td class="schedule-cell"> <?php
				print
				(
					$judgings[0]['Title']     . " " .
					$judgings[0]['FirstName'] . " " .
					$judgings[0]['LastName']  .
					"<br/>"
				);
				
			?> </td>

			<?php

				foreach (array_slice ($judgings, 1) as $judging)
				{
					//if new row
					if ($judging['SessionID'] != $cur_session)
					{
						//end previous row and begin new row
						print
						('
								</td>
							</tr>

							<tr>
								<td class="schedule-cell">' .
									$judging['StartTime'] . ' - ' . $judging['EndTime'] . '
								</td>

								<td class="schedule-cell">
						');

						//update booth and session
						$cur_booth = $judging['BoothNum'];
						$cur_session = $judging['SessionID'];
					}

					//if new cell
					else if ($judging['BoothNum'] != $cur_booth)
					{
						//end previous cell and begin new cell
						print('</td><td class="schedule-cell">');

						//update booth
						$cur_booth = $judging['BoothNum'];
					}

					//print judge
					print
					(
						$judging['Title']     . " " .
						$judging['FirstName'] . " " .
						$judging['LastName']  .
						"<br/>"
					);
				}


			?>

			</td>
		</tr>
	

</table>

</div>
</div>
