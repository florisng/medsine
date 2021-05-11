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
					<img src="img/stock.png" class="icon" />
					<span class="titre">Stock</span>
<input type="text" name="product" id="product" size="40" title="Enter a product." placeholder="Product name or Ctrl + F" autocomplete="off" required />
					<input type="submit" name="details" value="Search" class="btn" required />
                    <a href="products.php" class="btn right"><img src="./img/products.png" class="icon_in_menu" alt="">All products</a>
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
					if(isset($_POST["save"]))
					{
						if($_POST["quantity"] <= 0)
						{
							echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> ";
								echo "<b>Error !!!</b> Invalid quantity <b>".$_POST["quantity"]."</b> !!!";
							echo"</div>";
						} else {
								if($_POST["price_100"] < 0)
								{
									echo"<div id='askMsg'>";
										echo "<img src='img/warning.png' class='small' /> ";
										echo "<b>Error !!!</b> Invalid price <b>".$_POST["price_100"]."</b> !!!";
									echo"</div>";
								} else {
												if($_POST["exp"] < dater())
												{
													echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> ";
				echo "<b>Error !!!</b> Invalid Expiry date !!! <b>".dateEn($_POST["exp"])."</b> Can't be less than today.";
													echo"</div>";
												} else {
													$p = new Product();
													$res = $p->is_product_exist($_POST["product_name"]);
													if($res == true) {
														echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> ";
				echo "<b>Error !!!</b> The product <b>".$_POST["product_name"]."</b> already exist.";
				$pro = new Product();
				$infos_pro = $pro->infosParNom($_POST["product_name"]);
				$code_pro = $infos_pro[0];
				echo" <a href='update_product_shelf.php?code_pro=".$code_pro."' class='link'>See the details</a>";
														echo"</div>";
													} else {
														$pro = new Product();
														$product_code = $pro->product_code_generator();
						$p = new Product();
						$code_pro = $p->add($product_code, $_POST["product_name"], $_POST["unit"], $_POST["price_100"], 0, $_POST["secu_shelf"]);

						$s = new Shelf();
						$s->add_new_batch_in_shelf($product_code, $_POST["lot"], $_POST["quantity"], $_POST["exp"]);
						echo"<div id='okMsg'>";
							echo "<img src='img/cool.png' class='small' /> The product <b>".strtoupper($_POST["product_name"])."</b> was successfully saved !!!";
						echo"</div>";
													}
												}
								}
						}
					}
				?>
				<form action="" method="post">
							<table>
								<tr>
									<td>Add product: </td>
									<td>
	<input type="text" id="pro" name="product_name" size="40" placeholder="Product name" autocomplete="off" required />
									</td>
								</tr>
								<tr>
									<td>Stock min.: </td><td><input type="number" name="secu_shelf" placeholder="Security in Shelf" required /></td>
									<td>Quantity: </td><td><input type="number" name="quantity" placeholder="Quantity" required /></td>
								</tr>
								<tr>
									<td>Unit: </td><td><input type="text" id="unit" name="unit" placeholder="Unit" required /></td>
									<td>Price 100%: </td><td><input type="number" name="price_100" placeholder="Price 100%" required /></td>
								</tr>
								<tr>
									<td>Batch num: </td><td><input type="text" name="lot" placeholder="Batch number" required /></td>
									<td>Exp. date: </td><td><input type="date" name="exp" placeholder="Expiry date" required /></td>
									<td>
										<input type="submit" name="save" class="btn" value="Save the product" required />
									</td>
								</tr>
							</table>
				</form>
				</div>
				<div id="interne">
				<?php
					if(nombre("product") == 0)
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
							$s = new Shelf();
							if(isset($_POST["a"])) {
								$s->tous_by_letter_shelf("a");
								$one = true;
							}
							if(isset($_POST["b"])) {
								$s->tous_by_letter_shelf("b");
								$one = true;
							}
							if(isset($_POST["c"])) {
								$s->tous_by_letter_shelf("c");
								$one = true;
							}
							if(isset($_POST["d"])) {
								$s->tous_by_letter_shelf("d");
								$one = true;
							}
							if(isset($_POST["e"])) {
								$s->tous_by_letter_shelf("e");
								$one = true;
							}
							if(isset($_POST["f"])) {
								$s->tous_by_letter_shelf("f");
								$one = true;
							}
							if(isset($_POST["g"])) {
								$s->tous_by_letter_shelf("g");
								$one = true;
							}
							if(isset($_POST["h"])) {
								$s->tous_by_letter_shelf("h");
								$one = true;
							}
							if(isset($_POST["i"])) {
								$s->tous_by_letter_shelf("i");
								$one = true;
							}
							if(isset($_POST["j"])) {
								$s->tous_by_letter_shelf("j");
								$one = true;
							}
							if(isset($_POST["k"])) {
								$s->tous_by_letter_shelf("k");
								$one = true;
							}
							if(isset($_POST["l"])) {
								$s->tous_by_letter_shelf("l");
								$one = true;
							}
							if(isset($_POST["m"])) {
								$s->tous_by_letter_shelf("m");
								$one = true;
							}
							if(isset($_POST["n"])) {
								$s->tous_by_letter_shelf("n");
								$one = true;
							}
							if(isset($_POST["o"])) {
								$s->tous_by_letter_shelf("o");
								$one = true;
							}
							if(isset($_POST["p"])) {
								$s->tous_by_letter_shelf("p");
								$one = true;
							}
							if(isset($_POST["q"])) {
								$s->tous_by_letter_shelf("q");
								$one = true;
							}
							if(isset($_POST["r"])) {
								$s->tous_by_letter_shelf("r");
								$one = true;
							}
							if(isset($_POST["s"])) {
								$s->tous_by_letter_shelf("s");
								$one = true;
							}
							if(isset($_POST["t"])) {
								$s->tous_by_letter_shelf("t");
								$one = true;
							}
							if(isset($_POST["u"])) {
								$s->tous_by_letter_shelf("u");
								$one = true;
							}
							if(isset($_POST["v"])) {
								$s->tous_by_letter_shelf("v");
								$one = true;
							}
							if(isset($_POST["w"])) {
								$s->tous_by_letter_shelf("w");
								$one = true;
							}
							if(isset($_POST["x"])) {
								$s->tous_by_letter_shelf("x");
								$one = true;
							}
							if(isset($_POST["y"])) {
								$s->tous_by_letter_shelf("y");
								$one = true;
							}
							if(isset($_POST["z"])) {
								$s->tous_by_letter_shelf("z");
								$one = true;
							}
							
							// By default
							if($one == false) {
								$s = new Shelf();
								$s->tous_shelf();
							}
						}
				?>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#unit').autocomplete({
					serviceUrl: './selections/sel_unit.php',
					dataType: 'json'
				});
			});
			$(document).ready(function() {
				$('#product').autocomplete({
					serviceUrl: './selections/sel_product.php',
					dataType: 'json'
				});
			});
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