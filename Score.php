<?php

include "Entity.php";
include "Input.php";

class Score extends Entity
{
	//constructor
	function __construct ($connection)
	{
		parent::__construct ($connection, $_SESSION['user_type']);

		$this->table = "Judging";
		$this->title = "Project Scores";

		$this->fields = array("ProjectID" => "", "Score" => "");

	} //end constructor

	/* Override abstract methods */
	//display projects to score
	public function get_data()
	{
		//get projects from the current year
		$record_set = $this->connection->query
		("
			select
				Judging.JudgingID as ID,
				concat
				(
					'Booth ', Booth.BoothNum, ': ',
					Project.Title, ' - ',
					Judging.Score

				) as selection

			from Judging, Project, Booth

			where
				Judging.ProjectID = Project.ProjectID         and
				Project.BoothID   = Booth.BoothID             and
				Judging.JudgeID   = " . $_SESSION['Judge'] . " and
				Year = YEAR(CURDATE())
		");

		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		return $records;

	} //end function get_data

	//Score page has no back button
	public function back_button()
	{}

	//display the body of the form for adding or editing
	protected function display_form_body ($action)
	{
		//Project
		$projects = Input::get_dropdown_options
		(
			$this->connection,

			"select
				Project.ProjectID as ID
				concat ('Booth ', Booth.BoothNum, ': ', Project.Title) as Name
			from Project, Booth
			where Project.BoothID = Booth.BoothID"
		);

		Input::display_dropdown
		(
			"ProjectID",
			$this->fields['ProjectID'],
			"Project to Score",
			$projects,
			$this->msgs
		);

		//Score
		Input::display_input
		(
			"number",
			"Score",
			$this->fields['Score'],
			"Score (0 - 100)",
			$this->msgs
		);

	} //end function display_form_body

	//check if score submitted
	protected function submitted ($post)
	{
		return isset ($post['Score']);
	}

	//validate score
	protected function validate ($original = "NULL")
	{
		$valid = $this->invalidate_blanks
		(
			array
			(
				"ProjectID" => "Project",
				"Score" => "Score"
			)
		);

		if ($this->fields['Score'] < 0 || 100 < $this->fields['Score'])
		{
			$this->msgs['Score'] = "Score must be between 0 and 100.";

			$valid = false;
		}

		return $valid;

	} //end function validate

	//insert and update are combined in this class
	protected function insert()
	{
		$this->insert_or_update();
	}

	protected function update()
	{
		$this->insert_or_update();
	}

	//score a project
	private function insert_or_update()
	{
		print ("JudgeID: " . $_SESSION['Judge'] . "<br>");

		//determine whether the project has been scored by this judge yet
		$query = $this->connection->prepare
		("
			select count(*) as count
			from Judging
			where JudgeID = ? and ProjectID = ?
		");

		$query->execute (array ($_SESSION['Judge'], $this->fields['ProjectID']));
		$scored = $query->fetch (PDO::FETCH_ASSOC)['count'] > 0;

		print("Scored: " . $scored);

		//query to be executed
		$query_string = "";

		//if scored before, update score
		if ($scored)
		{
			$query_string =
			"
				update Judging
				set Score = ?
				where
					JudgeID   = ? and
					ProjectID = ?
			";
		}

		//otherwise, add a new score
		else
		{
			$query_string =
			"
				insert into Judging (Score, JudgeID, ProjectID)
				values              (    ?,       ?,         ?)
			";
		}

		$query = $this->connection->prepare ($query_string);

		$query->execute
		(
			array
			(
				$this->fields['Score'],
				$_SESSION['Judge'],
				$this->fields['ProjectID']
			)
		);
	
	} //end function insert_or_update

	//confirmatino functions
	protected function confirm_add()
	{
		return
			'<font color="green">' .
				'Project successfully scored.' .
			'</font><br>'
		;
	}

	protected function confirm_edit()
	{
		return $this->confirm_add();

	} //end confirmation functions

	//set fields array to columns and values of target record from database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select ProjectID, Score
			from Judging
			where JudgingID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill

} //end class Score

?>
