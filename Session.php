<!--

	Session.php

	Session is a class that inherits from AutofillNumberEntity, overriding abstract
	methods from Entity to achieve polymorphic behavior.

-->

<?php

include "AutofillNumberEntity.php";

class Session extends AutofillNumberEntity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields =
			array ("SessionNum" => "", "StartTime" => "", "EndTime" => "");

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				SessionID as ID,
				CONCAT
				(
					SessionNum, ': ',
					TIME(StartTime), ' - ',
					TIME(EndTime)
				) as selection
			from Session
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();

	//display the body of the form for adding or editing a Session
	protected function display_form_body ($action)
	{
		//SessionNum
		$this->display_input ("number", "SessionNum", "Session Number");
		print
		("
			If this field is left blank, a session number will be auto-generated.<br>
		");

		$this->display_input ("datetime-local", "StartTime", "Session Start Time");
		$this->display_input ("datetime-local", "EndTime", "Session EndTime");

	} //end function display_form_body()

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["SessionNum"]);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		$valid = $this->invalidate_blanks (
			array ("StartTime" => "Start Time", "EndTime" => "End Time")
		);

		//start time valid format
		if ($this->fields['StartTime'] !== "")
		{
			if ($this->valid_datetime ($this->fields['StartTime']) == false)
			{
				$valid = false;
				$this->msgs['StartTime'] = "Invalid datetime format.";
			}
		}

		//end time valid format
		if ($this->fields['EndTime'] !== "")
		{
			if ($this->valid_datetime ($this->fields['EndTime']) == false)
			{
				$valid = false;
				$this->msgs['EndTime'] = "Invalid datetime format.";
			}
		}

		//SessionNum
		if ($this->fields['SessionNum'] != "")
		{
			if ($original == false)
				$original = "NULL";

			$query = $this->connection->prepare
			("
				select count(*) as 'count'
				from Session
				where
					SessionNum = ? AND
					NOT SessionID <=> " . $original
			);
			$query->execute(array($this->fields['SessionNum']));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			if ($count !== "0")
			{
				$valid = false;
				$this->msgs['SessionNum'] = "There is already a session with this number.";
			}

		} //end SessionNum uniqueness check

		return $valid;

	} //end function validate()

	//Check the format of a date
	private function valid_datetime ($datetime)
	{
		$datetime = explode(' ', $datetime);

		if (count($datetime) !== 2)
			return false;

		//separate everything into segments
		$datetime[0] = explode ('-', $datetime[0]);
		$datetime[1] = explode (':', $datetime[1]);

		foreach ($datetime as $half)
		{
			//date and time should each have three parts
			if (count ($half) !== 3)
				return false;

			//must consist entirely of numbers
			foreach ($half as $number)
			{
				if (ctype_digit ($number) == false)
					return false;
			}
		}

		//date should have valid values
		$date = $datetime[0];
		if (checkdate ($date[1], $date[2], $date[0]) == false)
			return false;

		//times should have proper values
		$time = $datetime[1];
		if (
				($time[0] > 23) ||
				($time[1] > 59) ||
				($time[2] > 59))
		{
			return false;
		}

		//if date is valid, return true
		return true;
	
	} //end function valid_datetime()

	//Session doesn't use any options
	protected function get_options()
	{}

	//insert data from fields array into database
	protected function insert()
	{
		$this->autofill_number ("SessionNum");

		$query = $this->connection->prepare
		("
			insert into
				Session (SessionNum, StartTime, EndTime)
				values  (         ?,         ?,       ?)
		");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$this->autofill_number ("SessionNum");

		$query = $this->connection->prepare
		("
			update Session
			set
				SessionNum = ?,
				StartTime  = ?,
				EndTime    = ?
			where SessionID = ?
		");

		$query->execute (array_values($this->fields));

	} //end function update()

	//autofill SessionNum, used by insert() and update()
	private function autofill_SessionNum()
	{
		//if SessionNum blank
		if ($this->fields['SessionNum'] == "")
		{
			//get new number
			$record_set = $this->connection->query
			("
				select MAX(SessionNum) + 1 as NewNum
				from Session
			");

			//asign new number
			$this->fields['SessionNum'] =
				$record_set->fetch(PDO::FETCH_ASSOC)['NewNum'];

		} //end if SessionNum blank
	
	} //end function authofill_SessionNum()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg = 'Successfully added Session ' . $this->fields['SessionNum'];
		
		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg = 'Successfully modified Session ' . $this->fields['SessionNum'];

		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select SessionNum, StartTime, EndTime
			from Session
			where SessionID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Student
