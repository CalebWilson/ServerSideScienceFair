<!--

	Booth.php

	Booth is a class that inherits from Entity, overriding methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";
include "Input.php";
include "AutofillNumber.php";

class Booth extends Entity
{
	//autofill booth number
	private $autofiller;

	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields = ["BoothNum" => ""];

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
		Input::display_input
		(
			"number",
			"BoothNum",
			$this->fields['BoothNum'],
			"Booth Number",
			$this->msgs
		);

	} //end function display_form_body()

	//override edit for autofilling
	public function edit ($post)
	{
		$this->autofiller->set_id ($post);

		parent::edit($post);

	} //end function edit

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		//return false if Booth is non-blank and duplicate
		if ($this->fields['BoothNum'] != "")
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

		} //end if Booth is non-blank and duplicate

		//return true otherwise
		return true;

	} //end validate()

	//autofill number
	private function autofill()
	{
		$this->autofiller->autofill_number
		(
			$this->fields,
			"Booth",
			"BoothNum"
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

		parent::update();

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

