<?php
	session_start();
	if(!isset($_SESSION["logged_in"])){
		include("connect.php");
		include("functions.php");
		if($stmt = mysqli_prepare($connect, "SELECT id,email,password FROM accounts WHERE email=?")){
			$email = htmlspecialchars($_POST["email"]);
			$password = htmlspecialchars($_POST["password"]);
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id,$email_result,$password_hashed);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			if(password_verify($password,$password_hashed)){
				$_SESSION["logged_in"] = true;
				$_SESSION["uid"] = $id;
				$_SESSION["email"] = $email;
			}else{
				$_SESSION["user_not_found"] = true;
			}
			mysqli_close($connect);
		}
	}
	header("Location: index");
?>
