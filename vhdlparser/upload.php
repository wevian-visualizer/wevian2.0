<?php
	session_start();
	include("../connect.php");
	include("../functions.php");
	date_default_timezone_set('Europe/Athens');
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(isset($_FILES['file'])){
			$zip = new ZipArchive;
			$file_name = $_FILES['file']['name'];
			$file_tmp =$_FILES['file']['tmp_name'];
			$ext = pathinfo($file_name);
			$expension = "zip";
			
			if(strcmp($ext['extension'], $expension) == 0){
				if($stmt = mysqli_prepare($connect, "SELECT id FROM accounts WHERE email=?")){
					$email = $_SESSION["email"];
					mysqli_stmt_bind_param($stmt, "s", $email);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt, $id);
					mysqli_stmt_fetch($stmt);
					mysqli_stmt_close($stmt);
					$filename = $_POST['fileName'];
					$date = date("Y-m-d H:i:s");
					$filename2 = $ext['filename'];
					$selected = 0;
					if($stmt = mysqli_prepare($connect, "INSERT INTO vhdl_files (name,userid,date,selected) VALUES (?,?,?,?)")){
						mysqli_stmt_bind_param($stmt, "sisi", $filename,$id,$date,$selected);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);
						if($stmt = mysqli_prepare($connect, "SELECT id FROM vhdl_files WHERE userid=? and name=? and date=? and selected=?")){
							mysqli_stmt_bind_param($stmt, "issi", $id,$filename,$date,$selected);
							mysqli_stmt_execute($stmt);
							mysqli_stmt_bind_result($stmt, $fid);
							mysqli_stmt_fetch($stmt);
							mysqli_stmt_close($stmt);
							$_SESSION["fid_vhdl"] = $fid;
							if($stmt = mysqli_prepare($connect, "UPDATE vhdl_files SET selected=1 WHERE userid=? and name=? and date=? and selected=?")){
								mysqli_stmt_bind_param($stmt, "issi", $id,$filename,$date,$selected);
								mysqli_stmt_execute($stmt);
								mysqli_stmt_close($stmt);
								if(!file_exists("../vhdlfiles/uploads/".$id)){
									mkdir("../vhdlfiles/uploads/".$id);
								}
								if(!file_exists("../vhdlfiles/uploads/".$id."/".$fid)){
									mkdir("../vhdlfiles/uploads/".$id."/".$fid);
								}
								//move_uploaded_file($file_tmp, "uploads/".$file_name);
								if ($zip->open($file_tmp) === TRUE) {
									$zip->extractTo("../vhdlfiles/uploads/".$id."/".$fid);
									$zip->close();
									//$_SESSION['path'] = "./vhdlfiles/uploads/".$id."/".$fid;
									$_SESSION['fname'] = $_POST['fileName'];
									header('Location: ../vhdlparser.php');
									die();
								} else {
									die("Error opening the zip.");
								}
							}
						}
					}
				}
			}else{
				unset($_SESSION['path']);
				header("Location: ../vhdlparser.php");
			}
		}else{
			unset($_SESSION['path']);
			header("Location: ../vhdlparser.php");
		}
	}else{
		unset($_SESSION['path']);
		header("Location: index");
	}
?>