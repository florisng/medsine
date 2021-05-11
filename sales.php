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
					serviceUrl: './selections/sel_sales.php',
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
				<div id="entete">
					<form action="#entete" method='get'>
						<img src="img/result.jpg" class="icon" />
								<span class="titre">Sales | </span>
											<label for='year'>Year: </label>
											<select id="year" name="year_of_sale">
												<?php
													$infos_date = infosDate(dater());
													$current_year = $infos_date[2];
													for($year = $current_year; $year >= 2020; $year--)
													{
														?>
<option value="<?php echo $year; ?>" <?php if(isset($_GET["year_of_sale"])){if($_GET["year_of_sale"] == $year){echo "selected";}}else{if($current_year == $year){echo "selected";}}?>><?php echo $year; ?></option>
														<?php
													}
												?>
											</select>
											<input type="submit" value="Search" class="btn" required />
											<a href='#sales_details' class='link'>Details</a>
											<?php
												if(isset($_GET["year_of_sale"])) {
													echo " | <a href='sales.php' class='link'><<< Back</a>";
												}
											?>
								<a href="sales_rssb.php" class="big_btn right">
									<img src="img/rssb.png" class="icon_in_menu" alt="">
									RSSB
								</a>
								<a href="sales_insu.php" class="big_btn right">
									<img src="img/insu.png" class="icon_in_menu" alt="">
									Insurance
								</a>
								<a href="sales_cash.php" class="big_btn right">
									<img src="img/pay_cash.png" class="icon_in_menu" alt="">
									Cash 100%
								</a>
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
										$ok_graphic = true;
										if(isset($_GET["year_of_sale"])) {
											$year = intval($_GET["year_of_sale"]);
											$infos_date = infosDate(dater());
											$current_year = $infos_date[2];
											if($year <= 0 || $year > $current_year) {
												$ok_graphic = false;
												echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid year value: '<b>".$_GET["year_of_sale"]."</b>' !!!";
												echo"</div>";
											}
										} else {
											$infos_date = infosDate(dater());
											$year = $infos_date[2];
										}
										
										if($ok_graphic == true) {
											echo "<div id='chartContainer' style='height: 370px; width: 100%;'></div>";
											$com = new Commande();
											$jan = $com->sales_by_month(1, $year);
											$feb = $com->sales_by_month(2, $year);
											$mar = $com->sales_by_month(3, $year);
											$apr = $com->sales_by_month(4, $year);
											$may = $com->sales_by_month(5, $year);
											$jun = $com->sales_by_month(6, $year);
											$jul = $com->sales_by_month(7, $year);
											$aug = $com->sales_by_month(8, $year);
											$sep = $com->sales_by_month(9, $year);
											$oct = $com->sales_by_month(10, $year);
											$nov = $com->sales_by_month(11, $year);
											$dec = $com->sales_by_month(12, $year);

											$dataPoints = array(
												array("y" => $jan, "label" => "January" ),
												array("y" => $feb, "label" => "Febuary" ),
												array("y" => $mar, "label" => "March" ),
												array("y" => $apr, "label" => "April" ),
												array("y" => $may, "label" => "May" ),
												array("y" => $jun, "label" => "June" ),
												array("y" => $jul, "label" => "July" ),
												array("y" => $aug, "label" => "August" ),
												array("y" => $sep, "label" => "September" ),
												array("y" => $oct, "label" => "October" ),
												array("y" => $nov, "label" => "November" ),
												array("y" => $dec, "label" => "December" )
											);
										}
									?>
								<hr>
							<div id='sales_details'>
							<span class='subtitle'>Details</span>
								<form action="#sales_details" method="GET">
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
	<input type="text" name="product" value="<?php if(isset($_GET["product"])){echo $_GET["product"];}else{echo "All";} ?>" id="product" size="40" title="Enter a product name." placeholder="Product name" autocomplete="off" required />
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
												<?php
													if(isset($_GET["month"])) {
														echo "<a href='sales.php' class='link'><<< Back</a>";
													}
												?>
											</td>
										</tr>
									</table>
								</form>
									<?php
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
												$com = new Commande();
												$res_com = $com->is_product_exist_in_commande($product);
												if($product != "All" AND $res_com == false) {
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
														$com->search_sales($_GET["month"], $_GET["year"], $product, $_GET["username"]);
													}
												}
											}
										} else {
											$com = new Commande();
											$com->tous();
										}
										echo "</div>";
									}
				?>
				<script src="./js/canvasjs.min.js"></script>
				<script type="text/javascript">
					window.onload = function () {
						let chart = new CanvasJS.Chart("chartContainer", {
							theme: "light1",
							animationEnabled: true,
							// title:{
							// 	text: 
							// 		<?php
							// 			$currency = "NET Profit in " . $currency;
							// 			echo json_encode($currency, JSON_NUMERIC_CHECK);
							// 		?>
							// },
							data: [{
									type: "column",
									yValueFormatString: "#,##0.## RwF",
									dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
								}]
						});
						chart.render();
					}
				</script>
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