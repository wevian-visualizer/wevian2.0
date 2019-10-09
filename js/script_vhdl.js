config = {
    container: ".divLeft"
};
nodes = [];
json = [
    config
];

function deleteFile(fid){
	if(confirm("This cannot be undone, are you sure?"))
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {fid:fid, deleteFileVHDL:true},
			success: function() {
				$("#fid"+fid).slideUp(500);
				$("#fid2_"+fid).slideUp(500);
			}
		});
}

function loadFile(fid){
	var name = document.getElementById("infoname"+fid).innerHTML;
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {name:name, fid:fid, loadFileVHDL:true},
		success: function() {
			window.location.reload();
		}
	});
}

function updateVHDLName(fid){
	var name = $("#infoname"+fid).val();
	if(name.length >= 3 && name.length<=50)
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {name:name, fid:fid, updateVHDLName:true},
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

$("#viz").click(function(){
	var topLvlVal = $("#selectmain").val();
	var LibVal = $("#selectlib1").val();
	$.ajax({
		type: "POST",
		url: "./vhdlparser/parseVHDL.php",
		data : { data1 : topLvlVal,
				 data2 : LibVal },
		dataType: "json",
		success: function(data) {
			for(var i=0;i<Object.keys(data).length;i++){
				var id = String(100+i);
				if(i==0){
					nodes[id] = {
									text: { name: data[id]["name"] }
								};
				}else{
					nodes[id] = {
									parent: nodes[String(data[id]["pid"])],
									text: { name: data[id]["name"] }
								};
				}
				json.push(nodes[id]);
			}
			new Treant( json );

			$.ajax({
				type: "POST",
				url: "./vhdlparser/getGenFiles.php",
				data : { data1 : "ok" },
				success: function(data1) {
					$('#downloadFiles').html("<a href='"+data1+"' id='dbtn' download>Download files</a><br><br>");
				},
				error: function(){alert("fail2");}
			});
		},
		error: function() {alert("fail");}
	});
});