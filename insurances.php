<?php
	include("functions.php");
	$_SESSION["page"] = "insu";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Medsine - Insurances</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_insu.php");
				require("./classes/class_commande.php");
				require("./classes/class_facture_insurance.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="entete">
				<img src="img/insu.png" class="icon" />
				<span class="titre">Insurances</span>
			</div>
			<div id="sous_entete">
				<?php
					// Add new insurance
					if(isset($_POST["save"]))
					{
						$i = new Insurance();
						$res = $i->is_insu_name_exist($_POST["insu_name"]);
						if($res == true)
						{
							echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The insurance named <b>".$_POST["insu_name"]."</b> already exists !!!";
							echo"</div>";
						}
						else{
							echo"<div id='okMsg'>";
								$i = new Insurance();
								$i->add($_POST["insu_name"], $_POST["add"], $_POST["phone"], $_POST["email"]);
				echo "<img src='img/cool.png' class='small' /> The insurance <b>".$_POST["insu_name"]."</b> has been well saved !!!";
							echo"</div>";
						}
					}

					// Save update
					if(isset($_POST["save_modify"]))
					{
						$i = new Insurance();
						$res = $i->is_insu_name_exist_except($_POST["new_insu_name"], $_POST["insu_name"]);
						if($res == true)
						{
							echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The insu_name <b>".$_POST["new_insu_name"]."</b> is already taken !!!";
							echo"</div>";
						}
						else{
							echo"<div id='okMsg'>";
		$i = new Insurance();
		$res = $i->update_insu($_POST["new_insu_name"], $_POST["add"], $_POST["phone"], $_POST["email"], $_POST["insu_code"]);
								echo "<img src='img/cool.png' class='small' /> Saved !!!";
							echo"</div>";
						}
					}

					// Update insurance infos
					if(isset($_POST["modify_insu"]))
					{
							$i = new Insurance();
							$infos_insu = $i->infos_insu($_POST["insu_code"]);
							$name = $infos_insu[0];
							$add = $infos_insu[1];
							$phone = $infos_insu[2];
							$email = $infos_insu[3];
									echo "<form action='' method='post' id='okMsg'>";
										echo "<table>";
											echo "<tr>";
												echo "<td>Insurance name: </td>";
												echo "<td>";
				echo "<input type='text' name='new_insu_name' value='".$name."' placeholder='Insurance name' required />";
												echo "</td>";
											echo "</tr>";
											echo "<tr>";
												echo "<td>Contact: </td>";
												echo "<td>";
				echo "<input type='text' name='phone' value='".$phone."' placeholder='Phone num' />";
												echo "</td>";
												echo "<td>Address: </td>";
												echo "<td>";
				echo "<input type='text' name='add' value='".$add."' placeholder='Address' />";
												echo "</td>";
												echo "<td>Email: </td>";
												echo "<td>";
				echo "<input type='email' name='email' value='".$email."' placeholder='Email' />";
												echo "</td>";
												echo "<td>";
													echo " <input type='hidden' name='insu_code' value='".$_POST["insu_code"]."' required />";
													echo " <input type='hidden' name='insu_name' value='".$_POST["insu_name"]."' required />";
													echo " <input type='submit' name='save_modify' value='Apply changes' class='btn' required />";
													echo " <a href='' class='link'>Cancel</a>";
												echo "</td>";
											echo "</tr>";
										echo "</table>";
									echo "</form>";
					} else {
				?>
					<div id="">
						<form action="" method="post">
							<table>
								<tr>
									<td>New insurance: </td><td><input type="text" name="insu_name" placeholder="Insurance name" required /></td>
								</tr>
								<tr>
									<td>Contact: </td><td><input type="text" name="phone" placeholder="Phone num" /></td>
									<td>Address: </td><td><input type="text" name="add" placeholder="Address" /></td>
									<td>Email: </td><td><input type="email" name="email" placeholder="Email" /></td>
									<td>
										<input type="submit" name="save" value="Save" class="btn" />
									</td>
								</tr>
							</table>
						</form>
					</div>
				<?php
					}
				?>
				</div>
				<div id="interne">
					<?php	
						if(isset($_POST["ok_delete_insu"]))
						{
							echo"<div id='okMsg'>";
								$i = new Insurance();
								$i->del_insu($_POST["insu_code"]);
								echo "<img src='img/cool.png' class='small' /> The insurance <b>".$_POST["insu_name"]."</b> has been well deleted !!!";
							echo"</div>";
						}
						
						if(isset($_POST["delete_insu"]))
						{
								echo"<div id='askMsg'>";
									echo"<form action='' method='post'>";
		echo "<img src='img/warning.png' class='small' /> <b>Warning</b>: Do you really want to delete <b>".$_POST["insu_name"]."</b> !!! ";
										echo"<input type='hidden' name='insu_name' value='".$_POST["insu_name"]."' />";
										echo"<input type='hidden' name='insu_code' value='".$_POST["insu_code"]."' />";
										echo"<input type='submit' name='ok_delete_insu' value='Yes delete' class='small_btn' /> <a href='' class='link'>Cancel</a>";
									echo"</form>";
								echo"</div>";
						}
						
						if(nombre("insu") == 0)
						{
							echo "<p>";
								echo "<img src='img/warning.png' class='small small_down' /> ";
								echo "No insurance !!!";
							echo "</p>";
						}
						else{
							if(isset($_POST["verify"])) {
								if($_POST["rate"] <= 0 || $_POST["rate"] > 100) {
									echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid rate <b>".$_POST["rate"]."%</b> !!! Please respect the rate range:  [1, 100]";
									echo"</div>";
								} else {
									header("Location: by_insurance.php?insu_name=".$_POST["insu_name"]."&rate=".$_POST["rate"]."");
								}
							}

							if(isset($_POST["sell"])) {
								echo "<p>";
									echo "<form action='' method='POST'>";
										echo "<b>Insurance name: </b>";
										echo "<input type='text' value='".$_POST["insu_name"]."' name='insu_name' readonly>";
										echo "<input type='number' name='rate' placeholder='Rate in %' required>";
										echo "<input type='submit' value='Proceed' name='verify' class='small_btn'>";
										echo " <a href='' class='link'>Cancel</a>";
									echo "</form>";
								echo "</p>";
							}
							$i = new Insurance();
							$i->tous();

							if(isset($_GET["insu_id"])) {
								echo "<div id='show'>";
									$insu = new Insurance();
									$res = $insu->is_insu_code_exist($_GET["insu_id"]);
									if($res == false) {
										echo"<div id='askMsg'>";
					echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The insurance code <b>".$_GET["insu_id"]."</b> does NOT exist !!!";
										echo"</div>";
									} else {
										echo "<p>";
											$insu = new Insurance();
											$insu_infos = $insu->infos_insu($_GET["insu_id"]);
											$insu_name = $insu_infos[0];
											echo "<span class='titre'>".$insu_name."</span>";
										echo "</p>";
										$fact = new Facture_insurance();
										$fact->tous_insu_by_insurance($_GET["insu_id"]);
									}
								echo "</div>";
							}
						}
					?>
			</div>
		</div>
		<?php
			} else {
				header('Location: interdit.php');
			}
		?>
    </body>
</html>
