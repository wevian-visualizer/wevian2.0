<?PHP
	@session_start();
	if(isset($_SESSION["shareUserID"]) && !empty($_SESSION["shareUserID"]) && isset($_SESSION["shareFileID"]) && !empty($_SESSION["shareFileID"])){
		unset($_SESSION["shareUserID"]);
		unset($_SESSION["shareFileID"]);
		if(isset($_SESSION["shareType"]) && !empty($_SESSION["shareType"]) && isset($_SESSION["shareFilename"]) && !empty($_SESSION["shareFilename"])){
			unset($_SESSION["shareType"]);
			unset($_SESSION["shareFilename"]);
		}
	}
?>