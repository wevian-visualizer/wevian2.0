<?PHP
	$type = htmlspecialchars($_POST["type"]);
	$fid1 = htmlspecialchars($_POST["fid1"]);
	$fid2 = htmlspecialchars($_POST["fid2"]);
	$fn1 = htmlspecialchars($_POST["fn1"]);
	$fn2 = htmlspecialchars($_POST["fn2"]);
	if(isset($type) && !empty($type) && isset($fid1) && !empty($fid1) && isset($fid2) && !empty($fid2) && isset($fn1) && !empty($fn1) && isset($fn2) && !empty($fn2)){
		$file = fopen("../benchmarks/".$type."/".$type.".nodes", "r");
		$dataNODES = array();
		while (($buffer = fgets($file)) !== false) {
			if (strpos($buffer, "NumNodes") !== false) {
				$NumNodes = explode(':', $buffer);
				$array["NumNodes"] = intval($NumNodes[1]);
				continue;
			}
			if(strpos($buffer, "NumTerminals") !== false){
				$NumTerminals = explode(':', $buffer);
				$array["NumTerminals"] = intval($NumTerminals[1]);
				break;
			}
		}
		while (($buffer = fgets($file)) !== false) {
			$expBuff = preg_split('/\s+/', $buffer);
			$dataNODES[$expBuff[1]]["width"] = $expBuff[2];
			$dataNODES[$expBuff[1]]["height"] = $expBuff[3];
		}
		fclose($file);
		$file = fopen("../benchmarks/".$type."/".$type.".scl", "r");
		while (($buffer = fgets($file)) !== false) {
			if(strpos($buffer, "NumRows") !== false){
				$NumRows = explode(':', $buffer);
				$array["NumRows"] = intval($NumRows[1]);
				break;
			}
		}
		while (($buffer = fgets($file)) !== false) {
			if (strpos($buffer, "Height") !== false) {
				$rowHeight = explode(':', $buffer);
				$array["rowHeight"] = trim($rowHeight[1]);
				break;
			}
		}
		while (($buffer = fgets($file)) !== false) {
			if (strpos($buffer, "SubrowOrigin") !== false) {
				$Numsites = explode(':', $buffer);
				$array["Numsites"] = trim($Numsites[2]);
				break;
			}
		}
		fclose($file);

		$file = fopen("../benchmarks/".$type."/".$type.".nets", "r");
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
				for($j=0;$j<$array1["NetDegree"];$j++){
					if(($buffer = fgets($file)) !== false){
						$expBuff = preg_split('/\s+/', $buffer);
						$array1["node_name".$j] = $expBuff[1];
					}
				}
				array_push($array, $array1);
				unset($array1);
			}
		}
		fclose($file);
		
		session_start();
		$file = fopen("../upload/".$_SESSION["uid"]."/".$fid1."/".$fn1, "r");
		$dataPL1INDEXED = array();
		while (($buffer = fgets($file)) !== false) {
			if (preg_match('/^[\s]*[a-zA-Z][a-zA-Z0-9]+[\s]+[\-0-9\.]+[\s]+[\-0-9\.]+[\s]*[:]*[\s]*[a-zA-Z]*/',$buffer)) {
				$expBuff = preg_split('/\s+/', $buffer);
				if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
					$dataPL1INDEXED[$expBuff[0]]["ll_Xcoord"] = $expBuff[1];
					$dataPL1INDEXED[$expBuff[0]]["ll_Ycoord"] = $expBuff[2];
				}else{
					$dataPL1INDEXED[$expBuff[1]]["ll_Xcoord"] = $expBuff[2];
					$dataPL1INDEXED[$expBuff[1]]["ll_Ycoord"] = $expBuff[3];
				}
			}
		}
		$temp = $temp1 = array();
		foreach ($dataPL1INDEXED as $key => $row) {
			$temp[$key] = $row['ll_Ycoord'];
			$temp1[$key] = $row['ll_Xcoord'];
		}
		array_multisort($temp, SORT_DESC, $temp1, SORT_ASC, $dataPL1INDEXED);
		fclose($file);
		
		$file = fopen("../upload/".$_SESSION["uid"]."/".$fid2."/".$fn2, "r");
		$dataPL2INDEXED = array();
		while (($buffer = fgets($file)) !== false) {
			if (preg_match('/^[\s]*[a-zA-Z][a-zA-Z0-9]+[\s]+[\-0-9\.]+[\s]+[\-0-9\.]+[\s]*[:]*[\s]*[a-zA-Z]*/',$buffer)) {
				$expBuff = preg_split('/\s+/', $buffer);
				if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
					$dataPL2INDEXED[$expBuff[0]]["ll_Xcoord"] = $expBuff[1];
					$dataPL2INDEXED[$expBuff[0]]["ll_Ycoord"] = $expBuff[2];
				}else{
					$dataPL2INDEXED[$expBuff[1]]["ll_Xcoord"] = $expBuff[2];
					$dataPL2INDEXED[$expBuff[1]]["ll_Ycoord"] = $expBuff[3];
				}
			}
		}
		$temp = $temp1 = array();
		foreach ($dataPL2INDEXED as $key => $row) {
			$temp[$key] = $row['ll_Ycoord'];
			$temp1[$key] = $row['ll_Xcoord'];
		}
		array_multisort($temp, SORT_DESC, $temp1, SORT_ASC, $dataPL2INDEXED);
		fclose($file);
		
		$file = fopen("../upload/".$_SESSION["uid"]."/".$fid1."/".$fn1, "r");
		$dataPL1NOTINDEXED = array();
		while (($buffer = fgets($file)) !== false) {
			if (preg_match('/^[\s]*[a-zA-Z][a-zA-Z0-9]+[\s]+[\-0-9\.]+[\s]+[\-0-9\.]+[\s]*[:]*[\s]*[a-zA-Z]*/',$buffer)) {
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
				array_push($dataPL1NOTINDEXED, $array1);
			}
		}
		$temp = $temp1 = array();
		foreach ($dataPL1NOTINDEXED as $key => $row) {
			$temp[$key] = $row['ll_Ycoord'];
			$temp1[$key] = $row['ll_Xcoord'];
		}
		array_multisort($temp, SORT_DESC, $temp1, SORT_ASC, $dataPL1NOTINDEXED);
		fclose($file);
		
		$file = fopen("../upload/".$_SESSION["uid"]."/".$fid2."/".$fn2, "r");
		$dataPL2NOTINDEXED = array();
		while (($buffer = fgets($file)) !== false) {
			if (preg_match('/^[\s]*[a-zA-Z][a-zA-Z0-9]+[\s]+[\-0-9\.]+[\s]+[\-0-9\.]+[\s]*[:]*[\s]*[a-zA-Z]*/',$buffer)) {
				$expBuff = preg_split('/\s+/', $buffer);
				if(preg_match('/[a-zA-Z0-9]+/',$expBuff[0])){
					$array1["node_name"] = $expBuff[0];
					$array1["ll_Xcoord"] = $expBuff[1];
					$array1["ll_Ycoord"] = $expBuff[2];
					if($expBuff[5] != ""){
						$array1["movetype"] = $expBuff[5];
					}
				}else{
					$array1["node_name"] = $expBuff[1];
					$array1["ll_Xcoord"] = $expBuff[2];
					$array1["ll_Ycoord"] = $expBuff[3];
					if($expBuff[6] != ""){
						$array1["movetype"] = $expBuff[6];
					}
				}
				array_push($dataPL2NOTINDEXED, $array1);
			}
		}
		$temp = $temp1 = array();
		foreach ($dataPL2NOTINDEXED as $key => $row) {
			$temp[$key] = $row['ll_Ycoord'];
			$temp1[$key] = $row['ll_Xcoord'];
		}
		array_multisort($temp, SORT_DESC, $temp1, SORT_ASC, $dataPL2NOTINDEXED);
		fclose($file);
		
		$sum=0;
		$minX=9999;
		$maxX=-9999;
		$minY=9999;
		$maxY=-9999;
		for($i=0;$i<$array["NumNets"];$i++){
			$minX=9999;
			$maxX=-9999;
			$minY=9999;
			$maxY=-9999;
			for($j=0;$j<$array[$i]["NetDegree"];$j++){
				if($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"]<$minX){
					$minX=($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"]);
				}
				if(($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"])<$minY){
					$minY=($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"]);
				}
				if(($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"])+($dataNODES[$array[$i]["node_name".$j]]["width"])>$maxX){
					$maxX=($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"])+($dataNODES[$array[$i]["node_name".$j]]["width"]);
				}
				if(($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"])+($dataNODES[$array[$i]["node_name".$j]]["height"])>$maxY){
					$maxY=($dataPL1INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"])+($dataNODES[$array[$i]["node_name".$j]]["height"]);
				}
			}
			$sum+=($maxX-$minX)+($maxY-$minY);
		}
		$array["HalfPerimeter1"] = $sum;
		
		$sum=0;
		$minX=9999;
		$maxX=-9999;
		$minY=9999;
		$maxY=-9999;
		for($i=0;$i<$array["NumNets"];$i++){
			$minX=9999;
			$maxX=-9999;
			$minY=9999;
			$maxY=-9999;
			for($j=0;$j<$array[$i]["NetDegree"];$j++){
				if($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"]<$minX){
					$minX=($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"]);
				}
				if(($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"])<$minY){
					$minY=($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"]);
				}
				if(($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"])+($dataNODES[$array[$i]["node_name".$j]]["width"])>$maxX){
					$maxX=($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Xcoord"])+($dataNODES[$array[$i]["node_name".$j]]["width"]);
				}
				if(($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"])+($dataNODES[$array[$i]["node_name".$j]]["height"])>$maxY){
					$maxY=($dataPL2INDEXED[$array[$i]["node_name".$j]]["ll_Ycoord"])+($dataNODES[$array[$i]["node_name".$j]]["height"]);
				}
			}
			$sum+=($maxX-$minX)+($maxY-$minY);
		}
		$array["HalfPerimeter2"] = $sum;
		
		error_reporting(0);
		$total=0;
		for ($i=0; $i<$array["NumNodes"]; $i++) {
			try {
				$pos = $i;
				$posBefore = $dataPL1NOTINDEXED[$i-1]["node_name"];
				if (((floatval($dataPL1NOTINDEXED[$pos-1]["ll_Xcoord"]) + floatval($dataNODES[$posBefore]["width"])) > floatval($dataPL1NOTINDEXED[$pos]["ll_Xcoord"])) && (floatval($dataPL1NOTINDEXED[$pos-1]["ll_Ycoord"]) == floatval($dataPL1NOTINDEXED[$pos]["ll_Ycoord"])) && substr($dataPL1NOTINDEXED[$pos]["node_name"],0,1)!="p" && !isset($dataPL2NOTINDEXED[$i]["movetype"])) {
					$total++;
				} else if ((floatval($dataPL1NOTINDEXED[$pos]["ll_Xcoord"])+floatval($dataNODES[$dataPL1NOTINDEXED[$i]["node_name"]]["width"])) > floatval($dataPL1NOTINDEXED[$pos+1]["ll_Xcoord"]) && (floatval($dataPL1NOTINDEXED[$pos+1]["ll_Ycoord"]) == floatval($dataPL1NOTINDEXED[$pos]["ll_Ycoord"])) && substr($dataPL1NOTINDEXED[$pos]["node_name"],0,1)!="p" && !isset($dataPL2NOTINDEXED[$i]["movetype"])) {
					$total++;
				}
			} catch(Exception $e) {}
		}
		$array["Overlap1"] = $total;
		
		$total=0;
		for ($i=0; $i<$array["NumNodes"]; $i++) {
			try {
				$pos = $i;
				$posBefore = $dataPL2NOTINDEXED[$i-1]["node_name"];
				if (((floatval($dataPL2NOTINDEXED[$pos-1]["ll_Xcoord"]) + floatval($dataNODES[$posBefore]["width"])) > floatval($dataPL2NOTINDEXED[$pos]["ll_Xcoord"])) && (floatval($dataPL2NOTINDEXED[$pos-1]["ll_Ycoord"]) == floatval($dataPL2NOTINDEXED[$pos]["ll_Ycoord"])) && substr($dataPL2NOTINDEXED[$pos]["node_name"],0,1)!="p" && !isset($dataPL2NOTINDEXED[$i]["movetype"])) {
					$total++;
				} else if ((floatval($dataPL2NOTINDEXED[$pos]["ll_Xcoord"])+floatval($dataNODES[$dataPL2NOTINDEXED[$i]["node_name"]]["width"])) > floatval($dataPL2NOTINDEXED[$pos+1]["ll_Xcoord"]) && (floatval($dataPL2NOTINDEXED[$pos+1]["ll_Ycoord"]) == floatval($dataPL2NOTINDEXED[$pos]["ll_Ycoord"])) && substr($dataPL2NOTINDEXED[$pos]["node_name"],0,1)!="p" && !isset($dataPL2NOTINDEXED[$i]["movetype"])) {
					$total++;
				}
			} catch(Exception $e) {}
		}
		$array["Overlap2"] = $total;
		error_reporting(E_ALL);
		$total=0;
		for($i=0; $i<$array["NumNodes"]; $i++){
			if((floatval($dataPL1NOTINDEXED[$i]["ll_Xcoord"])+floatval($dataNODES[$dataPL1NOTINDEXED[$i]["node_name"]]["width"])>floatval($array["Numsites"]) || floatval($dataPL1NOTINDEXED[$i]["ll_Xcoord"])<0 || floatval($dataPL1NOTINDEXED[$i]["ll_Ycoord"])+floatval($dataNODES[$dataPL1NOTINDEXED[$i]["node_name"]]["height"])>floatval($array["rowHeight"])*floatval($array["NumRows"]) || floatval($dataPL1NOTINDEXED[$i]["ll_Ycoord"])<0) && substr($dataPL1NOTINDEXED[$i]["node_name"],0,1)!="p" && !isset($dataPL2NOTINDEXED[$i]["movetype"])){
				$total++;
			}
		}
		$array["Overflow1"] = $total;
		
		$total=0;
		for($i=0; $i<$array["NumNodes"]; $i++){
			if(((floatval($dataPL2NOTINDEXED[$i]["ll_Xcoord"])+floatval($dataNODES[$dataPL2NOTINDEXED[$i]["node_name"]]["width"]))>floatval($array["Numsites"]) || floatval($dataPL2NOTINDEXED[$i]["ll_Xcoord"])<0 || (floatval($dataPL2NOTINDEXED[$i]["ll_Ycoord"])+floatval($dataNODES[$dataPL2NOTINDEXED[$i]["node_name"]]["height"]))>(floatval($array["rowHeight"])*floatval($array["NumRows"])) || floatval($dataPL2NOTINDEXED[$i]["ll_Ycoord"])<0) && substr($dataPL2NOTINDEXED[$i]["node_name"],0,1)!="p" && !isset($dataPL2NOTINDEXED[$i]["movetype"])){
				$total++;
			}
		}
		$array["Overflow2"] = $total;
		
		echo json_encode($array);
	} else {
		header("Location: ../placement.php");
	}
?>