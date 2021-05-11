<?php
	require("functions.php");
	$_SESSION["page"] = "cash";
?>
<!DOCTYPE html>
<html>
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
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
				<?php include("header.php"); ?>
				<div id="other_interne">
				<?php
					if(isset($_POST["save_all"])) {
						$date = dater();
						$time = getCurrentTime();
						
						$f = new Facture_rssb();
						$num_fact = $f->add_rssb($_POST["prescri"], $_POST["card_number"], $_POST["affi_name"], $_POST["affected"], $_POST["station"], $_POST["bene_name"], $_POST["relation"], $_POST["sex"], $_POST["age"], $_POST["hospital"], $_POST["doctor"], $_POST["receiver"], $_POST["id_number"], $_POST["place"], $_POST["pay_mode"], $date, $time);
						
						$c = new Commande();
						$c->add_commande_insu_rssb("tempo_rssb", $num_fact, $date, "rssb");
						
						$bdd = connexionDb();
						$req = $bdd->prepare("DELETE FROM tempo_rssb WHERE user = '".$_SESSION["username"]."'");
						$req->execute();

						// Delete all exhausted products
						$s = new Shelf();
						$s->del_exhausted_product();

						header("Location: open_fact_rssb.php?num_fact=".$num_fact."");
					}
				?>
					<div class="rssb_entete">
						<div class="rssb_entete_intro">
							<img src="./img/rssb.png" class="rssb_logo" />
							<span class="subtitle">Rwanda Social Security Board</span>
							<p>
								B.P.: 6655 Kigali
							</p>
							<b>FACTURE DES MEDICAMENTS FOURNIS N°:</b> 2377
						</div>
						<div class="rssb_entete_pharma">
							<p>
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
											echo "<td><b>Address</b></td>";
											echo "<td> : ".$add."</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td><b>Phone nr</b></td>";
											echo "<td> : ".$tel."</td>";
										echo "</tr>";
									echo "</table>";
								?>
							</p>
						</div>
					</div>
					<div class="produit_fournis" id="display">
						<?php
							if(isset($_POST["del_all"]))
							{
								// Vider tempo
								$bdd = connexionDb();
								$req = $bdd->prepare("DELETE FROM tempo_rssb WHERE user = '".$_SESSION["username"]."'");
								$req->execute();
							}

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
												echo"<div id='askMsg'>";
		echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["product_name"]."</b> - Insufficient quantity !!! <b>".number_format($q)." ".$unite."</b> left.";
												echo"</div>";
											}
											else{
		$p = new Product();
		$infos_pro = $p->infosParNom($_POST["product_name"]);
		$product_code = $infos_pro[0];
		$prix = $infos_pro[3];

		// Get the old batch number (expire soon)
		$s = new Shelf();
		$batch_num = $s->get_young_batch($product_code);

												$c = new Commande();
												$res = $c->is_product_exist_in_rssb_tempo($product_code, $_SESSION["username"]);
												if($res == true)
												{
													$c = new Commande();
													$new_quantity = $c->total_quantity_rssb_tempo_by_user($product_code, $_SESSION["username"]) + $_POST["quantity"];

													$c = new Commande();
													$total = $prix * $new_quantity;
													$bdd = connexionDb();
													$reponse = $bdd->prepare("UPDATE tempo_rssb SET quantity = :q, total = :t WHERE product_code = :p AND user = :u");
													$reponse->execute(array(
														'q' => $new_quantity,
														't' => $total,
														'p' => $product_code,
														'u' => $_SESSION["username"]
													));
													$reponse->closeCursor();
												} else {
													$total = $prix * $_POST["quantity"];
													$credit = ($total * 95) / 100;
													$cash = $total - $credit;
		$bdd = connexionDb();
		$req = $bdd->prepare("INSERT INTO tempo_rssb (product_code, lot, quantity, price, total, credit, cash, user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$req->execute(array($product_code, $batch_num, $_POST["quantity"], $prix, $total, $credit, $cash, $_SESSION["username"]));
		$req->closeCursor();
												}
											}
									}
								}
							}

							if(isset($_POST["del"]))
							{
								$bdd = connexionDb();
								$bdd->query("DELETE FROM tempo_rssb WHERE id = ".$_POST["id_tempo"]."");
							}
						?>
							<table>
								<tr>
									<th></th>
									<th>N° du produit</th>
									<th>Produit Fournis</th>
									<th>Quantité</th>
									<th>Prix Unitaire</th>
									<th>Total</th>
								</tr>
								<tr>
									<?php
										$c = new Commande();
										$infos_rssb_tempo = $c->tempo_rssb();
										$i = $infos_rssb_tempo[0] + 1;
										$total_tempo = $infos_rssb_tempo[1];
										$total_adherent = ($total_tempo * 15) / 100;
										$total_rssb = $total_tempo - $total_adherent;
									?>
								</tr>
								<tr>
									<form action="" method="POST">
										<td><?php echo $i; ?></td>
										<td></td>
										<td>
	<input type="text" name="product_name" id="pro" size="40" title="Enter a product." placeholder=" Product name" autocomplete="off" required />
										</td>
										<td><input type="number" name="quantity" placeholder="Quantity" required></td>
										<td></td>
										<td></td>
										<td><input type="submit" value="Add product" name="add_product"></td>
									</form>
								</tr>
								<?php
									$c = new Commande();
									$res = $c->nbr_element_in_tempo_rssb($_SESSION["username"]);
									if($res > 0)
									{
								?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>
										<table>
											<tr>
												<td>Total (100%)</td>
												<td>: <?php echo number_format($total_tempo); ?></td>
											</tr>
											<tr>
												<td>Adhérent (15%)</td>
												<td>: <?php echo number_format($total_adherent); ?></td>
											</tr>
											<tr>
												<td>RSSB (85%)</td>
												<td>: <?php echo number_format($total_rssb); ?></td>
											</tr>
										</table>
									</td>
								</tr>
								<?php
									}
								?>
							</table>
					</div>
					<?php
						$c = new Commande();
						$res = $c->nbr_element_in_tempo_rssb($_SESSION["username"]);
						if($res > 0)
						{
					?>
					<form action="" method="post">
						<div class="affiliate_form">
							<div class="affi_form">
								<span class="subtitle">Affiliate</span>
								<hr>
								<table>
									<tr>
										<td><label for="aff_num">Card number:</label></td>
										<td><input type="text" name="card_number" id="aff_num" placeholder="Affiliate number" required></td>
									</tr>
									<tr>
										<td><label for="affiliate">Full name:</label></td>
										<td><input type="text" name="affi_name" id="affiliate" placeholder="Name of the affiliate" required></td>
									</tr>
									<tr>
										<td><label for="aff_dep">Affected:</label></td>
										<td><input type="text" name="affected" id="aff_dep" placeholder="Affected Departure" required></td>
									</tr>
									<tr>
										<td><label for="station">Station:</label></td>
										<td><input type="text" name="station" id="station" placeholder="Duty station" required></td>
									</tr>
								</table>
							</div>
							<div class="bene_form">
								<span class="subtitle">Beneficiary</span>
								<hr>
								<table>
									<tr>
										<td><label for="bene_name">Full name:</label></td>
										<td><input type="text" name="bene_name" id="bene_name" placeholder="Beneficiary names" required></td>
									</tr>
									<tr>
										<td><label for="relation">Relationship:</label></td>
										<td>
											<select name="relation" id="relation" required>
												<option value=""></option>
												<option value="Spouce">Spouce</option>
												<option value="Children">Children</option>
												<option value="Parent">Parent</option>
											</select>
										</td>
									</tr>
									<tr>
										<td><label for="sex">Sex:</label></td>
										<td>
											<select name="sex" id="sex" required>
												<option value=""></option>
												<option value="Female">Female</option>
												<option value="Male">Male</option>
											</select>
										</td>
									</tr>
									<tr>
										<td><label for="age">Age:</label></td>
										<td><input type="number" name="age" id="age" placeholder="Age of beneficiary" required></td>
									</tr>
								</table>
							</div>
							<div class="doctor">
								<span class="subtitle">Other</span>
								<hr>
								<table>
									<tr>
										<td><label for="prescri">Prescription blank:</label></td>
										<td><input type="number" name="prescri" id="prescri" placeholder="Prescription" required></td>
									</tr>
									<tr>
										<td><label for="hospital">Hospital:</label></td>
										<td><input type="text" name="hospital" id="hospital" placeholder="Hospital name" required></td>
									</tr>
									<tr>
										<td><label for="doctor">Doctor:</label></td>
										<td><input type="text" name="doctor" id="doctor" placeholder="Doctor names" required></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="rssb_footer">
							<div  class="rssb_footer_three">
								<span class="subtitle">Reception</span>
								<hr>
								<p>
									<table>
										<tr>
		<td><label for="nom">Receiver:</label></td><td><input type="text" name="receiver" id="nom" placeholder="Full name" required></td>
										</tr>
										<tr>
							<td><label for="cni">ID Number:</label></td><td><input type="text" name="id_number" id="cni" placeholder="ID Card Number" required></td>
										</tr>
										<tr>
							<td><label for="lieu">Delivered place:</label></td><td><input type="text" name="place" id="lieu" placeholder="Lieu de délivrance" required></td>
										</tr>
									</table>
								</p>
								<p>
					<span class='new'><input type='radio' id='cash' name='pay_mode' value='CASH' required /> <label for='cash'>CASH</label></span>
					<span class='new'><input type='radio' id='momo' name='pay_mode' value='MoMo' required /> <label for='momo'>MoMo</label></span>
					<span class='new'><input type='radio' id='pos' name='pay_mode' value='POS' required /> <label for='pos'>POS</label></span>
								</p>
							</div>
						</div>
						<p>
							<input type="submit" value="Save the invoice" name="save_all" class="left">
						</p>
					</form>
					<form action="" method="POST">
						<input type="submit" value="Cancel the operation" name="del_all" class="red_btn right">
					</form>
						<?php } ?>
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