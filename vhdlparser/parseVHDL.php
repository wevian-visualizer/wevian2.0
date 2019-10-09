<?php
	function getNets($pname, $ports, $inc, $info, &$graph, &$numPins){
		$endPin = null;
		for($i=0; $i<sizeof($info['portmaps'][$pname]); $i++){
			$tname = $info['portmaps'][$pname][$i]['name'];
			if(empty($info['cells'][$tname])){
				for($j=0; $j<sizeof($info['portmaps'][$pname][$i])-2; $j++){
					$inc++;
					$ports[$inc][$tname][$info['comps'][$tname][$j]['name']] = $info['portmaps'][$pname][$i][$j];
				}
				getNets($tname, $ports, $inc, $info, $graph, $numPins);
			}else{
				if(!empty($info['pins'][$ports[$inc][$pname][$info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']]]])){
					$gname = $ports[$inc][$pname][$info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']]].".1";
					$endPin = $ports[$inc][$pname][$info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']]];
				}else{
					$gname = $ports[$inc][$pname][$info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']]];
				}
				$graph['names'][$gname] = $info['portmaps'][$pname][$i]['name'];
				for($k=sizeof($info['portmaps'][$pname][$i])-4; $k>=0; $k--){
					$cname = $info['portmaps'][$pname][$i][$k];
					if($k==sizeof($info['portmaps'][$pname][$i])-4 && !is_null($endPin)){
						$graph['g'][$gname][] = $endPin;
						$numPins++;
						$endPin = null;
					}
					if($gname != $cname){
						$graph['g'][$ports[$inc][$pname][$cname]][] = $gname;
						$numPins++;
					}
				}
				
			}
		}
	}


	session_start();
	include("../connect.php");
	$tree = array();
	$info = array();
	$fileStack = array();
	$fileDir = array();
	$ports = array();
	$graph = array();
	$filesi = 2;
	$currFile = 2;
	$id = 100;
	$nid = 0;
	$pid = 0;
	$inInd = 0;
	$pinInd = 1;
	$numNodes = 0;
	$maxCellWidth = 0;
	$cellAreaSum = 0;
	$found = null;
	$found2 = null;
	
	if(!empty($_POST['data1']) && !empty($_POST['data2'])){
		if($stmt = mysqli_prepare($connect, "SELECT id FROM accounts WHERE email=?")){
			$email = $_SESSION["email"];
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $uid);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			$fid = $_SESSION["fid_vhdl"];
			$topLvl = $_POST['data1'];
			$library = $_POST['data2'];
			//unset($_SESSION["fid_vhdl"]);
			if($stmt = mysqli_prepare($connect, "UPDATE vhdl_files SET type=?, main=? WHERE id=?")){
				mysqli_stmt_bind_param($stmt, "ssi", $library,$topLvl,$fid);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
		}
		rename(".".$_SESSION['path']."/".$_POST['data1'], ".".$_SESSION['path']."/1".$_POST['data1']);
		$files = array_diff(scandir(".".$_SESSION['path']), array('..', '.'));
		//$files = array_diff(scandir('vhdl/full_adder'), array('..', '.'));
		while($filesi<=sizeof($files)) {
			$tempi = 0;
			//$file = fopen("vhdl/full_adder/".$files[$currFile], "r");
			$file = fopen(".".$_SESSION['path']."/".$files[$currFile], "r");
			$temp = $currFile;
			while (($buffer = fgets($file)) !== false) {
				if(preg_match('/\b((e|E)(n|N)(T|t)(i|I)(t|T)(y|Y))\b [A-Za-z0-9_-]+ \b((i|I)(s|S))\b/' ,$buffer)){
					$expBuff = preg_split('/\s/' ,$buffer);
					if(empty($fileDir[$expBuff[1]])) $fileDir[$expBuff[1]] = $files[$currFile];
						if($filesi==2){
							$tree[$id++] = array('name'=>$expBuff[1], 'pid'=>$pid, 'level'=>0, 'isLeaf'=>false);
							$pid = 100;
							$filesi++;
							while (($buffer = fgets($file)) !== false) {
								if(preg_match('/\b((e|E)(n|N)(D|d))\b [A-Za-z0-9_-]+( |)\;/' ,$buffer)) break;
								else{
									if(preg_match('/([A-Za-z0-9,_]+\s*)+\:\s*(\b((i|I)(n|N))\b|\b((o|O)(u|U)(t|T))\b)/' ,$buffer, $matches)){
										$expBuff2 = preg_split('/[, :]/' ,$matches[0],null,PREG_SPLIT_NO_EMPTY);
										for($i=0;$i<count($expBuff2)-1;$i++){
											$info["pins"][$expBuff2[$i]]["name"] = "p".$pinInd++;
											$info["pins"][$expBuff2[$i]]["IO"] = end($expBuff2);
											$info["comps"][$expBuff[1]][$inInd]['name'] = $expBuff2[$i];
											$info["comps"][$expBuff[1]][$inInd++]['IO'] = end($expBuff2);
										}
									}else continue;
								}
							}
							$inInd=0;
						}elseif(strcmp(end($fileStack),$expBuff[1])==0){
							for($i=0; $i < sizeof($tree); $i++){
								if(strcasecmp($tree[$i+100]['name'], $expBuff[1]) == 0) $pid = $i+100;
							}
							array_pop($fileStack);
							$filesi++;
						}else{
							if($currFile>sizeof($files)) $currFile=2;
							else $currFile++;
						}
					break;
				}
			}
			
			if($temp != $currFile) continue;
			
			while (($buffer = fgets($file)) !== false) {
				if(preg_match('/\b((c|C)(o|O)(m|M)(p|P)(o|O)(n|N)(e|E)(n|N)(t|T))\b [A-Za-z0-9_-]+/' ,$buffer)){
					if($tree[$pid]['isLeaf'] != false) $numNodes--;
					$tree[$pid]["isLeaf"] = false;
					$expBuff = preg_split('/\s/' ,$buffer);
					$tree[$id++] = array('name'=>$expBuff[1], 'pid'=>$pid, 'level'=>$tree[$pid]['level']+1, 'isLeaf'=>true);
					$numNodes++;
					if(!array_key_exists($expBuff[1], $info["comps"])){
						while (($buffer = fgets($file)) !== false) {
							if(preg_match('/\b((e|E)(n|N)(D|d))\b [A-Za-z0-9_-]+( |)\;/' ,$buffer)) break;
							else{
								if(preg_match('/([A-Za-z0-9,_]+\s*)+\:\s*(\b((i|I)(n|N))\b|\b((o|O)(u|U)(t|T))\b)/' ,$buffer, $matches)){
									$expBuff2 = preg_split('/[, :]/' ,$matches[0],null,PREG_SPLIT_NO_EMPTY);
									for($i=0;$i<count($expBuff2)-1;$i++){
										$info["comps"][$expBuff[1]][$inInd]['name'] = $expBuff2[$i];
										$info["comps"][$expBuff[1]][$inInd++]['IO'] = end($expBuff2);
									}
								}else continue;
							}
						}
						$inInd=0;
					}
					array_push($fileStack, $expBuff[1]);
				}
				if(preg_match('/\s\b(s|S)(i|I)(g|G)(n|N)(a|A)(l|L)\b\s*([A-Za-z0-9_]+\,*\s*)+/' ,$buffer, $matches) && $pid==100){
					while (($buffer = fgets($file)) !== false) {
						if(preg_match('/\s*\b((b|B)(e|E)(g|G)(i|I)(n|N))\b/' ,$buffer)){
							while (($buffer = fgets($file)) !== false) {
								if(preg_match('/\b((e|E)(n|N)(D|d))\b [A-Za-z0-9_-]+( |)\;/' ,$buffer)) break;
								elseif(preg_match('/\s*[A-Za-z0-9_]+\b\s*((p|P)(o|O)(r|R)(t|T)\b\s*\b(m|M)(a|A)(p|P))\b/' ,$buffer, $matches)){
									$expBuff = preg_split('/\s/' ,$matches[0],null,PREG_SPLIT_NO_EMPTY); //$expBuff[0] comp name
									if(preg_match('/\((\s*[A-Za-z0-9,_]+\s*)+\)/' ,$buffer, $matches)){
										$expBuff2 = preg_split('/[, ()]/' ,$matches[0],null,PREG_SPLIT_NO_EMPTY);
										$info['portmaps'][$tree[$pid]['name']][$tempi]['name'] = $expBuff[0];
										for($k=0;$k<sizeof($expBuff2);$k++){
											if(empty($info['portmaps'][$tree[$pid]['name']][$tempi]['outs']) && strcmp($info['comps'][$expBuff[0]][$k]["IO"],'out')==0){
												$info['portmaps'][$tree[$pid]['name']][$tempi]['outs'] = $k;
											}
											$info['portmaps'][$tree[$pid]['name']][$tempi][$k] = $expBuff2[$k];
										}
										$tempi++;
									}
								}
							}
							continue;
						}
						$expBuff = preg_split('/(\,|\s)/' ,$matches[0],null,PREG_SPLIT_NO_EMPTY);
						for($i=1; $i<sizeof($expBuff); $i++){
							$info["signals"][$expBuff[$i]] = "0";
						}
					}
				}
				if(preg_match('/\s*\b((b|B)(e|E)(g|G)(i|I)(n|N))\b/' ,$buffer)){
					while (($buffer = fgets($file)) !== false) {
						if(preg_match('/\b((e|E)(n|N)(D|d))\b [A-Za-z0-9_-]+( |)\;/' ,$buffer)) break;
						elseif(preg_match('/\s*[A-Za-z0-9_]+\b\s*((p|P)(o|O)(r|R)(t|T)\b\s*\b(m|M)(a|A)(p|P))\b/' ,$buffer, $matches)){
							$expBuff = preg_split('/\s/' ,$matches[0],null,PREG_SPLIT_NO_EMPTY); //$expBuff[0] comp name
							if(preg_match('/\((\s*[A-Za-z0-9,_]+\s*)+\)/' ,$buffer, $matches)){
								$expBuff2 = preg_split('/[, ()]/' ,$matches[0],null,PREG_SPLIT_NO_EMPTY);
								$info['portmaps'][$tree[$pid]['name']][$tempi]['name'] = $expBuff[0];
								for($k=0;$k<sizeof($expBuff2);$k++){
									if(empty($info['portmaps'][$tree[$pid]['name']][$tempi]['outs']) && strcmp($info['comps'][$expBuff[0]][$k]["IO"],'out')==0){
										$info['portmaps'][$tree[$pid]['name']][$tempi]['outs'] = $k;
									}
									$info['portmaps'][$tree[$pid]['name']][$tempi][$k] = $expBuff2[$k];
								}
								$tempi++;
							}
						}
					}
				}
			}
			fclose($file);
		}
		
		switch($_POST['data2']){
			case "SAED_EDK32.28nm_CORE_HVT_v_01132015":
				$libtype = "stdcell_hvt";
				break;
			case "SAED_EDK32.28nm_CORE_LVT_v_01132015":
				$libtype = "stdcell_lvt";
				break;
			case "SAED_EDK32.28nm_CORE_RVT_v_01132015":
				$libtype = "stdcell_rvt";
				break;
		}
		$libpath = '../libraries/'.$_POST['data2'].'/lib/'.$libtype.'/lef';
		//$libpath = 'libraries\SAED_EDK32.28nm_CORE_HVT_v_01132015\lib\stdcell_hvt\lef';
		if(!file_exists("../vhdlfiles/generated_files/".$uid)){
			mkdir("../vhdlfiles/generated_files/".$uid);
		}
		if(!file_exists("../vhdlfiles/generated_files/".$uid."/".$fid)){
			mkdir("../vhdlfiles/generated_files/".$uid."/".$fid);
		}
		$nodesFile = fopen("../vhdlfiles/generated_files/".$uid."/".$fid."/nodesFile.nodes", "wb");
		fwrite($nodesFile, "NumNodes : ".sizeof($info['pins'])."\n");
		fwrite($nodesFile, "NumTerminals : ".$numNodes."\n");
		$plFile = fopen("../vhdlfiles/generated_files/".$uid."/".$fid."/plFile.pl", "wb");
		foreach($tree as $node){
			$found2 = 1;
			if($node["isLeaf"] == true){
				foreach(new DirectoryIterator($libpath) as $fileInfo){
					if($fileInfo->isDot()) continue;
					$file = fopen($libpath."/".$fileInfo, "r");
					while (($buffer = fgets($file)) !== false) {
						if(preg_match('/\b((m|M)(a|A)(c|C)(r|R)(o|O))\b( |)[A-Za-z0-9_-]+( |)/' ,$buffer)){
							$expBuff = preg_split('/\s+/' ,$buffer);
							if(strcmp($expBuff[1], $node['name'])==0) $found = $expBuff[1];
						}
						if($found != null){
							if(preg_match('/( |)*\b((s|S)(i|I)(z|Z)(e|E))\b( |)*[0-9.]+( |)*\b((b|B)(y|Y))\b( |)*[0-9.]+( |)*\;/' ,$buffer)){
								$expBuff2 = preg_split('/\s+/' ,$buffer);
								$sizes = array(intval(floatval($expBuff2[2])*10), intval(floatval($expBuff2[4])*10));
								$strdtCellHeight = $sizes[1];
								$maxCellWidth = max($maxCellWidth, $sizes[0]);
								$cellAreaSum += $sizes[0]*$sizes[1];
								fwrite($nodesFile, "	a".$nid."		".$sizes[0]."		".$sizes[1]."\n");
								fwrite($plFile, "	a".$nid."		0		0	:	N\n");
								$info["cells"][$expBuff[1]] = "a".$nid++;
								$found = null;
								break;
							}
						}
					}
				}
			}
		}
		foreach($info['pins'] as $pins){
			$cellAreaSum += 1;
			fwrite($nodesFile, "	".$pins['name']."		1		1	terminal\n");
			fwrite($plFile, "	".$pins['name']."		0		0	:	/FIXED\n");
		}
		fclose($nodesFile);
		fclose($plFile);
		
		//Calculate SCL with maths
		$userX=0;$userY=0;
		if($userX!=0 && $userY!=0){
			$chipWidth = $userX;
			if($chipWidth<$maxCellWidth) $chipWidth=$maxCellWidth;
			$chipHeight = round($userY);
			$chipHeight = $chipHeight + ($strdtCellHeight - ($chipHeight % $strdtCellHeight));
			if($chipWidth*$chipHeight<$cellAreaSum) $chipWidth = $cellAreaSum-$userY;
			
		}else{
			$chipArea = 1.1*$cellAreaSum;
			$chipHeight = round(sqrt($chipArea));
			$chipHeight = $chipHeight + ($strdtCellHeight - ($chipHeight % $strdtCellHeight));
			$chipWidth = $chipArea/$chipHeight;
		}
		$numRows = $chipHeight/$strdtCellHeight;
		$sclFile = fopen("../vhdlfiles/generated_files/".$uid."/".$fid."/sclFile.scl", "wb");
        fwrite($sclFile, "NumRows : ".$numRows."\n\n");
        $coordinateScl = 0;
        for($i=0; $i < $numRows; $i++){
            fwrite($sclFile, "CoreRow Horizontal\n");
            fwrite($sclFile, " Coordinate\t\t:\t ".$coordinateScl."\n");
            fwrite($sclFile, " Height\t\t\t: \t ".$strdtCellHeight."\n");
            fwrite($sclFile, " Sitewidth\t\t: \t 1\n");
            fwrite($sclFile, " Sitespacing\t: \t 1\n");
            if($i % 2 == 0) fwrite($sclFile, " Siteorient\t\t: \t FS\n");
            else fwrite($sclFile, " Siteorient\t\t: \t N\n");
            fwrite($sclFile, " Sitesymmetry\t: \t Y\n");
            fwrite($sclFile, " SubrowOrigin\t: \t 0");
            fwrite($sclFile, " Numsites : \t ".$chipWidth."\n");
            fwrite($sclFile, "End\n");
            $coordinateScl += $strdtCellHeight;
        }
		
		$inception = 0;
		$pname = $tree['100']['name'];
		$endPin = null;
		$numPins = 0;
		for($i=0; $i<sizeof($info['portmaps'][$pname]); $i++){
			$tname = $info['portmaps'][$pname][$i]['name'];
			if(empty($info['cells'][$tname])){
				for($j=0; $j<sizeof($info['portmaps'][$pname][$i])-2; $j++){
					$ports[$inception][$tname][$info['comps'][$tname][$j]['name']] = $info['portmaps'][$pname][$i][$j];
				}
				getNets($tname, $ports, $inception, $info, $graph, $numPins);
				$inception++;
			}else{
				if(!empty($info['pins'][$info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']]])){
					$gname = $info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']].".1";
					$endPin = $info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']];
				}else{
					$gname = $info['portmaps'][$pname][$i][$info['portmaps'][$pname][$i]['outs']];
				}
				$graph['names'][$gname] = $info['portmaps'][$pname][$i]['name'];
				for($k=sizeof($info['portmaps'][$pname][$i])-4; $k>=0; $k--){
					$cname = $info['portmaps'][$pname][$i][$k];
					if($k==sizeof($info['portmaps'][$pname][$i])-4 && !is_null($endPin)){
						$graph['g'][$gname][] = $endPin;
						$numPins++;
						$endPin = null;
					}
					if($gname != $cname){
						$graph['g'][$cname][] = $gname;
						$numPins++;
					}
				}
			}
		}

		$netsFile = fopen('../vhdlfiles/generated_files/'.$uid."/".$fid.'/netsFile.nets', 'wb');
		fwrite($netsFile, "NumNets :		".sizeof($graph['g'])."\n");
		fwrite($netsFile, "NumPins :		".$numPins."\n");
		foreach($graph['g'] as $key => $g){
			fwrite($netsFile, "NetDegree : ".(sizeof($g)+1)."\n");
			if(!empty($info['pins'][$key])){
				fwrite($netsFile, "	".$info['pins'][$key]['name']."\n");
			}else{
				fwrite($netsFile, "	".$info['cells'][$graph['names'][$key]]."\n");
			}
			for($i=0;$i<sizeof($g);$i++){
				if(!empty($info['pins'][$g[$i]])){
					fwrite($netsFile, "	".$info['pins'][$g[$i]]['name']."\n");
				}else{
					fwrite($netsFile, "	".$info['cells'][$graph['names'][$g[$i]]]."\n");
				}
			}
		}
		fclose($netsFile);

		$auxFile = fopen("../vhdlfiles/generated_files/".$uid."/".$fid."/auxFile.aux", "wb");
		fwrite($auxFile, "RowBasedPlacement : nodesFile.nodes netsFile.nets plFile.pl sclFile.scl");
		fclose($auxFile);
		rename(".".$_SESSION['path']."/1".$_POST['data1'], ".".$_SESSION['path']."/".$_POST['data1']);
		
		//echo "<pre>".json_encode($tree, JSON_PRETTY_PRINT)."</pre><br><br>";
		//echo "<pre>".json_encode($info, JSON_PRETTY_PRINT)."</pre>";
		//echo "<pre>".json_encode($fileDir, JSON_PRETTY_PRINT)."</pre>";
		//echo "<pre>".json_encode($ports, JSON_PRETTY_PRINT)."</pre>";
		//echo "<pre>".json_encode($graph, JSON_PRETTY_PRINT)."</pre>";
		echo json_encode($tree);
		unset($tree);
	}else{
		header("Location: index.php");
	}
?>