<?php
	include("functions.php");
	$_SESSION["page"] = "sales";
?>
<!DOCTYPE html><html lang="en">
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#product').autocomplete({
					serviceUrl: './selections/sel_produit.php',
					dataType: 'json'
				});
			});
		</script>
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_commande.php");
				require("./classes/class_product.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<?php
				if($_SESSION["type"] == "Manager" || $_SESSION["type"] == "Admin") {
			?>
			<div id="entete">
								<img src="img/insu.png" class="icon" />
								<span class="titre">Sales - Insurance </span>
								<a href="invoices_insu.php" class="btn">Invoices</a>
								<a href="sales_rssb.php" class="big_btn right">
									<img src="img/rssb.png" class="icon_in_menu" alt="">
									RSSB
								</a>
								<a href="sales_cash.php" class="big_btn right">
									<img src="img/pay_cash.png" class="icon_in_menu" alt="">
									Cash
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
										<td>Product:</td>
										<td>
<input type="text" name="product" value="<?php if(isset($_GET["product"])){echo $_GET["product"];}else{echo "All";} ?>" id="product" size="20" title="Enter a product name." placeholder="Product name" autocomplete="off" required />
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
										</td>
										<td>
											<input type="submit" value="Search" class="btn" required />
										</td>
									</tr>
								</table>
							</form>
						</div>
						<div id="interne">
				<?php
									$nbr = nombre("commande");
									if($nbr <= 0)
									{
										echo "<p>";
											echo "No sale !!!";
										echo "</p>";
									}
									else
									{
										if(isset($_GET["month"]) AND isset($_GET["year"]) AND isset($_GET["product"]) AND isset($_GET["username"]))
										{
											$ok = true;
											$month = strtolower($_GET["month"]);
											if($month != "all") {
												$month = intval($month);
												if($month <= 0 || $month > 12) {
													$ok = false;
													echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid month value: '<b>".$_GET["month"]."</b>' !!!";
													echo"</div>";
												}
											}
											$year = strtolower($_GET["year"]);
											if($year != "all") {
												$year = intval($year);
												$infos_date = infosDate(dater());
												$current_year = $infos_date[2];
												if($year <= 0 || $year > $current_year) {
													$ok = false;
													echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid year value: '<b>".$_GET["year"]."</b>' !!!";
													echo"</div>";
												}
											}
											
											// If month & year are Ok
											if($ok == true) {
													$product = ucwords($_GET["product"]);
													$pro = new Product();
													$res_pro = $pro->is_product_exist($product);
													if($product != "All" AND $res_pro == false) {
														echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The product '<b>".$product."</b>' does NOT exist !!!";
														echo"</div>";
													}
													else {
														$u = new User();
														$user_pro = $u->is_username_exist($_GET["username"]);
														if($_GET["username"] != "All" AND $user_pro == false) {
															echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The username '<b>".$_GET["username"]."</b>' does NOT exist !!!";
															echo"</div>";
														}
														else {
															$com = new Commande();
															$com->search_sales_by_type($_GET["month"], $_GET["year"], $product, $_GET["username"], "insu");
														}
													}
											}
										}
										else {
											$com = new Commande();
											$com->tous_by_type("insu");
										}
                                    }
				?>
			</div>
			<?php
				} else {
					echo "<div id='other_interne'>";
						echo"<div id='askMsg'>";
							echo "<img src='img/warning.png' class='small' /> Oops !!! You have no right to access this information !!!";
						echo"</div>";
					echo"</div>";
				}
			?>
		</div>
		<?php
			}
			else{
				header('Location: interdit.php');
			}
		?>
    </body>
</html>