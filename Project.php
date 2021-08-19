<!--

	Project.php

	Project is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "AutofillNumberEntity.php";

class Project extends AutofillNumberEntity
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

	//display the body of the form for adding or editing a Project
	protected function display_form_body ($action)
	{
		$options = $this->get_options();

		//Project Title
		$this->display_input ("text", "Title", "Title");

		//Category
		$this->display_dropdown
		(
			"CategoryID",
			"Category",
			$options['categories'],
			"CategoryName"
		);

		//ProjectNum
		$this->display_input ("number", "ProjectNum", "Project Number");
		print ("Leave this field blank to auto-generate a new project number.<br>");

		//Booth
		$this->display_dropdown
		(
			"BoothID",
			"Booth",
			$options['booths'],
			"BoothNum"
		);

		//Abstract
		print
		('
			<label for="Abstract">Abstract:</label>
			<textarea
				placeholder="Enter project description"
				name="Abstract"
				class="p-desc">' . $this->fields['Abstract'] . '
			</textarea>
		');

	} //end function display_form_body()
	
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
		$this->autofill_number ("ProjectNum", "Year = YEAR(CURDATE())");

		$query = $this->connection->prepare
		("
			insert into
				Project (Title, CategoryID, ProjectNum, BoothID, Abstract)
				values  (    ?,          ?,          ?,       ?,        ?)
		");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$this->autofill_number ("ProjectNum", "Year = YEAR(CURDATE())");

		$query = $this->connection->prepare(
		"
			update Project
			set
				Title      = ?,
				CategoryID = ?,
				ProjectNum = ?,
				BoothID    = ?,
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
