<!--

	Category.php

	Category is a class that inherits from Entity, overriding abstract methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class Category extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//entity dependent on Category
		$this->dependent = "project";

		$this->title = "Categories";

		//empty fields
		$this->fields = array ("CategoryName" => "");

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select CategoryID as ID, CategoryName as selection from Category
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["CategoryName"]);
	}

	//validate field entries and update msgs array
	protected function validate (&$msgs, $original)
	{
		//validity
		if ($this->fields ['CategoryName'] == "")
		{
			$msgs['CategoryName'] = "Category cannot be empty.";

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
                from Category
                where CategoryName = ? AND 
                not CategoryID <=> " . $original //NULL-safe equals operator
			);
			$query->execute(array_values($this->fields));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			if ($count !== "0")
			{
				$msgs['CategoryName'] =
					"Catagory already exsits ";
				
				return false;
			}
		} //end uniqueness

		return true;

	} //end function validate()

	//return an array of option arrays for the form to use
	protected function get_options()
	{
		//get counties from database
		$record_set = $this->connection->query("select CategoryID, CategoryName from Category");
		return $record_set->fetchAll();
	}

	//insert data from fields array into database
	protected function insert()
	{
		$query = $this->connection->prepare
		("
            insert into
            Category (CategoryName)
            values (?)
        ");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$query = $this->connection->prepare
		("
        update Category
        set CategoryName = ?
        where CategoryID = ?
        ");

		$query->execute (array_values($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			'<font color="green">' .
				$this->fields['CategoryName'] . ' added to Categories.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg =
			'<font color="green">' .
				$this->fields['CategoryName'] . ' successfully modified.' .
			'</font><br>'
		;
		
		return $msg;

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select CategoryName
			from Category
			where CategoryID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Student

