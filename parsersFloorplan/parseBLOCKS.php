<?PHP
	session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"]))
		$file = fopen(glob("../floorplan_saves/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/*.blocks")[0], "r");
	else
		$file = fopen(glob("../floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/*.blocks")[0], "r");
	while (($buffer = fgets($file)) !== false) {
		if (strpos($buffer, "NumSoftRectangularBlocks") !== false) {
			$NumSoftRectangularBlocks = explode(':', $buffer);
			$array["NumSoftRectangularBlocks"] = intval($NumSoftRectangularBlocks[1]);
		}
		if (strpos($buffer, "NumHardRectilinearBlocks") !== false) {
			$NumHardRectilinearBlocks = explode(':', $buffer);
			$array["NumHardRectilinearBlocks"] = intval($NumHardRectilinearBlocks[1]);
		}
		if (strpos($buffer, "NumTerminals") !== false) {
			$NumTerminals = explode(':', $buffer);
			$array["NumTerminals"] = intval($NumTerminals[1]);
			break;
		}
	}
	while (($buffer = fgets($file)) !== false) {
		if (preg_match('/^[\s]*[a-zA-Z0-9\@\_]+[\s]+[a-zA-Z]+[\s]+[0-9\.]+[\s]+[\(]+/',$buffer)) {
			$expBuff1 = preg_split('/\s+/', $buffer);
			if(preg_match('/[a-zA-Z0-9]+/',$expBuff1[0])){
				$array1["node_name"] = $expBuff1[0];
				$array1["type"] = $expBuff1[1];
				$array1["vertex_number"] = $expBuff1[2];
				for($i=0;$i<$array1["vertex_number"]*2;$i += 2){
					$array1["vertex".$i] = $expBuff1[3+$i].$expBuff1[3+$i+1];
				}
			}else{
				$array1["node_name"] = $expBuff1[1];
				$array1["type"] = $expBuff1[2];
				$array1["vertex_number"] = $expBuff1[3];
				for($i=0;$i<$array1["vertex_number"]*2;$i += 2){
					$array1["vertex".$i] = $expBuff1[4+$i].$expBuff1[4+$i+1];
				}
			}
			array_push($array, $array1);
			unset($array1);
			continue;
		}
		if (preg_match('/^[\s]*[a-zA-Z0-9\@\_]+[\s]+[a-zA-Z]+[\s]+[0-9\.]+[\s]+[0-9\.]+[\s]+[0-9\.]+/',$buffer)) {
			$expBuff = preg_split('/\s+/', $buffer);
			if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
				$array1["node_name"] = $expBuff[0];
				$array1["type"] = $expBuff[1];
				$array1["area"] = $expBuff[2];
				$array1["min_aspect_ratio"] = $expBuff[3];
				$array1["max_aspect_ratio"] = $expBuff[4];
			}else{
				$array1["node_name"] = $expBuff[1];
				$array1["type"] = $expBuff[2];
				$array1["area"] = $expBuff[3];
				$array1["min_aspect_ratio"] = $expBuff[4];
				$array1["max_aspect_ratio"] = $expBuff[5];
			}
			array_push($array, $array1);
			unset($array1);
			continue;
		}
		if (preg_match('/^[\s]*[a-zA-Z0-9\@\_]+[\s]+[a-zA-Z]+/',$buffer)) {
			$expBuff = preg_split('/\s+/', $buffer);
			if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
				$array1["node_name"] = $expBuff[0];
				$array1["type"] = $expBuff[1];
			}else{
				$array1["node_name"] = $expBuff[1];
				$array1["type"] = $expBuff[2];
			}
			array_push($array, $array1);
			unset($array1);
		}
	}
	//echo "<pre>".json_encode($array, JSON_PRETTY_PRINT)."</pre>";
	fclose($file);
	echo json_encode($array);
?>