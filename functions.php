<?PHP
	@session_start();
	function updateSession(){
		$_SESSION["dirr"] = $_POST["type"];
		$_SESSION["fid"] = $_POST["fid"];
		$_SESSION["filename"] = $_POST["name"];
	}
	
	function updateSessionFloorplan(){
		$_SESSION["dirr_floorplan"] = $_POST["type"];
		$_SESSION["fid"] = $_POST["fid"];
		$_SESSION["filename"] = $_POST["name"];
	}
	
	function updateSessionVHDL(){
		$_SESSION['path'] = "./vhdlfiles/uploads/".$_SESSION["uid"]."/".$_POST["fid"];
		$_SESSION["fid_vhdl"] = $_POST["fid"];
		$_SESSION['fname'] = $_POST['name'];
	}
	
	function clearVisualizer(){
		if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
			unset($_SESSION["dirr"]);
			unset($_SESSION["filename"]);
			unset($_SESSION["fid"]);
		}
	}
	
	function clearVisualizerFloorplan(){
		if(isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"])){
			unset($_SESSION["dirr_floorplan"]);
			unset($_SESSION["filename"]);
			unset($_SESSION["fid"]);
		}
	}
	
	function deleteFile(){
		include("connect.php");
		if($stmt = mysqli_prepare($connect, "DELETE FROM placement_files WHERE userid=? and id=?")){
			mysqli_stmt_bind_param($stmt, "ii", $_SESSION["uid"],$_POST["fid"]);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		array_map('unlink', glob("upload/".$_SESSION["uid"]."/".$_POST["fid"]."/*.*"));
		rmdir("upload/".$_SESSION["uid"]."/".$_POST["fid"]);
		if(count(glob("upload/".$_SESSION["uid"]."/*", GLOB_ONLYDIR)) == 0){
			rmdir("upload/".$_SESSION["uid"]);
		}
	}
	
	function deleteFileVHDL(){
		include("connect.php");
		if($stmt = mysqli_prepare($connect, "DELETE FROM vhdl_files WHERE userid=? and id=?")){
			mysqli_stmt_bind_param($stmt, "ii", $_SESSION["uid"],$_POST["fid"]);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		array_map('unlink', glob("vhdl_files/uploads/".$_SESSION["uid"]."/".$_POST["fid"]."/*.*"));
		rmdir("vhdl_files/uploads/".$_SESSION["uid"]."/".$_POST["fid"]);
		if(count(glob("vhdl_files/uploads/".$_SESSION["uid"]."/*", GLOB_ONLYDIR)) == 0){
			rmdir("vhdl_files/uploads/".$_SESSION["uid"]);
		}
	}
	
	function deleteFileFloorplan(){
		include("connect.php");
		if($stmt = mysqli_prepare($connect, "DELETE FROM floorplan_files WHERE userid=? and id=?")){
			mysqli_stmt_bind_param($stmt, "ii", $_SESSION["uid"],$_POST["fid"]);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		array_map('unlink', glob("floorplan_saves/".$_SESSION["uid"]."/".$_POST["fid"]."/*.*"));
		rmdir("floorplan_saves/".$_SESSION["uid"]."/".$_POST["fid"]);
		if(count(glob("floorplan_saves/".$_SESSION["uid"]."/*", GLOB_ONLYDIR)) == 0){
			rmdir("floorplan_saves/".$_SESSION["uid"]);
		}
	}
	
	function compare_passwords($password, $password_retype){
		return $password == $password_retype ? true : false;
	}
	
	function updateFloorplanName(){
		include("connect.php");
		$name = htmlspecialchars($_POST["name"]);
		if(strlen($name) >= 3 && strlen($name) <=50)
			if($stmt = mysqli_prepare($connect, "UPDATE floorplan_files SET name=? WHERE id=?")){
				mysqli_stmt_bind_param($stmt, "si", $_POST["name"],$_POST["fid"]);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
	}
	
	function updateVHDLName(){
		include("connect.php");
		$name = htmlspecialchars($_POST["name"]);
		if(strlen($name) >= 3 && strlen($name) <=50)
			if($stmt = mysqli_prepare($connect, "UPDATE vhdl_files SET name=? WHERE id=?")){
				mysqli_stmt_bind_param($stmt, "si", $_POST["name"],$_POST["fid"]);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
	}
	
	if(isset($_POST["updateFloorplanName"]) && $_POST["updateFloorplanName"]) updateFloorplanName();
	if(isset($_POST["updateVHDLName"]) && $_POST["updateVHDLName"]) updateVHDLName();
	if(isset($_POST["loadFile"]) && $_POST["loadFile"]) updateSession();
	if(isset($_POST["loadFileFloorplan"]) && $_POST["loadFileFloorplan"]) updateSessionFloorplan();
	if(isset($_POST["loadFileVHDL"]) && $_POST["loadFileVHDL"]) updateSessionVHDL();
	if(isset($_POST["clearVisualizer"]) && $_POST["clearVisualizer"]) clearVisualizer();
	if(isset($_POST["clearVisualizerFloorplan"]) && $_POST["clearVisualizerFloorplan"]) clearVisualizerFloorplan();
	if(isset($_POST["deleteFile"]) && $_POST["deleteFile"]) deleteFile();
	if(isset($_POST["deleteFileFloorplan"]) && $_POST["deleteFileFloorplan"]) deleteFileFloorplan();
	if(isset($_POST["deleteFileVHDL"]) && $_POST["deleteFileVHDL"]) deleteFileVHDL();
?>