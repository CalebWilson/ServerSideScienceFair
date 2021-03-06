<!--

	School.php

	School is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "Entity.php";
include "Input.php";

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
	public function get_data()
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

	} //end function get_data();

	//display the body of the form for adding or editing a School
	protected function display_form_body ($action)
	{
		//School Name
		Input::display_input
		(
			"text",
			"SchoolName",
			$this->fields['SchoolName'],
			"School Name",
			$this->msgs
		);

		//County
		$counties = Input::get_dropdown_options 
		(
			$this->connection,
			"select CountyID as ID, CountyName as Name from County"
		);

		Input::display_dropdown
		(
			"CountyID",
			$this->fields['CountyID'],
			"County",
			$counties,
			$this->msgs
		);

	} //end function display_form_body()


	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["SchoolName"]);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		//invalidate blank fields
		$labels = array ("CountyID" => "County", "SchoolName" => "School name");
		if (Input::invalidate_blanks ($this->fields, $labels, $this->msgs))
		{
			//school uniqueness
			if
			(
				Input::is_duplicate
				(
					$this->connection,
					$this->table,
					"SchoolName",
					$this->fields['SchoolName'],
					$original,
					"CountyID = " . $this->fields["CountyID"]
				)
			)
			{
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
