<?PHP
	session_start();
	if(isset($_SESSION["shareType"]) && !empty($_SESSION["shareType"])){
		if($_SESSION["shareType"] == "ZIP"){
			$file = fopen("../upload/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/".substr($_SESSION["shareFilename"],0,-4).".nets", "r");
		}else
			$file = fopen("../benchmarks/".$_SESSION["shareType"]."/".$_SESSION["shareType"].".nets", "r");
	}elseif(!isset($_POST["type"])){
		if($_SESSION["dirr"] == "ZIP"){
			$file = fopen("../upload/".$_SESSION["uid"]."/".$_SESSION["fid"]."/".substr($_SESSION["filename"],0,-4).".nets", "r");
		}else
			$file = fopen("../benchmarks/".$_SESSION["dirr"]."/".$_SESSION["dirr"].".nets", "r");
	}else
		$file = fopen("../benchmarks/".$_POST["type"]."/".$_POST["type"].".nets", "r");
	while (($buffer = fgets($file)) !== false) {
		if (strpos($buffer, "NumNets") !== false) {
			$NumNets = explode(':', $buffer);
			$array["NumNets"] = intval($NumNets[1]);
			continue;
		}
		if(strpos($buffer, "NumPins") !== false){
			$NumPins = explode(':', $buffer);
			$array["NumPins"] = intval($NumPins[1]);
			break;
		}
	}
	while (($buffer = fgets($file)) !== false) {
		if(strpos($buffer, "NetDegree") !== false){
			$NetDegree = explode(':', $buffer);
			$array1["NetDegree"] = intval($NetDegree[1]);
		}
		if(isset($array1["NetDegree"])){
			for($i=0;$i<$array1["NetDegree"];$i++){
				if(($buffer = fgets($file)) !== false){
					$expBuff = preg_split('/\s+/', $buffer);
					$array1["node_name".$i] = $expBuff[1];
					$array1["pin_direction".$i] = $expBuff[2];
					if(isset($expBuff[4])){
							$array1["Xoffset".$i] = $expBuff[4];
					}
					if(isset($expBuff[5])){
							$array1["Yoffset".$i] = $expBuff[5];
					}
					
				}
			}
			array_push($array, $array1);
			unset($array1);
		}
	}
	//echo "<pre>".json_encode($array, JSON_PRETTY_PRINT)."</pre>";
	fclose($file);
	echo json_encode($array);
?>