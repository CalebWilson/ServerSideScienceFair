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
				CONCAT ('Project ', ProjectNum, ': ', Title, ' - ', AvgRank)
					as selection
			from AverageRanking
			order by ProjectNum
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function get_data();

} //end class Ranking
