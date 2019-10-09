<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WEVIAN</title>
		<link rel="icon" type="image/png" href="favicon.png" />
		<link rel="stylesheet" type="text/css" href="css/choose_category.css"/>
	</head>
	<body>
<?PHP
	session_start();
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
?>
		<div class="welcome">Welcome to WEVIAN</div>
		<div class="container">
			<div class="ok1"><a style="color: #ffffd0;text-decoration: none; font-size: 22px;" href="./vhdlparser.php">Netlist Structural Analysis</a></div>
			<hr class="hr1">
			<div class="ok2"><a style="color: #ffffd0;text-decoration: none; font-size: 22px;" href="./floorplan">Floorplan Visualization & Analysis</a></div>
			<hr class="hr2">
			<div class="ok3"><a style="color: #ffffd0;text-decoration: none; font-size: 22px;" href="./placement">Placement Visualization & Analysis</a></div>
			<hr class="hr3">
			<div class="ok4"><a style="color: #ffffd0;text-decoration: none; font-size: 22px;" href="./:/">Global Placement & Legalization</a></div>
			<a style="position: absolute; color: #ffffd0;text-decoration: none; font-size: 20px;" href="./logout">Logout</a>
		</div>
<?PHP
	}else{
		header("Location: index");
	}
?>
	</body>
</html>