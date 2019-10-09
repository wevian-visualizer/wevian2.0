<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WEVIAN</title>
		<link rel="icon" type="image/png" href="favicon.png" />
		<meta name="description" content="A web based visualizer/analyzer of Placement and Floorplan stages." />
		<link rel="stylesheet" type="text/css" href="css/style_placement.css"/>
	</head>
	<body>
<?PHP
	session_start();
	date_default_timezone_set('Europe/Athens');
	include("connect.php");
	include("functions.php");
?>
		<div class="divLeft">
<?PHP
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		header("Location: choose_category");
	}else{
?>
			<div id="loginRegisterDIV">
				<img style="position: absolute;top:20px;left:46.7%;vertical-align:middle;" src="logo_frames.png" width="5%"/>
				<div id="loginDIV">
					<form action="login.php" method="POST">
						<span>Email</span><br>
						<input type="email" name="email" required><br><br>
						<span>Password</span><br>
						<input type="password" name="password" required><br><br>
						<center><button class="loginRegisterBtn" name="submit">Login</button></center>
						<?PHP
							if(isset($_SESSION["user_not_found"])){
								echo "<center>User does not exist.</center>";
								unset($_SESSION["user_not_found"]);
							}
						?>
					</form>
				</div>
				<span id="horLine"></span>
				<div id="registerDIV">
					<form action="register.php" method="POST">
						<span>Email</span><br>
						<input type="email" name="email" required><br><br>
						<span>Password</span><br>
						<input id="password" type="password" name="password" required><br><br>
						<span>Retype Password</span><br>
						<input type="password" name="password_retype" required><br><br>
						<center><button id="reg_submit" class="loginRegisterBtn" name="submit">Register</button></center>
						<?PHP
							if(isset($_SESSION["user_just_registered"])){
								echo "<center>Account created successfully.</center>";
								unset($_SESSION["user_just_registered"]);
							}
						?>
					</form>
				</div>
			</div>
<?PHP
	}
?>
		</div>
	</body>
</html>