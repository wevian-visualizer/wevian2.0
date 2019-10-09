<?php
	session_start();
	if(isset($_FILES['file'])){
		$zip = new ZipArchive;
		$file_name = $_FILES['file']['name'];
		$file_tmp =$_FILES['file']['tmp_name'];
		$ext = pathinfo($file_name);
		$expension = "zip";
		
		if(strcmp($ext['extension'], $expension) == 0){
			mkdir('../vhdlfiles/uploads/'.$_POST['fileName'].'/'.$ext['filename']);
			//move_uploaded_file($file_tmp, "uploads/".$file_name);
			if ($zip->open($file_tmp) === TRUE) {
				$zip->extractTo('../vhdlfiles/uploads/'.$_POST['fileName'].'/'.$ext['filename']);
				$zip->close();
				$_SESSION['path'] = './vhdlfiles/uploads/'.$_POST['fileName'].'/'.$ext['filename'];
				$_SESSION['fname'] = $_POST['fileName'];
				header('Location: ../vhdlparser.php');
				die();
			} else {
				echo 'error zip';
				header("Location: ../vhdlparser.php");
			}
		}else{
			unset($_SESSION['path']);
			header("Location: ../vhdlparser.php");
		}
	}
	unset($_SESSION['path']);
	header("Location: ../vhdlparser.php");
?>