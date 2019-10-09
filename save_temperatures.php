<?PHP
	session_start();
	if(isset(glob("./floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/*.wts")[0])) {
		$file = fopen(glob("./floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/*.wts")[0], "w");
		foreach ($_POST as $key => $value)
			fwrite($file,$key." ".$value."\n");
		header("Location: ./floorplan.php");
	} else {
		header("Location: ./index.php");
	}
?>