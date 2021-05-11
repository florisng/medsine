<?php
	include("functions.php");
	$_SESSION["page"] = "settings";
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
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="other_entete">
				<img src="img/settings.png" class="icon hidden" />
				<span class="titre">Settings</span>
				<?php
					if($_SESSION["type"] == "Manager" || $_SESSION["type"] == "Admin") {
						echo "<a href='users.php' class='btn right'>";
							echo "<img src='img/users.jpg' class='icon_in_menu' alt=''>";
							echo "Users";
						echo "</a>";
					}
				?>
			</div>
			<div id="other_interne">
				<div id="my_account">
					<fieldset>
						<legend>My account</legend>
						<?php
							// Update user's infos
							if(isset($_POST["save_account_changes"]))
							{
								$u = new User();
								$res = $u->is_username_exist_except($_POST["username"], $_SESSION["username"]);
								if($res == true)
								{
									echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> ";
										if($_POST["username"] == "admin") {
											echo "Failed in creating user !!!";
										} else {
											echo "The username <b>".$_POST["username"]."</b> is already taken by another user !!!";
										}
									echo"</div>";
								}
								else{
									if(strlen(trim($_POST["password"])) < 6) {
										echo"<div id='askMsg'>";
							echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Password must have at least 6 characters, and no whitespaces !!!";
										echo"</div>";
									}
									else {
										$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE user SET username = :u, password = :p, nom = :n, prenom = :pr WHERE username = :us");
										$reponse->execute(array(
											'u' => $_POST["username"],
											'p' => sha1($_POST["password"]),
											'n' => $_POST["nom"],
											'pr' => $_POST["prenom"],
											'us' => $_SESSION["username"]
										));
										$reponse->closeCursor();
	
										// Update the username
										$_SESSION["username"] = $_POST["username"];

										// Redirect
										header('Location: settings.php');
									}
								}
							}
							
							// Retrieve user's data
							$bdd = connexionDb();
							$rep = $bdd->query("SELECT * FROM user WHERE username = '".$_SESSION["username"]."'");
							while($data = $rep->fetch())
							{
								$username = $data["username"];
								$nom = $data["nom"];
								$prenom = $data["prenom"];
								$type = $data["type"];
							}
							$rep->closeCursor();
						?>
						<div id="form">
							<form action="#my_account" method="post">
								<table>
									<tr>
										<td>First name: </td>
										<td>
	<input type="text" name="prenom" value="<?php echo $prenom; ?>" placeholder="First name" autocomplete="off" required />
										</td>
										<td>Last name: </td>
										<td>
	<input type="text" name="nom" value="<?php echo $nom; ?>" placeholder="Last name" autocomplete="off" required />
										</td>
									</tr>
									<tr>
										<td>Username: </td>
										<td>
	<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username" autocomplete="off" required />
										</td>
										<td>Password: </td>
										<td>
	<input type="password" name="password" placeholder="Password" autocomplete="off" required />
										</td>
										<td>Type: </td>
										<td><?php echo $type; ?></td>
										<td>
											<input type="submit" name="save_account_changes" value="Save" class="btn" required />
										</td>
									</tr>
								</table>
							</form>
						</div>
					</fieldset>
				</div>
				<?php						
					if($_SESSION["type"] == "Manager" || $_SESSION["type"] == "Admin") {
				?>
				<div id="compamy_info">
					<fieldset>
						<legend>Company Information</legend>
						<?php						
								if(isset($_POST["save"])) {
									echo"<div id='okMsg'>";
										if(nombre("company") == 0){
											$bdd = connexionDb();
											$req = $bdd->prepare('INSERT INTO company (nom, tin, address, tel, email) VALUES (?, ?, ?, ?, ?)');
						$req->execute(array($_POST["name"], $_POST["tin"], $_POST["address"], $_POST["tel"], $_POST["email"]));
											$req->closeCursor();
										}
										else{
											$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE company SET nom = :n, tin = :ni, address = :ad, tel = :t, email = :e WHERE id = :i");
											$reponse->execute(array(
												'n' => $_POST["name"],
												'ni' => $_POST["tin"],
												'ad' => $_POST["address"],
												't' => $_POST["tel"],
												'e' => $_POST["email"],
												'i' => 1
											));
											$reponse->closeCursor();
										}
										echo "<img src='img/cool.png' class='small' /> Your company information were successfuly saved !!!";
									echo"</div>";
								}
								
								$name = $tin = $address = $tel = $email = null;
								
								$bdd = connexionDb();
								$rep = $bdd->query("SELECT * FROM company");
								while($data = $rep->fetch())
								{
									$name = $data["nom"];
									$tin = $data["tin"];
									$address = $data["address"];
									$tel = $data["tel"];
									$email = $data["email"];
								}
								$rep->closeCursor();
						?>
						<div id="form">
							<form action="#compamy_info" method="post">
								<table>
									<tr>
										<td>Company name: </td>
										<td>
	<input type="text" name="name" value="<?php echo $name; ?>" placeholder="Company name" autocomplete="off" required />
										</td>
										<td>TIN: </td>
	<td><input type="text" name="tin" placeholder="tin" value="<?php echo $tin; ?>" autocomplete="off" required /></td>
									</tr>
									<tr>
										<td>Address: </td>
	<td><input type="text" name="address" value="<?php echo $address; ?>" placeholder="Address" autocomplete="off" required /></td>
										<td>Tel: </td>
	<td><input type="text" name="tel" placeholder="Phone nr" value="<?php echo $tel; ?>" autocomplete="off" required /></td>
										<td>E-mail: </td>
	<td><input type="email" name="email" value="<?php echo $email; ?>" placeholder="E-mail" autocomplete="off" required /></td>
										<td>
										<td>
											<input type="submit" name="save" value="Save" class="btn" required />
										</td>
									</tr>
								</table>
							</form>
						</div>
					</fieldset>
				</div>
				<?php
					}
				?>
				<fieldset>
					<legend>Information about Medsine</legend>
					<div>
						<form action="" method="post">
							<?php
								echo "<div class='right'>";						
									if($_SESSION["type"] == "Admin") {
										$bdd = connexionDb();
										$rep = $bdd->query("SELECT * FROM company");
										$exp_date = date("Y-m-d");;
										while($data = $rep->fetch())
										{
											$exp_date = $data["exp_date"];
										}
										$rep->closeCursor();
										echo "Due Date: <input type='date' name='due_date' value=".$exp_date." required>";
										echo "<input type='submit' name='save_due_date' value='Save'>";
									}
									echo "<br /><img src='img/logo.png' class='logo' />";
								echo "</div>";
							?>
							<table>
								<tr>
									<td>Software</td>
									<td>Medsine</td>
								</tr>
								<tr>
									<td>Version</td>
									<td>1.0</td>
								</tr>
								<tr>
									<td>Powered by: </td>
									<td>Akcess Rwanda Ltd</td>
								</tr>
								<tr>
									<td>Phone number: </td>
									<td>
			<a href="whatsapp://send?text=Hello Medsine team, ...&phone=+250787030024" target="_blank" class="link">+250 787030024</a>
			<img alt="" src="img/whtsp.jpg" class="whatsapp"> (WhatsApp)
									</td>
								</tr>
								<tr>
									<td>E-mail: </td>
									<td><a href="mailto:info@akcess.rw" class="link">info@akcess.rw</a></td>
								</tr>
								<tr>
									<td></td>
									<td><a href="http://www.akcess.rw" class="link" target="_blank">http://www.akcess.rw</a></td>
								</tr>
							</table>
						</form>
					</div>
				</fieldset>
			</div>
		</div>
		<?php
			}
			else{
				header('Location: interdit.php');
			}
		?>
    </body>
</html>