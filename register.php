<?php
	@session_start();
	if(!isset($_SESSION["logged_in"]) && !isset($_COOKIE["unique_hash_id"])){
		include("connect.php");
		include("functions.php");
		if(compare_passwords(htmlspecialchars($_POST["password"]), htmlspecialchars($_POST["password_retype"]))){
			if ($stmt = mysqli_prepare($connect, "SELECT email FROM accounts WHERE email=?")){
					$email = htmlspecialchars($_POST["email"]);
					mysqli_stmt_bind_param($stmt, "s", $email);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt)>0){
						echo "<script>alert('This email already exists!');window.location = './index';</script>";
					}
					mysqli_stmt_close($stmt);
					if ($stmt = mysqli_prepare($connect, "INSERT INTO accounts (email,password) VALUES (?,?)")){
						$password = htmlspecialchars($_POST["password"]);
						$password = password_hash($password,PASSWORD_DEFAULT);
						mysqli_stmt_bind_param($stmt, "ss", $email,$password);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);
						$_SESSION["user_just_registered"] = true;
					}
			}
		} else {
			echo "<script>alert('Passwords must be same!');window.location = './index';</script>";
		}
		mysqli_close($connect);
	}
	header("Location: index");
?>