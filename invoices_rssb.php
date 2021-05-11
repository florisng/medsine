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
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_commande.php");
				require("./classes/class_facture_rssb.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="entete">
								<img src="img/rssb.png" class="icon" />
								<span class="titre">Invoices - RSSB</span>
								<a href="invoices_insu.php" class="big_btn right">
									<img src="./img/insu.png" class="icon_in_menu" alt="">
									Insurance
								</a>
								<a href='invoices_cash.php' class='big_btn right'>
									<img src="./img/pay_cash.png" class="icon_in_menu" alt="">
									Cash 100%
								</a>
			</div>
			<div id="sous_entete">
				<form action="" method="GET">
									<table>
										<tr>
											<td>Month / Year:</td>
											<td>
												<?php
													$infos_date = infosDate(dater());
													$current_month = $infos_date[1];
												?>
												<select name="month">
<option value="All" <?php if(isset($_GET["month"])){if($_GET["month"] == "All"){echo "selected";}}?>>All</option>
<option value="1" <?php if(isset($_GET["month"])){if($_GET["month"] == 1){echo "selected";}}else{if($current_month == "1"){echo "selected";}}?>>January</option>
<option value="2" <?php if(isset($_GET["month"])){if($_GET["month"] == 2){echo "selected";}}else{if($current_month == "2"){echo "selected";}}?>>February</option>
<option value="3" <?php if(isset($_GET["month"])){if($_GET["month"] == 3){echo "selected";}}else{if($current_month == "3"){echo "selected";}}?>>March</option>
<option value="4" <?php if(isset($_GET["month"])){if($_GET["month"] == 4){echo "selected";}}else{if($current_month == "4"){echo "selected";}}?>>April</option>
<option value="5" <?php if(isset($_GET["month"])){if($_GET["month"] == 5){echo "selected";}}else{if($current_month == "5"){echo "selected";}}?>>May</option>
<option value="6" <?php if(isset($_GET["month"])){if($_GET["month"] == 6){echo "selected";}}else{if($current_month == "6"){echo "selected";}}?>>June</option>
<option value="7" <?php if(isset($_GET["month"])){if($_GET["month"] == 7){echo "selected";}}else{if($current_month == "7"){echo "selected";}}?>>July</option>
<option value="8" <?php if(isset($_GET["month"])){if($_GET["month"] == 8){echo "selected";}}else{if($current_month == "8"){echo "selected";}}?>>August</option>
<option value="9" <?php if(isset($_GET["month"])){if($_GET["month"] == 9){echo "selected";}}else{if($current_month == "9"){echo "selected";}}?>>September</option>
<option value="10" <?php if(isset($_GET["month"])){if($_GET["month"] == 10){echo "selected";}}else{if($current_month == "10"){echo "selected";}}?>>October</option>
<option value="11" <?php if(isset($_GET["month"])){if($_GET["month"] == 11){echo "selected";}}else{if($current_month == "11"){echo "selected";}}?>>November</option>
<option value="12" <?php if(isset($_GET["month"])){if($_GET["month"] == 12){echo "selected";}}else{if($current_month == "12"){echo "selected";}}?>>December</option>
											</select>
												<select name="year">
	<option value="All" <?php if(isset($_GET["year"])){if($_GET["year"] == "All"){echo "selected";}}?>>All</option>
													<?php
														$infos_date = infosDate(dater());
														$current_year = $infos_date[2];
														for($year = $current_year; $year >= 2020; $year--)
														{
													?>
<option value="<?php echo $year; ?>" <?php if(isset($_GET["year"])){if($_GET["year"] == $year){echo "selected";}}else{if($current_year == $year){echo "selected";}}?>><?php echo $year; ?></option>
													<?php
														}
													?>
												</select>
											</td>
											<td>Status:</td>
											<td>
												<select name="status">
			<option value="All" <?php if(isset($_GET["status"])){if($_GET["status"] == "All"){echo "selected";}} ?>>All</option>
			<option value="true" <?php if(isset($_GET["status"])){if($_GET["status"] == "true"){echo "selected";}} ?>>Paid</option>
			<option value="false" <?php if(isset($_GET["status"])){if($_GET["status"] == "false"){echo "selected";}} ?>>Unpaid</option>
												</select>
											</td>
											<td>Cashier:</td>
											<td>
												<select name="username" id="user">
													<option value="All">All</option>
													<?php
														$bdd = connexionDb();
														$rep = $bdd->query("SELECT * FROM user");
														while($data = $rep->fetch())
														{
															?>
					<option value="<?php echo $data["username"]; ?>" <?php if(isset($_GET["username"])){if($_GET["username"] == $data["username"]){echo "selected";}}?>><?php echo $data["prenom"]; ?></option>
															<?php
														}
														$rep->closeCursor();
													?>
												</select>
												<input type="submit" value="Search" class="btn" required />
												<?php
													if(isset($_GET["month"])) {
														echo "<a href='invoices_rssb.php' class='link'><<< Back</a>";
													}
												?>
											</td>
										</tr>
									</table>
								</form>
							</div>
							<div id="interne">
				<?php
					if(isset($_POST["by_number"]))
					{
							$f = new Facture_rssb();
							$res = $f->is_num_fact_exist($_POST["num_fact"]);
							if($res == false)
							{
								echo"<div id='askMsg'>";
			echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The invoice num <b>".$_POST["num_fact"]."</b> does NOT exist !!!";
								echo"</div>";
							}
							else{
								echo "<div id='result'>";
									$f = new Facture_rssb();
									$infos_fact = $f->infos_fact($_POST["num_fact"]);
									$date = $infos_fact[1];
									$user = $infos_fact[2];
					echo "Invoice num: <b>".$_POST["num_fact"]."</b> - <a href='open_fact.php?num_fact=".$_POST["num_fact"]."&user=".$user."&date=".$date."' target='_blank' class='link'>Open</a>";
								echo "</div>";
							}
					}
					
					if(isset($_POST["del_fact"]))
					{
							echo"<div id='okMsg'>";
								$bdd = connexionDb();
								$bdd->query("DELETE FROM facture_rssb WHERE numero = '".$_POST["num_fact"]."'");

								$bdd = connexionDb();
								$bdd->query("DELETE FROM invoices WHERE num_fact = '".$_POST["num_fact"]."'");
								
								$bdd = connexionDb();
								$bdd->query("DELETE FROM commande WHERE num_fact = '".$_POST["num_fact"]."'");
							
								echo "<img src='img/cool.png' class='small' /> ";
								echo "The invoice nr <b>".$_POST["num_fact"]."</b> deleted!";
							echo"</div>";
					}
						
					$nbr = nombre("facture_rssb");
					if($nbr <= 0)
					{
						echo "<p>";
							echo "No invoice !!!";
						echo "</p>";
					}
					else
					{
						if(isset($_GET["month"]) AND isset($_GET["year"]) AND isset($_GET["status"]) AND isset($_GET["username"]))
						{
							if($_GET["month"] < 0 OR $_GET["month"] > 12) {
								echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid month value: '<b>".$_GET["month"]."</b>' !!!";
								echo"</div>";
							}
							else {
								$infos_date = infosDate(dater());
								$current_year = $infos_date[2];
								if($_GET["year"] > $current_year) {
									echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid year: '<b>".$_GET["year"]."</b>' !!!";
									echo"</div>";
								}
								else {
									$f = new Facture_rssb();
									$f->search_invoices_rssb($_GET["month"], $_GET["year"], $_GET["status"], $_GET["username"]);
								}
							}
						}
						else{
							$f = new Facture_rssb();
							$f->tous_rssb();
						}
					}
				?>
			</div>
		</div>
		<?php
			}
			else{
				header('Location: index.php');
			}
		?>
    </body>
</html>