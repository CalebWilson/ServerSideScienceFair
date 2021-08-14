<!--

	Grade.php

	Grade is a class that inherits from Entity, overriding abstract methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class Grade extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//entity dependent on Grade
		$this->dependent = "student";

		//empty fields
		$this->fields = array ("GradeNum" => "");
	
	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
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

	} //end function display_data();

	//display the body of the form for adding or editing a Grade
	protected function display_form_body ($action)
	{
		//Grade Level
		$this->display_input ("number", "GradeNum", "Grade Level");

	} //end function display_form_body()

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post['GradeNum']);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		//invalidate blank GradeNum
		if ($this->invalidate_blanks (array ("GradeNum" => "Grade Level")))
		{
			if ($this->is_not_unique ("GradeNum", $original))
			{
				$this->msgs['GradeNum'] = "This grade level has already been added.";

				return false;
			}

			return true;

		} //end uniqueness

		return false;

	} //end function validate()

	//Grade has no dropdown options
	protected function get_options()
	{}

	//insert data from fields array into database
	protected function insert()
	{
		$query = $this->connection->prepare
		("
			insert into
			Grade (GradeNum)
			values (?)
		");

		$query->execute (array_values ($this->fields));

	} //end function insert()


	//update database with data from fields array
	protected function update()
	{
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
