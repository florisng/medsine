<?php
	include("functions.php");
	$_SESSION["page"] = "purchases";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#supplier').autocomplete({
					serviceUrl: './selections/sel_supplier_seller.php',
					dataType: 'json'
				});
			});
			
			$(document).ready(function() {
				$('#product').autocomplete({
					serviceUrl: './selections/sel_sold_product.php',
					dataType: 'json'
				});
			});
			
			$(document).ready(function() {
				$('#supplier_name').autocomplete({
					serviceUrl: './selections/sel_supplier.php',
					dataType: 'json'
				});
			});
			
			$(document).ready(function() {
				$('#product_name').autocomplete({
					serviceUrl: './selections/sel_product.php',
					dataType: 'json'
				});
			});
		</script>
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_product.php");
				require("./classes/class_shelf.php");
				require("./classes/class_supplier.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="entete">
				<img src="img/in.png" class="icon" />
				<span class="titre">Purchases | </span>
			</div>
			<div id="sous_entete">
					<?php
						$nbr_sup = nombre("supplier");
						$nbr_pro = nombre("product");
						if($nbr_sup == 0)
						{
							echo"<img src='img/warning.png' class='small' /> Can't purchase !!! No supplier !!!";
							echo " <a href='suppliers.php' class='link'>Add new supplier</a>";
						}
						else{
							if($nbr_pro == 0)
							{
								echo"<img src='img/warning.png' class='small' /> Can't purchase !!! No product !!!";
								echo " <a href='stock.php' class='link'>Add new product</a>";
							} else {
								// Add to existing quantity
								if(isset($_POST["add"])) {
									$pro = new Product();
									$infos_pro = $pro->infosParCode($_POST["product_code"]);
									$product_name = $infos_pro[0];

									$total = $_POST["buy_price"] * $_POST["quantity"];
									$bdd = connexionDb();
									$req = $bdd->prepare("INSERT INTO entree (product, supplier, quantity, buy_price, total, lot, exp, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
									$req->execute(array($product_name, $_POST["supplier"], $_POST["quantity"], $_POST["buy_price"], $total, $_POST["quantity"], $_POST["exp"], dater()));
									$req->closeCursor();
									
									$s = new Shelf();
									$s->update_shelf_quantity_add($_POST["product_code"], $_POST["lot"], $_POST["quantity"]);
									echo"<div id='okMsg'>";
										echo "<img src='img/cool.png' class='small' /> The product <b>".$product_name."</b> were successfully added !!!";
									echo"</div>";
								}

								// Replace to existing quantity
								if(isset($_POST["replace"])) {
									$pro = new Product();
									$infos_pro = $pro->infosParCode($_POST["product_code"]);
									$product_name = $infos_pro[0];

									$total = $_POST["buy_price"] * $_POST["quantity"];
									$bdd = connexionDb();
									$req = $bdd->prepare("INSERT INTO entree (product, supplier, quantity, buy_price, total, lot, exp, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
									$req->execute(array($product_name, $_POST["supplier"], $_POST["quantity"], $_POST["buy_price"], $total, $_POST["quantity"], $_POST["exp"], dater()));
									$req->closeCursor();
									
									$s = new Shelf();
									$s->update_batch_shelf($_POST["product_code"], $_POST["lot"], $_POST["quantity"], $_POST["exp"]);
									echo"<div id='okMsg'>";
										echo "<img src='img/cool.png' class='small' /> The product <b>".$product_name."</b> were successfully replaced !!!";
									echo"</div>";
								}

								if(isset($_POST["save"]))
								{
									$sup = new Supplier();
									$res = $sup->is_supplier_exist($_POST["supplier_name"]);
									if($res == false)
									{
										echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The supplier <b>".$_POST["supplier_name"]."</b> does NOT exist !!!";
										echo"</div>";
									} else {
										$p = new Product();
										$res = $p->is_product_exist($_POST["product_name"]);
										if($res == false)
										{
											echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The product <b>".$_POST["product_name"]."</b> does NOT exist !!!";
											echo"</div>";
										} else {
											if($_POST["quantity"] <= 0)
											{
												echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["quantity"]."</b>: Invalid quantity !!!";
												echo"</div>";
											} else {
												if($_POST["buy_price"] <= 0)
												{
													echo"<div id='askMsg'>";
											echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["buy_price"]."</b>: Invalid buying price !!!";
													echo"</div>";
												} else {
													if($_POST["exp"] <= dater())
													{
														echo"<div id='askMsg'>";
												echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".dateEn($_POST["exp"])."</b>: Invalid expiry date !!!";
														echo"</div>";
													} else {
									$pro = new Product();
									$infos_pro = $pro->infosParNom($_POST["product_name"]);
									$product_code = $infos_pro[0];

									$s = new Shelf();
									$res = $s->is_lot_for_this_product_exist($product_code, $_POST["lot"]);
									if($res == true) {
										echo"<div id='askMsg'>";
											echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The batch nr <b>".$_POST["lot"]."</b> already exist for <b>".$_POST["product_name"]."</b> !!!";
											echo "<p>";
												echo "<form action='' method='post'>";
													echo "<input type='hidden' name='quantity' value=".$_POST["quantity"]." >";
													echo "<input type='hidden' name='product_code' value='".$product_code."' >";
													echo "<input type='hidden' name='lot' value='".$_POST["lot"]."' >";
													echo "<input type='hidden' name='supplier' value='".$_POST["supplier_name"]."' >";
													echo "<input type='hidden' name='buy_price' value=".$_POST["buy_price"]." >";
													echo "<input type='hidden' name='exp' value=".$_POST["exp"]." >";
													echo "<input type='submit' name='add' value='Add to existing Quantity' >";
													echo " <input type='submit' name='replace' value='Replace existing Quatity' >";
													echo " <a href='' class='link'>Cancel</a>";
												echo "</form>";
											echo "</p>";
										echo"</div>";
									} else {
										$s = new Shelf();
										$s->add_entry($_POST["product_name"], $_POST["supplier_name"], $_POST["quantity"], $_POST["buy_price"], $_POST["lot"], $_POST["exp"]);
										echo"<div id='okMsg'>";
											echo "<img src='img/cool.png' class='small' /> The product <b>".$_POST["product_name"]."</b> was saved successfully !!!";
										echo"</div>";
									}
													}
												}
											}
										}
									}
								}
					?>
						<form action="" method="post">
							<table>
								<tr>
									<td>Supplier: </td>
									<td>
	<input type="text" size="40" id="supplier_name" name="supplier_name" placeholder="Supplier name" autocomplete="off" required />
									</td>
									<td>Product: </td>
									<td>
	<input type="text" id="product_name" name="product_name" size="50" placeholder="Product name" autocomplete="off" required />
									</td>
								</tr>
								<tr>
									<td>Batch num: </td><td><input type="text" name="lot" placeholder="Batch num" required /></td>
									<td>Expiry date: </td><td><input type="date" name="exp" placeholder="Expiry date" required /></td>
								</tr>
								<tr>
									<td>Buying price: </td><td><input type="number" name="buy_price" placeholder="Purchase price" required /></td>
									<td>Quantity: </td><td><input type="number" name="quantity" placeholder="Quantity" required /></td>
									<td>
										<input type="submit" name="save" class="btn" value="Save entry" required />
									</td>
								</tr>
							</table>
						</form>
				<?php
						}
					}
				?>
			</div>
			<div id="interne">
				<?php
					if(isset($_POST["delete_entry"]))
					{
						echo"<div id='askMsg'>";
							echo"<form action='' method='post'>";
			echo "<img src='img/warning.png' class='small' /> Delete the entry: <b>".$_POST["product"]."</b> of batch num <b>".$_POST["lot"]."</b> - Quantity: <b>".$_POST["quantity"]."</b> !!! ";
								echo"<input type='hidden' name='id_entry' value=".$_POST["id_entry"]." />";
		echo"<input type='submit' name='del_only_entry' value='Delete the entry' class='small_btn other_red_btn' /> <a href='' class='link'>Cancel</a>";
							echo"</form>";
						echo"</div>";
					}
					
					if(isset($_POST["del_only_entry"]))
					{
						echo"<div id='okMsg'>";
							$s = new Shelf();
							$s->del_entry($_POST["id_entry"]);
							echo "<img src='img/cool.png' class='small' /> The purchase was well deleted !!!";
						echo"</div>";
					}
					
					if(nombre("entree") == 0)
					{
						echo "<p>";
							echo "No purchase !!!";
						echo "</p>";
					}
					else{
						?>
							<form action="" method="GET">
										<label for="month">Month:</label>
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
										<label for="supplier">Supplier:</label>
<input type="text" name="supplier" value="<?php if(isset($_GET["supplier"])){echo $_GET["supplier"];}else{echo "All";} ?>" id="supplier" size="20" title="Enter a supplier name." value="All" placeholder="Supplier name or Ctrl + F" autocomplete="off" required />
									<label for="product">Product:</label>
<input type="text" name="product" value="<?php if(isset($_GET["product"])){echo $_GET["product"];}else{echo "All";} ?>" id="product" size="20" title="Enter a product name." placeholder="Product name" autocomplete="off" required />
								<input type="submit" value="Search" class="btn" required />
								<?php
									if(isset($_GET["month"])) {
										echo "<a href='purchases.php' class='link'><<< Back</a>";
									}
								?>
							</form>
					<?php
						if(isset($_GET["month"]) && isset($_GET["year"]) && isset($_GET["supplier"]) && isset($_GET["product"]))
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
												$s = new Shelf();
												$s->search_entries($month, $year, $_GET["supplier"], $_GET["product"]);
											}
						}
						else{
							$s = new Shelf();
							$s->all_entries();
						}
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