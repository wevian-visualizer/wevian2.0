<?PHP
	session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"]) && isset($_SESSION["shareFilename"]) && !empty($_SESSION["shareFilename"])){
		if($_SESSION["shareType"] == "ZIP"){
			$file = fopen("../upload/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/".substr($_SESSION["shareFilename"],0,-4).".pl", "r");
		}else
			$file = fopen("../upload/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/".$_SESSION["shareFilename"], "r");
	}elseif(!isset($_POST["fid"]) && !isset($_POST["fn"])){
		if($_SESSION["dirr"] == "ZIP"){
			$file = fopen("../upload/".$_SESSION["uid"]."/".$_SESSION["fid"]."/".substr($_SESSION["filename"],0,-4).".pl", "r");
		}else
			$file = fopen("../upload/".$_SESSION["uid"]."/".$_SESSION["fid"]."/".$_SESSION["filename"], "r");
	}else
		$file = fopen("../upload/".$_SESSION["uid"]."/".$_POST["fid"]."/".$_POST["fn"], "r");
	$array = array();
	while (($buffer = fgets($file)) !== false) {
		if (preg_match('/^[\s]*[a-zA-Z][a-zA-Z0-9]+[\s]+[\-0-9\.]+[\s]+[\-0-9\.]+[\s]*[:]*[\s]*[a-zA-Z]*/',$buffer)) {
			$expBuff = preg_split('/\s+/', $buffer);
			if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
				$array[$expBuff[0]]["ll_Xcoord"] = $expBuff[1];
				$array[$expBuff[0]]["ll_Ycoord"] = $expBuff[2];
				$array[$expBuff[0]]["orientation"] = $expBuff[4];
				if($expBuff[5] != ""){
					$array[$expBuff[0]]["movetype"] = $expBuff[5];
				}
			}else{
				$array[$expBuff[1]]["ll_Xcoord"] = $expBuff[2];
				$array[$expBuff[1]]["ll_Ycoord"] = $expBuff[3];
				$array[$expBuff[1]]["orientation"] = $expBuff[5];
				if($expBuff[6] != ""){
					$array[$expBuff[1]]["movetype"] = $expBuff[6];
				}
			}
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