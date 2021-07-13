<!--

	School.php

	School is a class that inherits from Entity, overriding abstract methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class School extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//entity dependent on School
		$this->dependent = "student";

		//empty fields
		$this->fields = array ("SchoolName" => "", "CountyID" => "");

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				SchoolID as ID,
				CONCAT (SchoolName, ' (', CountyName, ')') as selection
			from School, County
			where School.CountyID = County.CountyID
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["SchoolName"]);
	}

	//validate field entries and update msgs array
	protected function validate ($original)
	{
		//invalidate blank fields
		$labels = array ("CountyID" => "County", "SchoolName" => "School name");
		if ($this->invalidate_blanks ($labels))
		{
			//set empty original to NULL if adding
			if ($original == false)
				$original = "NULL";

			//school uniqueness
			if ($this->is_not_unique ("SchoolName", $original,
				"CountyID = " . $this->fields["CountyID"])
			){
				$this->msgs['SchoolName'] =
					"There is already a school with this name in the selected county.";
				
				//if not unique
				return false;
			}

			//if non-blank and unique
			return true;
		}

		//if blank
		return false;

	} //end function validate()

	//return an array of option arrays for the form to use
	protected function get_options()
	{
		//get counties from database
		$record_set = $this->connection->query
		(
			"select CountyID, CountyName from County"
		);

		return $record_set->fetchAll();
	}

	//insert data from fields array into database
	protected function insert()
	{
		$query = $this->connection->prepare
		("
			insert into
			School (SchoolName, CountyID)
			values (         ?,        ?)
		");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$query = $this->connection->prepare
		("
			update School
			set
				SchoolName = ?,
				CountyID   = ?
			where SchoolID = ?
		");

		$query->execute (array_values($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			'<font color="green">' .
				$this->fields['SchoolName'] . ' added to schools.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg =
			'<font color="green">' .
				$this->fields['SchoolName'] . ' successfully modified.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select SchoolName, CountyID
			from School
			where SchoolID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Student
