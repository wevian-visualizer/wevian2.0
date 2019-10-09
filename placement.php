<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WEVIAN | Placement</title>
		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/script_placement.js"></script>
		<script src="js/dropzone.js"></script>
		<script src="js/html2canvas.js"></script>
		<script src="js/Chart.bundle.js"></script>
		<link rel="icon" type="image/png" href="favicon.png" />
		<link rel="stylesheet" type="text/css" href="css/style_placement.css"/>
		<link rel="stylesheet" type="text/css" href="css/dropzone.css"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/modal.css"/>
		<script>
		Dropzone.options.dropzone = {
			paramName: "file",
			maxFilesize: 100,
			uploadMultiple: false,
			maxFiles: 1,
			acceptedFiles: ".pl, .zip",
			dictDefaultMessage: "1. Select type from List.<br>2. Drop (.pl) file or click here.<br><br>or<br><br>Drop .zip file or click here",
			dictInvalidFileType: "Only '.pl' and '.zip' files are accepted.",
			success: function(file){
				window.location.replace("placement");
			}
		};
		</script>
	</head>
	<body>
<?PHP
	session_start();
	date_default_timezone_set('Europe/Athens');
	include("connect.php");
?>
		<div class="divLeft">
<?PHP
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(isset($_SESSION["dirr"]) && !empty($_SESSION["dirr"])){
			echo '<div id="fixedDIV" onclick="onClickShowNets(event);"></div>';
		}
	}else{
		header("Location: index");
	}
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"]) && !isset($_SESSION["dirr"])){
		if($stmt = mysqli_prepare($connect, "SELECT id,type,name,DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') FROM placement_files WHERE userid=?")){
			mysqli_stmt_bind_param($stmt, "i", $_SESSION["uid"]);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $info["id"],$info["type"],$info["name"],$info["date"]);
			mysqli_stmt_store_result($stmt);
			if(mysqli_stmt_num_rows($stmt)>0){
?>
			<div id="savedProjectsSpan">These are your uploaded projects, click one to see it or upload another!<br>Compare projects by selecting the lower area.</div>
<?PHP
			}else{
?>
			<div id="savedProjectsSpan">Select type from dropdown menu and then upload your first project!</div>
<?PHP
			}
?>
			<div id="selectable">
<?PHP
			while(mysqli_stmt_fetch($stmt)){
				$info["name"] = htmlspecialchars($info["name"]);
?>
				<div id="fid<?PHP echo $info["id"]; ?>" class="loadfilediv">
					File name: <b><span id='infoname<?PHP echo $info["id"]; ?>'><?PHP echo $info["name"]; ?></span></b><br>
					Type: <span id='infotype<?PHP echo $info["id"]; ?>'><?PHP echo $info["type"]; ?></span><br>
					Uploaded at <b><span id='infodate<?PHP echo $info["id"]; ?>'><?PHP echo $info["date"]; ?></span></b> Athens/Greece local time.
				</div>
				<div id="fid2_<?PHP echo $info["id"]; ?>" class="loadfilediv2">
					<span class="textShadow" style="cursor: pointer;" onclick="loadFile(<?PHP echo $info["id"]; ?>);">Visualize</span>
					<a class="textShadow" style="margin-left: 20%; color: rgb(255,100,150); text-decoration: none;" href="upload/<?PHP echo $_SESSION["uid"]; ?>/<?PHP echo $info["id"]; ?>/<?PHP echo substr($info["name"],-3)=="zip"?substr($info["name"],0,-4):substr($info["name"],0,-3); ?>.txt" download="<?PHP echo $info["name"]; ?>" download>Download <?PHP echo strlen($info["name"]) > 10 ? substr($info["name"], 0, 10)."..." : $info["name"]; ?></a>
					<span class="textShadow" style="margin-left: 20%; cursor: pointer;" onclick="share(<?PHP echo $info["id"]; ?>, <?PHP echo $_SESSION["uid"]; ?>, '<?PHP echo $info["type"]; ?>', '<?PHP echo $info["name"]; ?>');">Share link</span>
					<span class="textShadow" onclick="deleteFile(<?PHP echo $info["id"]; ?>);" style="float:right; margin-left: 20%; cursor: pointer;">Delete</span>
				</div>
<?PHP	
			}
			mysqli_stmt_close($stmt);
?>
				<div id="statsModal3" class="modal" style="z-index: 1000;">
					<div class="modal-content3">
						<div id="modalContent3"></div>
					</div>
				</div>
			</div>
<?PHP
		}
	}
?>
		</div>
<?PHP
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(isset($_SESSION["dirr"]) && !empty($_SESSION["dirr"])){
			echo '<script>parseSCL_NODES_PL();</script>';
		}
	}
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
?>
		<div class="divCenter"><button class="triangleRight" onclick="divRightSlide();"></button></div>
		<div class="divRight">
			<div id="menu">
				<ul>
<?PHP
	if(isset($_SESSION["dirr"]) && !empty($_SESSION["dirr"])){
?>
					<li id="highlightCell">
						<input class="textShadowInput" placeholder="Node name" id="cellInput" type="text">
						<button id="cellInputBtn" onclick="panelButtons(0);">Highlight Cell</button>
					</li>
					<li id="clearHighlightedCell">
						<input class="textShadowInput" placeholder="Node name" id="cellClear" type="text">
						<button id="cellClearBtn" onclick="panelButtons(1);">Clear Cell</button>
					</li>
					<li id="displayNets">
						<input class="textShadowInput" placeholder="Node name" id="NetInput" type="text">
						<button id="displayNetsBtn" onclick="panelButtons(4);">Highlight Net</button>
						<span id="executedTimeData3"></span>
					</li>
					<li id="clearNets">
						<input class="textShadowInput" placeholder="Node name" id="NetClearInput" type="text">
						<button id="clearNetsBtn" onclick="panelButtons(6);">Clear Net</button>
						<hr>
					</li>
					<li id="displayRows">
						<span id="spanRows" style="font-size: 14px;">Show Rows</span>
						<div style="vertical-align:middle;" id="displayRowsBtn" onclick="$('.toggle-button').toggleClass('toggle-button-selected'); panelButtons(8);" class="textShadowInput toggle-button">
							<button id="displayRowsBtn2"></button>
						</div>
					</li>
					<li id="displayOverlap">
						<span id="spanOverlap" style="font-size: 14px;">Show Overlap</span>
						<div style="vertical-align:middle;" id="displayOverlapBtn" onclick="$('.toggle-button2').toggleClass('toggle-button-selected2'); panelButtons(3);" class="textShadowInput toggle-button2">
							<button id="displayOverlapBtn2"></button>
						</div>
						<span id="executedTimeData2"></span>
					</li>
					<li id="displayOverflow">
						<span id="spanOverflow" style="font-size: 14px;">Show Overflow</span>
						<div style="vertical-align:middle;" id="displayOverflowBtn" onclick="$('.toggle-button3').toggleClass('toggle-button-selected3'); panelButtons(9);" class="textShadowInput toggle-button3">
							<button id="displayOverflowBtn2"></button>
						</div>
						<span id="executedTimeData4"></span>
					</li>
					<li id="displayHeatmap">
						<span id="spanHeatmap" style="font-size: 14px;">Show Thermal Map</span>
						<div style="vertical-align:middle;" id="displayHeatmapBtn" onclick="$('.toggle-button4').toggleClass('toggle-button-selected4'); panelButtons(10);" class="textShadowInput toggle-button4">
							<button id="displayHeatmapBtn2"></button>
						</div>
						<span id="executedTimeData5"></span>
					</li>
					<li id="displayCongestion">
						<input class="textShadowInput" placeholder="Box Num." id="BoxInput" type="text">
						<button id="displayCongestionBtn" onclick="panelButtons(11);">Congestion Map</button>
						<span id="executedTimeData6"></span>
					</li>
					<li id="clearCongestion">
						<button id="clearCongestionBtn" onclick="panelButtons(13);">Clear Congestion Map</button>
					</li>
					<li id="displayHalfPerimeter">
						<button id="displayHalfPerimeterBtn" onclick="panelButtons(12);">Calculate Half-Perimeter</button>
						<span id="executedTimeData7"></span>
						<hr>
					</li>
					<li id="cellInfoHeader">
						<table class="cellInfo">
							<tr>
								<th>Name</th>
								<th>X</th>
								<th>Y</th>
								<th>Orient.</th>
								<th>Movetype</th>
								<th>x</th>
							</tr>
						</table>
						<hr>
					</li>
					<li id="exportInfo">
						<button id="exportBtn" onclick="panelButtons(2);">Show statistics</button>
						<div id="statsModal" class="modal" style="z-index: 1000;">
							<div class="modal-content">
								<span class="close">&times;</span>
								<div id="modalContent"></div>
							</div>
						</div>
					</li>
					<li id="exportoIMAGE">
						<button id="exportIMAGE" onclick="panelButtons(14);">Export to PNG</button>
					</li>
					<li id="exportoPDF">
						<button id="exportPDF" onclick="panelButtons(7);">Print</button>
						<hr>
					</li>
					<li id="clearVisualizer">
						<button id="session_destroy" onclick="panelButtons(5);">Return to Placement project</button>
					</li>
<?PHP
	}
	if(!isset($_SESSION["dirr"])){
?>
					<li id="formDropzone">
						<form action="upload.php" id="dropzone" method="POST" class="dropzone textShadowSelect">
							<center>
								<select id="selectOptions" name="options" required>
									<option disabled selected value=""> -- Select an option -- </option>
									<option value="ibm01">ibm01</option>
									<option value="ibm02">ibm02</option>
									<option value="ibm03">ibm03</option>
									<option value="ibm04">ibm04</option>
									<option value="ibm05">ibm05</option>
									<option value="ibm06">ibm06</option>
									<option value="ibm07">ibm07</option>
									<option value="ibm08">ibm08</option>
									<option value="ibm09">ibm09</option>
									<option value="ibm10">ibm10</option>
									<option value="ibm11">ibm11</option>
									<option value="ibm12">ibm12</option>
									<option value="ibm13">ibm13</option>
									<option value="ibm14">ibm14</option>
									<option value="ibm15">ibm15</option>
									<option value="ibm16">ibm16</option>
									<option value="ibm17">ibm17</option>
									<option value="ibm18">ibm18</option>
								</select>
							</center>
						</form >
					</li>
					<hr>
					<li>
						<input name="cfid1" id="cfid1" value="" type="hidden">
						<input name="cfid2" id="cfid2" value="" type="hidden">
						<button onclick="check_compare();">Compare selected</button>
						<div id="statsModal2" class="modal" style="z-index: 1000;">
							<div class="modal-content2">
								<span class="close">&times;</span>
								<div id="modalContent2"></div>
							</div>
						</div>
					</li>
					<hr>
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
	if(isset($_SESSION["dirr"]) && !empty($_SESSION["dirr"])){
?>
					<li id="executedTime">
						<span id="executedTimeData"></span>
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
		<script>
			$('#selectable').bind("mousedown", function (e) {
				e.metaKey = true;
			}).selectable();
			$("#selectable").selectable({
				selecting: function(event, ui) {
					if ($(".ui-selected, .ui-selecting").length > 2) {
						$('.ui-selecting').removeClass("ui-selecting");
					}
				},
				filter: ".loadfilediv2",
				selected: function(event, ui){
					if ($(".ui-selected, .ui-selecting").length > 2) {
						$('.ui-selected').removeClass('ui-selected');
					}else{
						if($("#cfid1").attr("value") == "" && $("#cfid2").attr("value") != ui.selected.id.substr(5))
							$("#cfid1").attr("value", ui.selected.id.substr(5));
						else if($("#cfid2").attr("value") == "" && $("#cfid1").attr("value") != ui.selected.id.substr(5))
							$("#cfid2").attr("value", ui.selected.id.substr(5));
						if($("#cfid1").attr("value") > $("#cfid2").attr("value")){
							var lastval = $("#cfid1").attr("value");
							$("#cfid1").attr("value", $("#cfid2").attr("value"));
							$("#cfid2").attr("value", lastval);
						}
					}
				},
				cancel: 'span,a,.loadfilediv',
				unselected: function(event, ui){
					if($("#cfid1").attr("value") == ui.unselected.id.substr(5))
						$("#cfid1").attr("value", "");
					else if($("#cfid2").attr("value") == ui.unselected.id.substr(5))
						$("#cfid2").attr("value", "");
				}
			});
		</script>
	</body>
</html>