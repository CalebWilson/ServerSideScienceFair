<!--

	Administrator.php

	Administrator is a class that inherits from Entity, overriding abstract
	methods to achieve polymorphic behavior.

-->

<?php

include "Entity.php";

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
		$record_set->closeCursor();

		return $records;

	} //end function get_data();

	//display the body of the form for adding or editing an Administrator
	protected function display_form_body ($action)
	{
		//Name
		$this->display_input ("text", "FirstName", "First Name");
		$this->display_input ("text", "MiddleName", "Middle Name");
		$this->display_input ("text", "LastName", "Last Name");

		//Email
		$this->display_input ("text", "Email", "Email");

		//Username
		$this->display_input ("text", "Username", "Username");

		//Password
		$password_label = "Password";
		if ($action == "edit")
			$password_label = "New Password";

		$this->display_input ("password", "Password", $password_label);
		$this->display_input ("password", "pass_conf", "Confirm " . $password_label);

		//AuthorityLevel
		$this->display_dropdown
		(
			"AuthorityLevel",
			"Authority Level",
			[
				["name" => "Normal Admin", "AuthorityLevel" => 2],
				["name" =>  "Super Admin", "AuthorityLevel" => 1]
			],
			"name"
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

		//if authority level is 1, show buttons
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

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["FirstName"]);
	}

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
		$valid = $this->invalidate_blanks ($labels);

		if ($this->fields['Email'] != "")
		{
			//email validity
			if (filter_input (INPUT_POST, "Email", FILTER_VALIDATE_EMAIL) == false)
			{
				$valid = false;
				$this->msgs['Email'] = "Email is invalid.";

			}

			//email uniqueness
			elseif ($this->is_not_unique ("Email", $original))
			{
				$valid = false;

				$this->msgs['Email'] =
					"Another administrator is already using this email.";
			}

		}

		if ($this->is_not_unique ("Username", $original))
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

	//Administrator has no dropdown options
	protected function get_options()
	{}

	//insert data from fields array into database
	protected function insert()
	{
		//pass_conf is redundant
		unset ($this->fields['pass_conf']);

		$query = $this->connection->prepare
		("
			insert into
				Administrator (FirstName, MiddleName, LastName, Email, Username, Password, AuthorityLevel)
				values        (        ?,          ?,        ?,     ?,        ?,        ?,              ?)
		");

		$query->execute (array_values($this->fields));

	} //end function insert

	//update database with data from fields array
	protected function update()
	{
		//password confirmation is redundant
		unset ($this->fields['pass_conf']);

		//begin building query
		$query_text =
		"
			update Administrator
			set
				FirstName = ?,
				MiddleName = ?,
				LastName = ?,
				Email = ?,
				Username = ?,
		";

		//if password is empty, ignore it
		if ($this->fields['Password'] == "")
			unset ($this->fields ['Password']);

		//if password not empty, include it
		else
		{
			$query_text .=
			"
				Password = ?,
			";
		}

		//finish building query
		$query_text .=
		"
				AuthorityLevel = ?
			where AdministratorID = ?
		";

		$query = $this->connection->prepare($query_text);
		$query->execute (array_values($this->fields));

	} //end function update()

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
