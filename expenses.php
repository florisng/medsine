<?php
	include("functions.php");
	$_SESSION["page"] = "expenses";
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
				require("./classes/class_expense.php");
				require("./classes/class_user.php");
		?>
		<div id="welcome">
			<?php include("header.php"); ?>
			<div id="entete">
					<form action="" method="get">
						<img src="img/expenses.png" class="icon" />
						<span class="titre">Expenses | </span>
						Month / Year:
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
											<input type="submit" value="Search" class="btn" required />
							</form>
				</div>
				<div id="sous_entete">
					<?php
						if(isset($_POST["save"]))
						{
							if($_POST["amount"] <= 0){
								echo"<div id='askMsg'>";
									echo "<img src='img/warning.png' class='small' /> ";
									echo "The amount can't be <b>0</b> or less !!!";
								echo"</div>";
							} else {
								echo"<div id='okMsg'>";
									$e = new Expense();
									$e->add($_POST["descri"], $_POST["amount"], $_POST["date"]);
									echo "<img src='img/cool.png' class='small' /> ";
									echo "The expense is well saved !!!";
								echo"</div>";
							}
						}
						
						if(isset($_POST["ok_modify"]))
						{
							echo"<div id='okMsg'>";
								$e = new Expense();
								$e->update_expense($_POST["descri"], $_POST["amount"], $_POST["date"], $_POST["id_exp"]);
								echo "<img src='img/cool.png' class='small' /> Saved !!!";
							echo"</div>";
						}
									
						if(isset($_POST["modify"])){
							$e = new Expense();
							$infos_exp = $e->infos_expense($_POST["id_exp"]);
							$descri = $infos_exp[0];
							$amount = $infos_exp[1];
							$date = $infos_exp[2];
							echo "<div id='form'>";
								echo "<form action='' method='post'>";
									echo "<table>";
										echo "<tr>";
											echo "<td>Date: </td>";
											echo "<td>";
												echo "<input type='date' name='date' value=".$date." required />";
											echo "</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td>Description: </td>";
											echo "<td>";
		echo "<input type='text' size='40' name='descri' placeholder='Description' value='".$descri."' autocomplete='off' required />";
											echo "</td>";
											echo "<td>Amount: </td>";
											echo "<td>";
												echo "<input type='text' name='amount' value=".$amount." placeholder='Amount' required />";
											echo "</td>";
											echo "<td>";
												echo "<input type='hidden' name='id_exp' value=".$_POST["id_exp"]." required />";
												echo "<input type='submit' name='ok_modify' value='Save changes' class='btn' required />";
											echo "</td>";
											echo "<td>";
												echo "<a href='' class='link'>Cancel</a>";
											echo "</td>";
										echo "</tr>";
									echo "</table>";
								echo "</form>";
							echo "</div>";
						}
						else{
					?>
						<div id="form">
							<form action="" method="post">
								<table>
									<tr>
										<td>Date: </td>
										<td>
											<input type="date" name="date" value="<?php echo dater(); ?>" required />
										</td>
									</tr>
									<tr>
										<td>Description: </td>
										<td>
			<input type="text" size="40" maxlength="50" name="descri" placeholder="Description" autocomplete="off" required />
										</td>
										<td>Amount: </td>
										<td>
											<input type="text" name="amount" placeholder="Amount" required />
										</td>
										<td>
											<input type="submit" name="save" value="Save" class="btn" required />
										</td>
									</tr>
								</table>
							</form>
						</div>
						<?php
					}
					?>
					</div>
					<div id="interne">
				<?php
						if(isset($_POST["delete_expense"]))
						{
							echo"<div id='askMsg'>";
								echo"<form action='' method='post'>";
		echo "<img src='img/warning.png' class='small' /> <b>Warning</b>: Do you really want to delete the expense !!! ";
									echo"<input type='hidden' name='id_exp' value='".$_POST["id_exp"]."' />";
									echo"<input type='submit' name='ok_delete_expense' value='Yes delete' class='small_btn' /> <a href='' class='link'>Cancel</a>";
								echo"</form>";
							echo"</div>";
						}
						
						if(isset($_POST["ok_delete_expense"]))
						{
							echo"<div id='okMsg'>";
								$ex = new Expense();
								$ex->delete_expense($_POST["id_exp"]);
								echo "<img src='img/cool.png' class='small' /> The expense has been well deleted !!!";
							echo"</div>";
						}
						
						$nbr_exp = nombre("expense");
						if($nbr_exp == 0)
						{
							echo "<p>";
								echo "No expenses !!!";
							echo "</p>";
						} else {
							if(isset($_GET["month"]) AND isset($_GET["year"]))
							{
											$ok = true;
											
											if($_GET["month"] != "All") {
												$month = intval($_GET["month"]);
												if($month <= 0 || $month > 12) {
													$ok = false;
													echo"<div id='askMsg'>";
								echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> Invalid month value: '<b>".$_GET["month"]."</b>' !!!";
													echo"</div>";
												}
											}
											
											if($_GET["year"] != "All") {
												$year = intval($_GET["year"]);
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
												$exp = new Expense();
												// $exp->tous_by_period($month, $year);
												$exp->tous_by_period($_GET["month"], $_GET["year"]);
											}
							} else {
								$exp = new Expense();
								$exp->tous();
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