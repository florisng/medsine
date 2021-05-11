<?php
	include("functions.php");
	$_SESSION["page"] = "forbiden";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Medsine - Forbiden</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.ico" />
    </head>
	<body>
		<div id="welcome">
			<div>
				<img src="img/lock.png" class="icon" />
				<span class="titre">401 - Unauthorized page.</span>
			</div>
			<hr>
			<div class="centered">
				<img src="img/lock.png" class="lock" />
				<hr>
				<p>
		<a href="javascript:window.history.back()" class="left"><img src="img/back.png" alt="" class="average"></a>
		<a href='index.php' class='btn right'>Log In</a>
				</p>
			</div>
    </body>
</html>