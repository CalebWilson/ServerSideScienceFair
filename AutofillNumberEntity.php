<!--

	AutofillNumberEntity.php

	AutofillNumberEntity is an abstract class that inherits from Entity and defines
	methods that each instances of AutofillNumberEntity can use to generate a
	unique, user-friendly number for its corresponding record in the database,
	distinct from the PK. For instance, Sessions need a user-facing Session Number,
	and Projects likewise need a user-facing Project Number.

-->

<?php

include "Entity.php";

abstract class AutofillNumberEntity extends Entity
{
	//id of the main record corresponding to this entity
	protected $id;

	//constructor
	protected function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

	} //end constructor

	//override Entity::edit to save ID for autofill()
	public function edit ($post)
	{
		//capture ID
		if (isset ($post['selected']) && count($post['selected']) === 1)
		{
			$this->id = $post['selected'][0];
		}

		//continue with normal edit
		parent::edit ($post);

	} //end function edit()

	//if the user did not provide a value for $field, automatically generate one
	protected function autofill_number($field, $condition = "1")
	{
		if ($this->fields[$field] == "")
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
					select MAX(" . $field . ") + 1 as " . $field . "
					from " . $this->table . "
					where " . $condition
				;
			}

			$record_set = $this->connection->query ($query_string);

			$this->fields[$field] =
				$record_set->fetch(PDO::FETCH_ASSOC)[$field];
		}

	} //end function number_autofill()

} //end class AutofillNumberEntity
