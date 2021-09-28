<!--

	Project.php

	Project is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "Entity.php";
include "Input.php";
include "AutofillNumber.php";

class Project extends Entity
{
	//autofill project number
	private $autofiller;

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

		//instantiate AutofillNumber
		$this->autofiller = new AutofillNumber ($connection);

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function get_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				ProjectID as ID,
				concat ('Project ', ProjectNum, ': ', Title) as selection
			from Project
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function get_data();

	//display the body of the form for adding or editing a Project
	protected function display_form_body ($action)
	{
		//Project Title
		Input::display_input
		(
			"text",
			"Title",
			$this->fields['Title'],
			"Title",
			$this->msgs
		);

		//Category
		$categories = Input::get_dropdown_options
		(
			$this->connection,
			"select CategoryID as ID, CategoryName as Name from Category"
		);

		Input::display_dropdown
		(
			"CategoryID",
			$this->fields['CategoryID'],
			"Category",
			$categories,
			$this->msgs
		);

		//ProjectNum
		Input::display_input
		(
			"number",
			"ProjectNum",
			$this->fields['ProjectNum'],
			"Project Number",
			$this->msgs
		);
		print ("Leave this field blank to auto-generate a new project number.<br>");

		//Booth
		$booths = Input::get_dropdown_options
		(
			$this->connection,
			"select BoothID as ID, BoothNum as Name from Booth"
		);

		Input::display_dropdown
		(
			"BoothID",
			$this->fields['BoothID'],
			"Booth",
			$booths,
			$this->msgs
		);
		if ($action == "edit")
		{
			print
			(
				"Selecting a Booth already in use will cause the Project using it " .
				"to swap Booths with this Project.<br>"
			);
		}

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

	//override edit for autofilling
	public function edit ($post)
	{
		$this->autofiller->set_id ($post);

		parent::edit($post);

	} //end function edit

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["Title"]);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		$valid = Input::invalidate_blanks
		(
			$this->fields,
			array
			(
				"BoothID"    => "Booth",
				"CategoryID" => "Category",
				"Title"      => "Title"
			),
			$this->msgs
		);

		/* uniqueness */
		$year_condition = "Year = YEAR(CURDATE())";

		//Title
		if
		(
			Input::is_duplicate
			(
				$this->connection,
				$this->table,
				"Title",
				$this->fields['Title'],
				$original,
				$year_condition
			)
		)
		{
			$valid = false;

			$this->msgs['Title'] =
				"There is already a project with that title this year.";
		}

		//Booth
		if (!$this->autofiller->has_id())
		{
			if
			(
				Input::is_duplicate
				(
					$this->connection,
					$this->table,
					"BoothID",
					$this->fields['BoothID'],
					$original,
					$year_condition
				)
			)
			{
				$valid = false;

				$this->msgs['BoothID'] =
					"There is already a project at that booth this year.";
			}
		}

		//Project Number
		if
		(
			Input::is_duplicate
			(
				$this->connection,
				$this->table,
				"ProjectNum",
				$this->fields,
				$original,
				$year_condition
			)
		)
		{
			$valid = false;

			$this->msgs['ProjectNum'] =
				"There is already a project with this number this year.";
		}

		return $valid;

	} //end function validate()

	//autofill number
	private function autofill()
	{
		$this->autofiller->autofill_number
		(
			$this->fields,
			"Project",
			"ProjectNum",
			"Year = YEAR(CURDATE())"
		);

	} //end function autofill

	//insert data from fields array into database
	protected function insert()
	{
		$this->autofill();

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
		$this->autofill();

		$this->print_assoc ($this->fields);

		$query = $this->connection->prepare ("call UpdateProjectBooth (?, ?)");
		$query->execute ([$this->fields['ID'], $this->fields['BoothID']]);

		$booth = $this->fields['BoothID'];
		unset ($this->fields['BoothID']);

		$query = $this->connection->prepare
		("
			update Project
			set
				Title      = ?,
				CategoryID = ?,
				ProjectNum = ?,
				Abstract   = ?
			where ProjectID = ?
		");

		$query->execute (array_values($this->fields));

		$this->fields['BoothID'] = $booth;

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

	//set fields to current database values of the record to be edited
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

?>
