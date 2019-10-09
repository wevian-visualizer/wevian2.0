<?PHP
	$connect = mysqli_connect("localhost","name","password","database");
	mysqli_set_charset($connect, "utf8");
	if(mysqli_connect_errno()){
		die("Failed to connect to MySQL!");
	}
?>