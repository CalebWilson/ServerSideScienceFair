<!--

	Ranking.php

	Ranking is a class that inherits from AdminEntity. All abstract methods are
	left blank except for display_data(), as rankings are read-only and not
	detailed.

-->

<?php

include "AdminEntity.php";

class Ranking extends AdminEntity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//no fields

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				RankingID as ID,
				CONCAT (ProjectNum, ' ', Title, ' ', AvgRank) as selection
			from Ranking
			order by RankingID
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();

	//don't show action buttons
	public function buttons()
	{}

	//rankings are read-only, so the rest of the functions are left blank
	protected function submitted ($post)
	{}

	protected function validate (&$msgs, $original)
	{}

	protected function get_options()
	{}

	protected function insert()
	{}

	protected function update()
	{}

	protected function confirm_add()
	{}

	protected function confirm_edit()
	{}

	protected function prefill ($target)
	{}

} //end class Ranking
