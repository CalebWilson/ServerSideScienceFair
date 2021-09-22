<?php

class FullSchedule
{
	private $projects;

	private $judgings;

	private $cur_day;
	private $cur_session;
	private $cur_booth;

	//constructor
	private function __construct ($connection)
	{
		//get projects
		$project_records = $connection->query
		("
			select distinct Title, BoothNum
			from Project, Booth
			where Project.BoothID    = Booth.BoothID
			order by BoothNum
		");
		$this->projects = $project_records->fetchAll (PDO::FETCH_ASSOC);

		//get judgings
		$judging_records = $connection->query ("select * from FullSchedule_vw");
		$this->judgings = $judging_records->fetchAll (PDO::FETCH_ASSOC);

		//replace judging session start and end times with DateTimes
		foreach ($this->judgings as &$judging)
		{
			$judging['StartTime'] = date_create ($judging['StartTime']);
			$judging['EndTime'] = date_create ($judging['EndTime']);
		}
		unset ($judging);

		//set sentries
		$this->cur_day     = $this->date_to_day ($this->judgings[0]['StartTime']);
		$this->cur_session = $this->judgings[0]['SessionID'];
		$this->cur_booth   = $this->judgings[0]['BoothNum'];
	}

	//date formatting
	private static function date_to_day ($date)
	{
		return $date.format ("l m-d-y");
	}

	private static function date_to_time ($date)
	{
		return $date.format ("g:i a");
	}

	//display schedule
	public function render()
	{
		print
		('
			<div class="wrapper">
			<div class="full-schedule">

			<h1>Schedule</h1>
		');

		self::open_table ($this->judgings[0]);

		self::render_judging ($this->judgings[0]);

		//for each judging
		foreach (array_slice ($judgings, 1) as $judging)
		{
			//if new day, new table
			if (self::date_to_day ($judging) != $this->cur_day)
			{
				//end table
				print('</td><tr></tbody></table><br/>');

				//new table
				self::open_table ($judging);

				//update booth, session, and day
				$this->cur_booth   = $judging['BoothNum'];
				$this->cur_session = $judging['SessionID'];

				$this->day = self::date_to_day ($judging['StartTime']);
			}

			//if new row
			else if ($judging['SessionID'] != $this->cur_session)
			{
				//end previous row
				print ('</td></tr>');

				//begin new row
				self::open_row ($judging);

				//update booth and session
				$this->cur_booth   = $judging['BoothNum'];

				$this->cur_session = $judging['SessionID'];
			}
	
			//if new cell
			else if ($judging['BoothNum'] != $this->cur_booth)
			{
				//end previous cell
				print ('</td>');

				//begin new cell
				self::open_cell();

				//update booth
				$this->cur_booth = $judging['BoothNum'];
			}

			//print judge
			self::render_judging ($judging);

		} //end for each judging

		//close table
		print ('</td></tr></tbody></table><br/>');

	} //end function render

	//begin a table, row, and cell
	private function open_table ($judging)
	{
		//start table
		print
		('
			<table class="schedule-table">

				<thead>
					<th class="schedule-cell">' .
						self::date_to_day($judging['StartTime']) . '
					</th>
		');

		//table header
		foreach ($this->projects as $project)
		{
			print
			(
					'<th class="schedule-cell">Booth ' . $project['BoothNum'] .
						':<br/>' .  $project['Title'] .
					'</th>
			');
		}

		print
		('
			</thead>

			<tbody>
		');

		//begin first row
		self::open_row ($judging);

	} //end function open_table

	//begin a row
	private static function open_row ($judging)
	{
		print ('<tr>');
		
		self::open_cell();

		print
		(
				self::date_to_time ($judging['StartTime']) .
				" - " .
				self::date_to_time ($judging['EndTime']) .
			'</td>'
		);

		self::open_cell();

	} //end function open_row

	//begin a cell
	private static function open_cell()
	{
		print ('<td class="schedule-cell">');

	} //end function open_cell

	//render one of the contents of a cell
	private static function render_judging ($judging)
	{
		print
		(
			$judgings['Title']     . " " .
			$judgings['FirstName'] . " " .
			$judgings['LastName']  .
			"<br/>"
		);

	} //end function render_judging

} //end class FullSchedule

?>
