<!--

	Booth.php

	Booth is a class that inherits from Entity, overriding methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";
include "Input.php";

class Booth extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields = ["BoothNum" => ""];

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function get_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				BoothID as ID,
				CONCAT ('Booth ', BoothNum) as selection
			from Booth
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function get_data();

	//display the body of the form for adding or editing a Booth
	protected function display_form_body ($action)
	{
		//BoothNum
		$this->display_input ("number", "BoothNum", "Booth Number");

	} //end function display_form_body()

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post['BoothNum']);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		if
		(
			Input::invalidate_blanks
			(
				$this->fields,
				array ("BoothNum" => "Booth number"),
				$this->msgs
			)
		)
		{
			if
			(
				Input::is_duplicate
				(
					$this->connection,
					$this->table,
					"BoothNum",
					$this->fields['BoothNum'],
					$original
				)
			)
			{
				$this->msgs['BoothNum'] =
					"There is already a booth with this number.";

				//not unique
				return false;
			}

			//not blank and unique
			return true;

		} //end uniqueness

		//blank
		return false;

	} //end validate()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg = 'Successfully added Booth ' . $this->fields['BoothNum'];
		return '<font color = green>' . $msg . '.</font><br>';

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit()
	{
		$msg = 'Successfully modified Booth ' . $this->fields['BoothNum'];
		return '<font color = green>' . $msg . '.</font><br>';

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select BoothNum
			from Booth
			where BoothID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Booth

?>

