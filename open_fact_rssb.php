<?php
	require("functions.php");
	$_SESSION["page"] = "invoices";
?>
<!DOCTYPE html>
<html>
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_user.php");
				require("./classes/class_product.php");
				require("./classes/class_shelf.php");
				require("./classes/class_commande.php");
				require("./classes/class_facture_rssb.php");
		?>
			<div id="welcome">
				<div id="">
					<div class="options_div hidden">
						<div class="options_div_one">
							<a href='invoices_rssb.php' class='blue_btn_rssb left'>
								<img src="./img/back.png" class="icon_in_menu" alt="">
								Invoices
							</a>
						</div>
						<div class="options_div_two">
							<a href='rssb.php' class='yellow_btn_rssb right'>
								<img src="./img/back.png" class="icon_in_menu" alt="">
								New invoice
							</a>
						</div>
					</div>
					<?php
						if(isset($_GET["num_fact"])) {
							echo "<div class='hidden'>";
								if(isset($_POST["save_footer"])) {
									echo"<div id='okMsg'>";
										$bdd = connexionDb();
										$reponse = $bdd->prepare("UPDATE facture_rssb SET receiver = :r, id_number = :i, place = :p WHERE numero = :n");
										$reponse->execute(array(
											'r' => $_POST["receiver"],
											'i' => $_POST["id_number"],
											'p' => $_POST["place"],
											'n' => $_GET["num_fact"]
										));
										$reponse->closeCursor();
										echo "<img src='img/cool.png' class='small' /> ";
										echo " Changes well saved !!!";
									echo"</div>";
								}

								if(isset($_POST["save_affi"])) {
									echo"<div id='okMsg'>";
										$bdd = connexionDb();
										$reponse = $bdd->prepare("UPDATE facture_rssb SET card_number = :c, affi_name = :an, affected = :aff, station = :st, bene_name = :b, relation = :r, sex = :s, age = :a, hospital = :h, doctor = :d WHERE numero = :n");
										$reponse->execute(array(
											'c' => $_POST["card_number"],
											'an' => $_POST["affi_name"],
											'aff' => $_POST["affected"],
											'st' => $_POST["station"],
											'b' => $_POST["bene_name"],
											'r' => $_POST["relation"],
											's' => $_POST["sex"],
											'a' => $_POST["age"],
											'h' => $_POST["hospital"],
											'd' => $_POST["doctor"],
											'n' => $_GET["num_fact"]
										));
										$reponse->closeCursor();
										echo "<img src='img/cool.png' class='small' /> ";
										echo " Changes well saved !!!";
									echo"</div>";
								}
							echo"</div>";
							
							$fact = new Facture_rssb();
							$res = $fact->is_num_fact_exist($_GET["num_fact"]);
							if($res == true) {
								$num_fact = $_GET["num_fact"];
								$fact = new Facture_rssb();
								$fact_infos = $fact->infos_fact_rssb($num_fact);
								$card_number = $fact_infos[1];
								$affi_name = $fact_infos[2];
								$affected = $fact_infos[3];
								$station = $fact_infos[4];
								$bene_name = $fact_infos[5];
								$relation = $fact_infos[6];
								$sex = $fact_infos[7];
								$age = $fact_infos[8];
								$receiver = $fact_infos[9];
								$id_number = $fact_infos[10];
								$place = $fact_infos[11];
								$date = $fact_infos[12];
								$user = $fact_infos[13];
								$status = $fact_infos[14];
								$time = $fact_infos[15];
								$hospital = $fact_infos[16];
								$doctor = $fact_infos[17];
								$prescri = $fact_infos[18];
								$pay_mode = $fact_infos[19];
					?>
					<div class="rssb_entete">
						<div class="rssb_entete_intro">
							<img src="./img/rssb.png" class="rssb_logo" />
							<span class="subtitle">Rwanda Social Security Board</span>
							<p>
								B.P.: 6655 Kigali
							</p>
							<b>FACTURE DES MEDICAMENTS FOURNIS N°:</b> <?php echo $num_fact; ?>
							<?php echo "<span class='right'><b>Prescription blank: </b>".$prescri."</span>"; ?>
						</div>
						<div class="rssb_entete_pharma">
								<?php
									$bdd = connexionDb();
									$rep = $bdd->query("SELECT * FROM company");
									while($data = $rep->fetch())
									{
										$name = $data["nom"];
										$add = $data["address"];
										$tel = $data["tel"];
									}
									$rep->closeCursor();
									echo "<span class='subtitle'>".$name."</span>";
									echo "<hr>";
									echo "<table>";
										echo "<tr>";
											echo "<td><b>Address:</b></td>";
											echo "<td>".$add."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td><b>Phone num:</b></td>";
											echo "<td>".$tel."</td>";
										echo "</tr>";
									echo "</table>";
								?>
						</div>
					</div>
					<div id="affiliate_form">
						<form action="" method="POST" class="affiliate_form">
							<div class="affi_form">
								<span class="subtitle">Affiliate</span>
								<hr>
								<table>
									<tr>
										<td><label for="aff_num">Card Nr:</label></td>
										<td><input type="text" name="card_number" id="aff_num" placeholder="Affiliate number" value="<?php echo $card_number; ?>" required></td>
									</tr>
									<tr>
										<td><label for="affiliate">Name:</label></td>
										<td><input type="text" name="affi_name" id="affiliate" placeholder="Name of the affiliate" value="<?php echo $affi_name; ?>" required></td>
									</tr>
									<tr>
										<td><label for="aff_dep">Affected:</label></td>
										<td><input type="text" name="affected" id="aff_dep" placeholder="Affected Departure" value="<?php echo $affected; ?>" required></td>
									</tr>
									<tr>
										<td><label for="station">Station:</label></td>
										<td><input type="text" name="station" id="station" placeholder="Duty station" value="<?php echo $station; ?>" required></td>
									</tr>
								</table>
							</div>
							<div class="bene_form">
								<span class="subtitle">Beneficiary</span>
								<hr>
								<table>
									<tr>
										<td><label for="bene_name">Full name:</label></td>
										<td><input type="text" name="bene_name" id="bene_name" placeholder="Beneficiary names" value="<?php echo $bene_name; ?>" required></td>
									</tr>
									<tr>
										<td><label for="relation">Relationship:</label></td>
										<td>
											<select name="relation" id="relation" required>
												<option value="Spouce" value="<?php if($relation == "Spouce"){echo "selected";} ?>">Spouce</option>
												<option value="Children" value="<?php if($relation == "Children"){echo "selected";} ?>">Children</option>
												<option value="Parent" value="<?php if($relation == "Parent"){echo "selected";} ?>">Parent</option>
											</select>
										</td>
									</tr>
									<tr>
										<td><label for="sex">Sex:</label></td>
										<td>
											<select name="sex" id="sex" required>
												<option value="Female" value="<?php if($sex == "Female"){echo "selected";} ?>">Female</option>
												<option value="Male" value="<?php if($sex == "Male"){echo "selected";} ?>">Male</option>
											</select>
										</td>
									</tr>
									<tr>
										<td><label for="age">Age:</label></td>
										<td><input type="number" name="age" id="age" placeholder="Age of beneficiary" value="<?php echo $age; ?>" required></td>
									</tr>
								</table>
							</div>
							<div class="doctor">
								<span class="subtitle">Other</span>
								<hr>
								<table>
									<tr>
										<td><label for="hospital">Hospital:</label></td>
										<td><input type="text" name="hospital" id="hospital" placeholder="Hospital name" value="<?php echo $hospital; ?>" required></td>
									</tr>
									<tr>
										<td><label for="doctor">Doctor:</label></td>
										<td><input type="text" name="doctor" id="doctor" placeholder="Doctor names" value="<?php echo $doctor; ?>" required></td>
									</tr>
									<tr class="hidden">
										<td></td>
										<td><input type="submit" value="Apply changes" name="save_affi" class="left"></td>
									</tr>
								</table>
							</div>
						</form>
					</div>
					<div class="produit_fournis" id="display">
						<?php
							if(isset($_POST["add_product"])) {
								$p = new Product();
								$res = $p->is_product_exist($_POST["product_name"]);
								if($res == false)
								{
									echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> ";
										echo "<b>Error !!!</b> The product <b>".$_POST["product_name"]."</b> does NOT exist !!!";
									echo"</div>";
								}
								else{
									if($_POST["quantity"] <= 0)
									{
										echo"<div id='askMsg'>";
											echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["quantity"]."</b>: Invalid quantity !!!";
										echo"</div>";
									}
									else{
											$p = new Product();
											$infos_pro = $p->infosParNom($_POST["product_name"]);
											$product_code = $infos_pro[0];
											$unite = $infos_pro[1];
											
											$s = new Shelf();
											$quantity = $s->get_product_quantity_shelf($product_code);
											$c = new Commande();
											$total_quantity_tempo = $c->total_quantity_rssb_tempo($product_code) + $_POST["quantity"];
											if($quantity < $total_quantity_tempo){
												$q = $quantity - $c->total_quantity_rssb_tempo($product_code);
												echo"<div id='askMsg' class='hidden'>";
		echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["product_name"]."</b> - Insufficient quantity !!! <b>".number_format($q)." ".$unite."</b> left.";
												echo"</div>";
											}
											else{
		$p = new Product();
		$infos_pro = $p->infosParNom($_POST["product_name"]);
		$product_code = $infos_pro[0];
		$price = $infos_pro[3];

		// Get the old batch number (expire soon)
		$s = new Shelf();
		$batch_num = $s->get_young_batch($product_code);
		
													$total = $price * $_POST["quantity"];
													$credit = ($total * 95) / 100;
													$cash = $total - $credit;

													$bdd = connexionDb();
													$req = $bdd->prepare("INSERT INTO commande (num_fact, product_code, product_name, quantity, price, total, credit, cash, user, date, status, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
													$req->execute(array($_GET["num_fact"], $product_code, $_POST["product_name"], $_POST["quantity"], $price, $total, $credit, $cash, $_SESSION["username"], $date, $status, "rssb"));
													$req->closeCursor();
											}
									}
								}
							}

							// Delete a product in tempo_rssb
							if(isset($_POST["del"]))
							{
								echo "<form action='#display' method='POST' id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> Confirm ";
									echo "<input type='hidden' name='id_com' value=".$_POST["id_com"].">";
									echo "<input type='submit' name='confirm_del_com' value='Yes delete' class='small_btn'>";
									echo "<a href='' class='link'>Cancel</a>";
								echo "</form>";
							}

							if(isset($_POST["confirm_del_com"]))
							{
								$bdd = connexionDb();
								$bdd->query("DELETE FROM commande WHERE id = ".$_POST["id_com"]."");
							}
						?>
								<table>
									<tr>
										<th></th>
										<th>Product Code</th>
										<th>Produits</th>
										<th>Quantity</th>
										<th>Generic. Price</th>
										<th>Specialty. Price</th>
										<th>Total</th>
									</tr>
									<tr>
										<?php
											$c = new Commande();
											$infos_commande_rssb = $c->commande_rssb($num_fact);
											$i = $infos_commande_rssb[0] + 1;
											$total_commande_rssb = $infos_commande_rssb[1];
											$total_adherent = ($total_commande_rssb * 15) / 100;
											$total_rssb = $total_commande_rssb - $total_adherent;
										?>
									</tr>
									<tr class="hidden">
	<form action="#display" method="post">
										<td><?php echo $i; ?></td>
										<td></td>
										<td>
		<input type="text" name="product_name" id="pro" size="40" title="Enter a product." placeholder=" Product name" autocomplete="off" required />
										</td>
										<td><input type="number" name="quantity" placeholder="Quantity" required></td>
										<td colspan="3"></td>
										<td><input type="submit" value="Add product" name="add_product"></td>
	</form>
									</tr>
									<tr>
										<td colspan="6"></td>
										<td>
											<table>
												<tr>
													<td>Total (100%)</td>
													<td>: <?php echo number_format($total_commande_rssb); ?></td>
												</tr>
												<tr>
													<td>Adhérent (15%)</td>
													<td>: <?php echo number_format($total_adherent); ?></td>
												</tr>
												<tr>
													<td>RSSB (85%)</td>
													<td>: <?php echo number_format($total_rssb); ?></td>
												</tr>
												<tr>
													<td><b>Pay method</b></td>
													<td>: <?php echo $pay_mode; ?></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
					</div>
					<div id="rssb_footer">
							<form action="" method="post" class="rssb_footer">
								<div class="rssb_footer_one">
									<p>
										<b>RSSB VISA</b>
									</p>
									<hr>
									<p>
										Date & Signature
									</p>
									<p class="smallText">
										<b>Specialty</b> ifite <b>Generic</b>, iyo umurwayi ahisemo gufata <b>Specialty</b> mu mwanya wa <b>Generic</b>, RSSB yishyura ikurikije igiciro ca <b>Generic</b> gusa.
									</p>
								</div>
								<div  class="rssb_footer_two">
									<p>
										<b>Name - Signature - Stamp</b>
									</p>
									<hr>
									<p>
										<?php
											$bdd = connexionDb();
											$rep = $bdd->query("SELECT * FROM company");
											while($data = $rep->fetch())
											{
												$name = $data["nom"];
												$tel = $data["tel"];
											}
											$rep->closeCursor();
											echo "<b>".$name."</b> - Phone: ".$tel;
										?>
									</p>
									<p>
										<?php
											echo "<b>Operator: </b>".$user;
										?>
									</p>
								</div>
								<div  class="rssb_footer_three">
									<p>
										<b>For reception</b>
									</p>
									<hr>
									<p>
										<table>
											<tr>
			<td><label for="nom">Name:</label></td><td><input type="text" name="receiver" id="nom" placeholder="Full name" value="<?php echo $receiver; ?>" required></td>
											</tr>
											<tr>
								<td><label for="cni">ID Number:</label></td><td><input type="text" name="id_number" id="cni" placeholder="ID Card Number" value="<?php echo $id_number; ?>" required></td>
											</tr>
											<tr>
								<td><label for="lieu">Delivered place:</label></td><td><input type="text" name="place" id="lieu" placeholder="Lieu de délivrance" value="<?php echo $place; ?>" required></td>
											</tr>
											<tr>
												<td>Date: </td><td><?php echo dateEn($date)." - ".$time; ?></td>
											</tr>
										</table>
									</p>
									<hr>
									<p>
										Signature
									</p>
									<p class="hidden">
										<input type="submit" value="Apply changes" name="save_footer" class="right">
									</p>
								</div>
							</form>
					</div>
					<div class="options_div hidden">
						<div class="options_div_one">
							<a href='javascript:window.print()' class='left'>
								<img src="./img/print.jpg" class="print" alt="">
							</a>
						</div>
						<div class="options_div_two">
							<span class='open_hiden_div_btn red_btn_rssb right' onclick="show_div()">
								<img src="./img/non_valid.png" class="icon_in_menu" alt="">
								Delete the invoice
							</span>
							<?php
								echo "<div id='hidden_div' class='right hidden'>";
									echo "<form action='invoices_rssb.php' method='post' id='askMsg'>";
										echo "<img src='./img/warning.png' class='small'> Do you really want to delete this invoice?";
										echo "<input type='hidden' name='num_fact' value='".$_GET["num_fact"]."' />";
										echo "<input type='submit' name='del_fact' value='Yes delete' class='other_red_btn small_btn' />";
										echo " <span class='link' onclick='hide_div()'>Cancel</span>";
									echo "</form>";
								echo "</div>";
							?>
						</div>
					</div>
					</p>
					<?php
							}
							else{
								echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> ";
									echo "<b>Error !!!</b> The invoice number <b>".$_GET["num_fact"]."</b> does NOT exist !!!";
								echo"</div>";
							}
						} else {
							header('Location: rssb.php');
						}
					?>
				</div>
			</div>
		<script type="text/javascript" src="js/js_script.js"></script>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#pro').autocomplete({
					serviceUrl: './selections/sel_product.php',
					dataType: 'json'
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#hospital').autocomplete({
					serviceUrl: './selections/sel_hospital.php',
					dataType: 'json'
				});
			});
		</script>
		<?php
			}
			else{
				header('Location: interdit.php');
			}
		?>
    </body>
</html>