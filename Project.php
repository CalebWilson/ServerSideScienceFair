<!--

	Project.php

	Project is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class Project extends Entity
{
	//id of project for use in autofill_ProjectNum
	private $project_id;

	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//entity dependent on Project
		$this->dependent = "student";

		//empty fields
		$this->fields = array
		(
			"BoothID"    => "",
			"CategoryID" => "",
			"ProjectNum" => "",
			"Title"      => "",
			"Abstract"   => ""
		);

	} //end constructor

	//autofill ProjectNum, used by insert() and update()
	private function autofill_ProjectNum()
	{
		if ($this->fields['ProjectNum'] == "")
		{
			$query_string = "";

			//if project already has a ProjectNum, get it
			if (isset ($this->project_id))
			{
				$query =
				"
					select ProjectNum
					from Project
					where ProjectID = " . $this->project_id
				;
			}

			//otherwise, generate a new one
			else
			{
				$query = 
				"
					select MAX(ProjectNum) + 1 as ProjectNum
					from Project
					where Year = YEAR(CURDATE())
				";
			}

			$record_set = $this->connection->query($query);

			$this->fields['ProjectNum'] =
				$record_set->fetch(PDO::FETCH_ASSOC)['ProjectNum'];
		}

	} //end function autofill_ProjectNum()

	//override Entity::edit to save ID for autofill_ProjectNum
	public function edit ($post)
	{
		//capture ID
		if (isset ($post['selected']) && count($post['selected']) === 1)
		{
			$this->project_id = $post['selected'][0];
		}

		//continue with normal edit
		parent::edit ($post);

	} //end function edit()

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select ProjectID as ID,
			Title as selection
			from Project
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();
	
	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["Title"]);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		$valid = $this->invalidate_blanks
		(
			array
			(
				"BoothID"    => "Booth",
				"CategoryID" => "Category",
				"Title"      => "Title"
			)
		);

		/* uniqueness */
		$year_condition = "Year = YEAR(CURDATE())";
		if ($this->is_not_unique ("Title", $original, $year_condition))
		{
			$valid = false;
			$this->msgs['Title'] =
				"There is already a project with that title this year.";
		}

		//Project Number
		if ($this->is_not_unique ("ProjectNum", $original, $year_condition))
		{
			$valid = false;

			$this->msgs['ProjectNum'] =
				"There is already a project with this number this year.";
		}

		return $valid;

	} //end function validate()

	//return an array of option arrays for the form to use
	protected function get_options()
	{
		$options = array();

		//BoothNums
		$record_set = $this->connection->query ("select BoothID, BoothNum from Booth");
		$options['booths'] = $record_set->fetchAll();

		//Categories
		$record_set = $this->connection->query
			("select CategoryID, CategoryName from Category");
		$options['categories'] = $record_set->fetchAll();

		return $options;

	} //end function get_options()

	//insert data from fields array into database
	protected function insert()
	{
		$this->autofill_ProjectNum();

		$query = $this->connection->prepare
		("
			insert into
				Project (BoothID, CategoryID, ProjectNum, Title, Abstract)
				values  (      ?,        ?,          ?,     ?,        ?)
		");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$this->autofill_ProjectNum();

		$query = $this->connection->prepare(
		"
			update Project
			set
				BoothID    = ?,
				CategoryID = ?,
				ProjectNum = ?,
				Title      = ?,
				Abstract   = ?
			where ProjectID = ?
		");

		$query->execute (array_values($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg = 'Successfully added project ' . $this->fields['Title'] . '.';
		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit()
	{
		$msg = 'Successfully modified project ' . $this->fields['Title'] . '.';
		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_edit()

	//set fields array to columns and values of target record from database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select BoothID, CategoryID, ProjectNum, Title, Abstract
			from Project
			where ProjectID = " . $target
		);
		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Project
