<?PHP
	session_start();
	if(isset($_SESSION["shareType"]) && !empty($_SESSION["shareType"])){
		if($_SESSION["shareType"] == "ZIP"){
			$file = fopen("../upload/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/".substr($_SESSION["shareFilename"],0,-4).".nodes", "r");
		}else
			$file = fopen("../benchmarks/".$_SESSION["shareType"]."/".$_SESSION["shareType"].".nodes", "r");
	}elseif(!isset($_POST["type"])){
		if($_SESSION["dirr"] == "ZIP"){
			$file = fopen("../upload/".$_SESSION["uid"]."/".$_SESSION["fid"]."/".substr($_SESSION["filename"],0,-4).".nodes", "r");
		}else
			$file = fopen("../benchmarks/".$_SESSION["dirr"]."/".$_SESSION["dirr"].".nodes", "r");
	}else
		$file = fopen("../benchmarks/".$_POST["type"]."/".$_POST["type"].".nodes", "r");
	while (($buffer = fgets($file)) !== false) {
		if (strpos(strtolower($buffer), strtolower("NumNodes")) !== false) {
			$NumNodes = explode(':', $buffer);
			$array["NumNodes"] = intval($NumNodes[1]);
			continue;
		}
		if(strpos(strtolower($buffer), strtolower("NumTerminals")) !== false){
			$NumTerminals = explode(':', $buffer);
			$array["NumTerminals"] = intval($NumTerminals[1]);
			break;
		}
	}
	while (($buffer = fgets($file)) !== false) {
		$expBuff = preg_split('/\s+/', $buffer);
		$array[$expBuff[1]]["width"] = $expBuff[2];
		$array[$expBuff[1]]["height"] = $expBuff[3];
		if($expBuff[4] != ""){
			$array[$expBuff[1]]["movetype"] = $expBuff[4];
		}
	}
	//echo "<pre>".json_encode($array, JSON_PRETTY_PRINT)."</pre>";
	fclose($file);
	echo json_encode($array);
?>