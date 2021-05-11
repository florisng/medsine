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
	<body onload="document.querySelector('#pro').focus()">
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_facture.php");
				require("./classes/class_product.php");
				require("./classes/class_commande.php");
				require("./classes/class_shelf.php");
				require("./classes/class_user.php");
		?>
			<div id="welcome">
				<?php include("header.php"); ?>
				<?php
					if(!isset($_POST["save_all"]))
					{
				?>
				<div id="entete">
					<form action="" method="post">
						<img src="./img/pay_cash.png" class="icon" />
						<span class="titre">Cash - 100% | </span>
						<a href='rssb.php' class='big_btn right'>
							<img src="./img/rssb.png" class="icon_in_menu" alt="">
							RSSB
						</a>
						<a href="by_insurance.php" class="big_btn right">
							<img src="./img/insu.png" class="icon_in_menu" alt="">
							Insurance
						</a>
					</form>
				</div>
				<div id="interne">
				<?php
					}
					else{
				?>
				<div id="other_interne">
				<?php
					}
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
											$new_total_quantity_tempo = $c->total_quantity_tempo($product_code) + $_POST["quantity"];
											if($quantity < $new_total_quantity_tempo){
												$q = $quantity - $c->total_quantity_tempo($product_code);
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
		$prix = $infos_pro[4];

		// Get the old batch number (expire soon)
		$s = new Shelf();
		$batch_num = $s->get_young_batch($product_code);

												$c = new Commande();
												$res = $c->is_product_exist_in_tempo($product_code, $_SESSION["username"]);
												if($res == true)
												{
														$c = new Commande();
														$total = $prix * $new_total_quantity_tempo;
														
														$bdd = connexionDb();
														$reponse = $bdd->prepare("UPDATE tempo SET quantity = :q, total = :t WHERE product_code = :p AND user = :u");
														$reponse->execute(array(
															'q' => $new_total_quantity_tempo,
															't' => $total,
															'p' => $product_code,
															'u' => $_SESSION["username"]
														));
														$reponse->closeCursor();
												} else {
													$total = $prix * $_POST["quantity"];
		$bdd = connexionDb();
		$req = $bdd->prepare("INSERT INTO tempo (product_code, lot, quantity, price, total, user) VALUES (?, ?, ?, ?, ?, ?)");
		$req->execute(array($product_code, $batch_num, $_POST["quantity"], $prix, $total, $_SESSION["username"]));
		$req->closeCursor();
												}
											}
									}
								}
							}
							
							if(isset($_POST["save_all"]) AND $_SESSION["done"] == false)
							{
								echo "<p>";
											$date = dater();
											$time = getCurrentTime();
											
											$f = new Facture();
											$num_fact = $f->add_facture($_POST["client"], $_POST["pay_mode"], $date, $time);
											
											$c = new Commande();
											$c->add_commande($num_fact, $date);
											
											$bdd = connexionDb();
											$req = $bdd->prepare("DELETE FROM tempo WHERE user = '".$_SESSION["username"]."'");
											$req->execute();

											// Delete all exhausted products
											$s = new Shelf();
											$s->del_exhausted_product();

											$_SESSION["done"] = true;

											header("Location: open_fact.php?num_fact=".$num_fact."");
								echo "<p>";
							}
							else{
								$_SESSION["done"] = false;
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
								}
					
								if(isset($_POST["del"]))
								{
									$bdd = connexionDb();
									$bdd->query("DELETE FROM tempo WHERE id = ".$_POST["id_tempo"]."");
								}

								if(isset($_POST["ok_price"]))
								{
									$t = $_POST["new_price"] * $_POST["quantity"];
									$bdd = connexionDb();
									$reponse = $bdd->prepare("UPDATE tempo SET quantity = :q, price = :p, total = :t WHERE id = :i");
									$reponse->execute(array(
										'q' => $_POST["quantity"],
										'p' => $_POST["new_price"],
										't' => $t,
										'i' => $_POST["id_tempo"]
									));
									$reponse->closeCursor();
								}
								
								if(isset($_POST["del_all"]))
								{
									// Vider tempo
									$bdd = connexionDb();
									$req = $bdd->prepare("DELETE FROM tempo WHERE user = '".$_SESSION["username"]."'");
									$req->execute();
								}
						echo "<p>";
							$c = new Commande();
							$res = $c->nbr_element_in_tempo($_SESSION["username"]);
							if($res > 0)
							{
								$c = new Commande();
								$c->tempo();
							}
						echo "</p>";
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
		<?php
			}
			else{
				header('Location: interdit.php');
			}
		?>
    </body>
</html>