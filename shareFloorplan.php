<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>WEVIAN | Floorplanner</title>
		<script src="js/jquery.js"></script>
		<script src="js/script_floorplan.js"></script>
		<script src="js/html2canvas.js"></script>
		<script src="js/overlap.js"></script>
		<link rel="icon" type="image/png" href="favicon.png" />
		<link rel="stylesheet" type="text/css" href="css/style_floorplan.css"/>
	</head>
	<body>
		<div class="divLeft">
<?PHP
	$uid = htmlspecialchars($_GET["uid"]);
	$fid = htmlspecialchars($_GET["fid"]);
	if(!isset($uid) || empty($uid) || !isset($fid) || empty($fid)){
		header("Location: index");
	}
	@session_start();
	$_SESSION["shareUserID"] = $uid;
	$_SESSION["shareFileID"] = $fid;
	echo '<div id="fixedDIV" onclick="onClickShowNets(event);"></div>';
	echo '<script>parseBLOCKS_PL_SHARE();</script>';
?>
		</div>
		<div class="divCenter"><button class="triangleRight" onclick="divRightSlide();"></button></div>
		<div class="divRight">
			<div id="menu">
				<ul>
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
						<div style="vertical-align:middle;" id="displayHeatmapBtn" onclick="$('.toggle-button4').toggleClass('toggle-button-selected4'); panelButtons(2);" class="textShadowInput toggle-button4">
							<button id="displayHeatmapBtn2"></button>
						</div>
						<span id="executedTimeData5"></span>
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
							<div style="margin: 5px; vertical-align: middle; display: inline-block; width: 30px; height: 30px; border: none; background: linear-gradient(to right, red , orange, yellow, green, blue, purple);"></div> Net highlight<br>
						</span>
					</li>
				</ul>
			</div>
		</div>
	</body>
</html>