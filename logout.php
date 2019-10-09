<?php
	session_start();
	if(isset($_SESSION["logged_in"])){
		include("functions.php");
		session_destroy();
		if(isset($_COOKIE["unique_hash_id"]) || !empty($_COOKIE["unique_hash_id"])){
			setcookie("unique_hash_id", "", time() - 3600);
		}
	}
	header("Location: index");
?>