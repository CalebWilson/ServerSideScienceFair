<!--

	AutofillNumberEntity.php

	AutofillNumberEntity is class that defines methods that Entities can use to
	generate a unique, user-friendly number for a record in the database, distinct
	from the PK. For instance, Sessions need a user-facing Session Number, and
	Projects likewise need a user-facing Project Number.

-->

<?php

class AutofillNumber
{
	//id of the main record corresponding to this entity
	private $id;

	//connection to the database
	private $connection;

	//constructor: establish database connection
	public function __construct ($connection)
	{
		$this->connection = $connection;

	} //end constructor

	/*
		Check whether an ID has been set. Returns true if $this->id has a value, and
		returns false otherwise.
	*/
	public function has_id()
	{
		return isset($this->id);
	}

	/*
		If there is a single 'selected' record in the post array, set $this->ID to
		its ID.
	*/
	public function set_id ($post)
	{
		//capture ID
		if (isset ($post['selected']) && count($post['selected']) === 1)
		{
			$this->id = $post['selected'][0];
		}

	} //end function set_id

	//return ID
	public function get_id()
	{
		return $this->id;
	}

	//if the user did not provide a value for $field, automatically generate one
	public function autofill_number (&$fields, $table, $field, $condition = "1")
	{
		if ($fields[$field] == "")
		{
			$query_string = "";

			//if project already has a number, get it
			if (isset ($this->id))
			{
				$query_string =
				"
					select " . $field . "
					from " . $this->table . "
					where " . $this->table . "ID = " . $this->id
				;
			}

			//otherwise, generate a new one
			else
			{
				$query_string = 
				"
					select min(" . $field . ") + 1 as missing
					from " . $table . "
					where
						" . $field . " + 1 not in
							(select " . $field . " from Grade) and " .
						$condition
				;
			}

			$record_set = $this->connection->query ($query_string);

			$fields[$field] =
				$record_set->fetch(PDO::FETCH_ASSOC)["missing"];
		}

	} //end function autofill_number

} //end class AutofillNumber
