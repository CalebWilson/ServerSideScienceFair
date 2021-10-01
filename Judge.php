<?php

include "Entity.php";
include "Input.php";

class Judge extends Entity
{
	public function __construct ($connection)
	{
		parent::__construct($connection);

		//empty fields
		$this->fields = array
		(
			"FirstName"      => "",
			"MiddleName"     => "",
			"LastName"       => "",
			"Title"          => "",
			"DegreeID"       => "",
			"Employer"       => "",
			"Email"          => "",
			"Username"       => "",
			"Password"       => "",
			"pass_conf"      => "",
			"CatPref1"       => "",
			"CatPref2"       => "",
			"CatPref3"       => "",
			"LowerGradePref" => "",
			"UpperGradePref" => "",
		);

	} //end constructor

	//retrieve judges from database
	public function get_data()
	{
		$record_set = $this->connection->query
		("
			select
				JudgeID as ID,
				concat (Title, ' ', FirstName, ' ', LastName) as selection
			from Judge
		");

		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		return $records;

	} //end function get_data

	//display the body of the form
	protected function display_form_body ($action)
	{
		//First Name
		Input::display_input
		(
			"text",
			"FirstName",
			$this->fields['FirstName'],
			"First Name",
			$this->msgs
		);

		//Middle Name
		Input::display_input
		(
			"text",
			"MiddleName",
			$this->fields['MiddleName'],
			"Middle Name",
			$this->msgs
		);

		//Last Name
		Input::display_input
		(
			"text",
			"LastName",
			$this->fields['LastName'],
			"Last Name",
			$this->msgs
		);

		//Title
		Input::display_input
		(
			"text",
			"Title",
			$this->fields['Title'],
			"Title",
			$this->msgs
		);

		//Degree
		$degrees = Input::get_dropdown_options
		(
			$this->connection,
			"select DegreeID as ID, DegreeName as Name from Degree"
		);

		Input::display_dropdown
		(
			"DegreeID",
			$this->fields['DegreeID'],
			"Highest Degree Earned",
			$degrees,
			$this->msgs
		);

		//Title
		Input::display_input
		(
			"text",
			"Employer",
			$this->fields['Employer'],
			"Employer",
			$this->msgs
		);

		//Email
		Input::display_input
		(
			"text",
			"Email",
			$this->fields['Email'],
			"Email",
			$this->msgs
		);

		//Username
		Input::display_input
		(
			"text",
			"Username",
			$this->fields['Username'],
			"Username",
			$this->msgs
		);

		//Password
		$password_label = "Password";
		if ($action == "edit")
			$password_label = "New Password";

		Input::display_input
		(
			"password",
			"Password",
			$this->fields['Password'],
			$password_label,
			$this->msgs
		);

		Input::display_input
		(
			"password",
			"pass_conf",
			$this->fields['pass_conf'],
			"Confirm " . $password_label,
			$this->msgs

		); //end password

		/* Category preferences */
		$categories = Input::get_dropdown_options
		(
			$this->connection,
			"select CategoryID as ID, CategoryName as Name from Category"
		);

		Input::display_dropdown
		(
			"CatPref1",
			$this->fields['CatPref1'],
			"Primary Category Preference",
			$categories,
			$this->msgs
		);

		Input::display_dropdown
		(
			"CatPref2",
			$this->fields['CatPref2'],
			"Secondary Category Preference",
			$categories,
			$this->msgs
		);

		Input::display_dropdown
		(
			"CatPref3",
			$this->fields['CatPref3'],
			"Tertiary Category Preference",
			$categories,
			$this->msgs

		); //end category preferences

		//Grade preferences
		$grades = Input::get_dropdown_options
		(
			$this->connection,
			"select GradeID as ID, GradeNum as Name from Grade"
		);

		Input::display_dropdown
		(
			"UpperGradePref",
			$this->fields['UpperGradePref'],
			"Highest Grade Level Preference",
			$grades,
			$this->msgs
		);

		Input::display_dropdown
		(
			"LowerGradePref",
			$this->fields['LowerGradePref'],
			"Lowest Grade Level Preference",
			$grades,
			$this->msgs

		); //end grade preferences

	} //end function display_form_body

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post['FirstName']);

	} //end function submitted

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		$labels = array
		(
			"FirstName"      => "First name",
			"LastName"       => "Last name",
			"Email"          => "Email",
			"Username"       => "Username"
		);

		//require password if adding
		if ($original == "NULL")
		{
			$labels['Password']  = "Password";
			$labels['pass_conf'] = "Password confirmation";
		}

		//invalidate blank fields
		$valid = Input::invalidate_blanks ($this->fields, $labels, $this->msgs);

		//Email
		if ($this->fields['Email'] != "")
		{
			//email validity
			if (filter_input (INPUT_POST, "Email", FILTER_VALIDATE_EMAIL) == false)
			{
				$valid = false;
				$this->msgs['Email'] = "Email is invalid.";

			}

			//email uniqueness
			elseif
			(
				Input::is_duplicate
				(
					$this->connection,
					$this->table,
					"Email",
					$this->fields['Email'],
					$original
				)
			)
			{
				$valid = false;

				$this->msgs['Email'] =
					"Another judge is already using this email.";
			}

		} //end Email

		//Username
		if
		(
			Input::is_duplicate
			(
				$this->connection,
				$this->table,
				"Username",
				$this->fields['Username'],
				$original
			)
		)
		{
			$valid = false;

			$this->msgs['Username'] =
				"Another judge is already using this username.";
		}

		//password confirmation
		if ($this->fields['pass_conf'] != $this->fields['Password'])
		{
			$valid = false;
			$this->msgs['pass_conf'] = "Passwords don't match.";
		}

		//Category Preferences
		if ($this->fields['CatPref2'] != "")
		{
			if ($this->fields['CatPref2'] == $this->fields['CatPref1'])
			{
				$this->msgs['CatPref2'] =
					"Secondary Category Preference must be different from Primary " .
					"Category Preference."
				;
			}
		}

		if ($this->fields['CatPref3'] != "")
		{
			if
			(
				$this->fields['CatPref3'] == $this->fields['CatPref1']
			||
				$this->fields['CatPref3'] == $this->fields['CatPref2']
			)
			{
				$this->msgs['CatPref2'] =
					"Tertiary Category Preference must be different from Primary " .
					"and Secondary Category Preferences."
				;
			}

		} //end Category Preferences

		//Grade Preference
		if ($this->fields['LowerGradePref'] > $this->fields['UpperGradePref'])
		{
			$this->msgs['LowerGradePref'] =
				"Lowest Grade Level Preference must be lower than Highest Grade " .
				"Level Preference."
			;
		}

		return $valid;

	} //end function validate

	//insert data from fields array into database
	protected function insert()
	{
		unset ($this->fields['pass_conf']);

		parent::insert();

		$this->fields['pass_conf'] = "";

	} //end function insert

	//update database with data from fields array
	protected function update()
	{
		$query = $this->connection->prepare
		("
			update Judge set
				FirstName      = ?,
				MiddleName     = ?,
				LastName       = ?,
				Title          = ?,
				DegreeID       = ?,
				Employer       = ?,
				Email          = ?,
				Username       = ?,
				Password       = ?,
				CatPref1       = ?,
				CatPref2       = ?,
				CatPref3       = ?,
				LowerGradePref = ?,
				UpperGradePref = ?
			where JudgeID = ?
		");

		$query->execute (array_values($this->fields));

	} //end function update

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			"Successfully added "      .
			$this->fields['Title']     . " " .
			$this->fields['FirstName'] . " " .
			$this->fields['LastName']  .
			" as Judge"
		;

		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_add

	//confirm an edit operation
	protected function confirm_edit()
	{
		$msg =
			$this->fields['Title']     . " " .
			$this->fields['FirstName'] . " " .
			$this->fields['LastName']  .
			" as Judge"
		;

		return '<font color="green">' . $msg . '</font><br>';


	} //end function confirm_edit

	//set fields to current database values of the record to be edited
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select
				FirstName, MiddleName, LastName,
				Title, DegreeID, Employer,
				Email, Username, Password,
				CatPref1, CatPref2, CatPref3,
				LowerGradePref, UpperGradePref
			from Judge
			where JudgeID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

		//clear password fields
		$this->fields ['Password'] = "";
		$this->fields ['pass_conf'] = "";

	} //end function prefill


} //end class Judge

?>
