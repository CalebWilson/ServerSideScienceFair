<!--

	ReadOnlyEntity.php

	Entity is an abstract class designed to be a prototype for any entity whose
	data will need to be viewed on the website, but not edited, such as Ranking.

-->

<?php

abstract class ReadOnlyEntity
{
	//names
	protected $table; //table name
	public    $title; //title of web page
	protected $view;  //name of view
	protected $form;  //name of form entry point, e.g. "administrator" or "judge"

	//database connection
	protected $connection;

	//constructor
	protected function __construct ($connection)
	{
		//get name attributes from class name
		$this->table = get_class($this);
		$this->title = $this->table . 's';
		$this->view  = strtolower ($this->table);
		$this->form  = strtolower ($_SESSION['user_type']);

		//get database connection
		$this->connection = $connection;

	} //end function __construct()

	protected function print_assoc ($arr)
	{
		foreach ($arr as $field => $value)
		{
			print ($field . " => " . $value . "<br>");
		}
	}
	
	protected function login_check()
	{
		include strtolower($this->form) . "_check.php";
	}

	public function display ($post)
	{
		print ($this->display_title());

		print ($this->display_data_header());

		print ($this->display_data_body($post, $this->get_data()));

		print ($this->display_data_footer());
	}

	protected function display_title()
	{
		$title =
		'
			<div class="wrapper">
			<div class="main-f">
				<h1><strong>' . $this->title . '</strong></h1>
		';

		return $title;
	}

	protected function display_data_header()
	{
		//TODO move form to entity
		$data_header =
		'
			<div class="form-s">
				<form
					action="' . $this->form . '.php?' . 
					'view=' . $this->view . '"
					method="post"
				>
					<div class="data-box">
		';

		return $data_header;

	} //end function display_data_header

	/*
		select identifying data from records

		Must return an array of associative arrays, each of the form:
		(
			"ID"        => [concrete Entity's primary key],
			"selection" => [a string to display that represents a record]
		).

		The code in view.php depends upon this format.
	*/
	abstract protected function get_data();

	protected function display_data_body ($post, $data)
	{
		$body = '';

		//for each record
		foreach ($data as $record)
		{
			$body = $body .
			'
				<label id="data">
				<input 
					 id="change"
					type="checkbox"
					name="selected[]"
					class="check-d";
					onchange="isChecked(this)"
				> ' .

					$record['selection'] . '

				</input>
				</label></><br>
			';

		} //end for each record

		return $body;

	} //end function display_data_body

	protected function display_data_footer()
	{
		return '</div></form><br/>' . $this->back_button() . '</div></div></div>';

	} //end function display_data_footer

	//show back button
	public function back_button()
	{
		$button = 
		'
			<form
				action="' . $this->form . '.php?' .
				'view=actions"
				method="get"
				class="back-btn"
			>
				<button type="submit">Back</button>
			</form>
		';

		return $button;

	} //end function back_button

	/*
		Return a query string fragment that will return the concatenation of the
		values of $column_names.

		$column_names: Column name strings to be injected into a query string.
			Concatenation of literal SQL strings will therefore require extra
			quotation marks, e.g. `nullsafe_concat ("FirstName", "' '", "LastName")`.

		I would have integrated this into the database, but MySQL doesn't allow
		stored functions to have a variable number of arguments, and there is
		insufficient documentation regarding the addition of loadable functions.
	*/
	protected static function nullsafe_concat (...$column_names)
	{
		//concatenating nothing returns empty string
		if (count ($column_names) === 0)
			return "";

		//build string fragment
		$sql = "concat (";

		//add each column name
		foreach ($column_names as $column_name)
		{
			$sql .= "coalesce (" . $column_name . ", ''), ";
		}

		//replace trailing comma and space with closing parenthesis
		$sql = substr ($sql, 0, -2) . ")";

		return $sql;

	} //end function concat

} //end class ReadOnlyEntity

?>
