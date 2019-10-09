<?PHP
	session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"])){
		$uid = $_SESSION["shareUserID"];
		$fid = $_SESSION["shareFileID"];
	}else{
		$uid = $_SESSION["uid"];
		$fid = $_SESSION["fid"];
	}
		
	if(isset(glob("../floorplan_saves/".$uid."/".$fid."/*.wts")[0])) {
		$file = fopen(glob("../floorplan_saves/".$uid."/".$fid."/*.wts")[0], "r");
		$array = array();
		$max = -1;
		$min = 9999;
		while (($buffer = fgets($file)) !== false) {
			if (preg_match('/^[\s]*[a-zA-Z][a-zA-Z0-9\_]+[\s]+[0-9\.\-]+/',$buffer)) {
				$expBuff = preg_split('/\s+/', $buffer);
				$array1["node_name"] = $expBuff[0];
				$array1["value"] = $expBuff[1];
				if($array1["value"]>$max){
					$max = floatval($array1["value"]);
				}
				if($array1["value"]<$min){
					$min = floatval($array1["value"]);
				}
				array_push($array, $array1);
			}
		}
		for($i=0;$i<sizeof($array);$i++){
			if(floatval($array[$i]["value"]) != 0)
				$array[$i]["value"] = number_format(floatval($array[$i]["value"])/floatval($max), 100, '.', '');
			else
				$array[$i]["value"] = number_format(0, 100, '.', '');
		}
		$array["min"] = floatval($min);
		$array["max"] = floatval($max);
		//echo "<pre>".json_encode($array, JSON_PRETTY_PRINT)."</pre>";
		fclose($file);
		echo json_encode($array);
	} else {
		echo json_encode("no_wts_file_found");
	}
?>