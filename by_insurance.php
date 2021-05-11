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
		<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
    </head>
	<body onload="document.querySelector('#insu').focus()">
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_facture_insurance.php");
				require("./classes/class_product.php");
				require("./classes/class_commande.php");
				require("./classes/class_shelf.php");
				require("./classes/class_insu.php");
				require("./classes/class_user.php");
		?>
			<div id="welcome">
				<?php include("header.php"); ?>
				<div id="entete">
					<img src="./img/insu.png" class="icon" alt="">
					<?php
						echo "<span class='titre'>";
						if(nombre("insu") > 0 && isset($_GET["insu_name"]) && isset($_GET["rate"])) {
							echo $_GET["insu_name"]." (".$_GET["rate"]." %) </span><a href='by_insurance.php' class='link'>Change <img src='./img/modify.png' /></a>";
						} else {
							echo "Insurance | </span>";
						}
					?>
					<a href='rssb.php' class='big_btn right'>
						<img src="./img/rssb.png" class="icon_in_menu" alt="">
						RSSB
					</a>
					<a href='cash.php' class='big_btn right'>
						<img src="./img/pay_cash.png" class="icon_in_menu" alt="">
						Cash - 100%
					</a>
				</div>
				<div id="interne">
					<?php
						if(nombre("insu") == 0)
						{
							echo "<p>";
								echo "<img src='img/warning.png' class='small small_down' /> ";
								echo "No insurance !!! <a href='insurances.php' class='small_btn'><img src='./img/add.png' class='small small_down'> Add an insurance</a>";
							echo "</p>";
						} else {
							if(isset($_GET["insu_name"]) && isset($_GET["rate"])) {
								$i = new Insurance();
								$res = $i->is_insu_name_exist($_GET["insu_name"]);
								if($res == false)
								{
									echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> ";
										echo "<b>Error !!!</b> The insurance <b>".$_GET["insu_name"]."</b> does NOT exist in the system !!!";
									echo"</div>";
								} else {
									if($_GET["rate"] <= 0 || $_GET["rate"] > 100) {
										echo"<div id='askMsg'>";
					echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid rate <b>".$_GET["rate"]."%</b> !!! Please respect the rate range:  [1, 100]";
										echo"</div>";
									} else {
										if(isset($_POST["save_in_tempo"]))
										{
											$p = new Product();
											$res = $p->is_product_exist($_POST["product_name"]);
											if($res == false)
											{
												echo"<div id='askMsg'>";
													echo "<img src='img/warning.png' class='small' /> ";
													echo "<b>Error !!!</b> The product <b>".$_POST["product_name"]."</b> does NOT exist !!!";
												echo"</div>";
											} else {
												if($_POST["quantity"] <= 0)
												{
													echo"<div id='askMsg'>";
														echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["quantity"]."</b>: Invalid quantity !!!";
													echo"</div>";
												} else {
													$p = new Product();
													$infos_pro = $p->infosParNom($_POST["product_name"]);
													$product_code = $infos_pro[0];
													$unite = $infos_pro[1];
													
													$s = new Shelf();
													$quantity = $s->get_product_quantity_shelf($product_code);
													$c = new Commande();
													$new_total_quantity_tempo = $c->total_quantity_insu_tempo($product_code) + $_POST["quantity"];
													if($quantity < $new_total_quantity_tempo){
														$q = $quantity - $c->total_quantity_insu_tempo($product_code);
														echo"<div id='askMsg'>";
			echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["product_name"]."</b> - Insufficient quantity !!! <b>".number_format($q)." ".$unite."</b> left.";
														echo"</div>";
													}
													else{
														$reste = $quantity - $new_total_quantity_tempo;
				echo "<b>".$_POST["product_name"]."</b>, there are <b>".$reste." ".$unite."</b> left.";
													
														$p = new Product();
														$infos_pro = $p->infosParNom($_POST["product_name"]);
														$product_code = $infos_pro[0];
														$prix = $infos_pro[3];

														// Get the old batch number (expire soon)
														$s = new Shelf();
														$batch_num = $s->get_young_batch($product_code);

														$c = new Commande();
														$res = $c->is_product_exist_in_tempo_insu($product_code, $_SESSION["username"]);
														if($res == true)
														{
															$c = new Commande();
															$total = $prix * $new_total_quantity_tempo;
															
															$bdd = connexionDb();
															$reponse = $bdd->prepare("UPDATE tempo_insu SET quantity = :q, price = :pr, total = :t WHERE product_code = :p AND user = :u");
															$reponse->execute(array(
																'q' => $new_total_quantity_tempo,
																'pr' => $prix,
																't' => $total,
																'p' => $product_code,
																'u' => $_SESSION["username"]
															));
															$reponse->closeCursor();
														} else {
															$total = $prix * $_POST["quantity"];
															$credit = ($total * $_GET["rate"]) / 100;
															$cash = $total - $credit;
															
			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO tempo_insu (product_code, lot, quantity, price, total, credit, cash, user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($product_code, $batch_num, $_POST["quantity"], $prix, $total, $credit, $cash, $_SESSION["username"]));
			$req->closeCursor();
														}
													}
												}
											}
										}
								
										if(isset($_POST["save_all"]))
										{
											$date = dater();
											$time = getCurrentTime();
											
											$i = new Insurance();
											$infos_insu = $i->infos_insu_by_name($_GET["insu_name"]);
											$insu_code = $infos_insu[0];
											
											$f = new Facture_insurance();
											$num_fact = $f->add_facture_insurance($insu_code, $_GET["insu_name"], $_POST["adherent"], $_POST["card_number"], $_POST["pay_mode"], $date, $time, $_GET["rate"]);
											
											$c = new commande();
											$c->add_commande_insu_rssb("tempo_insu", $num_fact, $date, "insu");
											
											$bdd = connexionDb();
											$req = $bdd->prepare("DELETE FROM tempo_insu WHERE user = '".$_SESSION["username"]."'");
											$req->execute();

											// Delete all exhausted products
											$s = new Shelf();
											$s->del_exhausted_product();
											header("Location: open_fact_insu.php?num_fact=".$num_fact."");
										}
									?>
									<p>
										<form action="" method="post">
														<table>
															<tr>
															<?php
																if(nombre("product") == 0)
																{
																	echo "<td>";
																		echo "<img src='img/warning.png' class='small' /> ";
																		echo "No product !!! <a href='shelf.php' class='link'>Add a product</a>";
																	echo "</td>";
																}
																else{
															?>
																<td>
								<input type="text" name="product_name" id="pro" size="60" title="Enter a product." placeholder="   Product name" autocomplete="off" required />
																</td>
																<td>
								<input type="number" name="quantity" placeholder="Quantity" required>
																</td>
																<td>
																	<input type="submit" class="btn" name="save_in_tempo" value=">>>" required />
																</td>
															</tr>
															<?php
																}
															?>
														</table>
										</form>
									</p>

									<?php
										if(isset($_POST["del"]))
										{
											$bdd = connexionDb();
											$bdd->query("DELETE FROM tempo_insu WHERE id = ".$_POST["id_tempo"]."");
										}
												
										if(isset($_POST["del_all"]))
										{
											// Vider tempo
											$bdd = connexionDb();
											$req = $bdd->prepare("DELETE FROM tempo_insu WHERE user = '".$_SESSION["username"]."'");
											$req->execute();
										}

										if(isset($_POST["ok_price"]))
										{
											$t = $_POST["new_price"] * $_POST["quantity"];
											$cre = ($t * $_GET["rate"]) / 100;
											$c = $t - $cre;
											$bdd = connexionDb();
											$reponse = $bdd->prepare("UPDATE tempo_insu SET quantity = :q, price = :p, total = :t, credit = :cre, cash = :c WHERE id = :i");
											$reponse->execute(array(
												'q' => $_POST["quantity"],
												'p' => $_POST["new_price"],
												't' => $t,
												'cre' => $cre,
												'c' => $c,
												'i' => $_POST["id_tempo"]
											));
											$reponse->closeCursor();
										}
										
										$c = new Commande();
										$res = $c->nbr_element_in_tempo_insu($_SESSION["username"]);
										if($res > 0)
										{
											$c = new Commande();
											$c->tempo_insu($_GET["rate"]);
										}
									}
								}
							} else {
								echo "<p>";
									echo "<form action='' method='GET'>";
										echo "<table>";
											echo "<tr>";
												echo "<td>";
				echo "<input type='text' name='insu_name' id='insu' size='60' title='Enter an insurance.' placeholder='Insurance name' autocomplete='off' required />";
												echo "</td>";
												echo "<td>Rate (%)</td>";
												echo "<td><input type='number' name='rate' placeholder='Rate in %' required /></td>";
												echo "<td>";
													echo "<input type='submit' class='btn' value='>>>' required />";
												echo "</td>";
											echo "</tr>";
										echo "</table>";
									echo "</form>";
								echo "</p>";
							}
						}
					?>
				</div>
			</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#insu').autocomplete({
					serviceUrl: './selections/sel_insu.php',
					dataType: 'json'
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#pro').autocomplete({
					serviceUrl: './selections/sel_product.php',
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