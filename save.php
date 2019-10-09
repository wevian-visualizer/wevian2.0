<?PHP
	session_start();
	if(isset($_SESSION["uid"]) && isset($_SESSION["fid"])){
		if(!file_exists("floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/work.save"))
			$file = fopen("floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/work.save", "w");
		else
			$file = fopen(glob("floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/*.save")[0], "w");
		fwrite($file,$_POST["html"]);
	}else
		header("Location: floorplan");
?>