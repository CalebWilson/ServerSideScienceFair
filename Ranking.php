<!--

	Ranking.php

	Ranking is a class that inherits from ReadOnlyEntity to display the average
	ranking of each project.

-->

<?php

include "ReadOnlyEntity.php";

class Ranking extends ReadOnlyEntity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		$this->table = "AverageRanking";
		$this->title = "Average Rankings";

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function get_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				concat ('Project ', ProjectNum, ': ', Title, ' . . . . . ', TotalRank)
					as selection
			from TotalRanking
		");
		$records = $record_set->fetchAll();

		//return records
		return $records;

	} //end function get_data();

	//attempt to explain to the user what this represents
	protected function display_data_header()
	{
		$msg =
			"<p>" .
				"How good each project was, compared with the other projects scored " .
				"by the same judge. The higher the number, the better the project." .
			"</p><br>";

		return $msg . parent::display_data_header();

	} //end function display_data_header()

} //end class Ranking
