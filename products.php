<?php
	include("functions.php");
	$_SESSION["page"] = "stock";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Medsine</title>
        <meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
		<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#pro').autocomplete({
					serviceUrl: './selections/sel_all_product.php',
					dataType: 'json'
				});
			});
		</script>
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_shelf.php");
				require("./classes/class_product.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="entete">
				<form action="" method='post' class="hidden">
					<img src="./img/products.png" class="icon" />
					<span class="titre">All products</span>
                    <a href="stock.php" class="btn right"><img src="./img/stock.png" class="icon_in_menu" alt="">Stock</a>
					<?php
						if(isset($_POST["details"])) {
							$pro = new Product();
							$res = $pro->is_product_exist($_POST["product"]);
							if($res == false) {
								echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> ";
									echo "<b>Error !!!</b> The product <b>".$_POST["product"]."</b> does NOT exist !!!";
								echo"</div>";
							} else {
								$pro = new Product();
								$infos_pro = $pro->infosParNom($_POST["product"]);
								$code_pro = $infos_pro[0];
								header("Location: update_product_shelf.php?code_pro=".$code_pro."");
							}
						}
					?>
				</form>
			</div>
			<div id="sous_entete">
				<?php
					if(isset($_POST["add"])) {
						$pro = new Product();
						$res = $pro->is_product_exist_in_allProducts($_POST["product_name"]);
						if($res == false) {
							echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> ";
								echo "<b>Error !!!</b> The product <b>".$_POST["product_name"]."</b> does NOT exist !!!";
							echo"</div>";
						} else {
							$pro = new Product();
							$res = $pro->is_product_exist($_POST["product_name"]);
							if($res == true) {
								echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> ";
									echo "<b>Error !!!</b> The product <b>".$_POST["product_name"]."</b> already exist in stock !!!";
								echo"</div>";
							} else {
								if($_POST["qty"] <= 0) {
									echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> ";
										echo "<b>Error !!!</b> Invalid Quantity value <b>".$_POST["qty"]."</b> !!!";
									echo"</div>";
								} else {
									if($_POST["secu"] <= 0) {
										echo"<div id='askMsg'>";
											echo "<img src='img/warning.png' class='small' /> ";
											echo "<b>Error !!!</b> Invalid value of Stock security <b>".$_POST["secu"]."</b> !!!";
										echo"</div>";
									} else {
										if($_POST["price_100"] <= 0) {
											echo"<div id='askMsg'>";
												echo "<img src='img/warning.png' class='small' /> ";
												echo "<b>Error !!!</b> Invalid Price value <b>".$_POST["price_100"]."</b> !!!";
											echo"</div>";
										} else {
											if($_POST["exp_date"] <= dater()) {
												echo"<div id='askMsg'>";
													echo "<img src='img/warning.png' class='small' /> ";
													echo "<b>Error !!!</b> Invalid expiry date <b>".dateEn($_POST["exp_date"])."</b> !!!";
												echo"</div>";
											} else {
							$pro = new Product();
							$pro_infos = $pro->infosParNom_allProducts($_POST["product_name"]);
							$product_code = $pro_infos[0];
							$product_unit = $pro_infos[1];
							$insu_price = $pro_infos[2];

							$pro = new Product();
							$pro->add($product_code, $_POST["product_name"], $product_unit, $_POST["price_100"], $insu_price, $_POST["secu"]);

							$s = new Shelf();
							$s->add_new_batch_in_shelf($product_code, $_POST["batch_num"], $_POST["qty"], $_POST["exp_date"]);
							echo"<div id='okMsg'>";
								echo "<img src='img/cool.png' class='small' /> The product <b>".strtoupper($_POST["product_name"])."</b> was successfully saved !!!";
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
							<td>Product</td>
							<td><input type="text" size="60" id="pro" name="product_name" placeholder="Product name" required /></td>
							<td>Stock sec.</td>
							<td><input type="number" name="secu" placeholder="Security Stock" required /></td>
						</tr>
						<tr>
							<td>Quantity</td>
							<td><input type="number" name="qty" placeholder="Quantity" required /></td>
							<td>Price 100%</td>
							<td><input type="number" name="price_100" placeholder="Price 100%" required /></td>
						</tr>
						<tr>
							<td>Batch number</td>
							<td><input type="text" name="batch_num" placeholder="Batch num." required /></td>
							<td>Expiry date</td>
							<td><input type="date" name="exp_date" required /></td>
							<td><input type="submit" name="add" value="Add in Stock"></td>
						</tr>
					</table>
				</form>
			</div>
			<div id="interne">
				<?php
					if(nombre("all_products") == 0)
					{
						echo "<p>";
							echo "No product !!!";
						echo "</p>";
					}
					else{
						echo "<div id='product_by_letter' class='product_by_letter'>";
							echo "<form action='#product_by_letter' method='post'>";
								?>
									<input type='submit' name='a' <?php if(isset($_POST["a"])) {echo "class=selected_letter";} ?> value='A' />
									<input type='submit' name='b' <?php if(isset($_POST["b"])) {echo "class=selected_letter";} ?> value='B' />
									<input type='submit' name='c' <?php if(isset($_POST["c"])) {echo "class=selected_letter";} ?> value='C' />
									<input type='submit' name='d' <?php if(isset($_POST["d"])) {echo "class=selected_letter";} ?> value='D' />
									<input type='submit' name='e' <?php if(isset($_POST["e"])) {echo "class=selected_letter";} ?> value='E' />
									<input type='submit' name='f' <?php if(isset($_POST["f"])) {echo "class=selected_letter";} ?> value='F' />
									<input type='submit' name='g' <?php if(isset($_POST["g"])) {echo "class=selected_letter";} ?> value='G' />
									<input type='submit' name='h' <?php if(isset($_POST["h"])) {echo "class=selected_letter";} ?> value='H' />
									<input type='submit' name='i' <?php if(isset($_POST["i"])) {echo "class=selected_letter";} ?> value='I' />
									<input type='submit' name='j' <?php if(isset($_POST["j"])) {echo "class=selected_letter";} ?> value='J' />
									<input type='submit' name='k' <?php if(isset($_POST["k"])) {echo "class=selected_letter";} ?> value='K' />
									<input type='submit' name='l' <?php if(isset($_POST["l"])) {echo "class=selected_letter";} ?> value='L' />
									<input type='submit' name='m' <?php if(isset($_POST["m"])) {echo "class=selected_letter";} ?> value='M' />
									<input type='submit' name='n' <?php if(isset($_POST["n"])) {echo "class=selected_letter";} ?> value='N' />
									<input type='submit' name='o' <?php if(isset($_POST["o"])) {echo "class=selected_letter";} ?> value='O' />
									<input type='submit' name='p' <?php if(isset($_POST["p"])) {echo "class=selected_letter";} ?> value='P' />
									<input type='submit' name='q' <?php if(isset($_POST["q"])) {echo "class=selected_letter";} ?> value='Q' />
									<input type='submit' name='r' <?php if(isset($_POST["r"])) {echo "class=selected_letter";} ?> value='R' />
									<input type='submit' name='s' <?php if(isset($_POST["s"])) {echo "class=selected_letter";} ?> value='S' />
									<input type='submit' name='t' <?php if(isset($_POST["t"])) {echo "class=selected_letter";} ?> value='T' />
									<input type='submit' name='u' <?php if(isset($_POST["u"])) {echo "class=selected_letter";} ?> value='U' />
									<input type='submit' name='v' <?php if(isset($_POST["v"])) {echo "class=selected_letter";} ?> value='V' />
									<input type='submit' name='w' <?php if(isset($_POST["w"])) {echo "class=selected_letter";} ?> value='W' />
									<input type='submit' name='x' <?php if(isset($_POST["x"])) {echo "class=selected_letter";} ?> value='X' />
									<input type='submit' name='y' <?php if(isset($_POST["y"])) {echo "class=selected_letter";} ?> value='Y' />
									<input type='submit' name='z' <?php if(isset($_POST["z"])) {echo "class=selected_letter";} ?> value='Z' />
								<?php
								echo "<a href='' class='btn'>All</a>";
							echo "</form>";
						echo "</div>";

						if(isset($_POST["confirm_delete_product"]))
						{
							echo"<div id='okMsg'>";
								$pro = new Product();
								$pro->delete_product($_POST["product_code"]);
								echo "<img src='img/cool.png' class='small' /> The product <b>".$_POST["product_name"]."</b> was successfully deleted.";
							echo"</div>";
							
						}
							
							$one = false;
							$pro = new Product();
							if(isset($_POST["a"])) {
								$pro->tous_by_letter_product("a");
								$one = true;
							}
							if(isset($_POST["b"])) {
								$pro->tous_by_letter_product("b");
								$one = true;
							}
							if(isset($_POST["c"])) {
								$pro->tous_by_letter_product("c");
								$one = true;
							}
							if(isset($_POST["d"])) {
								$pro->tous_by_letter_product("d");
								$one = true;
							}
							if(isset($_POST["e"])) {
								$pro->tous_by_letter_product("e");
								$one = true;
							}
							if(isset($_POST["f"])) {
								$pro->tous_by_letter_product("f");
								$one = true;
							}
							if(isset($_POST["g"])) {
								$pro->tous_by_letter_product("g");
								$one = true;
							}
							if(isset($_POST["h"])) {
								$pro->tous_by_letter_product("h");
								$one = true;
							}
							if(isset($_POST["i"])) {
								$pro->tous_by_letter_product("i");
								$one = true;
							}
							if(isset($_POST["j"])) {
								$pro->tous_by_letter_product("j");
								$one = true;
							}
							if(isset($_POST["k"])) {
								$pro->tous_by_letter_product("k");
								$one = true;
							}
							if(isset($_POST["l"])) {
								$pro->tous_by_letter_product("l");
								$one = true;
							}
							if(isset($_POST["m"])) {
								$pro->tous_by_letter_product("m");
								$one = true;
							}
							if(isset($_POST["n"])) {
								$pro->tous_by_letter_product("n");
								$one = true;
							}
							if(isset($_POST["o"])) {
								$pro->tous_by_letter_product("o");
								$one = true;
							}
							if(isset($_POST["p"])) {
								$pro->tous_by_letter_product("p");
								$one = true;
							}
							if(isset($_POST["q"])) {
								$pro->tous_by_letter_product("q");
								$one = true;
							}
							if(isset($_POST["r"])) {
								$pro->tous_by_letter_product("r");
								$one = true;
							}
							if(isset($_POST["s"])) {
								$pro->tous_by_letter_product("s");
								$one = true;
							}
							if(isset($_POST["t"])) {
								$pro->tous_by_letter_product("t");
								$one = true;
							}
							if(isset($_POST["u"])) {
								$pro->tous_by_letter_product("u");
								$one = true;
							}
							if(isset($_POST["v"])) {
								$pro->tous_by_letter_product("v");
								$one = true;
							}
							if(isset($_POST["w"])) {
								$pro->tous_by_letter_product("w");
								$one = true;
							}
							if(isset($_POST["x"])) {
								$pro->tous_by_letter_product("x");
								$one = true;
							}
							if(isset($_POST["y"])) {
								$pro->tous_by_letter_product("y");
								$one = true;
							}
							if(isset($_POST["z"])) {
								$pro->tous_by_letter_product("z");
								$one = true;
							}
							
							// By default
							if($one == false) {
								$pro = new Product();
								$pro->tous();
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