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
    </head>
	<body>
		<?php
			if(isset($_SESSION["connect"])) {
				require("./classes/class_product.php");
				require("./classes/class_stock.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="content">
				<p>
					<img src="img/details.png" class="icon" />
					<span class="titre">Product details</span>
				</p>
				<?php
					$p = new Product();
					$res = $p->is_code_product_exist($_GET["code_pro"]);
					if($res == false)
					{
						echo"<div id='askMsg'>";
							echo "<img src='img/warning.png' class='small' /> ";
							echo "<b>Error !!!</b> The product <b>".$_GET["code_pro"]."</b> does NOT exist!";
						echo"</div>";
					}
					else{
						

						$p = new Product();
						$infos = $p->infosParCode($_GET["code_pro"]);
						$product_name = $infos[0];
						$product_unit = $infos[1];
						$product_secu = $infos[2];

						echo "<div id='form'>";
							echo "<form action='' method='post'>";
								echo "<table>";
									echo "<tr>";
										echo "<td>Product name: </td>";
										echo "<td>";
		echo "<input type='text' name='new_name' size='40' value='".$product_name."' placeholder='Product name' autocomplete='off' required />";
										echo "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Security stock: </td>";
										echo "<td>";
				echo "<input type='number' value='".$product_secu."' name='product_secu' placeholder='Security stock' required />";
										echo "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Unit: </td>";
										echo "<td>";
				echo "<input type='text' value='".$product_unit."' name='product_unit' placeholder='Unit' required />";
										echo "</td>";
										echo "<td>";
											echo "<input type='hidden' name='code_pro' value='".$_GET["code_pro"]."' required />";
											echo "<input type='submit' name='modify' value='Save' required />";
										echo "</td>";
									echo "</tr>";
								echo "</table>";
							echo "</form>";
						echo "</div>";
						echo "<p>";
							$s = new Stock();
							$s->stock_details($_GET["code_pro"]);
						echo "</p>";
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