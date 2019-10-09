<?php
	session_start();
	include("connect.php");
	include("functions.php");
	date_default_timezone_set('Europe/Athens');
	$project_name = htmlspecialchars($_POST["project_name"]);
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(isset($_POST["options"]) && !empty($_POST["options"]) && isset($project_name) && !empty($project_name)){
			if($stmt = mysqli_prepare($connect, "SELECT id FROM accounts WHERE email=?")){
				$email = $_SESSION["email"];
				mysqli_stmt_bind_param($stmt, "s", $email);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $id);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
				$options = $_POST["options"];
				$date = date("Y-m-d H:i:s");
				$selected = 0;
				if($stmt = mysqli_prepare($connect, "INSERT INTO floorplan_files (type,name,userid,date,selected) VALUES (?,?,?,?,?)")){
					mysqli_stmt_bind_param($stmt, "ssisi", $options,$project_name,$id,$date,$selected);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					if($stmt = mysqli_prepare($connect, "SELECT id FROM floorplan_files WHERE userid=? and type=? and name=? and date=? and selected=?")){
						mysqli_stmt_bind_param($stmt, "isssi", $id,$options,$project_name,$date,$selected);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_bind_result($stmt, $fid);
						mysqli_stmt_fetch($stmt);
						mysqli_stmt_close($stmt);
						if($stmt = mysqli_prepare($connect, "UPDATE floorplan_files SET selected=1 WHERE userid=? and type=? and name=? and date=? and selected=?")){
							mysqli_stmt_bind_param($stmt, "isssi", $id,$options,$project_name,$date,$selected);
							mysqli_stmt_execute($stmt);
							mysqli_stmt_close($stmt);
							if(!file_exists("floorplan_saves")){
								mkdir("floorplan_saves");
							}
							if(!file_exists("floorplan_saves/".$id)){
								mkdir("floorplan_saves/".$id);
							}
							if(!file_exists("floorplan_saves/".$id."/".$fid)){
								mkdir("floorplan_saves/".$id."/".$fid);
							}
							foreach(glob("floorplan_benchmarks/".substr($options,0,4)."/".substr($options,-6,4)."/".substr($options,-2,2)."/*.*") as $file) {
							  copy($file, str_replace("floorplan_benchmarks/".substr($options,0,4)."/".substr($options,-6,4)."/".substr($options,-2,2),"floorplan_saves/".$id."/".$fid, $file));
							}
						}
					}
				}
			}
		}
	}
	header("Location: floorplan");
?>