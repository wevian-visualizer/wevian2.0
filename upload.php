<?php
	session_start();
	include("connect.php");
	include("functions.php");
	date_default_timezone_set('Europe/Athens');
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(isset($_POST["options"]) && !empty($_POST["options"]) || substr($_FILES["file"]["name"],-3)=="zip"){
			if($stmt = mysqli_prepare($connect, "SELECT id FROM accounts WHERE email=?")){
				$email = $_SESSION["email"];
				mysqli_stmt_bind_param($stmt, "s", $email);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $id);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
				$options = $_POST["options"] == "" ? "ZIP":$_POST["options"];
				$date = date("Y-m-d H:i:s");
				$filename = $_FILES["file"]["name"];
				$selected = 0;
				if($stmt = mysqli_prepare($connect, "INSERT INTO placement_files (type,name,userid,date,selected) VALUES (?,?,?,?,?)")){
					mysqli_stmt_bind_param($stmt, "ssisi", $options,$filename,$id,$date,$selected);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					if($stmt = mysqli_prepare($connect, "SELECT id FROM placement_files WHERE userid=? and type=? and name=? and date=? and selected=?")){
						mysqli_stmt_bind_param($stmt, "isssi", $id,$options,$filename,$date,$selected);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_bind_result($stmt, $fid);
						mysqli_stmt_fetch($stmt);
						mysqli_stmt_close($stmt);
						if($stmt = mysqli_prepare($connect, "UPDATE placement_files SET selected=1 WHERE userid=? and type=? and name=? and date=? and selected=?")){
							mysqli_stmt_bind_param($stmt, "isssi", $id,$options,$filename,$date,$selected);
							mysqli_stmt_execute($stmt);
							mysqli_stmt_close($stmt);
							if(!file_exists("upload")){
								mkdir("upload");
							}
							if(!file_exists("upload/".$id)){
								mkdir("upload/".$id);
							}
							if(!file_exists("upload/".$id."/".$fid)){
								mkdir("upload/".$id."/".$fid);
							}
							if(!move_uploaded_file($_FILES["file"]["tmp_name"], "upload/".$id."/".$fid."/".basename($_FILES["file"]["name"]))){
								die("Sorry, there was an error uploading your file.");
							}
							$_POST["options"] == "" ? copy("upload/".$id."/".$fid."/".basename($_FILES["file"]["name"]), substr("upload/".$id."/".$fid."/".basename($_FILES["file"]["name"]),0,-4).".txt"):copy("upload/".$id."/".$fid."/".basename($_FILES["file"]["name"]), substr("upload/".$id."/".$fid."/".basename($_FILES["file"]["name"]),0,-3).".txt");
							if($options=="ZIP"){
								$zip = new ZipArchive;
								$res = $zip->open("upload/".$id."/".$fid."/".basename($_FILES["file"]["name"]));
								if ($res === TRUE) {
									$zip->extractTo("upload/".$id."/".$fid);
									$zip->close();
								}
							}
						}
					}
				}
			}
		}
	}else{
		header("Location: index");
	}
?>