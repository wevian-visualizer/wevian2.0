var highlightarr = [];
var overlaparr = [];
var netarr = [];
var highlightTableRowsCount = 0;
var chipsetInfo = [];
var errors = [];

function parseBLOCKS_PL(){
	$.ajax({
		type: "POST",
		url: "unsetShare.php"
	}).done(function() {
		$.ajax({
			type: "POST",
			url: "./isSaved.php",
			success: function(saved) {
				if(!saved){
					$.ajax({
						type: "POST",
						url: "./parsersFloorplan/parseBLOCKS.php",
						dataType: "json",
						success: function(dataBLOCKS) {
							$.ajax({
								type: "POST",
								url: "./parsersFloorplan/parsePL2.php",
								dataType: "json",
								success: function(dataPL2) {
									chipsetInfo["NumSoftRectangularBlocks"] = dataBLOCKS["NumSoftRectangularBlocks"];
									chipsetInfo["NumHardRectilinearBlocks"] = dataBLOCKS["NumHardRectilinearBlocks"];
									chipsetInfo["NumTerminals"] = dataBLOCKS["NumTerminals"];
									var generateHeight = 0.85*window.innerHeight/dataPL2["maxHeight"];
									var generateWidth = 0.85*window.innerHeight/dataPL2["maxHeight"];
									//var blocksize = 70;
									var terminalsize = 1;
									for(var k=0; k<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"];k++){
										if(dataPL2[dataBLOCKS[k]["node_name"]] == undefined)
											continue;
										var div = document.createElement("DIV");
										div.setAttribute("id", dataBLOCKS[k]["node_name"]);
										if(dataBLOCKS[k]["type"]=="terminal"){
											div.setAttribute("style", "position: absolute; background-color: #b1b1ff; border-radius: 50%; border: 4px solid #ff9900; width:"+terminalsize+"px; height:"+terminalsize+"px; top:"+(-10+window.innerHeight -terminalsize -generateHeight*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Ycoord"])+"px; left:"+(generateWidth*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Xcoord"])+"px;");
										}else if(dataBLOCKS[k]["type"]!="hardrectilinear"){
											div.setAttribute("style", "position: absolute; background-color: #b1b1ff; border: 4px solid #ff9900; width:"+(Math.sqrt(dataBLOCKS[k]["area"]))+"px; height:"+(Math.sqrt(dataBLOCKS[k]["area"]))+"px; top:"+(-10+window.innerHeight -(Math.sqrt(dataBLOCKS[k]["area"])) -generateHeight*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Ycoord"])+"px; left:"+(10+generateWidth*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Xcoord"])+"px;");
										}else{
											var vertexHeight = parseInt(dataBLOCKS[k]["vertex2"].split(",")[1]) - parseInt(dataBLOCKS[k]["vertex0"].split(",")[1]);
											var vertexWidth = parseInt(dataBLOCKS[k]["vertex4"].split(",")[0].substring(1)) - parseInt(dataBLOCKS[k]["vertex0"].split(",")[0].substring(1));
											div.setAttribute("style", "position: absolute; background-color: #a1a1ff; border: 1px solid #c0c0ff; width:"+vertexWidth*generateWidth+"px; height:"+vertexHeight*generateHeight+"px; top:"+(-10+window.innerHeight -vertexHeight -generateHeight*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Ycoord"])+"px; left:"+(10+generateWidth*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Xcoord"])+"px;");
										}
										if(dataBLOCKS[k]["type"]=="softrectangular"){
											div.setAttribute("class", "manipulatable");
											div.setAttribute("area", Math.sqrt(dataBLOCKS[k]["area"])*Math.sqrt(dataBLOCKS[k]["area"]));
											div.setAttribute("minAspectRatio", dataBLOCKS[k]["min_aspect_ratio"]);
											div.setAttribute("maxAspectRatio", dataBLOCKS[k]["max_aspect_ratio"]);
											div.innerHTML = "<center style='font-size: 10px;'>"+dataBLOCKS[k]["node_name"]+"</center>";
											div.style.border = "4px dashed #ff9900";
										}
										$("#fixedDIV").append(div);
									}
									$(".manipulatable").draggable({
										opacity: 0.5,
										snap: true
									});
									$(".manipulatable").resizable({
										handles: "all",
										aspectRatio: false,
										grid: [ 20, 20 ],
										resize: function( event, ui ) {
											if($(this).outerWidth()*$(this).outerHeight()>$(this).attr("area")*1.1 || $(this).outerWidth()*$(this).outerHeight()<$(this).attr("area")*0.9){
												$(ui.element).css("border","4px solid red");
											}else{
												$(ui.element).css("border","4px dashed rgb(255, 153, 0)");
											}
										}
									});
								}
							});
						}
					});
				}else{
					$.ajax({
						type: "POST",
						url: "./loadSaved.php",
						success: function(loadedContent) {
							$("#fixedDIV").append(loadedContent);
							$(".manipulatable").draggable({
								opacity: 0.5,
								snap: true
							});
							$(".manipulatable").resizable({
								handles: "all",
								aspectRatio: false,
								grid: [ 20, 20 ],
								resize: function( event, ui ) {
									if($(this).outerWidth()*$(this).outerHeight()>$(this).attr("area")*1.1 || $(this).outerWidth()*$(this).outerHeight()<$(this).attr("area")*0.9){
										$(ui.element).css("border","4px solid red");
									}else{
										$(ui.element).css("border","4px dashed rgb(255, 153, 0)");
									}
								}
							});
						}
					});
				}
			}
		});
	});
}

function parseBLOCKS_PL_SHARE(){
	$.ajax({
		type: "POST",
		url: "./isSaved.php",
		success: function(saved) {
			if(!saved){
				$.ajax({
					type: "POST",
					url: "./parsersFloorplan/parseBLOCKS.php",
					dataType: "json",
					success: function(dataBLOCKS) {
						$.ajax({
							type: "POST",
							url: "./parsersFloorplan/parsePL2.php",
							dataType: "json",
							success: function(dataPL2) {
								var generateHeight = 0.85*window.innerHeight/dataPL2["maxHeight"];
								var generateWidth = 0.85*window.innerHeight/dataPL2["maxHeight"];
								var blocksize = 70;
								var terminalsize = 1;
								for(var k=0; k<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"];k++){
									if(dataPL2[dataBLOCKS[k]["node_name"]] == undefined)
										continue;
									var div = document.createElement("DIV");
									div.setAttribute("id", dataBLOCKS[k]["node_name"]);
									if(dataBLOCKS[k]["type"]=="terminal"){
										div.setAttribute("style", "position: absolute; background-color: #b1b1ff; border-radius: 50%; border: 4px solid #ff9900; width:"+terminalsize+"px; height:"+terminalsize+"px; top:"+(-10+window.innerHeight -terminalsize -generateHeight*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Ycoord"])+"px; left:"+(generateWidth*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Xcoord"])+"px;");
									}else if(dataBLOCKS[k]["type"]!="hardrectilinear"){
										div.setAttribute("style", "position: absolute; background-color: #b1b1ff; border: 4px solid #ff9900; width:"+blocksize+"px; height:"+blocksize+"px; top:"+(-10+window.innerHeight -blocksize -generateHeight*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Ycoord"])+"px; left:"+(generateWidth*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Xcoord"])+"px;");
									}else{
										var vertexHeight = parseInt(dataBLOCKS[k]["vertex2"].split(",")[1]) - parseInt(dataBLOCKS[k]["vertex0"].split(",")[1]);
										var vertexWidth = parseInt(dataBLOCKS[k]["vertex4"].split(",")[0].substring(1)) - parseInt(dataBLOCKS[k]["vertex0"].split(",")[0].substring(1));
										div.setAttribute("style", "position: absolute; background-color: #a1a1ff; border: 1px solid #c0c0ff; width:"+vertexWidth*generateWidth+"px; height:"+vertexHeight*generateHeight+"px; top:"+(-10+window.innerHeight -blocksize -generateHeight*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Ycoord"])+"px; left:"+(generateWidth*dataPL2[dataBLOCKS[k]["node_name"]]["ll_Xcoord"])+"px;");
									}
									if(dataBLOCKS[k]["type"]=="softrectangular"){
										div.setAttribute("area", dataBLOCKS[k]["area"]);
										div.setAttribute("minAspectRatio", dataBLOCKS[k]["min_aspect_ratio"]);
										div.setAttribute("maxAspectRatio", dataBLOCKS[k]["max_aspect_ratio"]);
										div.innerHTML = "<center style='font-size: 10px;'>"+dataBLOCKS[k]["node_name"]+"</center>";
										div.style.border = "4px dashed #ff9900";
									}
									$("#fixedDIV").append(div);
								}
							}
						});
					}
				});
			}else{
				$.ajax({
					type: "POST",
					url: "./loadSaved.php",
					success: function(loadedContent) {
						$("#fixedDIV").append(loadedContent);
						$(".manipulatable").removeClass("manipulatable");
					}
				});
				
			}
		}
	});
}

function updateFloorplanName(fid){
	var name = $("#infoname"+fid).val();
	if(name.length >= 3 && name.length<=50)
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {name:name, fid:fid, updateFloorplanName:true},
			success: function() {
				$("#infoname"+fid).css("color", "green");
				$("#infoname"+fid).css("border-color", "green");
				setTimeout(function(){
					$("#infoname"+fid).css("color", "#ff859a");
					$("#infoname"+fid).css("border-color", "#ff859a");
				},400);
			}
		});
}

function share(fid, userid){
	$("#statsModal3").css("display", "block");
	$(".modal-content3").animate({bottom:'50%'}, 200);
	$("#modalContent3").html("<span id='spanShare'><center><input id='shareInput' value='https://wevian.xyz/shareFloorplan?uid="+userid+"&fid="+fid+"' readonly></center><br></span>");
	$("#shareInput").select();
	document.execCommand("Copy");
	$("#spanShare").remove();
	$("#modalContent3").append("<center><span style='color: #505050;'>Copied to clipboard.</span></center>");
	setTimeout(function(){
		$(".modal-content3").css("bottom", "100%");
		$("#statsModal3").css("display", "none");
	},1000);
}

function loadFile(fid){
	var name = document.getElementById("infoname"+fid).innerHTML;
	var type = document.getElementById("infotype"+fid).innerHTML;
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {type:type, name:name, fid:fid, loadFileFloorplan:true},
		success: function() {
			window.location.reload();
		}
	});
}

function divRightSlide(){
	$(".divRight").toggle(0,function(){
		$(".triangleRight").toggleClass("triangleLeft");
		$(".divCenter").toggleClass("divCenterRight");
	});
}

function onClickHighlightCell(choice){
	$("#"+choice).css("border-color", "#ff0000");
	$("#"+choice).css("z-index", "999");
	$.ajax({
		type: "POST",
		url: "./parsersFloorplan/parsePL2.php",
		dataType: "json",
		success: function(dataPL) {
			highlightarr.push(choice);
			highlightTableRowsCount++;
			$(".cellInfo").append("<tr><td>"+choice+"</td><td>"+dataPL[choice]["ll_Xcoord"]+"</td><td>"+dataPL[choice]["ll_Ycoord"]+"</td><td onclick='TDdeleteRow(this);' class='TDdeleteRow'>Delete row</td></tr>");
			$("#cellInfoHeader").fadeIn(1000);
		}
	});
}

function onClickShowNets(choice) {
	if(choice.ctrlKey){
		onClickHighlightCell(choice.target.id);
	}else{
		choice = choice.target.id;
		var flag = 0;
		$.ajax({
			type: "POST",
			url: "./parsersFloorplan/parseNETS.php",
			dataType: "json",
			success: function(dataNETS) {
				chipsetInfo["NumNets"] = dataNETS["NumNets"];
				chipsetInfo["NumPins"] = dataNETS["NumPins"];
				var rndCLR = 'rgb(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ')';
				for(var i=0;i<dataNETS["NumNets"];i++) {
					flag = 0;
					for(var j=0;j<dataNETS[i]["NetDegree"];j++) {
						if(choice == dataNETS[i]["node_name"+j] && flag == 0){
							flag = 1;
							j = 0;
						}
						if(flag == 1){
							$("#"+dataNETS[i]["node_name"+j]).css("border", "4px dashed "+rndCLR);
							netarr.push(dataNETS[i]["node_name"+j]);
						}
					}
				}
			},
			error: function(req, status, error) {
				window.alert(req+"\n"+status+"\n"+error);
			}
		});
	}
}

function deleteFile(fid){
	if(confirm("This cannot be undone, are you sure?"))
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {fid:fid, deleteFileFloorplan:true},
			success: function() {
				$("#fid"+fid).slideUp(500);
				$("#fid2_"+fid).slideUp(500);
			}
		});
}

function TDdeleteRow(elem){
	highlightTableRowsCount--;
	if(highlightTableRowsCount==0){
		$("#cellInfoHeader").css("display", "none");
	}
	$(elem).parent().remove();
}

function checkForOverlap(el1, el2) {
    var bounds1 = el1.getBoundingClientRect();
    var bounds2 = el2.getBoundingClientRect();
    var firstIstLeftmost = (bounds1.left <= bounds2.left);
    var leftest = firstIstLeftmost ? bounds1 : bounds2;
    var rightest = firstIstLeftmost ? bounds2 : bounds1;
    //change to >= if border overlap should count
    if(leftest.right > rightest.left) {
        var firstIsTopmost = (bounds1.top <= bounds2.top);
        var topest = firstIsTopmost ? bounds1 : bounds2;
        var bottomest = firstIsTopmost ? bounds2 : bounds1;
        //change to >= if border overlap should count
        return topest.bottom > bottomest.top;
    }
    else return false;
}

function panelButtons(choice) {
	switch (choice) {
		// Highlight Cell
		case 0:
			var cellName = document.getElementById("cellInput").value;
			$("#"+cellName).css("border-color", "#ff0000");
			$("#"+cellName).css("z-index", "999");
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parsePL2.php",
				dataType: "json",
				success: function(dataPL) {
					if(typeof dataPL[cellName]["ll_Xcoord"] != "undefined"){
						highlightarr.push(cellName);
						highlightTableRowsCount++;
					}
					$(".cellInfo").append("<tr><td>"+cellName+"</td><td>"+dataPL[cellName]["ll_Xcoord"]+"</td><td>"+dataPL[cellName]["ll_Ycoord"]+"</td><td onclick='TDdeleteRow(this);' class='TDdeleteRow'>Delete row</td></tr>");
					$("#cellInfoHeader").fadeIn(1000);
				}
			});
			break;
		// Cell Clear
		case 1:
			var cellName = document.getElementById("cellClear").value;
			if(cellName!=""){
				$("#"+cellName).css("border-color", "#ff9900");
				$("#"+cellName).css("z-index", "0");
			}else{
				for (var i=0; i<highlightarr.length; i++) {
					$("#"+highlightarr[i]).css("border-color", "#ff9900");
					$("#"+highlightarr[i]).css("z-index", "0");
				}
				highlightarr.length = 0;
			}
			break;
		// Share thermal
		case 2:
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parseBLOCKS.php",
				dataType: "json",
				success: function(dataBLOCKS) {
					$.ajax({
						type: "POST",
						url: "./parsersFloorplan/parseWTS.php",
						dataType: "json",
						success: function(dataWTS) {
							if ($("#displayHeatmapBtn").hasClass("toggle-button-selected4")){
								for(var i=0; i<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"]; i++){
									if(dataBLOCKS[i]["type"]!="terminal"){
										if(dataWTS!="no_wts_file_found"){
											if(parseFloat(dataWTS[i]["value"])>=0.5){
												var h = (1.0 - parseFloat(dataWTS[i]["value"])) * 60;
												$("#"+dataWTS[i]["node_name"]).css("border-color", "hsl(" + Math.floor(h) + ", 100%, 50%)");
												$("#"+dataWTS[i]["node_name"]).css("background-color", "hsl(" + Math.floor(h) + ", 100%, 50%)");
											}else{
												var l = (1.0-parseFloat(dataWTS[i]["value"]))*100;
												$("#"+dataWTS[i]["node_name"]).css("border-color", "hsl(60, 100%, " + Math.floor(l) + "%)");
												$("#"+dataWTS[i]["node_name"]).css("background-color", "hsl(60, 100%, " + Math.floor(l) + "%)");
											}
										}else{
											$("#"+dataBLOCKS[i]["node_name"]).css("border-color", "white");
											$("#"+dataBLOCKS[i]["node_name"]).css("background-color", "white");
										}
									}
								}
								var spanHeatmap = document.getElementById("spanHeatmap");
								spanHeatmap.innerHTML = "Hide Thermal Map";
							} else {
								for (var i=0; i<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"]; i++) {
									if(dataBLOCKS[i]["type"]!="terminal"){
										$("#"+dataBLOCKS[i]["node_name"]).css("border-color", "#ff9900");
										$("#"+dataBLOCKS[i]["node_name"]).css("background-color", "#a1a1ff");
									}
								}
								var spanHeatmap = document.getElementById("spanHeatmap");
								spanHeatmap.innerHTML = "Show Thermal Map";
							}
						}
					});
				}
			});
			break;
		// Overlap
		case 3:
			var t11 = performance.now();
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parseBLOCKS.php",
				dataType: "json",
				success: function(dataBLOCKS) {
					if ($("#displayOverlapBtn").hasClass("toggle-button-selected2")){
						for(var k=0; k<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"]; k++){
							var rect1 = document.getElementById(dataBLOCKS[k]["node_name"]);
							for(var j=0; j<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"]; j++){
								var rect2 = document.getElementById(dataBLOCKS[j]["node_name"]);
								if(dataBLOCKS[k]["node_name"]!=dataBLOCKS[j]["node_name"] && dataBLOCKS[k]["type"]!="terminal" && dataBLOCKS[j]["type"]!="terminal"){
									if(overlaparr.indexOf(dataBLOCKS[k]["node_name"]) != -1){
										continue;
									}
									if(checkForOverlap(rect1,rect2)){
										$("#"+dataBLOCKS[k]["node_name"]).css("border","4px dashed #00ff00");
										overlaparr.push(dataBLOCKS[k]["node_name"]);
									}
								}
							}
						}
						$("#totaloverlaps").html("Total overlaps: "+overlaparr.length);
						var spanOverlap = document.getElementById("spanOverlap");
						spanOverlap.innerHTML = "Hide Overlap";
						$("#executedTimeData6").append("<li>"+(Math.floor(performance.now()-t11))+"</li>");
					} else {
						for (var i=0; i<overlaparr.length; i++) {
							$("#"+overlaparr[i]).css("border", "4px dashed #ff9900");
						}
						$("#totaloverlaps").html("");
						overlaparr.length = 0;
						var spanOverlap = document.getElementById("spanOverlap");
						spanOverlap.innerHTML = "Show Overlap";
					}
				}
			});
			break;
		case 4:
			var t0 = performance.now();
			var cellNet = document.getElementById("NetInput").value;
			var flag = 0;
			var count = 0;
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parseNETS.php",
				dataType: "json",
				success: function(dataNETS) {
					chipsetInfo["NumNets"] = dataNETS["NumNets"];
					chipsetInfo["NumPins"] = dataNETS["NumPins"];
					var rndCLR = 'rgb(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ')';
					for(var i=0;i<dataNETS["NumNets"];i++){
						flag = 0;
						for(var j=0;j<dataNETS[i]["NetDegree"];j++){
							if(cellNet == dataNETS[i]["node_name"+j] && flag == 0){
								flag = 1;
								j = 0;
							}
							if(flag == 1){
								$("#"+dataNETS[i]["node_name"+j]).css("border", "4px dashed "+rndCLR);
								$("#"+dataNETS[i]["node_name"+j]).css("z-index", "999");
								netarr.push(dataNETS[i]["node_name"+j]);
								if(dataNETS[i]["node_name"+j] !== cellNet) count++;
							}
						}
					}
					var executedtimedata = document.getElementById("executedTimeData3");
					executedtimedata.innerHTML = "<br>" + cellNet + " is connected with " + count + " other nodes.<br>Runtime: " + (Math.floor(performance.now()-t0)) + " ms.";
				},
				error: function(req, status, error) {
					window.alert(req+"\n"+status+"\n"+error);
				}
			});
			break;
		case 5:
			$.ajax({
				type: "POST",
				url: "functions.php",
				data: {clearVisualizerFloorplan: true},
				success: function() {
					window.location.reload();
				}
			});
			break;
		break;
		case 6:
			var cellNet = document.getElementById("NetClearInput").value;
			var flag = 0;
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parseNETS.php",
				dataType: "json",
				success: function(dataNETS) {
					chipsetInfo["NumNets"] = dataNETS["NumNets"];
					chipsetInfo["NumPins"] = dataNETS["NumPins"];
					if(cellNet!=""){
						for(var i=0;i<dataNETS["NumNets"];i++){
							flag = 0;
							for(var j=0;j<dataNETS[i]["NetDegree"];j++){
								if(cellNet == dataNETS[i]["node_name"+j] && flag == 0){
									flag = 1;
									j = 0;
								}
								if(flag == 1){
									$("#"+dataNETS[i]["node_name"+j]).css("border", "4px dashed #ff9900");
									$("#"+dataNETS[i]["node_name"+j]).css("z-index", "0");
								}
							}
						}
					}else{
						for (var i=0; i<netarr.length; i++) {
							$("#"+netarr[i]).css("border", "4px dashed #ff9900");
							$("#"+netarr[i]).css("z-index", "0");
						}
						netarr.length = 0;
					}
				}, error: function(req, status, error) {
					window.alert(req+"\n"+status+"\n"+error);
				}
			});
			break;
		case 7:
			$(".divCenter").hide();
			$(".divRight").hide();
			window.print();
			$(".divCenter").slideDown(1000);
			$(".divRight").slideDown(1000);
			break;
		case 10:
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parseBLOCKS.php",
				dataType: "json",
				success: function(dataBLOCKS) {
					$.ajax({
						type: "POST",
						url: "./parsersFloorplan/parseWTS.php",
						dataType: "json",
						success: function(dataWTS) {
							if(dataWTS=="no_wts_file_found"){
								$.ajax({
									type: "POST",
									url: "./parsersFloorplan/createWTS.php",
									data: {dataBLOCKS: JSON.stringify(dataBLOCKS)},
									success: function() {
										panelButtons(10);
									}
								});
							} else {
								if ($("#displayHeatmapBtn").hasClass("toggle-button-selected4")){
									if(confirm("Do you want to edit temperatures?\nTemperatures are saved for future use.")){
										$("#fixedDIV").hide();
										$("#modalContent").html("");
										$("#modalContent").append("<form method='POST' action='./save_temperatures.php'><table id='statistics' class='statistics'><tr><th>Node Name</th><th>Temperature</th></tr>");
										for(var i=0;i<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"]; i++){
											if(dataBLOCKS[i]["type"]!="terminal")
												$("#statistics").append("<tr><td>"+dataWTS[i]["node_name"]+"</td><td><input name='"+dataWTS[i]["node_name"]+"' type='number' min='0' max='1000' step='0.01' value='"+(dataWTS[i]["value"]*dataWTS["max"])+"' required></td></tr>");
										}
										$("#statistics").append("<tr><td colspan='2'><center><button type='submit'>Save</button></center></td></tr>");
										$("#modalContent").append("</table></form>");
										var span = document.getElementsByClassName("close")[0];
										$("#statsModal").css("display", "block");
										span.onclick = function() {
											$("#statsModal").css("display", "none");
											$("#fixedDIV").show();
										}
									}else{
										var t12 = performance.now();
										chipsetInfo["min_temp"] = parseInt(dataWTS["min"]);
										chipsetInfo["max_temp"] = parseInt(dataWTS["max"]);
										for(var i=0; i<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"]; i++){
											if(dataBLOCKS[i]["type"]!="terminal"){
												if(parseFloat(dataWTS[i]["value"])>=0.5){
													var h = (1.0 - parseFloat(dataWTS[i]["value"])) * 60;
													$("#"+dataWTS[i]["node_name"]).css("border-color", "hsl(" + Math.floor(h) + ", 100%, 50%)");
													$("#"+dataWTS[i]["node_name"]).css("background-color", "hsl(" + Math.floor(h) + ", 100%, 50%)");
												}else{
													var l = (1.0-parseFloat(dataWTS[i]["value"]))*100;
													$("#"+dataWTS[i]["node_name"]).css("border-color", "hsl(60, 100%, " + Math.floor(l) + "%)");
													$("#"+dataWTS[i]["node_name"]).css("background-color", "hsl(60, 100%, " + Math.floor(l) + "%)");
												}
											}
										}
									}
									var spanHeatmap = document.getElementById("spanHeatmap");
									spanHeatmap.innerHTML = "Hide Thermal Map";
								} else {
									for (var i=0; i<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"]; i++) {
										if(dataBLOCKS[i]["type"]!="terminal"){
											$("#"+dataWTS[i]["node_name"]).css("border-color", "#ff9900");
											$("#"+dataWTS[i]["node_name"]).css("background-color", "#a1a1ff");
										}
									}
									var spanHeatmap = document.getElementById("spanHeatmap");
									spanHeatmap.innerHTML = "Show Thermal Map";
								}
							}
						}
					});
				}
			});
			break;
		case 12:
			var t1 = performance.now();
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parseNETS.php",
				dataType: "json",
				success: function(dataNETS) {
					$.ajax({
						type: "POST",
						url: "./parsersFloorplan/parsePL.php",
						dataType: "json",
						success: function(dataPL) {
							chipsetInfo["NumNets"] = dataNETS["NumNets"];
							chipsetInfo["NumPins"] = dataNETS["NumPins"];
							var sum=0, minX=9999,maxX=-9999,minY=9999,maxY=-9999;
							for(var i=0;i<dataNETS["NumNets"];i++){
								minX=9999,maxX=-9999,minY=9999,maxY=-9999;
								for(var j=0;j<dataNETS[i]["NetDegree"];j++){
									if(parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().left)<minX){
										minX=parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().left);
									}
									if(parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().top)<minY){
										minY=parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().top);
									}
									if(parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().left)+parseFloat($("#"+dataNETS[i]["node_name"+j]).width())>maxX){
										maxX=parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().left)+parseFloat($("#"+dataNETS[i]["node_name"+j]).width());
									}
									if(parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().top)+parseFloat($("#"+dataNETS[i]["node_name"+j]).height())>maxY){
										maxY=parseFloat($("#"+dataNETS[i]["node_name"+j]).offset().top)+parseFloat($("#"+dataNETS[i]["node_name"+j]).height());
									}
								}
								sum+=(maxX-minX)+(maxY-minY);
							}
							chipsetInfo["Half_Perimeter"] = sum;
							var executedtimedata = document.getElementById("executedTimeData7");
							executedtimedata.innerHTML = "<br>Half-Perimeter is "+sum+".<br>Runtime: "+(Math.floor(performance.now()-t1))+" ms.";
							//$("#executedTimeData7").append("<li>"+(Math.floor(performance.now()-t1))+"</li>");
							$("#executedTimeData7").fadeIn();
						}
					});
				}
			});
			break;
		case 14:
			var t13 = performance.now();
		    html2canvas($(".divLeft"), {
				onrendered: function (canvas) {
					var linkd = document.createElement("a");
					linkd.id = "pngImage";
					linkd.href = canvas.toDataURL();
					linkd.download = "WEVIAN.png";
					$(".divRight").append(linkd);
					$("#executedTimeData8").append("<li>"+(Math.floor(performance.now()-t13))+"</li>");
					linkd.click();
					$("#pngImage").remove();
				},
				background: "#fbfbfb"
			});
			break;
		case 15:
			$("#spanLegendFirst").fadeOut(function(){
				$("#spanLegend").slideDown();
			});
			break;
		case 16:
			$(".ui-resizable-handle").remove();
			$("#cellClear").attr("value", "");
			$("#NetClearInput").attr("value", "");
			panelButtons(1);
			panelButtons(6);
			$.ajax({
				type: "POST",
				url: "./save.php",
				data: {html: $("#fixedDIV").html()},
				dataType: "html",
				success: function(data) {
					$("#successSaving").fadeIn(function(){
						$("#successSaving").fadeOut(function(){
							window.location.reload();
						});
					});
				}
			});
			break;
		case 17:
			var t2 = performance.now();
			$.ajax({
				type: "POST",
				url: "./parsersFloorplan/parseBLOCKS.php",
				dataType: "json",
				success: function(dataBLOCKS) {
					var maxX=minY=0;
					var minX=maxY=9999;
					for(var i=0;i<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"];i++){
						if(dataBLOCKS[i]["type"] != "terminal"){
							if(parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().left) < minX){
								minX=parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().left);
								//alert(minX); //0
							}
							if((parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().top)+parseFloat($("#"+dataBLOCKS[i]["node_name"]).height())) > minY){
								minY=parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().top)+parseFloat($("#"+dataBLOCKS[i]["node_name"]).height());
								//alert(minY); //655
							}
							if((parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().left)+parseFloat($("#"+dataBLOCKS[i]["node_name"]).width())) > maxX){
								maxX=parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().left)+parseFloat($("#"+dataBLOCKS[i]["node_name"]).width());
								//alert(maxX); //318.76
							}
							if(parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().top) < maxY){
								maxY=parseFloat($("#"+dataBLOCKS[i]["node_name"]).offset().top);
								//alert(maxY); //198
							}
						}
					}
					minY +=8;
					maxX +=8;
					var sum=(maxX-minX)*(minY-maxY);
					sum = Math.floor(sum);
					$("#fixedDIV").append('<svg id="svg" width="'+(maxX-minX)+'" height="'+(minY-maxY)+'" style="position:absolute;z-index:5;transform: translate('+minX+'px, '+maxY+'px);"><rect x="0" y="0" width="'+(maxX-minX)+'" height="'+(minY-maxY)+'" style="fill:rgba(177,216,255,0.6);"/></svg>');
					setTimeout(function(){
						$("#svg").remove();
					}, 3000);
					for(var i=0;i<dataBLOCKS["NumSoftRectangularBlocks"]+dataBLOCKS["NumHardRectilinearBlocks"]+dataBLOCKS["NumTerminals"];i++){
						if(dataBLOCKS[i]["type"] != "terminal"){
							sum -= parseFloat($("#"+dataBLOCKS[i]["node_name"]).width()+8)*parseFloat($("#"+dataBLOCKS[i]["node_name"]).height()+8);
						}
					}
					var executedtimedata = document.getElementById("executedTimeData17");
					executedtimedata.innerHTML = "<br>Whitespace is "+parseFloat(sum).toFixed(2)+" ("+parseFloat(sum/((maxX-minX)*(minY-maxY))*100).toFixed(2)+"%).<br>Whitespace will not be accurate if overlaps exist.<br>Runtime: "+(Math.floor(performance.now()-t2))+" ms.";
					//$("#executedTimeData17").append("<li>"+(Math.floor(performance.now()-t2))+"</li>");
					$("#executedTimeData17").fadeIn();
				}
			});
			break;
		default:
			alert("Don't be a bad boy!");
			return;
	}
}