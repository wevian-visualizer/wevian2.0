<?PHP
	session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"])){
		if(file_exists("floorplan_saves/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/work.save")){
			$file = fopen("floorplan_saves/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/work.save", "r");
			$contents = fread($file,filesize("floorplan_saves/".$_SESSION["shareUserID"]."/".$_SESSION["shareFileID"]."/work.save"));
			echo $contents;
		}
	}else{
		if(isset($_SESSION["uid"]) && isset($_SESSION["fid"])){
			if(file_exists("floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/work.save")){
				$file = fopen("floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/work.save", "r");
				$contents = fread($file,filesize("floorplan_saves/".$_SESSION["uid"]."/".$_SESSION["fid"]."/work.save"));
				echo $contents;
			}
		}else
			header("Location: floorplan");
	}
?>