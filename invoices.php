<?php
	include("functions.php");
	$_SESSION["page"] = "invoices";
?>
<!DOCTYPE html><html lang="en">
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_commande.php");
				require("./classes/class_facture.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="entete">
								<img src="img/commande.jpg" class="icon" />
								<span class="titre">Invoices </span>
								<!-- <a href="sales_on_credit.php" class="big_btn">
									<img src="img/credit.png" class="icon_in_menu" alt="">
									On credit
								</a> -->
								<a href="invoices_rssb.php" class="big_btn right">
									<img src="img/rssb.png" class="icon_in_menu" alt="">
									RSSB
								</a>
								<a href="invoices_insu.php" class="big_btn right">
									<img src="img/insu.png" class="icon_in_menu" alt="">
									Insurance
								</a>
								<a href="invoices_cash.php" class="big_btn right">
									<img src="img/pay_cash.png" class="icon_in_menu" alt="">
									Cash 100%
								</a>
			</div>
			<div id="interne">
				<?php
					$nbr = nombre("invoices");
					if($nbr <= 0)
					{
						echo "<p>";
							echo "No invoice !!!";
						echo "</p>";
					} else {
						$f = new Facture();
						$f->tous();
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