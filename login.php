<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables
	$_SESSION['timeout'] = time(); //record the time at the user login 

	require_once "util.php";
	require_once "dbconnect.php";
	//always initialized variables to be used
	$msg = "";	
	$uname = "lancylu@hotmail.com";
	$pwd = "222";
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Login</title>
	<style type = "text/css">
  		h1, h2 {
    		text-align: center;
  		}
	</style>

	</head>

	<body>

		<?php
			if (isset($_POST['enter']))
			{
				
				
				//take the information submitted and verify inputs
				$uname =  trim($_POST['userName']); 
				$pwd = trim($_POST['pwd']);	


				//now veriy the username and password
					$stmt = $con->prepare("select count(*) as c from StudentTest where FirstName = ? and Password = ?");
					$stmt->execute(array($uname, $pwd));
					$row = $stmt->fetch(PDO::FETCH_OBJ);
					
					$count = $row->c;
					
					if ($count == 1)
					{
						$stmt = $con->prepare("Select FirstName, LastName, AdvisorID from StudentTest where FirstName = ? and Password = ?");
						$stmt->execute(array($uname, $pwd));
						$row = $stmt->fetch(PDO::FETCH_OBJ);

						/************************************************************************************************
						*Session variables are variables that belong to the session scope. 
						*They exit when a new session starts, and they are destroyed either when a session is killed or expired.
						*Instructions and concerns on using sessions can be found at http://www.php.net/manual/en/book.session.php.
						*User defined session variables can be used to pass data from one page to another. 
						*/
						$_SESSION['name'] = $uname;
						Header ("Location:process.php");

					}
					else $msg = "The information entered does not match with the records in our database.";


						
			}
			else 
			{	if (isset($_GET['l'])) //if the user is redirected from the home page
				{
					$tag = $_GET['l'];
					if ($tag == 'r') $msg = "You have already registered with this email. Click on Forget Password to retrieve your password.";

				}
			}
	
		?>

		<form action="login.php" method="post">
			<h1>Login</h1>
			<?php 
				print $msg;
				$msg = "";
			?>
			<br />
			Name: <input type="text" maxlength = "50" value="Scruff" name="userName" id="userName"   /> <br />
			Password: <input type="text" maxlength = "50" value="password1" name="pwd" id="pwd"   /> <br />

			
			<br />
			<br />


			<input name="enter" class="btn" type="submit" value="Submit" />

			<br /><br />
			<a href = "forget.php">Forget Password?</a>
		</form>



	</body>
</html>
