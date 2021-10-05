<!--

	Administrator.php

	Administrator is a class that inherits from Entity, overriding abstract
	methods to achieve polymorphic behavior.

-->

<?php

include "PasswordEntity.php";
include "Input.php";

class Administrator extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields = array
		(
			"FirstName"      => "",
			"MiddleName"     => "",
			"LastName"       => "",
			"Email"          => "",
			"Username"       => "",
			"Password"       => "",
			"pass_conf"      => "",
			"AuthorityLevel" => ""
		);

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function get_data()
	{
		//get administrators
		$record_set = $this->connection->query
		("
			select
				AdministratorID as ID,
				CONCAT (
					LEFT (FirstName, 15), ' ',
					LEFT (MiddleName, 1), '. ',
					LEFT (LastName,  15)
				) as selection
				from Administrator
		");
		$records = $record_set->fetchAll();

		return $records;

	} //end function get_data();

	//display the body of the form for adding or editing an Administrator
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
		);

		//AuthorityLevel
		Input::display_dropdown
		(
			"AuthorityLevel",
			$this->fields['AuthorityLevel'],
			"Authority Level",

			array
			(
				"1" => "Super Admin",
				"2" => "Normal Admin"
			),

			$this->msgs
		);

	} //end function display_form_body

	//only show action buttons if the administrator has sufficient authority
	public function buttons()
	{
		$record_set = $this->connection->query
		("
			select AuthorityLevel
			from Administrator
			where AdministratorID = " . $_SESSION['Administrator']
		);

		$admin = $record_set->fetch (PDO::FETCH_ASSOC);

		//sufficient authority
		if ($admin['AuthorityLevel'] === '1')
		{
			return parent::buttons();
		}

		//insufficient authority
		else
		{
			$button = 
				'<div class="view-b">
					<button type="submit" name="action" value="Edit" class="btn"
						>View</button>
				</div>'
			;

			return $button;

		} //end insufficient authority
	
	} //end function buttons()

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
			$labels['Password'] = "Password";
		}

		//invalidate blank fields
		$valid = Input::invalidate_blanks ($this->fields, $labels, $this->msgs);

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
					$this->table, "Email",
					$this->fields['Email'],
					$original
				)
			)
			{
				$valid = false;

				$this->msgs['Email'] =
					"Another administrator is already using this email.";
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
				"Another administrator is already using this username.";
		}

		//password confirmation
		if ($this->fields['pass_conf'] != $this->fields['Password'])
		{
			$valid = false;
			$this->msgs['pass_conf'] = "Passwords don't match.";
		}

		return $valid;

	} //end function validate()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			$this->fields['FirstName'] . " " . $this->fields['LastName'] .
			" added as administrator."
		;

		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit()
	{
		$msg =
			$this->fields['FirstName'] . " " . $this->fields['LastName'] .
			" successfully modified."
		;

		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_edit()

	//set fields array to columns and values of target record from database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select
				FirstName,
				MiddleName,
				LastName,
				Email,
				Username,
				Password,
				AuthorityLevel
			from Administrator
			where AdministratorID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

		//clear password fields
		$this->fields ['Password'] = "";
		$this->fields ['pass_conf'] = "";

	} //end function prefill()

} //end class Administrator

?>
