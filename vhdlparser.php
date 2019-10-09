<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WEVIAN | VHDL Parser</title>
		<script src="js/jquery.js"></script>
		<script src="js/html2canvas.js"></script>
		<script src="js/Treant.js"></script>
		<script src="js/script_vhdl.js"></script>
		<script src="vendor/raphael.js"></script>
		<link rel="icon" type="image/png" href="favicon.png" />
		<link rel="stylesheet" type="text/css" href="css/Treant.css"/>
		<link rel="stylesheet" type="text/css" href="css/style_floorplan.css"/>
	</head>
	<body>
		<div class="divLeft">
<?PHP
	session_start();
	date_default_timezone_set('Europe/Athens');
	include("connect.php");
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"]) && !isset($_SESSION["path"])){
		if($stmt = mysqli_prepare($connect, "SELECT id,name,DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') FROM vhdl_files WHERE userid=?")){
			mysqli_stmt_bind_param($stmt, "i", $_SESSION["uid"]);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $info["id"],$info["name"],$info["date"]);
?>
			<div id="savedProjectsSpan">These are your saved projects, click one to see it or create another!<br>If you don't want them saved then just delete them.</div>
<?PHP
			while(mysqli_stmt_fetch($stmt)){
?>
			<div id="fid<?PHP echo $info["id"]; ?>" class="loadfilediv">
				Name: <input title='3 to 50 characters' pattern='.{3,50}' class='textShadowInput projectName' onchange='updateVHDLName(<?PHP echo $info["id"]; ?>);' id='infoname<?PHP echo $info["id"]; ?>' value='<?PHP echo $info["name"]; ?>'><br>
				Uploaded at <b><span id='infodate<?PHP echo $info["id"]; ?>'><?PHP echo $info["date"]; ?></span></b> Athens/Greece local time.
			</div>
			<div id="fid2_<?PHP echo $info["id"]; ?>" class="loadfilediv2">
				<span class="textShadow" style="float: left; cursor: pointer;" onclick="loadFile(<?PHP echo $info["id"]; ?>);">Visualize</span>
				<span class="textShadow" onclick="deleteFile(<?PHP echo $info["id"]; ?>);" style="float: right; cursor: pointer;">Delete</span>
			</div>
<?PHP	
			}
			mysqli_stmt_close($stmt);
		}
	}
?>
		</div>
<?PHP
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
?>
		<div class="divRight">
			<div id="menu">
				<ul>
<?PHP
	if(isset($_SESSION["path"]) && !empty($_SESSION["path"])){
?>
		<li id="downloadFiles" style="margin-top: 10px;">Select top level:<br>
			<select id="selectmain" required>
			<?php
				foreach (new DirectoryIterator($_SESSION['path']) as $fileInfo) {
					if($fileInfo->isDot()) continue;
						echo "<option value='".$fileInfo->getFilename()."'>".$fileInfo->getFilename()."</option>";
				}
			?>
			</select><br><br>Select library:<br>
			<select id="selectlib1" required>
			<?php
				foreach (new DirectoryIterator('libraries') as $fileInfo) {
					if($fileInfo->isDot()) continue;
						echo "<option value='".$fileInfo->getFilename()."'>".$fileInfo->getFilename()."</option>";
				}
			?>
			</select><br><br>
			<button id="viz" type="button">Continue</button>
			<hr>
		</li>
		<li id="linksdiv"></li>
		<li id="clearVHDLParser">
			<form action="vhdlparser/upload.php" method="post" enctype="multipart/form-data">
				<button type="submit" name="submit">Return to saved projects</button>
			</form>
		</li>
<?PHP
	}
	if(!isset($_SESSION["path"])){
?>
					<li id="formDropzone">
						<form action="vhdlparser/upload.php" method="POST" enctype="multipart/form-data">
							<center style="border: 2px dashed black; padding: 5px;">
								<input class="textShadowInput" id="project_name" name="fileName" type="text" placeholder="Project name" required><br>
								Select .zip file:
								<input type="file" name="file" id="fileToUpload" required><br>
								<br>
								<input type="submit" value="Create" name="submit">
							</center>
						</form >
					</li>
					<li>
						<form action="choose_category" method="POST">
							<button name="submit">Return to menu</button>
						</form>
					</li>
<?PHP
	}
?>
					<li>
						<form action="logout.php" method="POST">
							<button name="submit">Logout</button>
						</form>
					</li>
				</ul>
			</div>
		</div>
<?PHP
	}
?>
	<script src="js/script_vhdl.js"></script>
	</body>
</html>