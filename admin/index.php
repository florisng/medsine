<?php
	include("../functions.php");
	$_SESSION["page"] = "admin";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="../img/icon.png" />
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				if($_SESSION["type"] == "Manager"){
					require('../classes/class_user.php');
		?>
		<div id="welcome">
			<div id="">
				<img src="../img/admin.png" class="icon" />
				<span class="titre">Admin | </span>
			</div>
			<div id="">
				
			</div>
		</div>
		<?php
				} else {
					header('Location: ../interdit.php');
				}
			} else{
				header('Location: ../interdit.php');
			}
		?>
    </body>
</html>