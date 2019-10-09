<?PHP
	$fid1 = htmlspecialchars($_POST["fid1"]);
	$fid2 = htmlspecialchars($_POST["fid2"]);
	if(isset($fid1) && isset($fid2) && !empty($fid1) && !empty($fid2)){
		session_start();
		include("../connect.php");
		if($stmt = mysqli_prepare($connect, "SELECT id, type, name, userid, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') FROM placement_files WHERE userid=? and id=? or id=?")){
			mysqli_stmt_bind_param($stmt, "iii", $_SESSION["uid"], $fid1, $fid2);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if(mysqli_num_rows($result) == 2){
				$rows = array();
				$row = mysqli_fetch_array($result, MYSQLI_NUM);
				array_push($rows, $row);
				$row = mysqli_fetch_array($result, MYSQLI_NUM);
				array_push($rows, $row);
				echo json_encode($rows);
			}else
				return false;
			mysqli_stmt_close($stmt);
		}
	} else {
		header("Location: ../placement.php");
	}
?>