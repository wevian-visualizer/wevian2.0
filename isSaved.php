<?PHP
	session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"])){
		echo file_exists("floorplan_saves/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/work.save") ? true : false;
	}else{
		if(isset($_SESSION["uid"]) && isset($_SESSION["fid"]))
			echo file_exists("floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/work.save") ? true : false;
		else
			header("Location: floorplan");
	}
?>