<!--

	Input.php

	Input is an abstract class designed as a container for input and input
	validation functions.

-->
<?php

abstract class Input
{
	/*
		display a basic input

		$type:  type of input to be displayed, e.g. "text", "number"
		$field: name of the identifier that the input assigns its value to
		$value: current value of the input
		$label: user-facing label for $field
		$msgs:  associative array of error messages mapping $field => $msg

	*/
	public static function display_input ($type, $field, $value, $label, &$msgs)
	{
		print
		('
			<label for="' . $field . '">' . $label . ':</label>
			<input
				type="'  . $type  . '"
				name="'  . $field . '"
				value="' . $value . '"
			><br>
		');

		//error message
		Input::display_input_error ($msgs, $field);

	} //end function display_text_input()

	/*
		display a dropdown menu

		$field:   name of the identifier that the dropdown assigns its value to
		$value:   current value of the dropdown
		$label:   user-facing label for $field
		$options: associative array of dropdown options mapping value => label
		$msgs:    associative array of error messages mapping $field => $msg
	*/
	public static function display_dropdown ($field, $value, $label, $options, &$msgs)
	{
		//begin dropdown
		print
		('
			<label for="' . $field . '">' . $label . ':</label>
			<select name="' . $field . '" id="' . $field . '">
		');

		//blank option
		print ('<option value=""</option>');

		//actual options
		foreach ($options as $option_value => $option_label)
		{
			print ("<option value=" . $option_value);

				//maintain selection
				if ($option_value === $value)
					print (" selected");

			print(">" . $option_label . "</option>");
		}

		//end dropdown
		print('</select><br>');

		//error message
		Input::display_input_error ($field);

	} //end function display_dropdown

	//display the error message for a field
	public static function display_input_error (&$msgs, $field)
	{
		if (isset ($msgs[$field]))
			print ($msgs[$field]);

	} //end display_input_error()

	/*
		Set the error message for any blank field in the $labels array. Return false
		if any of the fields are blank; return true otherwise.

		$fields: associative array mapping $field => $value
		$labels: associative array mapping $field => $label
		$msgs:   associative array of error messages mapping $field => $msg
	*/
	public static function invalidate_blanks ($fields, $labels, &$msgs)
	{
		$valid = true;

		foreach ($labels as $field => $label)
		{
			if ($fields[$field] == "")
			{
				$valid = false;
				$msgs[$field] = $label . " cannot be blank.";
			}
		}

		return $valid;

	} //end function invalidate_blanks()

	/*
		Returns false if there exists a record other than $original with the same
		value for $field where $condition, or if $field is blank, and returns true
		otherwise.

		$table:     table to check for $value
		$field:     field whose uniqueness is to be checked
		$value:     value to check for in $table
		$original:  ID of the record being edited, or NULL if adding new record

		$condition: additional condition added to the query for when a field only
			needs to be unique when another field is not; e.g. Schools only must have
			different names if they are in the same County
	*/
	public static function is_duplicate
	(
		$connection, $table, $field, $value, $original, $condition = "1"
	)
	{
		if ($value == "")
			return false;

		$query = $connection->prepare
		("
			select count(*) as 'count'
			from " . $table . "
			where
				" . $field . " = ? AND
				" . $condition . " AND
				NOT " . $table . "ID <=> " . $original //NULL-safe equals
		);
		
		$query->execute (array ($value));
		$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

		return $count !== "0";

	} //end function is_duplicate()

} //end class Input

?>
