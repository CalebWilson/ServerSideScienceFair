<!--

	AdminEntity.php

	AdminEntity.php is an abstract class that extends Entity for forms to be viewed
	by Administrators of the system.

-->

<?php

include "Entity.php";

abstract class AdminEntity extends Entity
{
	//constructor
	protected function __construct ($connection)
	{
		parent::__construct ($connection);

		$this->form = "Admin";

	} //end constructor

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

} //end class AdminEntity

?>
