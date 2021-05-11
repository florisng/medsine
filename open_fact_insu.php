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
				require("./classes/class_facture_insurance.php");
				require("./classes/class_shelf.php");
				require("./classes/class_user.php");
				require("./classes/class_insu.php");
		?>
			<div id="welcome">
				<?php include("header.php"); ?>
				<div id="other_interne">
					<?php
						if(isset($_GET["num_fact"])) {
							$f = new Facture_insurance();
							$res_fact = $f->is_num_fact_exist($_GET["num_fact"]);
							if($res_fact == false) {
								echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The invoice num: <b>".$_GET["num_fact"] ."</b> does NOT exist !!!";
								echo"</div>";
							}
							else{
									if(isset($_POST["paid"]))
									{
										$bdd = connexionDb();
										$reponse = $bdd->prepare("UPDATE facture_insurance SET status = :e WHERE numero = :n");
										$reponse->execute(array(
											'e' => true,
											'n' => $_GET["num_fact"]
										));
										$reponse->closeCursor();

										$bdd = connexionDb();
										$reponse = $bdd->prepare("UPDATE invoices SET status = :e WHERE num_fact = :n");
										$reponse->execute(array(
											'e' => true,
											'n' => $_GET["num_fact"]
										));
										$reponse->closeCursor();
									}
									
									if(isset($_POST["unpaid"]))
									{
										$bdd = connexionDb();
										$reponse = $bdd->prepare("UPDATE facture_insurance SET status = :e WHERE numero = :n");
										$reponse->execute(array(
											'e' => false,
											'n' => $_GET["num_fact"]
										));
										$reponse->closeCursor();

										$bdd = connexionDb();
										$reponse = $bdd->prepare("UPDATE invoices SET status = :e WHERE num_fact = :n");
										$reponse->execute(array(
											'e' => false,
											'n' => $_GET["num_fact"]
										));
										$reponse->closeCursor();
									}

									if(isset($_POST["confirm_del_com"]))
									{
										$bdd = connexionDb();
										$bdd->query("DELETE FROM commande WHERE id = ".$_POST["id_com"]."");
									}
									
									$fact = new Facture_insurance();
									$infos_fact = $fact->infos_fact_insurance($_GET["num_fact"]);
									$date = $infos_fact[1];
									$user = $infos_fact[2];
									$status = $infos_fact[3];
									$time = $infos_fact[4];
									$c = new Commande();
									$c->print_commande_open_insu($_GET["num_fact"], $date, $time, $user, $status);
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