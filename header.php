<?php
	// Get user's infos
	$u = new User();
	$res = $u->is_username_exist($_SESSION["username"]);
	if($res === false) {
		header('Location: interdit.php');
	}
	else {
?>
	<div id="header">
		<div class="header_one">
			<img src='img/logo.png' class='small_logo' />
		</div>
		<div class="header_two">
			<script>
				function open_calculator() {
					window.open('Calculator:///');
				}
			</script>
			<img src="./img/calculator.png" class="average" onclick="open_calculator()" alt="Calculator" title="Open calculator">
			<?php
				echo "<span class='right'>";
					if(noel() == true) {
						echo "<img src='./img/red_balls.jpg' class='xmas_balls' alt=''>";
						echo " <span class='happy_new_year'>Merry Christmas</span> ";
						echo "<img src='./img/red_balls.jpg' class='medium' alt=''>";
					}
					if(bonneAnnee() == true) {
						echo "<img src='./img/brown_star.png' class='xmas_balls' alt=''>";
						echo " <span class='happy_new_year'>Happy New Year</span> ";
						echo "<img src='./img/brown_star.png' class='small' alt=''>";
					}
				echo "</span>";
			?>
		</div>
		<div class="header_three">
			<?php
				// Get user's infos
				$u = new User();
				$infosUser = $u->infos_user($_SESSION["username"]);
				$nom = $infosUser[1];
				$prenom = $infosUser[2];
				$type = $infosUser[3];
				echo "<b>".$prenom." ".$nom."</b> (".$type.")";
				echo "<a href='deco.php'><img src='./img/deco.jpg' class='logout_btn' title='Logout' /></a>";
				echo "<hr>";
				echo date("l, F j, Y");
			?>
		</div>
	</div>
	<div id="adv">
		<div id="menu">
				<a href="cash.php" <?php if($_SESSION["page"] == 'cash'){echo "class=current_page";}?>>
					<img src="img/commande.jpg" class="icon_in_menu" />Sell
				</a>
				<a href="insurances.php" <?php if($_SESSION["page"] == 'insu'){echo "class=current_page";}?>>
					<img src='img/insu.png' class='icon_in_menu' />Insurances
				</a>
				<a href="invoices.php" <?php if($_SESSION["page"] == 'invoices'){echo "class=current_page";}?>>
					<img src='img/commande.jpg' class='icon_in_menu' />Invoices
				</a>
				<a href="sales.php" <?php if($_SESSION["page"] == 'sales'){echo "class=current_page";}?>>
					<img src='img/result.jpg' class='icon_in_menu' />Sales
				</a>
				<a href="suppliers.php" <?php if($_SESSION["page"] == 'suppliers'){echo "class=current_page";}?>>
					<img src='img/suppliers.png' class='icon_in_menu' />Suppliers
				</a>
				<a href="purchases.php" <?php if($_SESSION["page"] == 'purchases'){echo "class=current_page";}?>>
					<img src='img/in.png' class='icon_in_menu' />Purchases
				</a>
				<a href="stock.php" <?php if($_SESSION["page"] == 'stock'){echo "class=current_page";}?>>
					<img src='img/stock.png' class='icon_in_menu' />Stock
				</a>
				<a href="expenses.php" <?php if($_SESSION["page"] == 'expenses'){echo "class=current_page";}?>>
					<img src='img/expenses.png' class='icon_in_menu' />Expenses
				</a>
				<a href="settings.php" <?php if($_SESSION["page"] == 'settings'){echo "class=current_page";}?>>
					<img src='img/settings.png' class='icon_in_menu' />Settings
				</a>
		</div>
		<p class='centered smallText'>Powered by <a href="#" class="link" target="_blank">Akcess LTD</a></p>
	</div>
<?php
			$bdd = connexionDb();
			$rep = $bdd->query("SELECT * FROM company");
			while($data = $rep->fetch())
			{
				$exp_date = $data["exp_date"];
			}
			$rep->closeCursor();
			$infos_date = infosDate($exp_date);
			$jour = $infos_date[0];
			$mois = $infos_date[1];
			$annee = $infos_date[2];
			$exp_soon = date('Y-m-d', strtotime("-14 day", mktime(0, 0, 0, $mois, $jour, $annee)));
			if($exp_soon <= dater()) {
				echo "<div id='other_interne'>";
					echo "<div id='askMsg'>";
								$exp_soon_date = date_create($exp_date);
								$today_date = date_create(dater());
								$date_interval = date_diff($exp_soon_date, $today_date);
								$days_left = $date_interval->format('%d');
								echo "<img src='img/warning.png' class='small' /> ";
								if($days_left < 1) {$days_left = "less than 24 hours";} else {$days_left = $days_left." day(s)";}
								echo "<b>Warning !!!</b> The system will expire in <b>".$days_left."</b> !!!";
					echo "</div>";
				echo "</div>";
			}
	}
?>














