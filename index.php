<?php
	require("functions.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Medsine</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
	</head>
	<body>
		<div id="">
			<div id="index_entete" class="centered">
				<?php
					echo "<p><img src='img/logo.png' class='logo' /></p>";
				?>
			</div>
			<?php
				if(isset($_SESSION["first_time"])) {
					echo "<div id='index'>";
				} else {
					$_SESSION["first_time"] = true;
					echo "<div id='animated_index'>";
				}
			?>
				<p>
					<?php
						$bdd = connexionDb();
						$rep = $bdd->query("SELECT * FROM company");
						while($data = $rep->fetch())
						{
							$exp_date = $data["exp_date"];
						}
						$rep->closeCursor();
		$infos_date = infosDate($exp_date);
		$jour = $infos_date[0];
		$mois = $infos_date[1];
		$annee = $infos_date[2];
		$exp_soon = date('Y-m-d', strtotime("-14 day", mktime(0, 0, 0, $mois, $jour, $annee)));
		if($exp_soon <= dater()) {
			echo "<div id='askMsg'>";
										$exp_soon_date = date_create($exp_date);
										$today_date = date_create(dater());
										$date_interval = date_diff($exp_soon_date, $today_date);
										$days_left = $date_interval->format('%d');
										echo "<img src='img/warning.png' class='small' /> ";
										if($days_left < 1) {$days_left = "less than 24 hours";} else {$days_left = $days_left." day(s)";}
										echo "<b>Warning !!!</b> The system will expire in <b>".$days_left."</b> !!!";
			echo "</div>";
		}
						echo "<span class='right'>";
							if(noel() == true) {
								echo "<img src='img/noel.jpg' class='moyen' />";
							} else {
								if(bonneAnnee() == true) {
									$infos_date = infosDate(dater());
									$year = $infos_date[2];
									echo "<span class='new_year'>".$year."</span>";
									echo "<img src='img/new_year.png' class='index_new_year' />";
								} else {
									echo "<img src='img/users.jpg' class='moyen'>";
								}
							}
						echo "</span>";
					?>
				</p>
				<form action="" method="post">
						<p>
			<input type="text" size="30" title="Enter your username" autocomplete="off" name="username" size="20" placeholder="User name" required />
						</p>
						<p>
			<input id="pwdField" type="password" size="30" title="Enter your password" name="password" size="20" placeholder="Password" required />
						</p>
						<p>
			<input type="checkbox" onclick="myFunction()" id="showPwd"><label for="showPwd" class="showPwdTxt">Show Password</label>
						</p>			
						<p>
			<input type="submit" value="Login" title="Connexion" name="ok" class="btn" />
						</p>
				</form>
				<?php
						if(isset($_POST["ok"]))
						{
							require("classes/class_user.php");
							$u = new User();
							$res = $u->is_user_exist($_POST["username"], $_POST["password"]);
							if($res == true)
							{
								$_SESSION["connect"] = true;
								$_SESSION["username"] = $_POST["username"];

								// Change user status to true[connected]
								$u = new User();
								$u->update_status(true, $_POST["username"]);

								// Get user's infos
								$u = new User();
								$infosUser = $u->infos_user($_SESSION["username"]);
								$_SESSION["type"] = $infosUser[3];
								$_SESSION["username"] = $_SESSION["username"];
								
								// Redirect the user
								switch ($_SESSION["type"])
								{
									case "Cashier":
										header("Location: cash.php");
									break;
									case "Manager":
										header("Location: sales.php");
									break;
									case "Admin":
										header("Location: settings.php");
									break;
									default:
										echo"<div id='askMsg'>";
											echo "<img src='img/warning.png' class='small' /> Oops !!! What type of user you are? !!!";
										echo"</div>";
								}
							}
							else
							{
							  echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> Oops !!! The username or password is incorrect !!!";
							  echo"</div>";
							}
						}
						else {
							if(isset($_SESSION['username'])) {
								// Redirect the user
								switch ($_SESSION["type"])
								{
									case "Cashier":
										header("Location: cash.php");
									break;
									case "Manager":
										header("Location: sales.php");
									break;
									case "Admin":
										header("Location: settings.php");
									break;
									default:
										echo"<div id='askMsg'>";
											echo "<img src='img/warning.png' class='small' /> Oops !!! What type of user you are? !!!";
										echo"</div>";
								}
							}
						}
				?>
			</div>
		</div>
		<div id="index_footer" class="centered">
			<p>
				Powered by <a href="http://www.akcess.rw" target="_blank" class="link">aKcess Rwanda Ltd</a>
			</p>
		</div>
		<script>
			function myFunction() {
				let x = document.querySelector("#pwdField");
				if (x.type === "password") {
					x.type = "text";
				} else {
					x.type = "password";
				}
			}
		</script>
    </body>
</html>