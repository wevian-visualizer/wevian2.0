<?PHP
	session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"]))
		$file = fopen(glob("../floorplan_saves/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/*.pl")[0], "r");
	else
		$file = fopen(glob("../floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/*.pl")[0], "r");
	$array = array();
	while (($buffer = fgets($file)) !== false) {
		if (preg_match('/^[\s]*[a-zA-Z][a-zA-Z0-9\@\_]+[\s]+[0-9\.\-]+[\s]+[0-9\.\-]+/',$buffer)) {
			$expBuff = preg_split('/\s+/', $buffer);
			if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
				$array1["node_name"] = $expBuff[0];
				$array1["ll_Xcoord"] = $expBuff[1];
				$array1["ll_Ycoord"] = $expBuff[2];
			}else{
				$array1["node_name"] = $expBuff[1];
				$array1["ll_Xcoord"] = $expBuff[2];
				$array1["ll_Ycoord"] = $expBuff[3];
			}
			array_push($array, $array1);
		}
	}
	$temp = $temp1 = array();
	foreach ($array as $key => $row) {
		$temp[$key] = $row['ll_Ycoord'];
		$temp1[$key] = $row['ll_Xcoord'];
	}
	array_multisort($temp, SORT_DESC, $temp1, SORT_ASC, $array);
	//echo "<pre>".json_encode($array, JSON_PRETTY_PRINT)."</pre>";
	fclose($file);
	echo json_encode($array);
?>