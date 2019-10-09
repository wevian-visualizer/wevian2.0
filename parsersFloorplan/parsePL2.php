<?PHP
	session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"]))
		$file = fopen(glob("../floorplan_saves/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/*.pl")[0], "r");
	else
		$file = fopen(glob("../floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/*.pl")[0], "r");
	$array = array();
	$max_ll_Ycoord = 0;
	while (($buffer = fgets($file)) !== false) {
		if (preg_match('/^[\s]*[a-zA-Z0-9\@\_]+[\s]+[0-9\.\-]+[\s]+[0-9\.\-]+/',$buffer)) {
			$expBuff = preg_split('/\s+/', $buffer);
			if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
				$array[$expBuff[0]]["ll_Xcoord"] = $expBuff[1];
				$array[$expBuff[0]]["ll_Ycoord"] = $expBuff[2];
				if($expBuff[2]>$max_ll_Ycoord){
					$max_ll_Ycoord = $expBuff[2];
				}
			}else{
				$array[$expBuff[1]]["ll_Xcoord"] = $expBuff[2];
				$array[$expBuff[1]]["ll_Ycoord"] = $expBuff[3];
				if($expBuff[3]>$max_ll_Ycoord){
					$max_ll_Ycoord = $expBuff[3];
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
	$array["maxHeight"] = $max_ll_Ycoord;
	//echo "<pre>".json_encode($array, JSON_PRETTY_PRINT)."</pre>";
	fclose($file);
	echo json_encode($array);
?>