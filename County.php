<!--

	County.php

	County is a class that inherits from Entity, overriding abstract methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class County extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
        parent::__construct ($connection);
        $this->title = "Counties";

		//entity dependent on County
		$this->dependent = "school";

		//empty fields
		$this->fields = array ("CountyName" => "");

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select CountyID as ID, CountyName as selection from County
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();

	//check whether data has been submitted
	protected function submitted ($post)

	{		
		return isset ($post["CountyName"]);
	}

	//validate field entries and update msgs array
	protected function validate (&$msgs, $original)
	{
		//validity
		if ($this->fields ['CountyName'] == "")
		{
			$msgs['CountyName'] = "County cannot be empty.";

			return false;
		}

		//uniqueness
		else
		{
			if ($original == false)
				$original = "NULL";

			$query = $this->connection->prepare
			(
				"select count(*) as 'count'
                from County
                where CountyName = ? AND 
                not CountyID <=> " . $original //NULL-safe equals operator
			);
			$query->execute(array_values($this->fields));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			if ($count !== "0")
			{
				$msgs['CountyName'] =
					"There is a County with same ID ";
				
				return false;
			}
		} //end uniqueness

		return true;

	} //end function validate()

	//return an array of option arrays for the form to use
	protected function get_options()
	{
		//get counties from database
		$record_set = $this->connection->query("select CountyID, CountyName from County");
		return $record_set->fetchAll();
	}

	//insert data from fields array into database
	protected function insert()
	{
		$query = $this->connection->prepare
		("
            insert into
            County (CountyName)
            values (?)
        ");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$query = $this->connection->prepare
		("
        update County
        set CountyName = ?
        where CountyID = ?
        ");

		$query->execute (array_values($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			'<font color="green">' .
				$this->fields['CountyName'] . ' added to Counties.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg =
			'<font color="green">' .
				$this->fields['CountyName'] . ' successfully modified.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select CountyName
			from County
			where CountyID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Student

