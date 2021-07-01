<?php
	include"header.php";
	include"mail.php";
	session_start();
?>
	<body class="subpage"> 

		<!-- Header -->
			<header id="header">
				<div class="logo"><a href="index.php">Hielo <span>by TEMPLATED</span></a></div>
				<a href="#menu">Menu</a>
			</header>
<?php
	include"menu.php";

?>
		<!-- One -->
			<section id="three" class="wrapper style3">
				<div class="inner">
					<header class="align-center">
						<p>Consectetur adipisicing elit</p>
						<h2>Registration Information</h2>
					</header>
				</div>
			</section>
	<section id="three" class="wrapper style2  grid-style">
		<?php
			$fn = "";
			$ln = "";
			$gender = "";
			$status = "";
            $em = "";
			$pswd = "";
			$dpt = "";

			// Generate login link
			$link = "http://". $_SERVER['HTTP_HOST'] . "/lab3/login.php?code=".generator();

			// format email
			 $emailmsg = "<html> Hello, thank you for registering! \n \t Please click the following <a href='$link'>link</a> for an activation code! </html>";
			
			 //sets session varaibles
			$fn = $_SESSION['first_name'];
			$ln = $_SESSION['last_name'];
			$em = $_SESSION['email'];		
			$gender = $_SESSION['gender'];
            $status = $_SESSION['status'];
			$psswd = $_SESSION['psswd'];
			$dpt = $_SESSION['drop'];
			sendEmail($em,$emailmsg);
		?>
		<!-- dispays session variables -->
			<div class="process">
				<h1><strong>First Name: </strong> <?php print $fn; ?> </h1>
				<h1><strong>Last Name: </strong><?php print $ln; ?> </h1>
				<h1><strong>Email: </strong><?php print $em; ?> </h1>
				<h1><strong>Gender:</strong> <?php print $gender; ?> </h1>
            	<h1><strong>Status: </strong><?php print $status; ?> </h1>
            	<h1><strong>Password: </strong><?php print $psswd; ?></h1>
			</div>
			
					</form>
	</section>
				<!-- Footer -->
			<footer id="footer">
				<div class="container">
					<ul class="icons">
						<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
					</ul>
				</div>
				<div class="copyright">
					&copy; Untitled. All rights reserved.
				</div>
			</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>