<?php
	include("functions.php");
	$_SESSION["page"] = "settings";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Medsine - Users</title>
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
			<?php
				if($_SESSION["type"] == "Manager" || $_SESSION["type"] == "Admin") {
			?>
			<div id="entete">
				<img src="img/users.jpg" class="icon" />
				<span class="titre">Users</span>
				<?php
					echo "<a href='settings.php' class='btn right'>";
						echo "<img src='img/settings.png' class='icon_in_menu' alt=''>";
						echo "Settings";
					echo "</a>";
				?>
			</div>
			<div id="sous_entete">
				<?php
					// Save a new user
					if(isset($_POST["save"]))
					{
						// Check if the password has 4 characters min
						if(strlen(trim($_POST["password"])) < 6) {
							echo"<div id='askMsg'>";
						echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Password must have at least 6 characters, and no whitespaces !!!";
							echo"</div>";
						}
						else {
								$u = new User();
								$res = $u->is_username_exist($_POST["username"]);
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
									echo"<div id='okMsg'>";
										$u = new User();
										$u->add($_POST["nom"], $_POST["prenom"], $_POST["username"], $_POST["password"], $_POST["type"]);
						echo "<img src='img/cool.png' class='small' /> The user <b>".$_POST["username"]."</b> has been well saved !!!";
									echo"</div>";
								}
						}
					}
					
					// Save infos update
					if(isset($_POST["save_modify"]))
					{
						$ok = true;
						if($_POST["type"] != "Manager")
						{
							$redirect = false;
							if($_POST["username"] === $_SESSION["username"])
							{
								$redirect = true;
								$u = new User();
								$nbr_manager = $u->nbr_manager();
								if($nbr_manager === 1)
								{
									$ok = false;
								}
							}
						}

						if($ok === false) {
							echo"<div id='askMsg'>";
						echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> It has to be at least one manager !!!";
							echo"</div>";
						}
						else {
							// Check if the password has 6 characters min
							if(strlen(trim($_POST["password"])) < 6 AND $_POST["password"] != "") {
								echo"<div id='askMsg'>";
							echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Password must have at least 6 characters, and no whitespaces !!!";
								echo"</div>";
							}
							else {
									$u = new User();
									$res = $u->is_username_exist_except($_POST["new_username"], $_POST["username"]);
									if($res == true)
									{
										echo"<div id='askMsg'>";
											echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The username <b>".$_POST["new_username"]."</b> is already taken !!!";
										echo"</div>";
									}
									else {
					$u = new User();
					if($_POST["password"] == "") {
						$res = $u->update_user_noPwd($_POST["new_username"], $_POST["nom"], $_POST["prenom"], $_POST["type"], $_POST["username"]);
					} else {
						$res = $u->update_user($_POST["new_username"], sha1($_POST["password"]), $_POST["nom"], $_POST["prenom"], $_POST["type"], $_POST["username"]);
					}
										if($redirect === true) {
											$_SESSION["type"] = $_POST["type"];
											header('Location: settings.php');
										}
										else {
											// Refresh the page
											header('Location: users.php');
										}
									}
							}
						}
					}

					// Update user's infos
					if(isset($_POST["modify_user"]))
					{
						$u = new User();
						$infos_user = $u->infos_user($_POST["username"]);
						$nom = $infos_user[1];
						$prenom = $infos_user[2];
						$type = $infos_user[3];
							echo "<div id='form'>";
								echo "<form action='' method='post'>";
									echo "<table>";
										echo "<tr>";
											echo "<td>First name: </td>";
											echo "<td>";
			echo "<input type='text' name='prenom' value='".$prenom."' placeholder='First name' required />";
											echo "</td>";
											echo "<td>Last name: </td>";
											echo "<td>";
			echo "<input type='text' name='nom' value='".$nom."' placeholder='Last name' required />";
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>User name: </td>";
											echo "<td>";
			echo "<input type='text' name='new_username' value='".$_POST["username"]."' placeholder='Username' required />";
											echo "</td>";
											echo "<td>Password: </td>";
											echo "<td>";
			echo "<input type='password' name='password' placeholder='Password' />";
											echo "</td>";
											echo "<td>Type: </td>";
											echo "<td>";
												echo "<select name='type'>";
												?>
			<option value="Cashier" <?php if($type == "Cashier"){echo "selected";}?>>Cashier</option>
			<option value="Manager" <?php if($type == "Manager"){echo "selected";}?>>Manager</option>
												<?php
												echo "</select>";
												echo " <input type='hidden' name='username' value='".$_POST["username"]."' required />";
												echo " <input type='submit' name='save_modify' value='Apply' class='btn' required />";
												echo " <a href='' class='link'>Cancel</a>";
											echo "</td>";
										echo "</tr>";
									echo "</table>";
								echo "</form>";
							echo "</div>";
					}

					// Update user's infos
					if(!isset($_POST["modify_user"]))
					{
				?>
				<div id="form">
					<form action="" method="post">
						<table>
							<tr>
								<td>First name: </td><td><input type="text" name="prenom" placeholder="First name" required /></td>
								<td>Last name: </td><td><input type="text" name="nom" placeholder="Last name" required /></td>
							</tr>
							<tr>
								<td>User name: </td><td><input type="text" name="username" placeholder="User name" required /></td>
								<td>Password: </td><td><input type="password" name="password" placeholder="Password" required /></td>
								<td>Type: </td>
								<td>
									<select name="type" required>
										<option value=""></option>
										<option value="Cashier">Cashier</option>
										<option value="Manager">Manager</option>
									</select>
									<input type="submit" name="save" value="Save" class="btn" required />
								</td>
							</tr>
						</table>
					</form>
				</div>
				<?php
					}?>
			</div>
			<div id="interne">
				<?php
					// Confirm deletion
					if(isset($_POST["ok_delete_user"]))
					{
						echo"<div id='okMsg'>";
							$u = new User();
							$u->deleteUser($_POST["username"]);
							echo "<img src='img/cool.png' class='small' /> The user <b>".$_POST["nom"]." ".$_POST["prenom"]."</b> has been well deleted !!!";
						echo"</div>";
					}
					
					// Delete a user
					if(isset($_POST["delete_user"]))
					{
						echo"<div id='askMsg'>";
							echo"<form action='' method='post'>";
	echo "<img src='img/warning.png' class='small' /> Do you really want to delete <b>".$_POST["prenom"]." ".$_POST["nom"]."</b> (".$_POST["type"].") ? ";
								echo"<input type='hidden' name='username' value='".$_POST["username"]."' />";
								echo"<input type='hidden' name='nom' value='".$_POST["nom"]."' />";
								echo"<input type='hidden' name='prenom' value='".$_POST["prenom"]."' />";
								echo"<input type='submit' name='ok_delete_user' class='small_btn other_red_btn' value='Yes delete' /> <a href='' class='link'>Cancel</a>";
							echo"</form>";
						echo"</div>";
					}
				?>
				<?php
					$u = new User();
					$u->tous();
				?>
			</div>
			<?php
				} else {
					echo "<div id='other_interne'>";
						echo"<div id='askMsg'>";
							echo "<img src='img/warning.png' class='small' /> Oops !!! You have no right to access this information !!!";
						echo"</div>";
					echo"</div>";
				}
			?>
		</div>
		<?php
			}
			else{
				header('Location: interdit.php');
			}
		?>
    </body>
</html>