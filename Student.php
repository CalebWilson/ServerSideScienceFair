<!--

	Student.php

	Student is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class Student extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields = array
		(
			"SchoolID"   => "",
			"FirstName"  => "",
			"MiddleName" => "",
			"LastName"   => "",
			"Gender"     => "",
			"ProjectID"  => "",
			"GradeID"    => ""
		);

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function get_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				StudentID as ID,
				CONCAT (
					LEFT (LastName,  15), ', ',
					LEFT (FirstName, 15), ' ',
					LEFT (MiddleName, 1), '., (',
					LEFT (SchoolName, 15), ')'
				) as selection
				from Student, School
				where Student.SchoolID = School.SchoolID
				order by LastName
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function get_data();

	//display the body of the form for adding or editing a Student
	protected function display_form_body ($action)
	{
		//get dropdown options from the database
		$options = $this->get_options();

		//School
		$schools = Input::get_dropdown_options
		(
			$this->connection,
			"select SchoolID as ID, SchoolName as Name from School"
		);

		$this->display_dropdown
		(
			"SchoolID",
			$this->fields['SchoolID'],
			"School",
			$schools,
			$this->msgs
		);

		//First Name
		Input::display_input
		(
			"text",
			"FirstName",
			$this->fields['FirstName'],
			"First Name",
			$this->msgs
		);

		//Middle Name
		Input::display_input
		(
			"text",
			"MiddleName",
			$this->fields['MiddleName'],
			"Middle Name"
		);

		//Last Name
		Input::display_input
		(
			"text",
			"LastName",
			$this->fields['LastName'],
			"Last Name"
			$this->msgs
		);

		//Gender
		Input::display_dropdown
		(
			"Gender",
			$this->fields['Gender'],
			"Gender",
			[["Male" => "Male"], ["Female" => "Female"], ["Other" => "Other"]],
			$this->msgs
		);

		//Project
		$projects = Input::get_dropdown_options
		(
			$this->connection,

			"select
				ProjectID as ID
				concat ('Project ', ProjectNumber, ': ', Title) as Name"
		);

		Input::display_dropdown
		(
			"ProjectID",
			$this->fields['ProjectID'],
			"Project",
			$projects,
			$this->msgs
		);

		//Grade
		$grades = Input::get_dropdown_options
		(
			$this->connection,
			"select GradeID as ID, GradeNum as Name from Grade"
		);

		Input::display_dropdown
		(
			"GradeID",
			$this->fields['GradeID'],
			"Grade Level",
			$grades,
			$this->msgs
		);

	} //end function display_form_body()

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["FirstName"]);
	}

	//validate field entries and update msgs array
	protected function validate ($original = "NULL")
	{
		$labels = array
		(
			"SchoolID"  => "School",
			"FirstName" => "First name",
			"LastName"  => "Last name",
			"Gender"    => "Gender",
			"ProjectID" => "Project",
			"GradeID"   => "Grade"
		);

		return Input::invalidate_blanks ($this->fields, $labels, $this->msgs);

	} //end function validate()

	//get options from database
	private function get_options()
	{
		$options = array();

		//School
		$record_set = $this->connection->query
			("select SchoolID, SchoolName from School");
		$schools = $record_set->fetchAll();
		$options['schools'] = array();

		foreach ($schools as $school)
			$options['schools'][$school['SchoolID']] = $school['SchoolName'];

		//Project
		$record_set = $this->connection->query
		(
			"select ProjectID, Title from Project"
		);
		$projects = $record_set->fetchAll();
		$options['projects'] = array()

		foreach ($projects as $project)
			$options['projects'][$project['ProjectID']] = $project['Title'];

		//Grade
		$record_set = $this->connection->query
		(
			"select GradeID, GradeNum from Grade"
		);
		$grades = $record_set->fetchAll();
		$options['grades'] = array();

		foreach ($grades as $grade)
			$options['grades'][$grade['GradeID']] = $grade['GradeNum'];

		return $options;

	} //end function get_options()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			$this->fields['FirstName'] . " " .
			$this->fields['LastName'] .
			' added as student.'
		;
		
		return '<font color="green">' . $msg . '</font><br>';
	
	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg =
			$this->fields['FirstName'] . ' ' .
			$this->fields['LastName'] .
			' successfully modified.'
		;
		
		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select
				SchoolID, FirstName, MiddleName, LastName,
				Gender, ProjectID, GradeID
			from Student
			where StudentID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);
	
	} //end function prefill()

} //end class Student
