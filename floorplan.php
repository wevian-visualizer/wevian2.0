<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WEVIAN | Floorplanner</title>
		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/script_floorplan.js"></script>
		<script src="js/html2canvas.js"></script>
		<script src="js/overlap.js"></script>
		<link rel="icon" type="image/png" href="favicon.png" />
		<link rel="stylesheet" type="text/css" href="css/style_floorplan.css"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/modal.css"/>
	</head>
	<body>
		<div class="divLeft">
<?PHP
	session_start();
	date_default_timezone_set('Europe/Athens');
	include("connect.php");
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(isset($_SESSION["dirr_floorplan"]) && !empty($_SESSION["dirr_floorplan"])){
			echo '<div id="fixedDIV" onclick="onClickShowNets(event);"></div>';
		}
	}else{
		header("Location: index");
	}
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"]) && !isset($_SESSION["dirr_floorplan"])){
		if($stmt = mysqli_prepare($connect, "SELECT id,type,name,DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') FROM floorplan_files WHERE userid=?")){
			mysqli_stmt_bind_param($stmt, "i", $_SESSION["uid"]);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $info["id"],$info["type"],$info["name"],$info["date"]);
?>
			<div id="savedProjectsSpan">These are your saved projects, click one to see it or create another!<br>If you don't want them saved then just delete them.</div>
<?PHP
			while(mysqli_stmt_fetch($stmt)){
?>
			<div id="fid<?PHP echo $info["id"]; ?>" class="loadfilediv">
				Name: <input title='3 to 50 characters' pattern='.{3,50}' onchange='updateFloorplanName(<?PHP echo $info["id"]; ?>);' class='textShadowInput projectName' id='infoname<?PHP echo $info["id"]; ?>' value='<?PHP echo $info["name"]; ?>'><br>
				Type: <span id='infotype<?PHP echo $info["id"]; ?>'><?PHP echo substr(substr(glob("floorplan_saves/".$_SESSION['uid']."/".$info['id']."/*.pl")[0], 0, -3), strrpos(substr(glob("floorplan_saves/".$_SESSION['uid']."/".$info['id']."/*.pl")[0], 0, -3), "/")+1)." (".substr($info["type"],0,-2).")"; ?></span><br>
				Uploaded at <b><span id='infodate<?PHP echo $info["id"]; ?>'><?PHP echo $info["date"]; ?></span></b> Athens/Greece local time.
			</div>
			<div id="fid2_<?PHP echo $info["id"]; ?>" class="loadfilediv2">
				<span class="textShadow" style="float: left; cursor: pointer;" onclick="loadFile(<?PHP echo $info["id"]; ?>);">Visualize</span>
				<span class="textShadow" style="cursor: pointer;" onclick="share(<?PHP echo $info["id"]; ?>, <?PHP echo $_SESSION["uid"]; ?>);">Share link</span>
				<span class="textShadow" onclick="deleteFile(<?PHP echo $info["id"]; ?>);" style="float: right; cursor: pointer;">Delete</span>
			</div>
<?PHP	
			}
			mysqli_stmt_close($stmt);
		}
	}
?>
			<div id="statsModal3" class="modal">
				<div class="modal-content3">
					<div id="modalContent3"></div>
				</div>
			</div>
		</div>
<?PHP
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(isset($_SESSION["dirr_floorplan"]) && !empty($_SESSION["dirr_floorplan"])){
			echo '<script>parseBLOCKS_PL();</script>';
		}
	}
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
?>
		<div class="divCenter"><button class="triangleRight" onclick="divRightSlide();"></button></div>
		<div class="divRight">
			<div id="menu">
				<ul>
<?PHP
	if(isset($_SESSION["dirr_floorplan"]) && !empty($_SESSION["dirr_floorplan"])){
?>
					<li id="highlightCell">
						<input class="textShadowInput" placeholder="Node name" id="cellInput" type="text">
						<button id="cellInputBtn" onclick="panelButtons(0);">Highlight Block</button>
					</li>
					<li id="clearHighlightedCell">
						<input class="textShadowInput" placeholder="Node name" id="cellClear" type="text">
						<button id="cellClearBtn" onclick="panelButtons(1);">Clear Block</button>
					</li>
					<li id="displayNets">
						<input class="textShadowInput" placeholder="Node name" id="NetInput" type="text">
						<button id="displayNetsBtn" onclick="panelButtons(4);">Highlight Net</button>
						<span id="executedTimeData3"></span>
					</li>
					<li id="clearNets">
						<input class="textShadowInput" placeholder="Node name" id="NetClearInput" type="text">
						<button id="clearNetsBtn" onclick="panelButtons(6);">Clear Net</button>
					</li>
					<li id="displayHalfPerimeter">
						<button id="displayHalfPerimeterBtn" onclick="panelButtons(12);">Calculate Half-Perimeter</button>
						<span style="display:none;" onclick="$(this).fadeOut();" id="executedTimeData7"></span>
					</li>
					<li id="displayWhitespace">
						<button id="displayWhitespaceBtn" onclick="panelButtons(17);">Calculate Whitespace</button>
						<span style="display:none;" onclick="$(this).fadeOut();" id="executedTimeData17"></span>
					</li>
					<li id="displayOverlap">
						<span id="spanOverlap" style="font-size: 14px;">Show Overlap</span>
						<div style="vertical-align:middle;" id="displayOverlapBtn" onclick="$('.toggle-button2').toggleClass('toggle-button-selected2'); panelButtons(3);" class="textShadowInput toggle-button2">
							<button id="displayOverlapBtn2"></button>
						</div><br>
						<span id="totaloverlaps"></span>
					</li>
					<li id="displayHeatmap">
						<span id="spanHeatmap" style="font-size: 14px;">Show Thermal Map</span>
						<div style="vertical-align:middle;" id="displayHeatmapBtn" onclick="$('.toggle-button4').toggleClass('toggle-button-selected4'); panelButtons(10);" class="textShadowInput toggle-button4">
							<button id="displayHeatmapBtn2"></button>
						</div>
						<span id="executedTimeData5"></span>
						<div id="statsModal" class="modal">
							<div class="modal-content">
								<span class="close">&times;</span>
								<div id="modalContent"></div>
							</div>
						</div>
						<hr>
					</li>
					<li id="cellInfoHeader">
						<table class="cellInfo">
							<tr>
								<th>Name</th>
								<th>X</th>
								<th>Y</th>
								<th>x</th>
							</tr>
						</table>
						<hr>
					</li>
					<li id="exportoIMAGE">
						<button id="exportIMAGE" onclick="panelButtons(14);">Export to PNG</button>
					</li>
					<li id="exportoPDF">
						<button id="exportPDF" onclick="panelButtons(7);">Print</button>
						<hr>
					</li>
					<li id="colorLegend">
						<button id="spanLegendFirst" onclick="panelButtons(15);">Show Legend</button>
						<span id="spanLegend" style="font-size: 15px; display: none;">
							<div style="margin: 5px; vertical-align: middle; display: inline-block; width: 30px; height: 30px; border: 4px solid #ff9900; border-radius: 50%; background-color: #ff9900;"></div> Terminal<br>
							<div style="margin: 5px; vertical-align: middle; display: inline-block; width: 30px; height: 30px; border: 4px dashed #ff9900; background-color: #b1b1ff;"></div> Movable<br>
							<div style="margin: 5px; vertical-align: middle; display: inline-block; width: 30px; height: 30px; border: 4px dashed #00ff00; background-color: #b1b1ff;"></div> Overlap<br>
							<div style="margin: 5px; vertical-align: middle; display: inline-block; width: 30px; height: 30px; border: 4px dashed red; background-color: #b1b1ff;"></div> Block highlight<br>
							<div style="margin: 5px; vertical-align: middle; display: inline-block; width: 30px; height: 30px; border: 4px solid red; background-color: #b1b1ff;"></div> Block area changed, resize again<br>
							<div style="margin: 5px; vertical-align: middle; display: inline-block; width: 30px; height: 30px; border: none; background: linear-gradient(to right, red , orange, yellow, green, blue, purple);"></div> Net highlight<br>
						</span>
						<hr>
					</li>
					<li id="clearVisualizer">
						<button id="session_destroy" onclick="panelButtons(5);">Return to Floorplan project</button>
					</li>
<?PHP
	}
	if(!isset($_SESSION["dirr_floorplan"])){
?>
					<li id="formDropzone">
						<form action="create_floorplan.php" method="POST">
							<center style="border: 2px dashed black; padding: 5px;">
								<input class="textShadowInput" id="project_name" name="project_name" type="text" placeholder="Project name" required />
								<select id="selectOptions" name="options" required>
									<option disabled selected value=""> -- Select an option -- </option>
									<option value="GSRC_SOFT01">n10 (GSRC SOFT)</option>
									<option value="GSRC_SOFT02">n30 (GSRC SOFT)</option>
									<option value="GSRC_SOFT03">n50 (GSRC SOFT)</option>
									<option value="GSRC_SOFT04">n100 (GSRC SOFT)</option>
									<option value="GSRC_SOFT05">n200 (GSRC SOFT)</option>
									<option value="GSRC_SOFT06">n300 (GSRC SOFT)</option>
									<option value="MCNC_SOFT01">ami33 (MCNC SOFT)</option>
									<option value="MCNC_SOFT02">ami49 (MCNC SOFT)</option>
									<option value="MCNC_SOFT03">apte (MCNC SOFT)</option>
									<option value="MCNC_SOFT04">hp (MCNC SOFT)</option>
									<option value="MCNC_SOFT05">xerox (MCNC SOFT)</option>
									<option value="GSRC_HARD01">n10 (GSRC HARD)</option>
									<option value="GSRC_HARD02">n30 (GSRC HARD)</option>
									<option value="GSRC_HARD03">n50 (GSRC HARD)</option>
									<option value="GSRC_HARD04">n100 (GSRC HARD)</option>
									<option value="GSRC_HARD05">n200 (GSRC HARD)</option>
									<option value="GSRC_HARD06">n300 (GSRC HARD)</option>
									<option value="MCNC_HARD01">ami33 (MCNC HARD)</option>
									<option value="MCNC_HARD02">ami49 (MCNC HARD)</option>
									<option value="MCNC_HARD03">apte (MCNC HARD)</option>
									<option value="MCNC_HARD04">hp (MCNC HARD)</option>
									<option value="MCNC_HARD05">xerox (MCNC HARD)</option>
								</select><br>
								<button name="submit">Create</button>
							</center>
						</form >
					</li>
					<li>
						<form action="choose_category" method="POST">
							<button name="submit">Return to project selection</button>
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
<?PHP
	if(isset($_SESSION["dirr_floorplan"]) && !empty($_SESSION["dirr_floorplan"])){
?>
					<li id="saveWork">
						<hr>
						<center><button id="saveWorkBtn" onclick="panelButtons(16);">Save</button><br>
						<span id="successSaving" style="display: none; color: green;font-weight: bold;">Saved!</span></center>
					</li>
<?PHP
	}
?>
				</ul>
			</div>
		</div>
<?PHP
	}
?>
	</body>
</html>