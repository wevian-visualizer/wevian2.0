<?PHP
	session_start();
	if(!isset(glob("../floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/*.wts")[0])) {
		$file = fopen("../floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/temperatures.wts", "w");
		$dataBLOCKS    = $_POST["dataBLOCKS"];
		$dataBLOCKS    = json_decode("$dataBLOCKS", true);
		for($i=0; $i<$dataBLOCKS["NumSoftRectangularBlocks"]+$dataBLOCKS["NumHardRectilinearBlocks"]+$dataBLOCKS["NumTerminals"]; $i++){
			if($dataBLOCKS[$i]["type"]!="terminal")
				fwrite($file, $dataBLOCKS[$i]["node_name"]." 0\n");
		}
	} else {
		header("Location: ../index.php");
	}
?>