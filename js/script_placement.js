var overlaparr = [];
var highlightarr = [];
var netarr = [];
var overflowarr = [];
var highlightTableRowsCount = 0;
var chipsetInfo = [];
var statistics = [[],[],[],[]];

function parseSCL_NODES_PL() {
	$.ajax({
		type: "POST",
		url: "unsetShare.php"
	}).done(function() {
		$("#dropzone").hide();
		var t0 = performance.now();
		$.ajax({
			type: "POST",
			url: "./parsers/parseSCL.php",
			dataType: "json",
			success: function(data) {
				for (var i=data["NumRows"]-1; i>=0; i--) {
					var div = document.createElement("DIV");
					div.setAttribute("id", "div"+i);
					var w = 80*85*window.innerWidth/100/100;
					var h = 85*window.innerHeight/100;
					if (data[data["NumRows"]-1]["Coordinate"] > w && data["NumRows"]*data[i]["Height"] > h) {
						var generateWidth = 100*w/data[data["NumRows"]-1]["Numsites"]/100;
						var generateHeight = 100*h/(data["NumRows"]*data[i]["Height"])/100;
						div.setAttribute("style", "border: none; width:"+generateWidth*data[i]["SubrowOrigin"]+generateWidth*data[i]["Numsites"]*data[i]["Sitespacing"]+"px; height:"+(generateHeight*data[i]["Height"])+"px; top:"+generateHeight*data[i]["Coordinate"]+"px; left:"+data[i]["SubrowOrigin"]+"px;");
						div.setAttribute("class", "hoverRow");
					} else {alert("Something is wrong. ("+i+")"); break;}
					$("#fixedDIV").append(div);
				}
				chipsetInfo["rowWidth"] = data[0]["SubrowOrigin"]+data[0]["Numsites"]*data[0]["Sitespacing"];
				chipsetInfo["rowHeight"] = data[0]["Height"];
				chipsetInfo["NumRows"] = data["NumRows"];
				$.ajax({
					type: "POST",
					url: "./parsers/parseNODES2.php",
					dataType: "json",
					success: function(dataNODES) {
						$.ajax({
							type: "POST",
							url: "./parsers/parsePL.php",
							dataType: "json",
							success: function(dataPL) {
								chipsetInfo["NumNodes"] = dataNODES["NumNodes"];
								chipsetInfo["NumTerminals"] = dataNODES["NumTerminals"];
								var abscoord = 0;
								for (var k=dataNODES["NumNodes"]-1; k>=0; k--) {
									if (dataPL[k]["ll_Xcoord"] < 0 || dataPL[k]["ll_Ycoord"] < 0) {
										if (dataPL[k]["ll_Xcoord"] < 0) {
											abscoord = Math.abs(dataPL[k]["ll_Xcoord"]);
										} else {
											abscoord = Math.abs(dataPL[k]["ll_Ycoord"]);
										}
										var fixeddiv = document.getElementById("fixedDIV");
										fixeddiv.setAttribute("style", "margin: " + abscoord + "px 0px " + abscoord + "px " + abscoord + "px");
										chipsetInfo["abscoord"] = abscoord;
										break;
									}
								}
								var w = 80*85*window.innerWidth/100/100;
								var h = 85*window.innerHeight/100;
								var generateWidth = 100*w/(data[data["NumRows"]-1]["Numsites"])/100;
								var generateHeight = 100*h/(data["NumRows"]*data[0]["Height"])/100;
								chipsetInfo["generateWidth"] = generateWidth;
								chipsetInfo["generateHeight"] = generateHeight;
								for(var k=dataNODES["NumNodes"]-1;k>=0;k--){
									var div = document.createElement("DIV");
									div.setAttribute("id", dataPL[k]["node_name"]);
									div.setAttribute("style", "position: absolute; background-color: #9a9a9a; border: 1px solid #c0c0c0; width:"+generateWidth*dataNODES[dataPL[k]["node_name"]]["width"]+"px; height:"+generateHeight*dataNODES[dataPL[k]["node_name"]]["height"]+"px; top:"+(h-generateHeight*dataNODES[dataPL[k]["node_name"]]["height"]+abscoord-generateHeight*dataPL[k]["ll_Ycoord"])+"px; left:"+(abscoord+generateWidth*dataPL[k]["ll_Xcoord"])+"px;");
									$("#fixedDIV").append(div);
								}
								var executedtimedata = document.getElementById("executedTimeData");
								var time = (Math.floor(performance.now()-t0));
								chipsetInfo["visualise_time"] = time;
								executedtimedata.innerHTML = "App. runtime: " + time + " ms.";
							}
						});
					}
				});
			}
		});
	});
}

function parseSCL_NODES_PL_SHARE() {
	var t0 = performance.now();
	$.ajax({
		type: "POST",
		url: "./parsers/parseSCL.php",
		dataType: "json",
		success: function(data) {
			for (var i=data["NumRows"]-1; i>=0; i--) {
				var div = document.createElement("DIV");
				div.setAttribute("id", "div"+i);
				var w = 80*85*window.innerWidth/100/100;
				var h = 85*window.innerHeight/100;
				if (data[data["NumRows"]-1]["Coordinate"] > w && data["NumRows"]*data[i]["Height"] > h) {
					var generateWidth = 100*w/data[data["NumRows"]-1]["Numsites"]/100;
					var generateHeight = 100*h/(data["NumRows"]*data[i]["Height"])/100;
					div.setAttribute("style", "border: none; width:"+generateWidth*data[i]["SubrowOrigin"]+generateWidth*data[i]["Numsites"]*data[i]["Sitespacing"]+"px; height:"+(generateHeight*data[i]["Height"])+"px; top:"+generateHeight*data[i]["Coordinate"]+"px; left:"+data[i]["SubrowOrigin"]+"px;");
					div.setAttribute("class", "hoverRow");
				} else {alert("Something is wrong. ("+i+")"); break;}
				$("#fixedDIV").append(div);
			}
			chipsetInfo["rowWidth"] = data[0]["SubrowOrigin"]+data[0]["Numsites"]*data[0]["Sitespacing"];
			chipsetInfo["rowHeight"] = data[0]["Height"];
			chipsetInfo["NumRows"] = data["NumRows"];
			$.ajax({
				type: "POST",
				url: "./parsers/parseNODES2.php",
				dataType: "json",
				success: function(dataNODES) {
					$.ajax({
						type: "POST",
						url: "./parsers/parsePL.php",
						dataType: "json",
						success: function(dataPL) {
							chipsetInfo["NumNodes"] = dataNODES["NumNodes"];
							chipsetInfo["NumTerminals"] = dataNODES["NumTerminals"];
							var abscoord = 0;
							for (var k=dataNODES["NumNodes"]-1; k>=0; k--) {
								if (dataPL[k]["ll_Xcoord"] < 0 || dataPL[k]["ll_Ycoord"] < 0) {
									if (dataPL[k]["ll_Xcoord"] < 0) {
										abscoord = Math.abs(dataPL[k]["ll_Xcoord"]);
									} else {
										abscoord = Math.abs(dataPL[k]["ll_Ycoord"]);
									}
									var fixeddiv = document.getElementById("fixedDIV");
									fixeddiv.setAttribute("style", "margin: " + abscoord + "px 0px " + abscoord + "px " + abscoord + "px");
									chipsetInfo["abscoord"] = abscoord;
									break;
								}
							}
							var w = 80*85*window.innerWidth/100/100;
							var h = 85*window.innerHeight/100;
							var generateWidth = 100*w/(data[data["NumRows"]-1]["Numsites"])/100;
							var generateHeight = 100*h/(data["NumRows"]*data[0]["Height"])/100;
							chipsetInfo["generateWidth"] = generateWidth;
							chipsetInfo["generateHeight"] = generateHeight;
							for(var k=dataNODES["NumNodes"]-1;k>=0;k--){
								var div = document.createElement("DIV");
								div.setAttribute("id", dataPL[k]["node_name"]);
								div.setAttribute("style", "position: absolute; background-color: #9a9a9a; border: 1px solid #c0c0c0; width:"+generateWidth*dataNODES[dataPL[k]["node_name"]]["width"]+"px; height:"+generateHeight*dataNODES[dataPL[k]["node_name"]]["height"]+"px; top:"+(h-generateHeight*dataNODES[dataPL[k]["node_name"]]["height"]+abscoord-generateHeight*dataPL[k]["ll_Ycoord"])+"px; left:"+(abscoord+generateWidth*dataPL[k]["ll_Xcoord"])+"px;");
								$("#fixedDIV").append(div);
							}
							var executedtimedata = document.getElementById("executedTimeData");
							var time = (Math.floor(performance.now()-t0));
							chipsetInfo["visualise_time"] = time;
							executedtimedata.innerHTML = "Runtime: " + time + " ms.";
						}
					});
				}
			});
		}
	});
}

function share(fid, userid, type, name){
	$("#statsModal3").css("display", "block");
	$(".modal-content3").animate({bottom:'50%'}, 200);
	$("#modalContent3").html("<span id='spanShare'><center><input id='shareInput' value='https://wevian.xyz/sharePlacement?uid="+userid+"&fid="+fid+"&type="+type+"&name="+name+"' readonly></center><br></span>");
	$("#shareInput").select();
	document.execCommand("Copy");
	$("#spanShare").remove();
	$("#modalContent3").append("<center><span style='color: #505050;'>Copied to clipboard.</span></center>");
	setTimeout(function(){
		$(".modal-content3").css("bottom", "100%");
		$("#statsModal3").css("display", "none");
	},1000);
}

var myChart;

function changeChart(name, val1, val2){
	myChart.data.datasets[0].data[0] = val1;
	myChart.data.datasets[0].data[1] = val2;
	myChart.options.title.text = name;
	myChart.update();
}

function do_compare_action(fid1, fid2, fn1, fn2, type, numrows){
	var nodeName = $('#cellInput').val();
	var action = $("input:radio:checked").val();
	if(action == "highlight"){
		$.ajax({
			type: "POST",
			url: "./parsers/parseNODES2.php",
			data: {type:type},
			dataType: "json",
			success: function(dataNODES) {
				$.ajax({
					type: "POST",
					url: "./parsers/parseSCL.php",
					data: {type:type},
					dataType: "json",
					success: function(dataSCL) {
						$.ajax({
							type: "POST",
							url: "./parsers/parsePL2.php",
							data: {fid:fid1, fn:fn1},
							dataType: "json",
							success: function(dataPL) {
								var div = document.createElement("DIV");
								div.setAttribute("id", nodeName);
								var w = 30*85*window.innerWidth/10000;
								var h = 35*window.innerHeight/100;
								var generateWidth = w/dataSCL[numrows-1]["Numsites"];
								var generateHeight = h/(numrows*dataSCL[0]["Height"]);
								div.setAttribute("style", "margin-left: 1.5%; position: absolute; border: 1px solid red; width:"+generateWidth*dataNODES[nodeName]["width"]+"px; height:"+generateHeight*dataNODES[nodeName]["height"]+"px; top:"+(h-generateHeight*dataNODES[nodeName]["height"]+$("#div0i").position().top-generateHeight*dataPL[nodeName]["ll_Ycoord"])+"px; left:"+($("#div0i").position().left+generateWidth*dataPL[nodeName]["ll_Xcoord"])+"px;");
								div.setAttribute("class", "removable");
								$("#thumbnail1").append(div);
							}
						});
						$.ajax({
							type: "POST",
							url: "./parsers/parsePL2.php",
							data: {fid:fid2, fn:fn2},
							dataType: "json",
							success: function(dataPL) {
								var div = document.createElement("DIV");
								div.setAttribute("id", nodeName);
								var w = 30*85*window.innerWidth/10000;
								var h = 35*window.innerHeight/100;
								var generateWidth = w/dataSCL[numrows-1]["Numsites"];
								var generateHeight = h/(numrows*dataSCL[0]["Height"]);
								div.setAttribute("style", "margin-left: 1.5%; position: absolute; border: 1px solid red; width:"+generateWidth*dataNODES[nodeName]["width"]+"px; height:"+generateHeight*dataNODES[nodeName]["height"]+"px; top:"+(h-generateHeight*dataNODES[nodeName]["height"]+$("#div0j").position().top-generateHeight*dataPL[nodeName]["ll_Ycoord"])+"px; left:"+($("#div0j").position().left+generateWidth*dataPL[nodeName]["ll_Xcoord"])+"px;");
								div.setAttribute("class", "removable");
								$("#thumbnail2").append(div);
							}
						});
					}
				});
			}
		});
	}else if(action == "nets"){
		$.ajax({
			type: "POST",
			url: "./parsers/parseNETS.php",
			data: {type:type},
			dataType: "json",
			success: function(dataNETS) {
				$.ajax({
					type: "POST",
					url: "./parsers/parseSCL.php",
					data: {type:type},
					dataType: "json",
					success: function(dataSCL) {
						$.ajax({
							type: "POST",
							url: "./parsers/parseNODES2.php",
							data: {type:type},
							dataType: "json",
							success: function(dataNODES) {
								var rndCLR = 'rgb(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ')';
								$.ajax({
									type: "POST",
									url: "./parsers/parsePL2.php",
									data: {fid:fid1, fn:fn1},
									dataType: "json",
									success: function(dataPL) {
										for(var i=0;i<dataNETS["NumNets"];i++){
											flag = 0;
											for(var j=0;j<dataNETS[i]["NetDegree"];j++){
												if(nodeName == dataNETS[i]["node_name"+j] && flag == 0){
													flag = 1;
													j = 0;
												}
												if(flag == 1){
													var div = document.createElement("DIV");
													div.setAttribute("id", dataNETS[i]["node_name"+j]+"netsi");
													var w = 30*85*window.innerWidth/10000;
													var h = 35*window.innerHeight/100;
													var generateWidth = w/dataSCL[numrows-1]["Numsites"];
													var generateHeight = h/(numrows*dataSCL[0]["Height"]);
													div.setAttribute("style", "margin-left: 1.5%; position: absolute; border: 1px solid "+rndCLR+"; width:"+generateWidth*dataNODES[dataNETS[i]["node_name"+j]]["width"]+"px; height:"+generateHeight*dataNODES[dataNETS[i]["node_name"+j]]["height"]+"px; top:"+(h-generateHeight*dataNODES[dataNETS[i]["node_name"+j]]["height"]+$("#div0i").position().top-generateHeight*dataPL[dataNETS[i]["node_name"+j]]["ll_Ycoord"])+"px; left:"+($("#div0i").position().left+generateWidth*dataPL[dataNETS[i]["node_name"+j]]["ll_Xcoord"])+"px;");
													div.setAttribute("class", "removable");
													$("#thumbnail1").append(div);
												}
											}
										}
									}
								});
								$.ajax({
									type: "POST",
									url: "./parsers/parsePL2.php",
									data: {fid:fid2, fn:fn2},
									dataType: "json",
									success: function(dataPL) {
										for(var i=0;i<dataNETS["NumNets"];i++){
											flag = 0;
											for(var j=0;j<dataNETS[i]["NetDegree"];j++){
												if(nodeName == dataNETS[i]["node_name"+j] && flag == 0){
													flag = 1;
													j = 0;
												}
												if(flag == 1){
													var div = document.createElement("DIV");
													div.setAttribute("id", dataNETS[i]["node_name"+j]+"netsj");
													var w = 30*85*window.innerWidth/10000;
													var h = 35*window.innerHeight/100;
													var generateWidth = w/dataSCL[numrows-1]["Numsites"];
													var generateHeight = h/(numrows*dataSCL[0]["Height"]);
													div.setAttribute("style", "margin-left: 1.5%; position: absolute; border: 1px solid "+rndCLR+"; width:"+generateWidth*dataNODES[dataNETS[i]["node_name"+j]]["width"]+"px; height:"+generateHeight*dataNODES[dataNETS[i]["node_name"+j]]["height"]+"px; top:"+(h-generateHeight*dataNODES[dataNETS[i]["node_name"+j]]["height"]+$("#div0j").position().top-generateHeight*dataPL[dataNETS[i]["node_name"+j]]["ll_Ycoord"])+"px; left:"+($("#div0j").position().left+generateWidth*dataPL[dataNETS[i]["node_name"+j]]["ll_Xcoord"])+"px;");
													div.setAttribute("class", "removable");
													$("#thumbnail2").append(div);
												}
											}
										}
									}
								});
							}
						});
					}
				});
			}
		});
	}
}

function compare_placement(fid1, fid2){
	$.ajax({
		type: "POST",
		url: "./parsers/parseFileInfo.php",
		data: {fid1:fid1, fid2:fid2},
		success: function(data) {
			data = JSON.parse(data);
			if(data[0][1]!=data[1][1] || data[0][1]=="ZIP" || data[1][1]=="ZIP"){
				alert("Projects should be of the same type and zip currently not supported.");
				document.getElementsByClassName("close")[0].click();
				return false;
			}
			$.ajax({
				type: "POST",
				url: "./parsers/parseCompareInfo.php",
				data: {type:data[0][1], fid1:fid1, fid2:fid2, fn1:data[0][2], fn2:data[1][2]},
				success: function(data1) {
					data1 = JSON.parse(data1);
					if(data!=false){
						$("#compareStats").html("<center><b>PLACEMENT PROJECTS COMPARISON</b></center><br>");
						$("#compareStats").append("\
							<table class='compareStats'>\
								<tr>\
									<th style='width: 10% !important;'>Name</th>\
									<th>"+data[0][2]+"</th>\
									<th>"+data[1][2]+"</th>\
								</tr>\
								<tr class='zoom'>\
									<td style='width: 10% !important;'>Thumbnail</td>\
									<td id='thumbnail1'></td>\
									<td id='thumbnail2'></td>\
								</tr>\
								<tr>\
									<td style='width: 10% !important;'>Actions</td>\
									<td colspan='2'><input onchange='do_compare_action("+fid1+", "+fid2+", \""+data[0][2]+"\", \""+data[1][2]+"\", \""+data[0][1]+"\", "+data1["NumRows"]+");' placeholder='Node name' id='cellInput' type='text'>\
									<input type='radio' name='cmpradio' value='highlight' checked>Cell <input type='radio' name='cmpradio' value='nets'>Net <button onclick='$(\".removable\").remove();'>Clear</td>\
								</tr>\
								<tr>\
									<td style='width: 10% !important;'>Type</td>\
									<td colspan='2'>"+data[0][1]+"</td>\
								</tr>\
								<tr>\
									<td style='width: 10% !important;'>Uploaded</td>\
									<td>"+data[0][4]+"</td>\
									<td>"+data[1][4]+"</td>\
								</tr>\
							</table><br>\
							<table class='compareStats'>\
								<tr>\
									<td style='width: 10% !important;'>Nodes</td>\
									<td colspan='2'>"+data1["NumNodes"]+"</td>\
								</tr>\
								<tr>\
									<td style='width: 10% !important;'>Terminals</td>\
									<td colspan='2'>"+data1["NumTerminals"]+"</td>\
								</tr>\
								<tr>\
									<td style='width: 10% !important;'>Nets</td>\
									<td colspan='2'>"+data1["NumNets"]+"</td>\
								</tr>\
								<tr>\
									<td style='width: 10% !important;'>Pins</td>\
									<td colspan='2'>"+data1["NumPins"]+"</td>\
								</tr>\
								<tr>\
									<td style='width: 10% !important;'>Rows</td>\
									<td colspan='2'>"+data1["NumRows"]+"</td>\
								</tr>\
							</table><br>\
							<table class='compareStats'>\
								<tr onmouseover='changeChart(\"Overlaps\", "+data1["Overlap1"]+", "+data1["Overlap2"]+");'>\
									<td style='width: 10% !important;'>Overlaps</td>\
									<td>"+data1["Overlap1"]+"</td>\
									<td>"+data1["Overlap2"]+"</td>\
								</tr>\
								<tr onmouseover='changeChart(\"Overflows\", "+data1["Overflow1"]+", "+data1["Overflow2"]+");'>\
									<td style='width: 10% !important;'>Overflows</td>\
									<td>"+data1["Overflow1"]+"</td>\
									<td>"+data1["Overflow2"]+"</td>\
								</tr>\
								<tr onmouseover='changeChart(\"Half-Perimeter\", "+data1["HalfPerimeter1"]+", "+data1["HalfPerimeter2"]+");'>\
									<td style='width: 10% !important;'>Half-Perimeter</td>\
									<td>"+data1["HalfPerimeter1"]+"</td>\
									<td>"+data1["HalfPerimeter2"]+"</td>\
								</tr>\
							</table>\
							<div id='divt' style='left: "+(screen.width-300)+"px; top: "+(screen.height/2-250)+"px; position: fixed; width: 250px; height: 400px;'></div>");
							$.ajax({
								type: "POST",
								url: "./parsers/parseSCL.php",
								data: {type:data[0][1]},
								dataType: "json",
								success: function(dataSCL) {
									for (var i=0; i<data1["NumRows"]; i++) {
										var div = document.createElement("DIV");
										div.setAttribute("id", "div"+i+"i");
										var w = 30*85*window.innerWidth/100/100;
										var h = 35*window.innerHeight/100;
										var generateWidth = w/dataSCL[dataSCL["NumRows"]-1]["Numsites"];
										var generateHeight = h/(dataSCL["NumRows"]*dataSCL[i]["Height"]);
										div.setAttribute("style", "margin-left: 5%; background-color: #e0e0e0; border: none; width:"+generateWidth*dataSCL[i]["SubrowOrigin"]+generateWidth*dataSCL[i]["Numsites"]*dataSCL[i]["Sitespacing"]+"px; height:"+(generateHeight*dataSCL[i]["Height"])+"px; top:"+generateHeight*dataSCL[i]["Coordinate"]+"px; left:"+dataSCL[i]["SubrowOrigin"]+"px;");
										$("#thumbnail1").append(div);
										$("#div"+i+"i").clone().attr("id", "div"+i+"j").appendTo("#thumbnail2");
									}
								}
							});
							$("#divt").append("<canvas id='barchart' width='250' height='400'></canvas>");
							myChart = new Chart(document.getElementById("barchart"),{
								type: 'bar',
								data: {
									labels: [jQuery.trim(data[0][2]).substring(0, 8).trim(this) == data[0][2] ? data[0][2] : jQuery.trim(data[0][2]).substring(0, 8).trim(this) + "...",jQuery.trim(data[1][2]).substring(0, 8).trim(this) == data[1][2] ? data[1][2] : jQuery.trim(data[1][2]).substring(0, 8).trim(this) + "..."],
									datasets: [
										{
											backgroundColor: [
												"#FF6384",
												"#36A2EB",
											],
											data: [data1["Overlap1"],data1["Overlap2"]]
										}
									]
								},
								options: {
									 title: {
										display: true,
										text: 'Overlaps'
									},
									scales: {
										xAxes: [{
											gridLines: {
												display: false
											}
										}],
										yAxes: [{
											gridLines: {
												display: false
											},
											ticks:{
												beginAtZero: true,
												callback: function(data1, data2, data3){
													if(data1 > 9999){
														data1 = data1.toExponential();
													} else {
														if(!Number.isInteger(data1) && (data1 + "").split(".")[1].length > 2){
															data1 = data1.toFixed(1);
														}
													}
													return data1;
												}
											}
										}]
									},
									responsive: false,
									legend: {
										display: false
									}
								}
							});
					}
					$("#loading").remove();
				}
			});
		}
	});
}
		
function check_compare(){
	if($("#cfid1").val() == "" || $("#cfid2").val() == ""){
		alert("Select 2 projects.");
	}else{
		var div = document.createElement("DIV");
		div.setAttribute("id", "compareStats");
		$("#modalContent2").html(div);
		$("#modalContent2").append("<div id='loading'><center><img src='logo_frames.gif'/></center></div>");
		var span = document.getElementsByClassName("close")[0];
		$("#statsModal2").css("display", "block");
		span.onclick = function() {
			$("#statsModal2").css("display", "none");
		}
		window.onclick = function(event) {
			if (event.target.id == "statsModal2") {
				$("#statsModal2").css("display", "none");
			}
		}
		var fid1 = $("#cfid1").val();
		var fid2 = $("#cfid2").val();
		compare_placement(fid1, fid2);
	}
	return false;
}

function loadFile(fid){
	var name = document.getElementById("infoname"+fid).innerHTML;
	var type = document.getElementById("infotype"+fid).innerHTML;
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {type:type, name:name, fid:fid, loadFile:true},
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
		url: "./parsers/parsePL2.php",
		dataType: "json",
		success: function(dataPL) {
			highlightarr.push(choice);
			highlightTableRowsCount++;
			$(".cellInfo").append("<tr><td>"+choice+"</td><td>"+dataPL[choice]["ll_Xcoord"]+"</td><td>"+dataPL[choice]["ll_Ycoord"]+"</td><td>"+dataPL[choice]["orientation"]+"</td><td>"+dataPL[choice]["movetype"]+"</td><td onclick='TDdeleteRow(this);' class='TDdeleteRow'>Delete row</td></tr>");
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
			url: "./parsers/parseNETS.php",
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
							$("#"+dataNETS[i]["node_name"+j]).css("border", "2px solid "+rndCLR);
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
			data: {fid:fid, deleteFile:true},
			success: function() {
				$("#fid2_"+fid).removeClass("ui-selected");
				$("#cfid1").attr("value", "");
				$("#cfid2").attr("value", "");
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

function panelButtons(choice) {
	switch (choice) {
		// Highlight Cell
		case 0:
			var cellName = document.getElementById("cellInput").value;
			$("#"+cellName).css("border-color", "#ff0000");
			$("#"+cellName).css("z-index", "999");
			$.ajax({
				type: "POST",
				url: "./parsers/parsePL2.php",
				dataType: "json",
				success: function(dataPL) {
					highlightarr.push(cellName);
					highlightTableRowsCount++;
					$(".cellInfo").append("<tr><td>"+cellName+"</td><td>"+dataPL[cellName]["ll_Xcoord"]+"</td><td>"+dataPL[cellName]["ll_Ycoord"]+"</td><td>"+dataPL[cellName]["orientation"]+"</td><td>"+dataPL[cellName]["movetype"]+"</td><td onclick='TDdeleteRow(this);' class='TDdeleteRow'>Delete row</td></tr>");
					$("#cellInfoHeader").fadeIn(1000);
				}
			});
			break;
		// Cell Clear
		case 1:
			var cellName = document.getElementById("cellClear").value;
			if(cellName!=""){
				$("#"+cellName).css("border-color", "#c0c0c0");
				$("#"+cellName).css("z-index", "0");
			}else{
				for (var i=0; i<highlightarr.length; i++) {
					$("#"+highlightarr[i]).css("border-color", "#c0c0c0");
					$("#"+highlightarr[i]).css("z-index", "0");
				}
				highlightarr.length = 0;
			}
			break;
		// Show statistics
		case 2:
			$("#modalContent").html("");
			if(chipsetInfo["NumNodes"]!=undefined && chipsetInfo["NumTerminals"]!=undefined && chipsetInfo["NumRows"]!=undefined){
				if(chipsetInfo["overlaps"] !== 0)
				chipsetInfo["overlaps"] = chipsetInfo["overlaps"] ? chipsetInfo["overlaps"]:"You should run Overlap detection first.";
				if(chipsetInfo["overflows"] !== 0)
				chipsetInfo["overflows"] = chipsetInfo["overflows"] ? chipsetInfo["overflows"]:"You should run Overflow detection first.";
				if(chipsetInfo["min_area"] !== 0)
				chipsetInfo["min_area"] = chipsetInfo["min_area"] ? chipsetInfo["min_area"]:"You should run Congestion Map first.";
				if(chipsetInfo["min_temp"] !== 0)
				chipsetInfo["min_temp"] = chipsetInfo["min_temp"] ? chipsetInfo["min_temp"]:"You should run Thermal Map first.";
				if(chipsetInfo["max_area"] !== 0)
				chipsetInfo["max_area"] = chipsetInfo["max_area"] ? chipsetInfo["max_area"]:"You should run Congestion Map first.";
				if(chipsetInfo["max_temp"] !== 0)
				chipsetInfo["max_temp"] = chipsetInfo["max_temp"] ? chipsetInfo["max_temp"]:"You should run Thermal Map first.";
				if(chipsetInfo["Half_Perimeter"] !== 0)
				chipsetInfo["Half_Perimeter"] = chipsetInfo["Half_Perimeter"] ? chipsetInfo["Half_Perimeter"]:"You should run Half-Perimeter Calculation first.";
				if(chipsetInfo["NumNets"]==undefined){
					$.ajax({
						type: "POST",
						url: "./parsers/parseNETS.php",
						dataType: "json",
						success: function(dataNETS) {
							chipsetInfo["NumNets"] = dataNETS["NumNets"];
							chipsetInfo["NumPins"] = dataNETS["NumPins"];
							$("#modalContent").prepend("<table id='statistics' class='statistics'><tr><td>NumNodes</td><td style='border-right: 2px solid #c0c0c0;'>"+chipsetInfo["NumNodes"]+"</td><td>"+chipsetInfo["overlaps"]+"</td><td>Overlaps</td></tr><tr><td>NumTerminals</td><td style='border-right: 2px solid #c0c0c0;'>"+chipsetInfo["NumTerminals"]+"</td><td>"+chipsetInfo["overflows"]+"</td><td>Overflows</td></tr><tr><td>NumRows</td><td style='border-right: 2px solid #c0c0c0;'>"+chipsetInfo["NumRows"]+"</td><td>"+chipsetInfo["min_temp"]+"</td><td>Min. Temperature</td></tr><tr><td>NumNets</td><td style='border-right: 2px solid #c0c0c0;'>"+chipsetInfo["NumNets"]+"</td><td>"+chipsetInfo["max_temp"]+"</td><td>Max. Temperature</td></tr><tr><td>NumPins</td><td style='border-right: 2px solid #c0c0c0;'>"+chipsetInfo["NumPins"]+"</td><td>"+chipsetInfo["min_area"]+"</td><td>Min. Area</td></tr><tr><td>Half-Perimeter</td><td style='border-right: 2px solid #c0c0c0;'>"+chipsetInfo["Half_Perimeter"]+"</td><td>"+chipsetInfo["max_area"]+"</td><td>Max. Area</td></tr></table>");
						}
					});
				}else{
					$("#modalContent").prepend("<table id='statistics' class='statistics'><tr><td>NumNodes</td><td style='border-right: 1px solid #c0c0c0;'>"+chipsetInfo["NumNodes"]+"</td><td>"+chipsetInfo["overlaps"]+"</td><td>Overlaps</td></tr><tr><td>NumTerminals</td><td style='border-right: 1px solid #c0c0c0;'>"+chipsetInfo["NumTerminals"]+"</td><td>"+chipsetInfo["overflows"]+"</td><td>Overflows</td></tr><tr><td>NumRows</td><td style='border-right: 1px solid #c0c0c0;'>"+chipsetInfo["NumRows"]+"</td><td>"+chipsetInfo["min_temp"]+"</td><td>Min. Temperature</td></tr><tr><td>NumNets</td><td style='border-right: 1px solid #c0c0c0;'>"+chipsetInfo["NumNets"]+"</td><td>"+chipsetInfo["max_temp"]+"</td><td>Max. Temperature</td></tr><tr><td>NumPins</td><td style='border-right: 1px solid #c0c0c0;'>"+chipsetInfo["NumPins"]+"</td><td>"+chipsetInfo["min_area"]+"</td><td>Min. Area</td></tr><tr><td>Half-Perimeter</td><td style='border-right: 1px solid #c0c0c0;'>"+chipsetInfo["Half_Perimeter"]+"</td><td>"+chipsetInfo["max_area"]+"</td><td>Max. Area</td></tr></table>");
				}
			}else{
				alert("Something went wrong. Please refresh the page and start again.");
				break;
			}
			if(document.getElementById("piechart") === null){
				if(chipsetInfo["overflows"]>=0 && chipsetInfo["overlaps"]>=0){
					$("#modalContent").append("<br><canvas id='piechart' width='1000px' height='300px'></canvas>");
					var ctx = document.getElementById("piechart");
					var myPieChart = new Chart(ctx,{
						type: 'pie',
						data: {
							labels: ["Overlapped Nodes","Overflowed Nodes","Fixed Nodes","Regular Nodes"],
							datasets: [
								{
									backgroundColor: [
										"#FF6384",
										"#36A2EB",
										"#FFCE56",
										"#FF0000"
									],
									data: [chipsetInfo["overlaps"],chipsetInfo["overflows"],chipsetInfo["NumTerminals"], chipsetInfo["NumNodes"]-chipsetInfo["overlaps"]-chipsetInfo["overflows"]-chipsetInfo["NumTerminals"]]
								}
							]
						},
						options: {
							responsive: false,
							animation: {
								animateScale:true
							}
						}
					});
				}
			}
			if(document.getElementById("minmaxtemp") === null){
				if(statistics[0].length > 0 && statistics[1].length > 0){
					$("#modalContent").append("<br><canvas id='minmaxtemp' width='1000px' height='300px'></canvas>");
					var ctx = document.getElementById("minmaxtemp");
					var myLineChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: statistics[0],
							datasets: [
								{
									label: "Temperature",
									backgroundColor: "rgba(0,0,0,0)",
									borderColor: "rgba(255,0,0,0.1)",
									data: statistics[1]
								}
							]
						},
						options: {
							responsive: false
						}
					});
				}
			}
			if(document.getElementById("minmaxarea") === null){
				if(statistics[2].length > 0 && statistics[3].length > 0){
					$("#modalContent").append("<br><canvas id='minmaxarea' width='1000px' height='300px'></canvas>");
					var ctx = document.getElementById("minmaxarea");
					var myBarChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: statistics[2],
							datasets: [
								{
									label: "Area",
									backgroundColor: "rgba(75,192,192,1)",
									data: statistics[3]
								}
							]
						},
						options: {
							responsive: false
						}
					});
				}
			}
			if(chipsetInfo["visualise_time"]>0 || chipsetInfo["overlap_time"]>0 || chipsetInfo["overflow_time"]>0 || chipsetInfo["thermal_time"]>0 || chipsetInfo["congestion_time"]>0 || chipsetInfo["hperimeter_time"]>0){
				$("#modalContent").append("<br><canvas id='bartimes' width='1000px' height='300px'></canvas>");
				Chart.defaults.global.legend.labels.usePointStyle = true;
				var ctx = document.getElementById("bartimes");
				var myBarChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: ["Visualise","Overlap","Overflow","Thermal Map","Congestion Map","Half-Perimeter"],
						datasets: [
							{
								label: "Time (ms)",
								backgroundColor: "rgba(75,192,192,1)",
								data: [chipsetInfo["visualise_time"],chipsetInfo["overlap_time"],chipsetInfo["overflow_time"],chipsetInfo["thermal_time"],chipsetInfo["congestion_time"],chipsetInfo["hperimeter_time"]]
							}
						]
					},
					options: {
						responsive: false
					}
				});
			}
			var span = document.getElementsByClassName("close")[0];
			$("#statsModal").css("display", "block");
			span.onclick = function() {
				$("#statsModal").css("display", "none");
			}
			break;
		case 3:
			var t0 = performance.now();
			if ($("#displayOverlapBtn").hasClass("toggle-button-selected2")){
				if (overlaparr[0] != undefined) {
					for (var i=0; i<overlaparr.length; i++) {
						$("#"+overlaparr[i]).css("border", "1px solid #ff0000");
					}
					var spanOverlap = document.getElementById("spanOverlap");
					spanOverlap.innerHTML = "Hide Overlap";
				} else {
					$.ajax({
						type: "POST",
						url: "./parsers/parseNODES2.php",
						dataType: "json",
						success: function(dataNODES) {
							$.ajax({
								type: "POST",
								url: "./parsers/parsePL.php",
								dataType: "json",
								success: function(dataPL) {
									var pos, posBefore, total=0;
									for (var k=dataNODES["NumNodes"]-1; k>=0; k--) {
										try {
											pos = k;
											posBefore = dataPL[k-1]["node_name"];
											
											if (((parseFloat(dataPL[pos-1]["ll_Xcoord"]) + parseFloat(dataNODES[posBefore]["width"])) > parseFloat(dataPL[pos]["ll_Xcoord"])) && (parseFloat(dataPL[pos-1]["ll_Ycoord"]) == parseFloat(dataPL[pos]["ll_Ycoord"])) && (typeof dataPL[pos]["movetype"] == "undefined")) {
												$("#"+dataPL[pos]["node_name"]).css("border", "1px solid #ff0000");
												overlaparr.push(dataPL[pos]["node_name"]);
												total++;
											} else if ((parseFloat(dataPL[pos]["ll_Xcoord"])+parseFloat(dataNODES[dataPL[k]["node_name"]]["width"])) > parseFloat(dataPL[pos+1]["ll_Xcoord"]) && (parseFloat(dataPL[pos+1]["ll_Ycoord"]) == parseFloat(dataPL[pos]["ll_Ycoord"])) && (typeof dataPL[pos]["movetype"] == "undefined")) {
												$("#"+dataPL[pos]["node_name"]).css("border", "1px solid #ff0000");
												overlaparr.push(dataPL[pos]["node_name"]);
												total++;
											}
										} catch(err) {console.log(err);}
									}
									chipsetInfo["overlaps"] = total;
									var executedtimedata = document.getElementById("executedTimeData2");
									var time = (Math.floor(performance.now()-t0));
									chipsetInfo["overlap_time"] = time;
									executedtimedata.innerHTML = "<br>" + total + " Overlapped nodes.<br>Runtime: " + time + " ms.";
								},
								error: function(req, status, error) {
									window.alert(req+"\n"+status+"\n"+error);
								}
							});
						}
					});
					var spanOverlap = document.getElementById("spanOverlap");
					spanOverlap.innerHTML = "Hide Overlap";
				}
			} else {
				for (var i=0; i<overlaparr.length; i++) {
					$("#"+overlaparr[i]).css("border", "1px solid #c0c0c0");
				}
				var spanOverlap = document.getElementById("spanOverlap");
				spanOverlap.innerHTML = "Show Overlap";
			}
			break;
		case 4:
			var t0 = performance.now();
			var cellNet = document.getElementById("NetInput").value;
			var flag = 0;
			var count = 0;
			$.ajax({
				type: "POST",
				url: "./parsers/parseNETS.php",
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
								$("#"+dataNETS[i]["node_name"+j]).css("border", "2px solid "+rndCLR);
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
				data: {clearVisualizer: true},
				success: function() {
					window.location.reload();
				}
			});
			break;
		case 6:
			var cellNet = document.getElementById("NetClearInput").value;
			var flag = 0;
			$.ajax({
				type: "POST",
				url: "./parsers/parseNETS.php",
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
									$("#"+dataNETS[i]["node_name"+j]).css("border", "1px solid #c0c0c0");
									$("#"+dataNETS[i]["node_name"+j]).css("z-index", "0");
								}
							}
						}
					}else{
						for (var i=0; i<netarr.length; i++) {
							$("#"+netarr[i]).css("border", "1px solid #c0c0c0");
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
		case 8:
			if (!$("#displayRowsBtn").hasClass("toggle-button-selected")){
				$(".hoverRow").css("border", "none");
				var spanRows = document.getElementById("spanRows");
				spanRows.innerHTML = "Show Rows";
			} else {
				$(".hoverRow").css("border", "1px solid #b0b0b0");
				var spanRows = document.getElementById("spanRows");
				spanRows.innerHTML = "Hide Rows";
			}
			break;
		case 9:
			var t0 = performance.now();
			if ($("#displayOverflowBtn").hasClass("toggle-button-selected3")){
				if (overflowarr[0] != undefined) {
					for (var i=0; i<overflowarr.length; i++) {
						$("#"+overflowarr[i]).css("border", "1px solid #ff0000");
					}
					var spanOverflow = document.getElementById("spanOverflow");
					spanOverflow.innerHTML = "Hide Overflow";
				} else {
					$.ajax({
						type: "POST",
						url: "./parsers/parseNODES2.php",
						dataType: "json",
						success: function(dataNODES) {
							$.ajax({
								type: "POST",
								url: "./parsers/parsePL.php",
								dataType: "json",
								success: function(dataPL) {
									var total=0;
									for(var i=0; i<dataNODES["NumNodes"]; i++){
										if((parseFloat(dataPL[i]["ll_Xcoord"])+parseFloat(dataNODES[dataPL[i]["node_name"]]["width"])>parseFloat(chipsetInfo["rowWidth"]) || parseFloat(dataPL[i]["ll_Xcoord"])<0 || parseFloat(dataPL[i]["ll_Ycoord"])+parseFloat(dataNODES[dataPL[i]["node_name"]]["height"])>parseFloat(chipsetInfo["rowHeight"])*parseFloat(chipsetInfo["NumRows"]) || parseFloat(dataPL[i]["ll_Ycoord"])<0) && typeof dataNODES[dataPL[i]["node_name"]]["movetype"]=="undefined"){
											$("#"+dataPL[i]["node_name"]).css("border", "1px solid #ff0000");
											overflowarr.push(dataPL[i]["node_name"]);
											total++;
										}
									}
									chipsetInfo["overflows"] = total;
									var executedtimedata = document.getElementById("executedTimeData4");
									var time = (Math.floor(performance.now()-t0));
									chipsetInfo["overflow_time"] = time;
									executedtimedata.innerHTML = "<br>" + total + " Overflowed nodes.<br>Runtime: " + time + " ms.";
								}
							});
						}
					});
					var spanOverflow = document.getElementById("spanOverflow");
					spanOverflow.innerHTML = "Hide Overflow";
				}
			} else {
				for (var i=0; i<overflowarr.length; i++) {
					$("#"+overflowarr[i]).css("border", "1px solid #c0c0c0");
				}
				var spanOverflow = document.getElementById("spanOverflow");
				spanOverflow.innerHTML = "Show Overflow";
			}
			break;
		case 10:
			var t0 = performance.now();
			$.ajax({
				type: "POST",
				url: "./parsers/parseWTS.php",
				dataType: "json",
				success: function(dataWTS) {
					if ($("#displayHeatmapBtn").hasClass("toggle-button-selected4")){
						chipsetInfo["min_temp"] = parseInt(dataWTS["min"]);
						chipsetInfo["max_temp"] = parseInt(dataWTS["max"]);
						for(var i=0; i<chipsetInfo["NumNodes"]; i++){
							statistics[0].push(dataWTS[i]["node_name"]);
							statistics[1].push(dataWTS[i]["value"]);
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
						var executedtimedata = document.getElementById("executedTimeData5");
						var time = (Math.floor(performance.now()-t0));
						chipsetInfo["thermal_time"] = time;
						executedtimedata.innerHTML = "<br>Runtime: " + time + " ms.";
						var spanHeatmap = document.getElementById("spanHeatmap");
						spanHeatmap.innerHTML = "Hide Thermal Map";
					} else {
						for (var i=0; i<chipsetInfo["NumNodes"]; i++) {
							$("#"+dataWTS[i]["node_name"]).css("border-color", "#c0c0c0");
							$("#"+dataWTS[i]["node_name"]).css("background-color", "#9a9a9a");
						}
						var spanHeatmap = document.getElementById("spanHeatmap");
						spanHeatmap.innerHTML = "Show Thermal Map";
					}
				}
			});
			break;
		case 11:
			var t0 = performance.now();
			$.ajax({
				type: "POST",
				url: "./parsers/parsePL.php",
				dataType: "json",
				success: function(dataPL) {
					$.ajax({
						type: "POST",
						url: "./parsers/parseNODES2.php",
						dataType: "json",
						success: function(dataNODES) {
							var BoxCount = document.getElementById("BoxInput").value;
							var CongWidth = chipsetInfo["rowWidth"]/BoxCount;
							var CongHeight = chipsetInfo["rowHeight"]*chipsetInfo["NumRows"]/BoxCount;
							var areaC = [];
							for(var i=0;i<BoxCount;i++){
								for(var j=0;j<BoxCount;j++){
									var div = document.createElement("DIV");
									div.setAttribute("id", "Congi"+i+"j"+j);
									div.setAttribute("class", "CongClass");
									div.setAttribute("style", "position: absolute; width:"+chipsetInfo["generateWidth"]*CongWidth+"px; height:"+chipsetInfo["generateHeight"]*CongHeight+"px; left:"+(j*CongWidth*chipsetInfo["generateWidth"]+chipsetInfo["abscoord"])+"px; top:"+(i*CongHeight*chipsetInfo["generateHeight"]+chipsetInfo["abscoord"])+"px");
									$("#fixedDIV").append(div);
									areaC["Congi"+i+"j"+j] = 0;
								}
							}
							for(var k=0;k<dataNODES["NumNodes"];k++){
								var boxnumY = parseInt(Math.floor(dataPL[k]["ll_Xcoord"]/CongWidth)); //an vgalei 3.2 tote sto 3o kuti
								var boxnumX = BoxCount-1-parseInt(Math.floor(dataPL[k]["ll_Ycoord"]/CongHeight));
								if(dataNODES[dataPL[k]["node_name"]]["movetype"]!="terminal"){
									areaC["Congi"+boxnumX+"j"+boxnumY] += parseFloat(dataNODES[dataPL[k]["node_name"]]["width"])*parseFloat(dataNODES[dataPL[k]["node_name"]]["height"]);
								}
							}
							var maxC = minC = parseFloat(areaC["Congi0j0"]);
							for(var i=0;i<BoxCount;i++){
								for(var j=0;j<BoxCount;j++){
									if(parseFloat(areaC["Congi"+i+"j"+j]) > parseFloat(maxC)){
										maxC = parseFloat(areaC["Congi"+i+"j"+j]);
									}
									if(parseFloat(areaC["Congi"+i+"j"+j]) < parseFloat(minC)){
										minC = parseFloat(areaC["Congi"+i+"j"+j]);
									}
									statistics[2].push("Box"+i+j);
									statistics[3].push(areaC["Congi"+i+"j"+j]);
								}
							}
							chipsetInfo["min_area"] = minC;
							chipsetInfo["max_area"] = maxC;
							for(var i=0;i<BoxCount;i++){
								for(var j=0;j<BoxCount;j++){
									areaC["Congi"+i+"j"+j] = parseFloat((parseFloat(areaC["Congi"+i+"j"+j])/parseFloat(maxC)).toFixed(2));
									if(parseFloat(areaC["Congi"+i+"j"+j])>=0.5){
										var h = 240 - (1.0 - parseFloat(areaC["Congi"+i+"j"+j])) * 60;
										$("#Congi"+i+"j"+j).css("border-color", "hsla(" + (Math.floor(h)) + ", 100%, 50%,0.7)");
										$("#Congi"+i+"j"+j).css("background-color", "hsla(" + Math.floor(h) + ", 100%, 50%,0.7)");
									}else{
										var l = (1.0-parseFloat(areaC["Congi"+i+"j"+j]))*100;
										$("#Congi"+i+"j"+j).css("border-color", "hsla(180, 100%, " + Math.floor(l) + "%,0.7)");
										$("#Congi"+i+"j"+j).css("background-color", "hsla(180, 100%, " + Math.floor(l) + "%,0.7)");
									}
								}
							}
							var executedtimedata = document.getElementById("executedTimeData6");
							var time = (Math.floor(performance.now()-t0));
							chipsetInfo["congestion_time"] = time;
							executedtimedata.innerHTML = "<br>Runtime: " + time + " ms.";
						}
					});
				}
			});
/*0  : white  (hsl(60, 100%, 100%))
0.5 : yellow (hsl(60, 100%, 50%))
1    : red    (hsl(0, 100%, 50%))*/
			break;
		case 12:
			var t0 = performance.now();
			$.ajax({
				type: "POST",
				url: "./parsers/parseNETS.php",
				dataType: "json",
				success: function(dataNETS) {
					$.ajax({
						type: "POST",
						url: "./parsers/parseNODES2.php",
						dataType: "json",
						success: function(dataNODES) {
							$.ajax({
								type: "POST",
								url: "./parsers/parsePL2.php",
								dataType: "json",
								success: function(dataPL) {
									chipsetInfo["NumNets"] = dataNETS["NumNets"];
									chipsetInfo["NumPins"] = dataNETS["NumPins"];
									var sum=0, minX=9999,maxX=-9999,minY=9999,maxY=-9999;
									for(var i=0;i<dataNETS["NumNets"];i++){
										minX=9999,maxX=-9999,minY=9999,maxY=-9999;
										for(var j=0;j<dataNETS[i]["NetDegree"];j++){
											if(parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Xcoord"])<minX){
												minX=parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Xcoord"]);
											}
											if(parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Ycoord"])<minY){
												minY=parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Ycoord"]);
											}
											if(parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Xcoord"])+parseFloat(dataNODES[dataNETS[i]["node_name"+j]]["width"])>maxX){
												maxX=parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Xcoord"])+parseFloat(dataNODES[dataNETS[i]["node_name"+j]]["width"]);
											}
											if(parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Ycoord"])+parseFloat(dataNODES[dataNETS[i]["node_name"+j]]["height"])>maxY){
												maxY=parseFloat(dataPL[dataNETS[i]["node_name"+j]]["ll_Ycoord"])+parseFloat(dataNODES[dataNETS[i]["node_name"+j]]["height"]);
											}
										}
										sum+=(maxX-minX)+(maxY-minY);
									}
									chipsetInfo["Half_Perimeter"] = sum;
									var executedtimedata = document.getElementById("executedTimeData7");
									var time = (Math.floor(performance.now()-t0));
									chipsetInfo["hperimeter_time"] = time;
									executedtimedata.innerHTML = "<br>Half-Perimeter is "+sum+".<br>Runtime: " + time + " ms.";
								}
							});
						}
					});
				}
			});
			break;
		case 13:
			$(".CongClass").remove();
			break;
		case 14:
		    html2canvas($(".divLeft"), {
				onrendered: function (canvas) {
					var linkd = document.createElement("a");
					linkd.id = "pngImage";
					linkd.href = canvas.toDataURL();
					linkd.download = "WEVIAN.png";
					$(".divRight").append(linkd);
					linkd.click();
					$("#pngImage").remove();
				},
				background: "#fbfbfb"
			});
			break;
		default:
			alert("Don't be a bad boy!");
			return;
	}
}