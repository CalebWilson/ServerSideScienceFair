<!--

	Grade.php

	Grade is a class that inherits from Entity, overriding abstract methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";
include "Input.php";
include "AutofillNumber.php";

class Grade extends Entity
{
	//autofill grade number
	private $autofiller;

	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//entity dependent on Grade
		$this->dependent = "student";

		//empty fields
		$this->fields = array ("GradeNum" => "");

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
			select GradeID as ID, GradeNum as selection from Grade
		");

		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function get_data();

	//display the body of the form for adding or editing a Grade
	protected function display_form_body ($action)
	{
		//Grade Level
		Input::display_input
		(
			"number",
			"GradeNum",
			$this->fields['GradeNum'],
			"Grade Level",
			$this->msgs
		);
		print ("Leave this field blank to auto-generate a new grade level.<br>");

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
		return isset ($post['GradeNum']);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		//return false if Grade is non-blank and duplicate
		if ($this->fields['GradeNum'] != "")
		{
			if
			(
				Input::is_duplicate
				(
					$this->connection,
					$this->table,
					"GradeNum",
					$this->fields['GradeNum'],
					$original
				)
			)
			{
				$this->msgs['GradeNum'] = "This grade level has already been added.";

				return false;
			}

		} //end if Grade is non-blank and duplicate

		//return true otherwise
		return true;

	} //end function validate()

	//autofill number
	private function autofill()
	{
		$this->autofiller->autofill_number
		(
			$this->fields,
			"Grade",
			"GradeNum"
		);

	} //end function autofill

	//insert data from fields array into database
	protected function insert()
	{
		$this->autofill();

		parent::insert();

	} //end function insert()


	//update database with data from fields array
	protected function update()
	{
		$this->autofill();

		$query = $this->connection->prepare
		("
			update Grade
			set GradeLevel = ?
			where GradeID = ?
		");

		$query->execute (array_values ($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			'<font color="green">' .
				"Grade " . $this->fields['GradeNum'] . ' added to Grades.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg =
			'<font color="green">' .
				"Grade" . $this->fields['GradeNum'] . ' successfully modified.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select GradeNum
			from Grade
			where GradeID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Grade
