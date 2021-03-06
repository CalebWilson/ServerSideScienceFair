<!--

	Entity.php

	Entity is an abstract class designed to be a prototype for any entity whose
	data will need to be edited on the website. It inherits the functions to
	display data, one of which remains abstract.

-->

<?php

include "ReadOnlyEntity.php";

abstract class Entity extends ReadOnlyEntity
{
	//name of entity that is dependent on this
	protected $dependent;

	//associative array of fields and values
	protected $fields;
	
	//main message, associative array of fields and error messages
	protected $msg;
	protected $msgs;

	//constructor
	protected function __construct ($connection)
	{
		parent::__construct ($connection);

		//dependents
		$this->dependents = "other entity";

		//main error message
		$this->msg = "";

	} //end function __construct()

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

	//do the desired action if there is one, or display the data otherwise
	public function display ($post)
	{
		//if submitted
		if (isset($post['action']))
		{
			//do the desired action on the desired records of the entity
			$action = $post['action'];
			$this->msg = $this->$action($post);

		} //end if submitted

		parent::display($post);

	} //end function display

	protected function display_data_header()
	{
		$header = "<p>". $this->msg . "</p>";
		$header = $header . parent::display_data_header();
		
		$header = $header .
		"
			<script>

				function isChecked(elem)
				{
					elem.parentNode.style.background =
						(elem.checked) ? '#ffa500' : 'none';
				}

			</script>

		";

		return $header;
	}

	protected function display_data_body ($post, $data)
	{
		$body = '';

		//for each record
		foreach ($data as $record)
		{
			$body = $body .
			'
				<label id="data">
				<input 
					 id="change"
					type="checkbox"
					name="selected[]"
					class="check-d";
					onchange="isChecked(this)"
					value=' . $record['ID']
			;

			//preserve checkedness
			if (isset ($post['selected']))
			{
				if (in_array($record['ID'], $post['selected']))
					$body = $body . " checked";
			}

			$body = $body .
			'
				>  ' .
					$record['selection'] . '
				</input></label></><br>
			';

		} // end for each record

		return $body;

	} //end function display_data_body

	protected function display_data_footer()
	{
		$footer = '</div>' . $this->buttons() . '</form>' . $this->back_button();
		$footer = $footer . '</div></div></div>';

		return $footer;

	} //end function display_data_footer

	//display the form in its entirety
	protected function display_form ($action, $msg, $post)
	{
		$this->display_form_header ($action);

		print($msg);

		$this->display_form_body ($action);

		$this->display_form_footer ($action, $post);

		include "footer.php";

	} //end function display_form()

	//display the beginning of the form
	protected function display_form_header ($action)
	{
		include strtolower($this->form) . "_check.php";

		print
		('
			<div class = "wrapper">
			<title>' . ucfirst($action) . ' ' . $this->table . '</title>

			<div class="main-f">
				<h1><strong>' . $action . ' ' . $this->table . '</strong></h1>
				<div class="form-s">
					<form ' .
						'action="' . $this->form . '.php?' .  'view='   . $this->view . '" ' .  'method="post"
					>
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

		//submit button, back button, and end of form
		print
		('
						<button
							type="submit"
							name="action"
							value="' . $action . '" 
							class="btn"
						>Submit</button>

					</form>
				</div>

				<form
					action="' . $this->form . '.php?' .
					'view='   . $this->view . '"
					method="post"
					class="back-btn"
				>
						<button type="submit">Back</button>
				</form>

			</div>
			</div>
		');

	} //end function display_form_footer()

	//display the page for adding a new record to the table
	public function add ($post)
	{
		//main message
		$msg  = "";

		//if data has been submitted, prefill
		if ($this->submitted($post))
		{
			//initialize error message array
			$this->msgs = array_fill_keys(array_keys($this->fields), "");

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

		exit();

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
			$msg = "Please select a" .
			(
				in_array ($this->view[0], array ('a', 'e', 'i', 'o', 'u'))
				?
					"n " : " "
			) .
				$this->view . " to view/edit."
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
				if ($this->validate ($selected[0]))
				{
					//append ID
					$this->fields["ID"] = $selected[0];

					//update database
					$this->update();

					//confirmation message
					$msg .= $this->confirm_edit();

					//refresh fields from database
					$this->prefill ($selected[0]);

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
					{
						if ($error !== "")
							$error = '<font color="red">' . $error . '</font><br>';
					}

					unset ($error);

				} //end if invalid input

			} //end if data submitted

			//prefill fields from database if first edit attempt
			else $this->prefill ($selected[0]);

			//display form
			$this->display_form ("edit", $msg, $post);

			exit();

		} //end count === 1

		//count > 1
		else
			$msg = "Please select only one " . $this->view . " to view/edit.";

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
			if (isset($not_deleted[0]))
			{
				$msg .= $this->dependency_deletion_error ($not_deleted);
			}

		} //end at least 1 record selected

		//no records selected
		else
		{
			$msg = "Please select " . strtolower($this->title) . " to delete.";
			return '<font color=red>' . $msg . '</font>';
		}

		return $msg;

	} //end function delete

	/*
		Generate an error message when one or more records were not deleted due to
		dependencies.

		for example: "Marion County could not be deleted because there is at least
			one school that depends on it.<br>"
	*/
	protected function dependency_deletion_error ($not_deleted)
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

		//return the error or confirmation message
		return $msg;

	} //end function dependency_deletion_error

	//check whether data has been submitted
	private function submitted ($post)
	{
		$input = array_diff (array_keys ($post), array ("action", "selected"));

		return count($input) > 0;

	} //end function submitted

	//display the body of the form
	abstract protected function display_form_body ($action);

	/*
		validate field entries and update msgs array

		When validating an edit, $original should be the ID of the record being
		edited, and omitted when validating an add.
	*/
	abstract protected function validate ($original = "NULL");

	//insert data from fields array into database
	protected function insert()
	{
		//ignore blank fields
		$fields = $this->fields;
		foreach ($this->fields as $field => $value)
		{
			if ($value === "")
				unset ($fields[$field]);
		}

		//don't do anything if all fields are blank
		if (count($fields) === 0)
			return;

		//build field list and value list
		$field_list = array_keys($fields)[0]; //field list string
		$value_list = "?";                    //value list string

		//append to field list and value list
		foreach (array_slice ($fields, 1) as $field => $value)
		{
			$field_list .= ", " . $field;
			$value_list .= ", ?";

		}

		$query_string =
		"
			insert into " .
				$this->table . " (" . $field_list . ")
				values           (" . $value_list . ")
		";

		//execute the insert
		$query = $this->connection->prepare
		($query_string);

		$query->execute (array_values($fields));

	} //end function insert

	//update database with data from fields array
	protected function update()
	{
		$fields = $this->fields;
		unset ($this->fields['ID']); //don't include ID in field list

		$query_string = "update " . $this->table . " set ";

		//for each column, build that part of the query string
		foreach ($this->fields as $field => $value)
		{
			$query_string .= $field . " = ";

			//if blank, set null
			if ($value === "")
			{
				$query_string .= "null, ";
				unset ($fields[$field]);
			}

			//otherwise, prepare to execute from field values
			else
			{
				$query_string .= "?, ";
			}

		} //end for each column

		//remove trailing comma and space
		$query_string = substr ($query_string, 0, -2);

		//where clause
		$query_string .= " where " . $this->table . "ID = ?";

		//prepare and execute query
		$query = $this->connection->prepare ($query_string);
		$query->execute (array_values($fields));

		//reset ID
		$this->fields['ID'] = $fields['ID'];

	} //end function update

	//confirm an add operation
	abstract protected function confirm_add();

	//confirm an edit operation
	abstract protected function confirm_edit();

	//set fields to current database values of the record to be edited
	abstract protected function prefill ($target);

} //end class Entity

?>
