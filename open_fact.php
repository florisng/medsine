<?php
	include("functions.php");
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
				require("./classes/class_commande.php");
				require("./classes/class_product.php");
				require("./classes/class_facture.php");
				require("./classes/class_shelf.php");
				require("./classes/class_user.php");
		?>
			<div id="welcome">
				<?php include("header.php"); ?>
				<div id="other_interne">
					<?php
						if(isset($_GET["num_fact"])) {
							$f = new Facture();
							$res_fact = $f->is_num_fact_exist($_GET["num_fact"]);
							if($res_fact == false) {
								echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The invoice num: <b>".$_GET["num_fact"] ."</b> does NOT exist !!!";
								echo"</div>";
							}
							else{
									if(isset($_POST["save_in_tempo"]))	// Save YES because no need to change save_in_tempo
									{
										$p = new Product();
										$infos_produit = $p->infosParNom($_POST["product_name"]);
										$product_code = $infos_produit[0];
										$unite = $infos_produit[1];
										
										$s = new Shelf();
										$quantity = $s->get_shelf_product_quantity_by_lot($product_code, $_POST["lot"]);
										if($quantity < $_POST["quantity"]){
											echo"<div id='askMsg'>";
					echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["product_name"] ."</b> - Batch Nr <b>".$_POST["lot"]."</b> - Insufficient quantity !!! <b>".$quantity." ".$unite."</b> left";
											echo"</div>";
										}
										else{
											$s = new Shelf();
											$infos_lot = $s->product_infos_in_shelf($product_code, $_POST["lot"]);
											$prix = $infos_lot[2];
											
											$total = $_POST["quantity"] * $prix;
											
											$bdd = connexionDb();
											$req = $bdd->prepare("INSERT INTO commande (num_fact, produit, lot, quantite, prix, total, date, user, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
											$req->execute(array($_GET["num_fact"], $_POST["product_name"], $_POST["lot"], $_POST["quantity"], $prix, $total, dater(), $_SESSION["username"], "insu"));
											$req->closeCursor();
											
											$s = new Shelf();
											$s->update_shelf_quantity($product_code, $_POST["lot"], $_POST["quantity"]);
										}
									}			
									
									if(isset($_POST["confirm_del_com"]))
									{
										$bdd = connexionDb();
										$bdd->query("DELETE FROM commande WHERE id = ".$_POST["id_com"]."");
									}
									
									$c = new Commande();
									$c->print_commande_open($_GET["num_fact"]);
							}
						}
					?>
				</div>
		</div>
		<script type="text/javascript" src="js/js_script.js"></script>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#sel_pro').autocomplete({
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