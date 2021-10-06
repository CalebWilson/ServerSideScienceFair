<!--

	PasswordEntity.php

	PasswordEntity is an abstract class that inherits from Entity and serves as a
	prototype for any Entity that has a Password field.

-->

<?php

include "Entity.php";

abstract class PasswordEntity extends Entity
{
	//constructor
	function __construct ($connection)
	{
		parent::__construct ($connection);
	}

	//remove password confirmation from $fields before inserting
	protected function insert()
	{
		unset ($this->fields['pass_conf']);

		parent::insert();

		$this->fields['pass_conf'] = "";

	} //end function insert

	//update database with data from fields array
	protected function update()
	{
		//password confirmation is redundant
		unset ($this->fields['pass_conf']);

		//if password is empty, ignore it
		if ($this->fields['Password'] == "")
			unset ($this->fields ['Password']);

		parent::update();

		$this->fields['pass_conf'] = "";
		$this->fields['Password']  = "";

	} //end function update()

} //end class PasswordEntity

?>
