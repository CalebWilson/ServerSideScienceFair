
<!--

	Booth.php

	Booth is a class that inherits from Entity, overriding methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";

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
	public function display_data()
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

	} //end function display_data();

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
		if ($this->invalidate_blanks (array ("BoothNum" => "Booth number")))
		{
			if ($this->is_not_unique ("BoothNum", $original))
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


	//Booth has no option lists
	protected function get_options()
	{}

	//insert data from fields array into database
	protected function insert()
	{
		$query = $this->connection->prepare
		("
			insert into
				Booth  (BoothNum)
				values (?)
		");

		$query->execute(array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		//append ID to fields
		$query = $this->connection->prepare
		("
			update Booth
			set BoothNum = ?
			where BoothID = ?
		");

		$query->execute(array_values($this->fields));

	} //end function update()

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

