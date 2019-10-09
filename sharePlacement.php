<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WEVIAN | Placement</title>
		<script src="js/jquery.js"></script>
		<script src="js/script_placement.js"></script>
		<script src="js/html2canvas.js"></script>
		<script src="js/Chart.bundle.js"></script>
		<link rel="icon" type="image/png" href="favicon.png" />
		<link rel="stylesheet" type="text/css" href="css/style_placement.css"/>
		<link rel="stylesheet" type="text/css" href="css/modal.css"/>
	</head>
	<body>
<?PHP
	session_start();
?>
		<div class="divLeft">
<?PHP
	$uid = htmlspecialchars($_GET["uid"]);
	$fid = htmlspecialchars($_GET["fid"]);
	$type = htmlspecialchars($_GET["type"]);
	$filename = htmlspecialchars($_GET["name"]);
	if(!isset($uid) || empty($uid) || !isset($fid) || empty($fid) || !isset($type) || empty($type) || !isset($filename) || empty($filename)){
		header("Location: index");
	}
	@session_start();
	$_SESSION["shareUserID"] = $uid;
	$_SESSION["shareFileID"] = $fid;
	$_SESSION["shareType"] = $type;
	$_SESSION["shareFilename"] = $filename;
	echo '<div id="fixedDIV" onclick="onClickShowNets(event);"></div>';
	echo '<script>parseSCL_NODES_PL_SHARE();</script>';
?>
		</div>
		<div class="divCenter"><button class="triangleRight" onclick="divRightSlide();"></button></div>
		<div class="divRight">
			<div id="menu">
				<ul>
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
					<li id="executedTime">
						<span id="executedTimeData"></span>
					</li>
				</ul>
			</div>
		</div>
	</body>
</html>