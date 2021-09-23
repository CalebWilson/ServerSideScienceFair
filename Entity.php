<!--

	Entity.php

	Entity is an abstract class designed to be a prototype for any entity whose
	data will need to be viewed on the website. There will be default definitions
	for most methods, but some methods will be up to the concretions to define.

-->

<?php

abstract class Entity
{
	//names
	protected $table; //table name
	public  $title; //title of web page
	protected $view;  //name of view

	//database connection
	protected $connection;

	//name of entity that is dependent on this
	protected $dependent;

	//associative array of fields and values
	protected $fields;
	
	//associative array of fields and error messages
	protected $msgs;

	//constructor
	protected function __construct ($connection)
	{
		//get name attributes from class name
		$this->table = get_class($this);
		$this->title = $this->table . 's';
		$this->view  = strtolower ($this->table);

		//get database connection
		$this->connection = $connection;

		//dependents
		$this->dependents = "other entity";

	} //end function __construct()

	protected function print_assoc ($arr)
	{
		foreach ($arr as $field => $value)
		{
			print ($field . " => " . $value . "<br>");
		}
	}

	//show upload buttons if necessary
	public function upload_button()
	{
		return "";
	}

	//show action buttons
	public function buttons()
	{
		$buttons =
			'<div class="view-b">
				<button type="submit" name="action" value="Add" class="btn"
					>Add</button>

				<button type="submit" name="action" value="Edit" class="btn"
					>View/Edit</button>

				<button type="submit" name="action" value="Delete"
						onclick="return confirm(\'Delete?\')" class="btn"
					>Delete</button>
			</div>'
		; //end $buttons assignment

		return $buttons;

	} //end function buttons();

	//get admin's authority level
	protected function get_authority()
	{
		$record_set = $this->connection->query
		("
			select AuthorityLevel
			from Administrator
			where AdministratorID = " . $_SESSION['Administrator']
		);

		$admin = $record_set->fetch (PDO::FETCH_ASSOC);

		return $admin['AuthorityLevel'];
	}

	//display the beginning of the form
	protected function display_form_header ($action)
	{
		include "admin_check.php";

		print
		('
			<div class = "wrapper">
			<title>' . ucfirst($action) . ' ' . $this->table . '</title>

			<div class="main-f">
				<h1><strong>' . $action . ' ' . $this->table . '</strong></h1>
				<div class="form-s">
					<form action="Admin.php?view=' . $this->view . '" method="post" >
		');

	} //end function display_form_header()

	//display_form_body declared with other abstract methods

	//display the end of the form
	protected function display_form_footer ($action, $post)
	{
		//preserve selected
		if (isset($post['selected'][0]))
		{
			print ('<input type="hidden" name="selected[]" value="' .
				$post['selected'][0] . '">'); 
		}

		//submit button and end of form
		print
		('
						<button type="submit" name="action"
							value="' . $action . '" class="btn">Submit</button>

					</form>
				</div>

				<form action="Admin.php?view=' . $this->view . '" method="post" class="back-btn">
					<button type="submit">Back</button>
				</form>

			</div>
			</div>
		');

	} //end function display_form_footer()

	//display the form in its entirety
	protected function display_form ($action, $msg, $post)
	{
		$this->display_form_header ($action);

		print($msg);

		$this->display_form_body ($action);

		$this->display_form_footer ($action, $post);

	} //end function display_form()

	//display the error message for a field
	private function display_input_error ($field)
	{
		if (isset ($this->msgs[$field]))
			print ($this->msgs[$field]);

	} //end display_input_error()

	/*
		display a dropdown menu

		$field: the name of the identifier that the dropdown will give its value to
		$label: the user-facing label for $field
		$options: an array of dropdown options
		$option_label: the index of the user-facing label for each dropdown option
	*/
	protected function display_dropdown ($field, $label, $options, $option_label)
	{
		//begin dropdown
		print
		('
			<label for="' . $field . '">' . $label . ':</label>
			<select name="' . $field . '" id="' . $field . '">
		');

		//blank option
		print ('<option value=""</option>');

		//actual options
		foreach ($options as $option)
		{
			print ("<option value=" . $option[$field]);

				//maintain selection
				if ($option[$field] === $this->fields[$field])
					print (" selected");

			print(">" . $option[$option_label] . "</option>");
		}

		//end dropdown
		print('</select><br>');

		//error message
		$this->display_input_error ($field);

	} //end function display_dropdown

	//display a text input
	protected function display_input ($type, $field, $label)
	{
		print
		('
			<label for="' . $field . '">' . $label . ':</label>
			<input
				type="' . $type . '"
				name="' . $field . '"
				value="' . $this->fields[$field] . '"
			><br>
		');

		//error message
		$this->display_input_error ($field);

	} //end function display_text_input()

	//display the page for adding a new record to the table
	public function add ($post)
	{
		//main message
		$msg  = "";

		//if data has been submitted, prefill
		if ($this->submitted($post))
		{
			//copy POST data
			$this->fields = $post;
			unset ($this->fields['action']);
			unset ($this->fields['selected']);

			//validate input in add mode
			if ($this->validate())
			{
				//update database
				$this->insert();

				//confirmation message
				$msg = $this->confirm_add();

				//clear fields for next entry
				$this->fields = array_fill_keys (array_keys($this->fields), "");

			} //end if valid input

			//if invalid input
			else
			{
				//color error messages red
				foreach ($this->msgs as &$error)
				{
					if ($error != "")
						$error = '<font color="red">' . $error . '</font><br>';
				}

				//set main error message
				$msg =
					'<font color="red">' .
						'Some fields require attention.' .
					'</font><br>'
				;

				unset ($error);

			} //end if invalid input

		} //end if data submitted

		$this->display_form ("add", $msg, $post);

		include "footer.php";

		//I don't know if this will work correctly, but we'll see
		exit();

		return "";

	} //end function add()

	//display the page for viewing and editing an existing record of the table
	public function edit ($post)
	{
		//error message
		$msg = "";

		//array of selected records
		$selected = array();
		if (isset ($post['selected']))
			$selected = $post['selected'];

		//count == 0
		if (count($selected) === 0)
		{
			$msg = "Please select a"
				. (($this->view == "administrator") ? "n " : " ")
				. $this->view . " to view/edit."
			;
		}

		//count == 1
		elseif (count($selected) === 1)
		{
			//if data has been submitted, prefill
			if ($this->submitted($post))
			{
				//initialize error message array
				$this->msgs = array_fill_keys(array_keys($this->fields), "");

				//copy POST data
				$this->fields = $post;
				unset ($this->fields['action']);
				unset ($this->fields['selected']);

				//validate input in edit mode
				if ($this->validate ($post['selected'][0]))
				{
					//append ID to admin
					$this->fields["ID"] = $post['selected'][0];

					//update database
					$this->update();

					//confirmation message
					$msg = $this->confirm_edit();

				} //end if valid input

				//if invalid input
				else
				{
					//main error message
					$msg =
						'<font color="red">' .
							'Some fields require attention.' .
						'</font><br>'
					;

					//color error messages red
					foreach ($this->msgs as &$error)
						$error = '<font color="red">' . $error . '</font><br>';

					unset ($error);

				} //end if invalid input

			} //end if data submitted

			//prefill fields from database if first edit attempt
			else $this->prefill ($selected[0]);

			//get necessary options from database
			$options = $this->get_options();

			//display form
			$this->display_form ("edit", $msg, $post);

			exit();

			return "";
		} //end count === 1

		//count > 1
		else
			$msg = "Please select only one " . $this->edit . " to view/edit.";

		return '<font color="red">' . $msg . '</font>';

	} //end function edit()

	//delete the selected record(s)
	public function delete ($post)
	{
		//error or confirmation message
		$msg = "";

		//array of selected records
		$selected = array();
		if (isset ($post['selected']))
			$selected = $post['selected'];

		//at least 1 record selected
		if (count($selected) > 0)
		{
			//how many records were actually deleted
			$deleted = 0;

			//which records were not deleted
			$not_deleted = array();

			//delete
			foreach ($selected as $id)
			{
				if ($this->connection->exec
				("
					delete from " . $this->table . "
					where " . $this->table . "ID = " . $id
				))
				{
					$deleted ++;
				}
				else
				{
					array_push ($not_deleted, $this->connection->errorInfo()[2]); 
				}

			} //end deletion

			//remove empty elements from $not_deleted
			$not_deleted = array_diff($not_deleted, array(""));

			//add successful deletions to message
			if ($deleted > 0)
			{
				$msg = $deleted . " ";

				//pluralize
				if ($deleted === 1)
					$msg .= $this->view;
				else
					$msg .= strtolower($this->title);

				$msg = '<font color="green">' . $msg .  " deleted.</font><br>";
			}

			//compose the rest of the error message
			//e.g. "Marion County could not be deleted because there is at least one school that depends on it.<br>"
			if (isset($not_deleted[0]))
			{
				$msg .= '<font color="red">' . $not_deleted[0];

				//if more than 1 not deleted, use 'and'
				if (count($not_deleted) > 1)
				{
					//if more than 2 not deleted, use comma(s)
					if (count($not_deleted) > 2)
					{
						$msg .= ", ";

						for ($i = 1; $i < count($not_deleted) - 1; $i++)
							$msg .= $not_deleted[$i] .	", ";
					}
					else
						$msg .= " ";

					$msg .= "and " . end($not_deleted);

				} //end more than 1 not deleted

				$msg .= " could not be deleted because there is at least one " .
				$this->dependent . " that depends on ";

				if (count($not_deleted) == 1)
					$msg .= "it.";
				else
					$msg .= "them.";

				$msg .= "</font><br>";

			} //end not deleted

			//return the error or confirmation message
			return $msg;

		} //end at least 1 record selected

		//no records selected
		else
		{
			$msg = "Please select " . strtolower($this->title) . " to delete.";
			return '<font color=red>' . $msg . '</font>';
		}

	} //end function delete()

	/*
		Returns false if there exists a record other than $original with the same
		value for $field where $condition, or if $this->fields[$field] is blank, and
		returns true otherwise

		$field: the field whose uniqueness is to be checked

		$original: the ID of the record being edited, or NULL if adding new record

		$condition: additional condition for when a field only needs to be unique
		when another field is not; e.g. Schools must have different names if they
		are in the same County
	*/
	protected function is_not_unique ($field, $original, $condition = "1")
	{
		if ($this->fields[$field] == "")
			return false;

		$query = $this->connection->prepare
		("
			select count(*) as 'count'
			from " . $this->table . "
			where
				" . $field . " = ? AND
				" . $condition . " AND
				NOT " . $this->table . "ID <=> " . $original //NULL-safe equals
		);
		
		$query->execute (array ($this->fields[$field]));
		$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

		return $count !== "0";

	} //end function is_not_unique()

	/*
		Set the error message for any blank field in the $labels array. Return false
		if any of the fields are blank; return true otherwise.

		$labels must be an associative array mapping field identifiers to their
		user-facing labels.
	*/
	protected function invalidate_blanks ($labels)
	{
		$valid = true;

		foreach ($labels as $field => $label)
		{
			if ($this->fields[$field] == "")
			{
				$valid = false;
				$this->msgs[$field] = $label . " cannot be blank.";
			}
		}

		return $valid;

	} //end function invalidate_blanks()

	/*
		select identifying data from records

		Must return an array of associative arrays, each of the form:
		(
			"ID"        => [concrete Entity's primary key],
			"selection" => [a string to display that represents a record]
		).

		The code in view.php depends upon this format.
	*/
	abstract public function display_data();
	
	abstract protected function display_form_body ($action);

	//check whether data has been submitted
	abstract protected function submitted ($post);

	/*
		validate field entries and update msgs array

		When validating an edit, $original should be the ID of the record being
		edited, and omitted when validating an add.
	*/
	abstract protected function validate ($original = "NULL");

	//return an array of option arrays for the form to use
	abstract protected function get_options();

	//insert data from fields array into database
	abstract protected function insert();

	//update database with data from fields array
	abstract protected function update();

	//confirm an add operation
	abstract protected function confirm_add();

	//confirm an edit operation
	abstract protected function confirm_edit();

	//set fields to current database values of the record to be edited
	abstract protected function prefill ($target);

} //end class Entity

?>
