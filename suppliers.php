<?php
	include("functions.php");
	$_SESSION["page"] = "suppliers";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#supplier_name').autocomplete({
					serviceUrl: './selections/sel_supplier.php',
					dataType: 'json'
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#nom_supplier').autocomplete({
					serviceUrl: './selections/sel_supplier.php',
					dataType: 'json'
				});
			});
		</script>
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_supplier.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="entete">
				<div id="">
					<img src="img/suppliers.png" class="icon" />
					<span class="titre">Suppliers | </span>
					Search for supplier (Ctrl + F)
				</div>
			</div>
			<div id="sous_entete">
				<?php
							if(isset($_POST["save_modify"]))
							{
								$s = new Supplier();
								$res = $s->is_supplier_exist_except($_POST["name"], $_POST["old_name"]);
								if($res == true)
								{
									echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> ";
										echo "<b>Error !!!</b> The supplier <b>".$_POST["name"]."</b> already exists !!!";
									echo"</div>";
								} else {
									$bdd = connexionDb();
									$reponse = $bdd->prepare("UPDATE supplier SET nom = :n, adresse = :a, tel = :t, tin = :tin WHERE code = :c");
									$reponse->execute(array(
										'n' => $_POST["name"],
										'a' => $_POST["adresse"],
										't' => $_POST["tel"],
										'tin' => $_POST["tin"],
										'c' => $_POST["supplier_code"]
									));
									$reponse->closeCursor();
									echo"<div id='okMsg'>";
										echo "<img src='img/cool.png' class='small' /> ";
										echo "Saved !!!";
									echo"</div>";
								}
							}
					if(isset($_POST["modify"]))
					{
						$c = new supplier();
						$infos_supplier = $c->infosParCode($_POST["supplier_code"]);
						$supplier_name = $infos_supplier[0];
						$supplier_tel = $infos_supplier[1];
						$supplier_tin = $infos_supplier[2];
						$supplier_add = $infos_supplier[3];
						
							echo "<div id=''>";
								echo "<form action='' method='post' id='okMsg'>";
									echo "<table>";
										echo "<tr>";
											echo "<td>Supplier name: </td>";
											echo "<td>";
	echo "<input type='text' size='40' id='nom_supplier' name='name' value='".$supplier_name."' placeholder='supplier name' autocomplete='off' required />";
											echo "</td>";
											echo "<td>TIN: </td><td><input type='text' name='tin' value='".$supplier_tin."' placeholder='tin' autocomplete='off' required /></td>";
										echo "</tr>";
										echo "<tr>";
echo "<td>Address: </td><td><input type='text' size='40' name='adresse' value='".$supplier_add."' placeholder='Address' autocomplete='off' required /></td>";
											echo "<td>Phone num: </td><td><input type='text' name='tel' value='".$supplier_tel."' placeholder='Phone nr' autocomplete='off' required /></td>";
											echo "<td>";
												echo " <input type='hidden' name='supplier_code' value='".$_POST["supplier_code"]."' required />";
												echo " <input type='hidden' name='old_name' value='".$supplier_name."' required />";
												echo " <input type='submit' name='save_modify' value='Apply changes' class='btn' required />";
												echo " <a href='' class='link'>Cancel</a>";
											echo "</td>";
										echo "</tr>";
									echo "</table>";
								echo "</form>";
							echo "</div>";
					} else {
				?>
						<form action="" method="post">
							<table>
								<tr>
									<td>New supplier: </td>
									<td>
	<input type="text" size="40" id="nom_supplier" name="nom" placeholder="Supplier name" autocomplete="off" required />
									</td>
									<td>TIN: </td><td><input type="text" name="tin" placeholder="TIN" autocomplete="off" required /></td>
								</tr>
								<tr>
									<td>Address: </td><td><input type="text" size="40" name="adresse" placeholder="Address" autocomplete="off" required /></td>
									<td>Phone num: </td><td><input type="text" name="tel" placeholder="Phone nr" autocomplete="off" required /></td>
									<td>
										<input type="submit" name="save" value="Save" class="btn" required />
									</td>
								</tr>
							</table>
						</form>
				<?php
					}
				?>
				</div>
				<div id="interne">
					<?php
						if(isset($_POST["save"]))
						{
							$c = new supplier();
							$res = $c->is_supplier_exist($_POST["nom"]);
							if($res == true)
							{
								echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> ";
									echo "<b>Error !!!</b> The supplier <b>".$_POST["nom"]."</b> already exists";
								echo"</div>";
							}
							else{
								echo"<div id='okMsg'>";
									$c = new supplier();
									$c->add($_POST["nom"], $_POST["tin"], $_POST["adresse"], $_POST["tel"], $_POST["ass"]);
									echo "<img src='img/cool.png' class='small' /> ";
									echo "The supplier <b>".$_POST["nom"]."</b> saved !!!";
								echo"</div>";
							}
						}
						
						if(isset($_POST["delete_supplier"]))
						{
							echo"<div id='askMsg'>";
								echo"<form action='' method='post'>";
					echo "<img src='img/warning.png' class='small' /> <b>Warning</b>: Do you really want to delete <b>".$_POST["supplier_name"]."</b> !!! ";
									echo"<input type='hidden' name='supplier_name' value='".$_POST["supplier_name"]."' />";
									echo"<input type='hidden' name='supplier_code' value=".$_POST["supplier_code"]." />";
									echo"<input type='submit' name='ok_delete_supplier' value='Yes delete' />";
									echo " <a href='' class='link'>Cancel</a>";
								echo"</form>";
							echo"</div>";
						}
						
						if(isset($_POST["ok_delete_supplier"]))
						{
							echo"<div id='okMsg'>";
								$s = new Supplier();
								$s->delete_supplier($_POST["supplier_code"]);
								echo "<img src='img/cool.png' class='small' /> The supplier <b>".$_POST["supplier_name"]."</b> has been well deleted !!!";
							echo"</div>";
						}
						
						$nbr_supplier = nombre("supplier");
						if($nbr_supplier <= 0)
						{
							echo "<p>";
								echo "No supplier !!!";
							echo "</p>";
						}
						else{
							echo "<div class='product_by_letter'>";
								echo "<form action='' method='post'>";
									echo "<input type='submit' name='a' value='A' /> ";
									echo "<input type='submit' name='b' value='B' /> ";
									echo "<input type='submit' name='c' value='C' /> ";
									echo "<input type='submit' name='d' value='D' /> ";
									echo "<input type='submit' name='e' value='E' /> ";
									echo "<input type='submit' name='f' value='F' /> ";
									echo "<input type='submit' name='g' value='G' /> ";
									echo "<input type='submit' name='h' value='H' /> ";
									echo "<input type='submit' name='i' value='I' /> ";
									echo "<input type='submit' name='j' value='J' /> ";
									echo "<input type='submit' name='k' value='K' /> ";
									echo "<input type='submit' name='l' value='L' /> ";
									echo "<input type='submit' name='m' value='M' /> ";
									echo "<input type='submit' name='n' value='N' /> ";
									echo "<input type='submit' name='o' value='O' /> ";
									echo "<input type='submit' name='p' value='P' /> ";
									echo "<input type='submit' name='q' value='Q' /> ";
									echo "<input type='submit' name='r' value='R' /> ";
									echo "<input type='submit' name='s' value='S' /> ";
									echo "<input type='submit' name='t' value='T' /> ";
									echo "<input type='submit' name='u' value='U' /> ";
									echo "<input type='submit' name='v' value='V' /> ";
									echo "<input type='submit' name='w' value='W' /> ";
									echo "<input type='submit' name='x' value='X' /> ";
									echo "<input type='submit' name='y' value='Y' /> ";
									echo "<input type='submit' name='z' value='Z' /> ";
									echo "<a href='' class='btn'>All</a>";
								echo "</form>";
						echo "</div>";
							
							$one = false;
							$s = new Supplier();
							if(isset($_POST["a"])){
								$s->tous_by_letter("a");
								$one = true;
							}
							if(isset($_POST["b"])){
								$s->tous_by_letter("b");
								$one = true;
							}
							if(isset($_POST["c"])){
								$s->tous_by_letter("c");
								$one = true;
							}
							if(isset($_POST["d"])){
								$s->tous_by_letter("d");
								$one = true;
							}
							if(isset($_POST["e"])){
								$s->tous_by_letter("e");
								$one = true;
							}
							if(isset($_POST["f"])){
								$s->tous_by_letter("f");
								$one = true;
							}
							if(isset($_POST["g"])){
								$s->tous_by_letter("g");
								$one = true;
							}
							if(isset($_POST["h"])){
								$s->tous_by_letter("h");
								$one = true;
							}
							if(isset($_POST["i"])){
								$s->tous_by_letter("i");
								$one = true;
							}
							if(isset($_POST["j"])){
								$s->tous_by_letter("j");
								$one = true;
							}
							if(isset($_POST["k"])){
								$s->tous_by_letter("k");
								$one = true;
							}
							if(isset($_POST["l"])){
								$s->tous_by_letter("l");
								$one = true;
							}
							if(isset($_POST["m"])){
								$s->tous_by_letter("m");
								$one = true;
							}
							if(isset($_POST["n"])){
								$s->tous_by_letter("n");
								$one = true;
							}
							if(isset($_POST["o"])){
								$s->tous_by_letter("o");
								$one = true;
							}
							if(isset($_POST["p"])){
								$s->tous_by_letter("p");
								$one = true;
							}
							if(isset($_POST["q"])){
								$s->tous_by_letter("q");
								$one = true;
							}
							if(isset($_POST["r"])){
								$s->tous_by_letter("r");
								$one = true;
							}
							if(isset($_POST["s"])){
								$s->tous_by_letter("s");
								$one = true;
							}
							if(isset($_POST["t"])){
								$s->tous_by_letter("t");
								$one = true;
							}
							if(isset($_POST["u"])){
								$s->tous_by_letter("u");
								$one = true;
							}
							if(isset($_POST["v"])){
								$s->tous_by_letter("v");
								$one = true;
							}
							if(isset($_POST["w"])){
								$s->tous_by_letter("w");
								$one = true;
							}
							if(isset($_POST["x"])){
								$s->tous_by_letter("x");
								$one = true;
							}
							if(isset($_POST["y"])){
								$s->tous_by_letter("y");
								$one = true;
							}
							if(isset($_POST["z"])){
								$s->tous_by_letter("z");
								$one = true;
							}
							if(isset($_POST["all"])){
								$s->tous();
								$one = true;
							}
							
							if($one == false)
							{
								// By default
								$s = new Supplier();
								$s->tous();
							}
					}
				?>
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