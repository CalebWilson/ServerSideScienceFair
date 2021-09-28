<!-- This file is to be included in login pages -->

<?php

	require_once "db_connect.php";

	$msg = "";
	//if login credentials entered
	if (isset($_POST['username']))
	{
		//check login credentials
		$username = trim ($_POST['username']);
		$password = trim ($_POST['password']);

		//check if a record matches credentials
		$query = $connection->prepare
		("
			select count(*) as 'count'
			from ". $_SESSION ['user_type'] . "
			where
				Username = ? and
				Password = ?
		");
		$query->execute (array ($username, $password));
		$row = $query->fetch (PDO::FETCH_OBJ);
		$count = $row->count;

		//if match
		if ($count === "1")
		{
			//save their id
			$query = $connection->prepare
			("
				select " . $_SESSION ['user_type'] . "ID
				from " . $_SESSION ['user_type'] . "
				where
					Username = ? and
					Password = ?
			");
			$query->execute (array ($username, $password));
			$row = $query->fetch (PDO::FETCH_OBJ);
			$id_name = $_SESSION['user_type'] . "ID";
			$id = $row->$id_name;
			$_SESSION[$_SESSION['user_type']] = $id;

			//don't display login page after they're already logged in
			include strtolower($_SESSION['user_type']) . ".php";
			exit();
		}
		//if not a match, tell them
		else if ($count === "0")
		{
			$msg = '<p><font color="red">Incorrect username or password</font></p>';
		}
		else
		{
			$msg = '<p><font color="red">An error has occured. Please try again.</font></p>';
		}
	}
	//if no credentials entered yet
	else
	{
		$msg = "";
	}

	require_once "header.php";
?>

<title><?php print ($_SESSION['user_type']) ?> Login </title>
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print($_SESSION['user_type']) ?> Login</strong></h1>
	<?php print($msg) ?>
	<div class="form-s">
		<form
			action="<?php print(strtolower($_SESSION['user_type'])) ?>.php"
			method="post"
		>

			<div class="label">
				<label for="username">Username </label>
				<input type="text" name="username"><br>
			</div>

			<div class="label">
				<label for="password">Password </label>
				<input type="password" name="password"><br>
			</div>

			<input type="hidden" name="<?php print($_SESSION['user_type']) ?>" value=true>

			<input type="submit" class="btn" value="Submit"/><br>

		</form>
	</div>
            
</div>
</div>
