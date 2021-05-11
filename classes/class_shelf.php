<?php
	// Classe Shelf
	class Shelf
	{
		// Afficher tous les products
		public function tous_by_letter_shelf($l)
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th></th>";
					echo "<th>Code</th>";
					echo "<th>Product name</th>";
					echo "<th>Unit</th>";
					echo "<th>100% Price</th>";
					echo "<th>Insur. Price</th>";
					echo "<th>Stock Security</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM product");
			$i = 0;
			while($donnees = $reponse->fetch())
			{
				if(strtolower($donnees['nom'][0]) == $l)
				{
					$i++;
					if($i % 2 == 0){
						echo"<tr class='alt'>";
					}
					else{
						echo"<tr>";
					}
						echo "<td>".$i."</td>";
						echo "<td>".$donnees['code']."</td>";
						echo "<td class='keyword'>".$donnees['nom']."</td>";
						echo "<td>".$donnees['unit']."</td>";
						echo"<td><u><b>".number_format($donnees['price_100'])."</b></u></td>";
						echo"<td><u><b>".number_format($donnees['insu_price'])."</b></u></td>";
						echo"<td>".number_format($donnees['secu_shelf'])."</td>";
						echo"<td class='hidden'>";
							echo"<a href='update_product_shelf.php?code_pro=".$donnees['code']."' class='link'>Details</a>";
						echo "</td>";
					echo"</tr>";
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				echo "<th colspan='7'>".number_format($i)." result(s)</th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}
		
		// Afficher tous les products
		public function tous_shelf()
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th></th>";
					echo "<th>Code</th>";
					echo "<th>Product name</th>";
					echo "<th>Unit</th>";
					echo "<th>100% Price</th>";
					echo "<th>Insur. Price</th>";
					echo "<th>Stock Security</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM product");
			$i = 0;
			while($donnees = $reponse->fetch())
			{
					$i++;
					if($i % 2 == 0){
						echo"<tr class='alt'>";
					}
					else{
						echo"<tr>";
					}
						echo "<td>".$i."</td>";
						echo "<td>".$donnees['code']."</td>";
						echo "<td>".$donnees['nom']."</td>";
						echo "<td>".$donnees['unit']."</td>";
						echo"<td><u><b>".number_format($donnees['price_100'])."</b></u></td>";
						echo"<td><u><b>".number_format($donnees['insu_price'])."</b></u></td>";
						echo"<td>".number_format($donnees['secu_shelf'])."</td>";
						echo"<td class='hidden'>";
							echo"<a href='update_product_shelf.php?code_pro=".$donnees['code']."' class='link'>Details</a>";
						echo "</td>";
					echo"</tr>";
			}
			$reponse->closeCursor();
			echo "<tr>";
				echo "<th colspan='7'>".number_format($i)." result(s)</th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}

		// Afficher toutes les entrees
		public function all_entries()
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Supplier</th>";
					echo "<th>Product</th>";
					echo "<th>Quantity</th>";
					echo "<th>Buying price</th>";
					echo "<th>Total</th>";
					echo "<th>Batch num</th>";
					echo "<th>Expiry date</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM entree ORDER BY id DESC");
			$i = 0;
			$t = 0;
			$infosDate = infosDate(dater());
			$current_month = $infosDate[1];
			while($donnees = $reponse->fetch())
			{
				$infosDate = infosDate($donnees['date']);
				$month = $infosDate[1];
				if($current_month == $month)
				{
					$i++;
					echo"<form action='' method='post'>";
						if($donnees['date'] == dater())
						{
							echo"<tr class='today_move'>";
						}
						else{
							if($i % 2 == 0){
								echo"<tr class='alt'>";
							}
							else{
								echo"<tr>";
							}
						}
								echo"<td>".dateEn($donnees['date'])."</td>";
								echo"<td>".$donnees['supplier']."</td>";
								echo"<td>".$donnees['product']."</td>";
								echo"<td>".number_format($donnees['quantity'])."</td>";
								echo"<td>".number_format($donnees['buy_price'])."</td>";
								echo"<td>".number_format($donnees['total'])."</td>";
								echo"<td>".$donnees['lot']."</td>";
								echo"<td>".dateEn($donnees['exp'])."</td>";
								echo"<td>";
									echo"<input type='hidden' name='product' value='".$donnees['product']."' />";
									echo"<input type='hidden' name='lot' value='".$donnees['lot']."' />";
									echo"<input type='hidden' name='quantity' value='".$donnees['quantity']."' />";
									echo"<input type='hidden' name='id_entry' value='".$donnees['id']."' />";
									echo" <input type='submit' name='delete_entry' value='' class='del' title='Delete' />";
								echo"</td>";
							echo"</tr>";
					echo"</form>";
					$t = $t + $donnees['total'];
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				echo "<th colspan='5'>".number_format($i)." result(s)</th>";
				echo "<th>".number_format($t)."</th>";
				echo "<th colspan='2'></th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}

		// Afficher toutes les entrees
		public function search_entries($month, $year, $supplier, $product)
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Supplier</th>";
					echo "<th>Product</th>";
					echo "<th>Quantity</th>";
					echo "<th>Total</th>";
					echo "<th>Batch num</th>";
					echo "<th>Expiry date</th>";
				echo "</tr>";
			$bdd = connexionDb();
			
			echo "<p>";
			if($month != "all" AND $year == "all" AND $supplier == "All" AND $product == "All"){
				echo "Month: ".mois($month);
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." ORDER BY id DESC");
			}
			if($month != "all" AND $year != "all" AND $supplier == "All" AND $product == "All"){
				echo "Month: ".mois($month)." ".$year;
	$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." ORDER BY id DESC");
			}
			if($month != "all" AND $year != "all" AND $supplier != "All" AND $product == "All"){
				echo "Month: ".mois($month)." ".$year." - Supplier: ".$supplier;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND supplier = '".$supplier."' ORDER BY id DESC");
			}
			if($month != "all" AND $year != "all" AND $supplier != "All" AND $product != "All"){
				echo "Month: ".mois($month)." ".$year." - Supplier: ".$supplier." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND supplier = '".$supplier."' AND product = '".$product."' ORDER BY id DESC");
			}
			if($month != "all" AND $year != "all" AND $supplier == "All" AND $product != "All"){
				echo "Month: ".mois($month)." ".$year." - Product: ".$product;
	$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND product = '".$product."' ORDER BY id DESC");
			}
			if($month != "all" AND $year == "all" AND $supplier != "All" AND $product != "All"){
				echo "Month: ".mois($month)." - Supplier: ".$supplier." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." AND supplier = '".$supplier."' AND product = '".$product."' ORDER BY id DESC");
			}
			if($month != "all" AND $year == "all" AND $supplier == "All" AND $product != "All"){
				echo "Month: ".mois($month)." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." AND product = '".$product."' ORDER BY id DESC");
			}
			if($month != "all" AND $year == "all" AND $supplier != "All" AND $product == "All"){
				echo "Month: ".mois($month)." ".$year." - Cleint: ".$supplier;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%m') = ".$month." AND supplier = '".$supplier."' ORDER BY id DESC");
			}
			
			
			if($month == "all" AND $year != "all" AND $supplier == "All" AND $product == "All"){
				echo "Year: ".$year;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%Y') = ".$year." ORDER BY id DESC");
			}
			if($month == "all" AND $year != "all" AND $supplier != "All" AND $product == "All"){
				echo "Year: ".$year." - Supplier: ".$supplier;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%Y') = ".$year." AND supplier = '".$supplier."' ORDER BY id DESC");
			}
			if($month == "all" AND $year != "all" AND $supplier != "All" AND $product != "All"){
				echo "Year: ".$year." - Supplier: ".$supplier." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%Y') = ".$year." AND supplier = '".$supplier."' AND product = '".$product."' ORDER BY id DESC");
			}
			if($month == "all" AND $year != "all" AND $supplier == "All" AND $product != "All"){
				echo "Year: ".$year." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM entree WHERE DATE_FORMAT(date, '%Y') = ".$year." AND product = '".$product."' ORDER BY id DESC");
			}
			if($month == "all" AND $year == "all" AND $supplier != "All" AND $product == "All"){
				echo "Supplier: ".$supplier;
				$reponse = $bdd->query("SELECT * FROM entree WHERE supplier = '".$supplier."' ORDER BY id DESC");
			}
			if($month == "all" AND $year == "all" AND $supplier != "All" AND $product != "All"){
				echo "Supplier: ".$supplier." - Product: ".$product."";
				$reponse = $bdd->query("SELECT * FROM entree WHERE supplier = '".$supplier."' AND product = '".$product."' ORDER BY id DESC");
			}
			if($month == "all" AND $year == "all" AND $supplier == "All" AND $product != "All"){
				echo "Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM entree WHERE product = '".$product."' ORDER BY id DESC");
			}
			if($month == "all" AND $year == "all" AND $supplier == "All" AND $product == "All"){
				echo "Select: All";
				$reponse = $bdd->query("SELECT * FROM entree ORDER BY id DESC");
			}
			echo "</p>";
			
			$i = 0;
			$t = 0;
			while($donnees = $reponse->fetch())
			{
				$i++;
				echo"<form action='' method='post'>";
					if($donnees['date'] == dater())
					{
						echo"<tr class='today_move'>";
					}
					else{
						if($i % 2 == 0){
							echo"<tr class='alt'>";
						}
						else{
							echo"<tr>";
						}
					}
							if($month != "all" OR $year != "all"){echo"<td class='keyword'>";}else{echo "<td>";}
								echo dateEn($donnees['date']);
							echo "</td>";
							if($supplier != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
								echo $donnees['supplier'];
							echo "</td>";
							if($product != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
								echo $donnees['product'];
							echo "</td>";
							echo"<td>".number_format($donnees['quantity'])."</td>";
							echo"<td>".number_format($donnees['total'])."</td>";
							echo"<td>".$donnees['lot']."</td>";
							echo"<td>".dateEn($donnees['exp'])."</td>";
							echo"<td>";
								echo"<input type='hidden' name='product' value='".$donnees['product']."' />";
								echo"<input type='hidden' name='lot' value='".$donnees['lot']."' />";
								echo"<input type='hidden' name='quantity' value='".$donnees['quantity']."' />";
								echo"<input type='hidden' name='id_entry' value='".$donnees['id']."' />";
								echo" <input type='submit' name='delete_entry' value='' class='del' title='Delete' />";
							echo"</td>";
						echo"</tr>";
				echo"</form>";
				$t += $donnees['total'];
			}
			echo "<tr>";
				echo "<th colspan='4'>".number_format($i)." result(s)</th>";
				echo "<th>".number_format($t)."</th>";
				echo "<th colspan='2'></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}

		// Suppression d'une entee
		public function del_entry($id_entry)
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM entree WHERE id = ".$id_entry."");
		}
		
		// Recuperer la quantite d'un product par lot
		public function get_shelf_product_quantity_by_lot($code_pro, $lot)
		{
			$bdd = connexionDb();
			$rep = $bdd->query("SELECT * FROM shelf");
			$quantity = 0;
			while($data = $rep->fetch())
			{
				if($data["product_code"] == $code_pro AND $data["lot"] == $lot){
					$quantity = $data["quantity"];
				}
			}
			$rep->closeCursor();
			return $quantity;
		}

		// Get the old batch number (expire soon)
		public function get_young_batch($product_code)
		{
			$bdd = connexionDb();
			$rep = $bdd->query("SELECT * FROM shelf WHERE product_code = '".$product_code."' AND quantity > 0");
			$lot = null;
			$i = 1;
			while($data = $rep->fetch())
			{
				if($i == 1) {
					$date_exp = $data["exp"];
					$lot = $data["lot"];
				} else {
					if($date_exp > $data["exp"])
					{
						$date_exp = $data["exp"];
						$lot = $data["lot"];
					}
				}
				$i++;
			}
			$rep->closeCursor();
			return $lot;
		}
			
		// Recuperer la quantite d'un product
		public function get_product_quantity_shelf($code_pro)
		{
			$bdd = connexionDb();
			$rep = $bdd->query("SELECT quantity FROM shelf WHERE product_code = '".$code_pro."'");
			$total_quantity = 0;
			while($data = $rep->fetch())
			{
				$total_quantity += $data["quantity"];
			}
			$rep->closeCursor();
			return $total_quantity;
		}
		
		// Suppression d'un lot d'un product
		public function delete_lot_in_shelf($id_shelf)
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM shelf WHERE id = ".$id_shelf."");
		}

		// Suppression d'un product tous les products epuises
		public function del_exhausted_product()
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM shelf WHERE quantity = 0");
		}
		
		function update_shelf($code_pro, $new_lot, $old_lot, $quantity, $exp)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE shelf SET lot = :nl, quantity = :q, exp = :e WHERE product_code = :c AND lot = :ol");
			$reponse->execute(array(
				'nl' => $new_lot,
				'q' => $quantity,
				'e' => $exp,
				'c' => $code_pro,
				'ol' => $old_lot
			));
			$reponse->closeCursor();
		}

		function update_batch_shelf($code_pro, $lot, $quantity, $exp)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE shelf SET quantity = :q, exp = :e WHERE product_code = :c AND lot = :l");
			$reponse->execute(array(
				'q' => $quantity,
				'e' => $exp,
				'c' => $code_pro,
				'l' => $lot,
			));
			$reponse->closeCursor();
		}

		// Update when selling
		function update_shelf_when_selling($code_pro, $lot, $quantity)
		{
			do {
				if($quantity < 0) {
					$quantity *= -1;
					$lot = $this->get_young_batch($code_pro);
				}
				$quantity = $this->update_shelf_quantity($code_pro, $lot, $quantity);
			}
			while($quantity < 0);
		}
		
		// Update shelf quantity on a specific product & batch
		function update_shelf_quantity($code_pro, $lot, $quantity)
		{
			$old_quantity = $this->get_shelf_product_quantity_by_lot($code_pro, $lot);
			$new_quantity = $rest = $old_quantity - $quantity;
			if($new_quantity < 0) {
				$new_quantity = 0;
			}
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE shelf SET quantity = :q WHERE product_code = :c AND lot = :l");
			$reponse->execute(array(
				'q' => $new_quantity,
				'c' => $code_pro,
				'l' => $lot
			));
			$reponse->closeCursor();
			return $rest;
		}

		// Update shelf quantity on a specific product & batch
		function update_shelf_quantity_add($code_pro, $lot, $quantity)
		{
			$old_quantity = $this->get_shelf_product_quantity_by_lot($code_pro, $lot);
			$q = $old_quantity + $quantity;
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE shelf SET quantity = :q WHERE product_code = :c AND lot = :l");
			$reponse->execute(array(
				'q' => $q,
				'c' => $code_pro,
				'l' => $lot
			));
			$reponse->closeCursor();
		}
		
		// Product infos
		public function product_infos_in_shelf($code_pro, $lot)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM shelf");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["product_code"] == $code_pro AND $donnees["lot"] == $lot)
				{
					$trouver = true;
					$quantity = $donnees["quantity"];
					$prix_vente = $donnees["prix_vente"];
					$lot = $donnees["lot"];
					$exp = $donnees["exp"];
					$infos = array ($quantity, $prix_vente, $lot, $exp);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Afficher les details shelf d'un product
		public function shelf_details_for_product($code_pro)
		{
			$p = new Product();
			$infos_pro = $p->infosParCode($code_pro);
			$nom_product = $infos_pro[0];
			$unit_product = $infos_pro[1];
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Batch Nr</th>";
					echo "<th>Exp. date</th>";
					echo "<th>Qty</th>";
					echo "<th>100% Price</th>";
					echo "<th>100% Total</th>";
					echo "<th>Insur. Price</th>";
					echo "<th>Insur. Total</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM shelf ORDER BY exp asc");
			$i = 0;
			$total_quantity = 0;
			$total_vente_100 = 0;
			$total_vente_insu = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees['product_code'] == $code_pro)
				{
						if($donnees['exp'] <= dater())
						{
							echo"<tr class='exp_pro'>";
						} else {
							$i++;
							if($i % 2 == 0){
								echo"<tr class='alt'>";
							}
							else{
								echo"<tr>";
							}
						}
						echo "<form action='#update_shelf' method='post'>";
							echo "<td>";
		echo "<input type='text' name='new_lot' value=".$donnees['lot']." required />";
							echo "</td>";
							echo "<td>";
		echo "<input type='date' name='exp' value=".$donnees['exp']." required />";
							echo "</td>";
							echo "<td>";
		echo "<input type='text' size='3' name='quantity' value=".$donnees['quantity']." required />";
							echo "</td>";
							echo "<td>";
		$product = new Product();
		$infos_pro = $product->infosParCode($code_pro);
		$price_100 = $infos_pro[4];
		echo "<u><b>".number_format($price_100)."</b></u>";
							echo "</td>";
							echo "<td>";
								$t_vente_100 = $donnees['quantity'] * $price_100;
								echo number_format($t_vente_100);
							echo "</td>";
							echo "<td>";
		$product = new Product();
		$infos_pro = $product->infosParCode($code_pro);
		$insu_price = $infos_pro[3];
		echo "<u><b>".number_format($insu_price)."</b></u>";
							echo "</td>";
							echo "<td>";
								$t_vente_insu = $donnees['quantity'] * $insu_price;
								echo number_format($t_vente_insu);
							echo "</td>";
							echo "<td>";
								echo "<input type='hidden' name='id_shelf' value=".$donnees['id']." />";
								echo "<input type='hidden' name='code_pro' value='".$donnees['product_code']."' />";
								echo "<input type='hidden' name='old_lot' value='".$donnees['lot']."' />";
								echo " <input type='submit' name='update_shelf' value='Update' title='Update' />";
							echo"</td>";
							echo"<td>";
								echo " <input type='submit' name='delete_lot' value='' class='del' title='Delete' />";
							echo "</td>";
							if($donnees['exp'] > dater())
							{
								$infos_date = infosDate($donnees['exp']);
								$jour = $infos_date[0];
								$mois = $infos_date[1];
								$annee = $infos_date[2];
								$exp_soon = date('Y-m-d', strtotime("-30 day", mktime(0, 0, 0, $mois, $jour, $annee)));
								if($exp_soon <= dater())
								{
									echo"<td>";
										$date_one = date_create($donnees['exp']);
										$date_two = date_create(dater());
										$date_interval = date_diff($date_one, $date_two);
										$days_left = $date_interval->format('%d');
										echo "<img src='img/warning.png' class='small' /> Exp. ".$days_left." day(s)";
									echo"</td>";
								}
							}
						echo"</form>";
						echo"</tr>";
					$total_quantity += $donnees['quantity'];
					$total_vente_insu += $t_vente_insu;
					$total_vente_100 += $t_vente_100;
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				echo "<th colspan='2'></th>";
				echo "<th>";
					$p = new Product();
					$infos_pro = $p->infosParCode($code_pro);
					$secu = $infos_pro[2];
					$quantity = $this->get_product_quantity_shelf($code_pro);
					echo number_format($total_quantity)." ".$unit_product;
					if($quantity < $secu)
					{
						echo " <img src='img/warning.png' class='small' /> ";
					}
				echo "</th>";
				echo "<th></th>";
				echo "<th>".number_format($total_vente_100)."</th>";
				echo "<th></th>";
				echo "<th>".number_format($total_vente_insu)."</th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}
		
		// Afficher les details shelf d'un product
		public function shelf_details_sale($code_pro)
		{
			$p = new Product();
			$infos_pro = $p->infosParCode($code_pro);
			$nom_product = $infos_pro[0];
			$unit_product = $infos_pro[1];
			echo "<div id='display'>";
			echo "<h2>On the Shelf: ".$nom_product."</h2>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Batch num</th>";
					echo "<th>Qty</th>";
					echo "<th>Price</th>";
					echo "<th>Exp. date</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM shelf ORDER BY exp ASC");
			$i = 0;
			$total = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees['product_code'] == $code_pro)
				{
						if($donnees['exp'] <= dater())
						{
							echo"<tr class='exp_pro'>";
						}
						else{
							$i++;
							if($i % 2 == 0){
								echo"<tr class='alt'>";
							}
							else{
								echo"<tr>";
							}
						}
							echo"<td>".$donnees['lot']."</td>";
							echo"<td>".number_format($donnees['quantity'])." ".$unit_product."</td>";
							echo"<td>".number_format($donnees['prix_vente'])."</td>";
							echo"<td>".dateEn($donnees['exp'])."</td>";
							if($donnees['exp'] > dater())
							{
								$infos_date = infosDate($donnees['exp']);
								$jour = $infos_date[0];
								$mois = $infos_date[1];
								$annee = $infos_date[2];
								$exp_soon = date('Y-m-d', strtotime("-30 day", mktime(0, 0, 0, $mois, $jour, $annee)));
								if($exp_soon <= dater())
								{
									echo"<td>";
										$date_one = date_create($donnees['exp']);
										$date_two = date_create(dater());
										$date_interval = date_diff($date_one, $date_two);
										$days_left = $date_interval->format('%d');
										echo "<img src='img/warning.png' class='small' /> ".$days_left." day(s) left";
									echo"</td>";
								}			
							}
							else{
								echo "<td>Expired !!!</td>";
							}			
						echo"</tr>";
					$total += $donnees['quantity'];
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				echo "<th></th>";
				echo "<th>".number_format($total)." ".$unit_product."</th>";
				echo "<th colspan='2'></th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
			return $total;
		}
		
		// Shelf existant
		public function add_new_batch_in_shelf($code_pro, $lot, $quantity, $exp)
		{
			$bdd = connexionDb();
	$req = $bdd->prepare("INSERT INTO shelf (product_code, quantity, lot, exp) VALUES (?, ?, ?, ?)");
			$req->execute(array($code_pro, $quantity, $lot, $exp));
			$req->closeCursor();
		}
		
		// Verifier si le nom du product existe
		public function is_this_product_exist_in_shelf($code_pro)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM shelf");
			$res = false;
			while($donnees = $reponse->fetch())
			{
				if($donnees["product_code"] == $code_pro)
				{
					$res = true;
				}
			}
			$reponse->closeCursor();
			return $res;
		}

		// Enregistrer une entree
		public function add_entry($product, $supplier, $quantity, $buy_price, $lot, $exp)
		{
			$total = $buy_price * $quantity;
			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO entree (supplier, product, quantity, buy_price, total, lot, exp, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($supplier, $product, $quantity, $buy_price, $total, $lot, $exp, dater()));
			$req->closeCursor();
			
			$p = new Product();
			$infos_pro = $p->infosParNom($product);
			$product_code = $infos_pro[0];
			$bdd = connexionDb();
	$req = $bdd->prepare("INSERT INTO shelf (product_code, quantity, lot, exp) VALUES (?, ?, ?, ?)");
			$req->execute(array($product_code, $quantity, $lot, $exp));
			$req->closeCursor();
		}
		
		// Verifier si le nom du product existe
		public function is_lot_for_this_product_exist($code_pro, $lot)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM shelf");
			$res = false;
			while($donnees = $reponse->fetch() AND $res == false)
			{
				if($donnees["product_code"] == $code_pro AND $donnees["lot"] == $lot)
				{
					$res = true;
				}
			}
			$reponse->closeCursor();
			return $res;
		}
		
		// Verifier si le nom du product existe sauf
		public function is_lot_for_this_product_exist_except($code_pro, $lot, $old_lot)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM shelf WHERE lot != '".$old_lot."'");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["product_code"] == $code_pro AND $donnees["lot"] == $lot)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Toutes les infos du product
		public function batch_infos_shelf($code_pro, $lot)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM shelf");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["lot"]) == strtolower($lot))
				{
					$trouver = true;
					$quantity = $donnees["quantity"];
					$prix_vente = $donnees["prix_vente"];
					$exp = $donnees["exp"];
					$infos = array ($quantity, $prix_vente, $exp);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}

		public function nbr_expired_product($product_code) {
															$bdd = connexionDb();
															$reponse = $bdd->query("SELECT * FROM shelf");
															$n = 0;
															while($donnees = $reponse->fetch())
															{
																if($donnees['product_code'] == $product_code)
																{
																	if($donnees['exp'] > dater())
																	{
																		$n++;
																	}
																}
															}
															$reponse->closeCursor();
															return $n;
		}
	}
?>






