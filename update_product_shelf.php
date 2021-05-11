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
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#product').autocomplete({
					serviceUrl: './selections/sel_product.php',
					dataType: 'json'
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#unit').autocomplete({
					serviceUrl: './selections/sel_unit.php',
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
				if(isset($_GET["code_pro"]))
				{
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<?php
				if($_SESSION["type"] == "Manager" OR $_SESSION["type"] == "Admin") {
			?>
			<div id="entete">
				<?php
					$p = new Product();
					$res = $p->is_code_product_exist($_GET["code_pro"]);
					if($res == false)
					{
						echo"<div id='askMsg'>";
		echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The product code '<b>".$_GET["code_pro"]."</b>' does NOT exist.";
						echo"</div>";
					}
					else
					{
						echo "<form action='' method='post'>";
							echo "<img src='img/stock.png' class='icon' /> ";
							echo "<span class='titre'>Stock - </span>Code: ".$_GET["code_pro"]." - ";
							echo "Search for a product:"; 
	echo "<input type='text' name='product' id='product' size='40' title='Enter a product.' placeholder='Product name' autocomplete='off' required />";
							echo "<input type='submit' name='details' value='Search' class='btn' required />";
							echo "<a href='shelf.php' class='btn'>Stock</a>";
						echo "</form>";
						
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
								header("Location: ?code_pro=".$code_pro."");
							}
						}
				?>
			</div>
			<div id="interne">
								<?php
									if(isset($_POST["ok"]))
									{
										$pro = new Product();
										$res = $pro->is_product_exist($_POST["product"]);
										if($res == false)
										{
											echo"<div id='askMsg'>";
												echo "<img src='img/warning.png' class='small' /> ";
												echo "<b>Error !!!</b> The product <b>".$_POST["product"]."</b> does NOT exist.";
											echo"</div>";
										}
										else{
											$pro = new Product();
											$infos_pro = $pro->infosParNom($_POST["product"]);
											$code_pro = $infos_pro[0];
											header("Location: ?code_pro=".$code_pro."");
										}
									}
									
									if(isset($_POST["modify"]))
									{
										$pro = new Product();
										$res = $pro->is_product_exist_except($_POST["old_product"], $_POST["new_name"]);
										if($_POST["prix_vente_100"] < 0) {
				echo"<div id='askMsg'>";
					echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The 100% Price <b>".$_POST["prix_vente_100"]."</b> must be greater than 0 !!!";
				echo"</div>";
										} else {
											if($_POST["prix_vente_insu"] < 0) {
												echo"<div id='askMsg'>";
													echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The Insurance Price <b>".$_POST["prix_vente_insu"]."</b> must be greater than 0 !!!";
												echo"</div>";
											} else {
												if($_POST["secu_stock"] < 0) {
													echo"<div id='askMsg'>";
														echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The quantity security <b>".$_POST["secu_stock"]."</b> must be greater than 0 !!!";
													echo"</div>";
												} else {
				$p = new Product();
				$p->modify_product_info($_POST["new_name"], $_POST["product_unit"], $_POST["prix_vente_100"], $_POST["prix_vente_insu"], $_POST["secu_stock"], $_POST["code_pro"]);
				echo"<div id='okMsg'>";
					echo "<img src='img/cool.png' class='small' /> The changes are saved !!!";
				echo"</div>";
												}
											}
										}
									}
									
									$p = new Product();
									$infos = $p->infosParCode($_GET["code_pro"]);
									echo "<div id=''>";
										echo "<form action='' method='post'>";
											echo "<table>";
												echo "<tr>";
													echo "<td>Product: </td>";
													echo "<td>";
					$product_name = $infos[0];
					echo "<input type='text' name='new_name' size='40' value='".$product_name."' placeholder='Product name' autocomplete='off' required />";
													echo "</td>";
												echo "</tr>";
												echo "<tr>";
													echo "<td>100% Price: </td>";
													echo "<td>";
					$price_100 = $infos[4];
					echo "<input type='text' value=".$price_100." name='prix_vente_100' placeholder='Sell. price - 100%' required />";
													echo "</td>";
													echo "<td>Stock security: </td>";
													echo "<td>";
					$secu_stock = $infos[2];
					echo "<input type='text' value=".number_format($secu_stock)." name='secu_stock' placeholder='stock security' required />";
													echo "</td>";
												echo "</tr>";
												echo "<tr>";
													echo "<td>Insur. Price: </td>";
													echo "<td>";
					$insu_price = $infos[3];
					echo "<input type='text' value=".$insu_price." name='prix_vente_insu' placeholder='Sell. price - Insur.' required />";
													echo "</td>";
													echo "<td>Unit: </td>";
													echo "<td>";
					$product_unit = $infos[1];
					echo "<input type='text' value='".$product_unit."' name='product_unit' id='unit' placeholder='Unit' required />";
													echo "<td>";
														echo "<input type='hidden' name='old_product' value='".$product_name."' />";
														echo "<input type='hidden' name='code_pro' value=".$_GET["code_pro"]." />";
														echo "<input type='submit' name='modify' value='Apply' class='btn' />";
													echo "</td>";
												echo "</tr>";
											echo "</table>";
										echo "</form>";
									echo "</div>";
					echo "<hr>";
					echo "<p>";
						echo "<span class='moja link'>Add a batch</span>";
					echo "</p>";
					
					if(isset($_POST["delete_lot"]))
					{
						$s = new Shelf();
						$s->delete_lot_in_shelf($_POST["id_shelf"]);
					}
					
					if(isset($_POST["update_shelf"]))
					{
						if($_POST["quantity"] < 0)
						{
							echo"<div id='askMsg'>";
	echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The quantity can't be less than 0";
							echo"</div>";
						}
						else{
							$s = new Shelf();
							$res = $s->is_lot_for_this_product_exist_except($_GET["code_pro"], $_POST["new_lot"], $_POST["old_lot"]);
							if($res == true)
							{
								echo"<div id='askMsg'>";
		echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The batch number '<b>".$_POST["new_lot"]."</b>' already exists for this product.";
								echo"</div>";
							}
							else{
								echo"<div id='okMsg'>";
		$s = new Shelf();
		$s->update_shelf($_GET["code_pro"], $_POST["new_lot"], $_POST["old_lot"], $_POST["quantity"], $_POST["exp"]);
		echo "<img src='img/cool.png' class='small' /> Successfully updated.";
								echo"</div>";
							}
						}
					}
					
					if(isset($_POST["add_batch"]))
					{
						$s = new Shelf();
						$res = $s->is_lot_for_this_product_exist($_GET["code_pro"], $_POST["lot"]);
						if($res == true)
						{
							echo"<div id='askMsg'>";
	echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The product batch num '<b>".$_POST["lot"]."</b>' already exists.";
									echo"</div>";
						}
						else{
							$s = new Shelf();
	$s->add_new_batch_in_shelf($_GET["code_pro"], $_POST["lot"], $_POST["quantity"], $_POST["exp"]);
						}
					}
					
					echo "<div id='moja'>";
						echo "<div id=''>";
							echo "<form action='' method='post'>";
								echo "<table>";
									echo "<tr>";
										echo "<td>Batch num: </td>";
										echo "<td>";
		echo "<input type='text' name='lot' title='Enter a batch num.' placeholder='Batch num' autocomplete='off' required />";
										echo "</td>";
										echo "<td>Quantity: </td>";
										echo "<td>";
		echo "<input type='number' name='quantity' title='Enter a quantity.' placeholder='Quantity' autocomplete='off' required />";
										echo "</td>";
										echo "<td>Expiry date: </td>";
										echo "<td>";
		echo "<input type='date' name='exp' title='Enter a date.' placeholder='Expiry date' autocomplete='off' required />";
										echo "</td>";
										echo "<td>";
											echo "<input type='submit' name='add_batch' value='Add' class='btn' required />";
											echo " <span class='funga link'>Cancel</span>";
										echo "</td>";
									echo "</tr>";
								echo "</table>";
							echo "</form>";
						echo "</div>";
					echo "</div>";
					echo "<div id='update_shelf'>";
						$s = new Shelf();
						$s->shelf_details_for_product($_GET["code_pro"]);
					echo "</div>";

									echo "<p>";
										echo "<div class='hidden'>";
											echo "<div id='two'>";
												echo "<form action='stock.php' method='post' id='askMsg'>";
													echo "<img src='img/warning.png' class='small' /> Do you really want to delete this product?";
													$pro = new Product();
													$product_infos = $pro->infosParCode($_GET["code_pro"]);
													$product_name = $product_infos[0];
													echo "<input type='hidden' name='product_name' value='".$product_name."' />";
													echo "<input type='hidden' name='product_code' value='".$_GET["code_pro"]."' />";
													echo "<input type='submit' name='confirm_delete_product' value='Yes delete' class='other_red_btn small_btn' />";
													echo " <span class='two link'>Cancel</span>";
												echo "</form>";
											echo "</div>";
											echo "<span class='one red_btn small_btn'><img src='./img/delete.png' class='small small_down'> Delete this product</span>";
										echo "</div>";
									echo "</p>";
								}
					?>
			</div>
			<?php
				} else {
					echo "<div id='interne'>";
						echo"<div id='askMsg'>";
							echo "<img src='img/warning.png' class='small' /> Oops !!! You have no right to access this information !!!";
						echo"</div>";
					echo"</div>";
				}
			?>
		</div>
		<?php
				}
			}
			else{
				header('Location: interdit.php');
			}
		?>
    </body>
</html>