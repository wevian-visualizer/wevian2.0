<?php
	session_start();
	include("../connect.php");
	if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
		if(!empty($_POST['data1'])){
			if($stmt = mysqli_prepare($connect, "SELECT id FROM accounts WHERE email=?")){
				$email = $_SESSION["email"];
				mysqli_stmt_bind_param($stmt, "s", $email);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $id);
				mysqli_stmt_fetch($stmt);
				mysqli_stmt_close($stmt);
				$fid = $_SESSION["fid_vhdl"];
				unset($_SESSION["fid_vhdl"]);
			}
			$zip = new ZipArchive;
			$rootPath = "../vhdlfiles/generated_files/".$id."/".$fid;
			$zip->open($rootPath.'/genFiles.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
			$files = array_diff(scandir($rootPath), array('..', '.'));
			foreach($files as $file){
				$ext = pathinfo($rootPath."/".$file);
				if(strcmp($ext['extension'], "zip") != 0){
					$zip->addFile($rootPath."/".$file, $file);
				}
			}
			$zip->close();
			echo $rootPath."/genFiles.zip";
		}else{
			header("Location: ../vhdlparser.php");
		}
	}else{
		header("Location: index");
	}
?>