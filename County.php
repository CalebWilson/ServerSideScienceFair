<!--

	County.php

	County is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "Entity.php";
include "Input.php";

class County extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);
		$this->title = "Counties";

		//entity dependent on County
		$this->dependent = "school";

		//empty fields
		$this->fields = array ("CountyName" => "");

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function get_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select CountyID as ID, CountyName as selection from County
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function get_data();

	//display the body of the form for adding or editing a County
	protected function display_form_body ($action)
	{
		//County Name
		Input::display_input
		(
			"text",
			"CountyName",
			$this->fields['CountyName'],
			"County Name",
			$this->msgs
		);

	} //end function display_form_body()

	//check whether data has been submitted
	protected function submitted ($post)
	{		
		return isset ($post["CountyName"]);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		//invalidate blank county name
		if
		(
			Input::invalidate_blanks
			(
				$this->fields,
				array ("CountyName" => "County name"),
				$this->msgs
			)
		)

		//if not blank, check uniqueness
		{
			if
			(
				Input::is_duplicate
				(
					$this->connection,
					$this->table,
					"CountyName",
					$this->fields['CountyName'],
					$original
				)
			)
			{
				$this->msgs['CountyName'] =
					"There is already a County with this name.";

				//not unique
				return false;
			}

			//valid
			return true;

		} //end uniqueness

		//blank
		return false;

	} //end function validate()

	//insert data from fields array into database
	protected function insert()
	{
		$query = $this->connection->prepare
		(
			"insert into County (CountyName) values (?)"
		);

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$query = $this->connection->prepare
		(
			"update County set CountyName = ? where CountyID = ?"
		);

		$query->execute (array_values($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			'<font color="green">' .
				$this->fields['CountyName'] . ' added to Counties.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg =
			'<font color="green">' .
				$this->fields['CountyName'] . ' successfully modified.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select CountyName
			from County
			where CountyID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Student

