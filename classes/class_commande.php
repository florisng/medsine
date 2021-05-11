<?php
	// Classe Commande pour les ambulants
	class Commande
	{
		// Recuperer la quantity enregistree
		public function total_quantity_tempo($product_code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo");
			$trouver = false;
			$quantity = 0;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["product_code"] == $product_code)
				{
					$quantity = $donnees["quantity"];
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $quantity;
		}

		// Recuperer la quantity enregistree
		public function total_quantity_insu_tempo($product_code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo_insu");
			$trouver = false;
			$quantity = 0;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["product_code"] == $product_code)
				{
					$quantity = $donnees["quantity"];
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $quantity;
		}

		// Recuperer la quantity enregistree
		public function total_quantity_rssb_tempo($product_code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo_rssb");
			$quantity = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees["product_code"] == $product_code)
				{
					$quantity += $donnees["quantity"];
				}
			}
			$reponse->closeCursor();
			return $quantity;
		}

		// Recuperer la quantity enregistree
		public function total_quantity_rssb_tempo_by_user($product_code, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo_rssb");
			$trouver = false;
			$quantity = 0;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["user"]) == strtolower($username))
				{
					if($donnees["product_code"] == $product_code)
					{
						$quantity = $donnees["quantity"];
						$trouver = true;
					}
				}
			}
			$reponse->closeCursor();
			return $quantity;
		}
		
		// Check if the product is already in DB
		public function is_product_exist_in_rssb_tempo($code_pro, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo_rssb");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["user"]) == strtolower($username))
				{
					if($donnees["product_code"] == $code_pro)
					{
						$trouver = true;
					}
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}

		// Check if the product is already in DB
		public function is_product_exist_in_tempo($code_pro, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["user"]) == strtolower($username))
				{
					if($donnees["product_code"] == $code_pro)
					{
						$trouver = true;
					}
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}

		// Check if the product is already in DB
		public function is_product_exist_in_tempo_insu($code_pro, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo_insu");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["user"]) == strtolower($username))
				{
					if($donnees["product_code"] == $code_pro)
					{
						$trouver = true;
					}
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Calculer le nombre d'elements dans une table
		public function nbr_element_in_tempo_insu($username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo_insu");
			$trouver = false;
			$nbr = 0;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["user"] == $username)
				{
					$nbr++;
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $nbr;
		}
		
		// Calculer le nombre d'elements dans une table
		public function nbr_element_in_tempo($username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo");
			$trouver = false;
			$nbr = 0;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["user"] == $username)
				{
					$nbr++;
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $nbr;
		}

		// Calculer le nombre d'elements dans la table tempo
		public function nbr_element_in_tempo_rssb($username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo_rssb");
			$trouver = false;
			$nbr = 0;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["user"] == $username)
				{
					$nbr++;
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $nbr;
		}
		
		// Afficher toutes les commandes ambulant en cours
		public function tempo()
		{
			echo"<p>";
				echo "<form action='' method='post'>";
					echo "<input type='submit' name='del_all' value='Cancel all' class='red_btn small_btn' />";
				echo "</form>";
			echo "</p>";
			echo "<div id='display'>";
				echo "<table>";
					echo "<tr>";
						echo "<th>Product</th>";
						echo "<th>Qty</th>";
						echo "<th>Price</th>";
						echo "<th>Total</th>";
					echo "</tr>";
					$bdd = connexionDb();
					$reponse = $bdd->query('SELECT * FROM tempo ORDER BY id ASC');
					$total = 0;
				while($donnees = $reponse->fetch())
				{
					if($donnees["user"] == $_SESSION["username"])
					{
						echo"<tr>";
							echo"<form action='' method='post'>";
								echo"<td>";
									$p = new Product();
									$infos_pro = $p->infosParCode($donnees['product_code']);
									$product_name = $infos_pro[0];
									echo $product_name;
								echo "</td>";
								echo"<td>";
									echo "<span class='price_to_be_changed'>".number_format($donnees['quantity'])."</span>";
									echo"<span class='change_price'>";
										echo "<input type='text' size='3' name='quantity' value=".$donnees['quantity'].">";
									echo"</span>";
								echo"</td>";
								echo"<td>";
									echo "<span class='price_to_be_changed'>".number_format($donnees['price'])."</span>";
									echo"<span class='change_price hidden'>";
										echo "<input type='text' size='3' name='new_price' value=".$donnees['price']." required>";
									echo"</span>";
								echo"</td>";
								echo"<td>".number_format($donnees['total'])."</td>";
								echo"<td>";
									echo"<input type='hidden' name='id_tempo' value=".$donnees['id']." />";
									echo"<span class='change_price'>";
										echo "<input type='submit' name='ok_price' value='Save' title='Change the price' />";
									echo"</span>";
									echo" <span class='close_change_price link'>Cancel</span>";
									echo" <span class='link small open_change_price small_down'>Modify</span>";
								echo"</td>";
								echo"<td> ========== </td>";
								echo"<td>";
									echo"<input type='submit' name='del' value='&times;' title='Delete' />";
								echo"</td>";
							echo"</form>";			
							$total = $total + $donnees['total'];
						echo"</tr>";
					}
				}
				$reponse->closeCursor();
				echo"<tr>";
					echo"<th colspan='3'>Total (100%)</th>";
					echo"<th>".number_format($total)."</th>";
				echo"</tr>";
				echo "</table>";
				echo "<p>";
					echo "<form action='' method='post'>";
						echo "<span class='new'><input type='radio' id='cash' name='pay_mode' value='CASH' required /><label for='cash'>CASH</label></span>";
						echo "<span class='new'><input type='radio' id='momo' name='pay_mode' value='MoMo' required /><label for='momo'>MoMo</label></span>";
						echo "<span class='new'><input type='radio' id='pos' name='pay_mode' value='POS' required /><label for='pos'>POS</label></span>";
						echo "<hr>";
						echo "Client:</td><td><input type='text' name='client' value='Ordinaire' placeholder='Client name' required><input type='submit' name='save_all' class='btn' value='Checkout' />";
					echo "</form>";
				echo "</p>";
			echo "</div>";
		}

		// Afficher toutes les commandes ambulant en cours
		public function tempo_rssb()
		{
				$bdd = connexionDb();
				$reponse = $bdd->query('SELECT * FROM tempo_rssb ORDER BY id ASC');
				$i = $total = 0;
				while($donnees = $reponse->fetch())
				{
					if($donnees["user"] == $_SESSION["username"])
					{
						$i++;
						echo"<tr>";
							echo"<form action='#display' method='post'>";
								echo"<td>".$i."</td>";
								echo"<td>".$donnees['product_code']."</td>";
								echo"<td>";
									$p = new Product();
									$infos_pro = $p->infosParCode($donnees['product_code']);
									$product_name = $infos_pro[0];
									echo $product_name;
								echo "</td>";
								echo"<td>".number_format($donnees['quantity'])."</td>";
								echo"<td>".number_format($donnees['price'])."</td>";
								echo"<td>".number_format($donnees['total'])."</td>";
								echo"<td>";
									echo"<input type='hidden' name='id_tempo' value=".$donnees['id']." />";
									echo"<input type='submit' name='del' value='&times;' title='Delete' />";
								echo"</td>";
							echo"</form>";
						echo"</tr>";
						$total += $donnees['total'];
					}
				}
				$reponse->closeCursor();
				return [$i, $total];
		}

		// Afficher toutes les commandes ambulant en cours
		public function commande_rssb($num_fact)
		{
				$bdd = connexionDb();
				$reponse = $bdd->query("SELECT * FROM commande WHERE num_fact = '".$num_fact."' ORDER BY id ASC");
				$i = $total = 0;
				while($donnees = $reponse->fetch())
				{
						$i++;
						echo"<tr>";
							echo"<form action='#display' method='post'>";
								echo"<td>".$i."</td>";
								echo"<td>".$donnees['product_code']."</td>";
								echo"<td>";
									$p = new Product();
									$infos_pro = $p->infosParCode($donnees['product_code']);
									$product_name = $infos_pro[0];
									echo $product_name;
								echo "</td>";
								echo"<td>".number_format($donnees['quantity'])."</td>";
								echo"<td>".number_format($donnees['price'])."</td>";
								echo"<td>".number_format($donnees['total'])."</td>";
								echo"<td class='hidden'>";
									echo"<input type='hidden' name='id_com' value=".$donnees['id']." />";
									echo"<input type='submit' name='del' value='&times;' title='Delete' />";
								echo"</td>";
							echo"</form>";
						echo"</tr>";
						$total += $donnees['total'];
				}
				$reponse->closeCursor();
				return [$i, $total];
		}
		
		// Afficher toutes les commandes ambulant en cours
		public function tempo_insu($rate)
		{
			echo"<p>";
				echo "<form action='' method='post'>";
					echo "<input type='submit' name='del_all' value='Cancel all' class='red_btn small_btn' />";
				echo "</form>";
			echo "</p>";
			echo "<div id='display'>";
				echo "<table>";
					echo "<tr>";
						echo "<th>Product</th>";
						echo "<th>Qty</th>";
						echo "<th>Price</th>";
						echo "<th>Total (100%)</th>";
						echo "<th>Insurance (".$rate."%)</th>";
						echo "<th>";
							$cash_in_rate = 100 - $rate;
							echo "Client (".$cash_in_rate."%)";
						echo "</th>";
					echo "</tr>";
					$bdd = connexionDb();
					$reponse = $bdd->query('SELECT * FROM tempo_insu ORDER BY id ASC');
					$total = 0;
					$credit = 0;
					$cash = 0;
				while($donnees = $reponse->fetch())
				{
					if($donnees["user"] == $_SESSION["username"])
					{
						echo"<tr>";
							echo"<form action='' method='post'>";
								echo"<td>";
									$p = new Product();
									$infos_pro = $p->infosParCode($donnees['product_code']);
									echo $infos_pro[0];
								echo "</td>";
								echo"<td>";
									echo "<span class='price_to_be_changed'>".number_format($donnees['quantity'])."</span>";
									echo"<span class='change_price hidden'>";
										echo "<input type='text' size='3' name='quantity' value=".$donnees['quantity'].">";
									echo"</span>";
								echo"</td>";
								echo"<td>";
									echo "<span class='price_to_be_changed'>".number_format($donnees['price'])."</span>";
									echo"<span class='change_price hidden'>";
										echo "<input type='text' size='3' name='new_price' value=".$donnees['price']." required>";
									echo"</span>";
								echo"</td>";
								echo"<td>".number_format($donnees['total'])."</td>";
								echo"<td>".number_format($donnees['credit'])."</td>";
								echo"<td>".number_format($donnees['cash'])."</td>";
								echo"<td>";
									echo"<input type='hidden' name='id_tempo' value=".$donnees['id']." />";
									echo"<span class='change_price'>";
										echo "<input type='submit' name='ok_price' value='Save' title='Change the price' />";
									echo"</span>";
									echo" <span class='close_change_price link'>Cancel</span>";
									echo" <img src='./img/modify.png' class='small open_change_price small_down' />";
								echo"</td>";
								echo"<td> ========== </td>";
								echo"<td>";
									echo"<input type='submit' name='del' value='&times;' title='Delete' />";
								echo"</td>";
							echo"</form>";
							$total = $total + $donnees['total'];
							$credit = $credit + $donnees['credit'];
							$cash = $cash + $donnees['cash'];
						echo"</tr>";
					}
				}
				$reponse->closeCursor();
				echo"<tr>";
					echo"<th>Total</th>";
					echo"<th colspan='2'></th>";
					echo"<th>".number_format($total)."</th>";
					echo"<th>".number_format($credit)."</th>";
					echo"<th>".number_format($cash)."</th>";// Get user's infos
					$u = new User();
					$infosUser = $u->infos_user($_SESSION["username"]);
					$nom = $infosUser[1];
					$prenom = $infosUser[2];
				echo"</tr>";
				echo "</table>";
				echo "<p>";
					echo "<form action='' method='post'>";
						echo "<table>";
							echo "<tr>";
								echo "<td>Adherent:</td><td><input type='text' name='adherent' placeholder='Full name' required /></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Card num:</td><td><input type='text' name='card_number' placeholder='Card num' required /></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Pay. method</td>";
					echo "<td><span class='new'><input type='radio' id='cash' name='pay_mode' value='CASH' required /> <label for='cash'>CASH</label></span>";
					echo "<span class='new'><input type='radio' id='momo' name='pay_mode' value='MoMo' required /> <label for='momo'>MoMo</label></span>";
					echo "<span class='new'><input type='radio' id='pos' name='pay_mode' value='POS' required /> <label for='pos'>POS</label></span>";
					echo "</td><td><input type='submit' name='save_all' class='btn' value='Checkout' /></td>";
							echo "<tr>";
						echo "</table>";
					echo "</form>";
				echo "</p>";
			echo "</div>";
		}
		
		// Calculer le total dans tempo
		public function total_in_tempo()
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM tempo');
			$total = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees["user"] == $_SESSION["username"])
				{
					$total = $total + $donnees['total'];
				}
			}
			$reponse->closeCursor();
			return $total;
		}
		
		// Calculer le cash dans tempo
		public function total_in_tempo_insu()
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM tempo_insu');
			$cash = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees["user"] == $_SESSION["username"])
				{
					$cash = $cash + $donnees['cash'];
				}
			}
			$reponse->closeCursor();
			return $cash;
		}
		
		// Calculer le total sur facture
		public function total_facture($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM commande');
			$total = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees["num_fact"] == $num_fact)
				{
					$total += $donnees['total'];
				}
			}
			$reponse->closeCursor();
			return $total;
		}

		// Calculer le total credit sur facture
		public function total_facture_credit($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM commande');
			$credit = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees["num_fact"] == $num_fact)
				{
					$credit += $donnees['credit'];
				}
			}
			$reponse->closeCursor();
			return $credit;
		}

		// Calculer le total cash sur facture
		public function total_facture_cash($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM commande');
			$cash = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees["num_fact"] == $num_fact)
				{
					$cash += $donnees['cash'];
				}
			}
			$reponse->closeCursor();
			return $cash;
		}
		
		// Enregistrer les commandes en cash
		public function add_commande($num_fact, $date)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo WHERE user = '".$_SESSION["username"]."'");
			$total = 0;
			while($donnees = $reponse->fetch())
			{
				$p = new Product();
				$infos_pro = $p->infosParCode($donnees["product_code"]);
				$product_name = $infos_pro[0];
				$bdd = connexionDb();
	$req = $bdd->prepare("INSERT INTO commande (num_fact, product_code, product_name, quantity, price, total, cash, user, date, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$req->execute(array($num_fact, $donnees["product_code"], $product_name, $donnees["quantity"], $donnees["price"], $donnees["total"], $donnees["total"], $_SESSION["username"], $date, "cash"));
				$req->closeCursor();
				$total += $donnees["total"];
			}
			$reponse->closeCursor();
			
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM tempo WHERE user = '".$_SESSION["username"]."'");
			while($donnees = $reponse->fetch())
			{
				$s = new Shelf();
				$s->update_shelf_when_selling($donnees["product_code"], $donnees["lot"], $donnees["quantity"]);
			}
			$reponse->closeCursor();
		}

		// Enregistrer les commandes en cash
		public function add_commande_insu_rssb($tab, $num_fact, $date, $type)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM $tab WHERE user = '".$_SESSION["username"]."'");
			$total = 0;
			while($donnees = $reponse->fetch())
			{
				$p = new Product();
				$infos_pro = $p->infosParCode($donnees["product_code"]);
				$product_name = $infos_pro[0];
				$bdd = connexionDb();
	$req = $bdd->prepare("INSERT INTO commande (num_fact, product_code, product_name, quantity, price, total, credit, cash, user, date, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$req->execute(array($num_fact, $donnees["product_code"], $product_name, $donnees["quantity"], $donnees["price"], $donnees["total"], $donnees["credit"], $donnees["cash"], $_SESSION["username"], $date, $type));
				$req->closeCursor();
				$total += $donnees["total"];
			}
			$reponse->closeCursor();
			
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM $tab WHERE user = '".$_SESSION["username"]."'");
			while($donnees = $reponse->fetch())
			{
				$s = new Shelf();
				$s->update_shelf_when_selling($donnees["product_code"], $donnees["lot"], $donnees["quantity"]);
			}
			$reponse->closeCursor();
		}
		
		// Afficher toutes les ventes
		public function tous()
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM commande ORDER BY id DESC');
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice nr</th>";
					echo "<th>Product</th>";
					echo "<th>Price</th>";
					echo "<th>Qty</th>";
					echo "<th>Total</th>";
					echo "<th>Credit</th>";
					echo "<th>Cash</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			$today_nbr = 0;
			$other_nbr = 0;
			$done = false;
			$t = 0;
			$cre = 0;
			$c = 0;
			$date = date_create(dater());
			date_sub($date,date_interval_create_from_date_string("30 days"));
			$last_thirty_days_date = date_format($date,"Y-m-d");
			while($donnees = $reponse->fetch())
			{
				if($donnees['date'] >= $last_thirty_days_date)
				{
					if($donnees['date'] == dater())
					{
						echo"<tr class='today_move'>";
						$today_nbr++;
					} else {
						$other_nbr++;
						if($today_nbr > 0 && $done == false) {
							$done = true;
							echo "<tr>";
								echo "<th colspan='5'>".$today_nbr." result(s)</th>";
								echo "<th>".number_format($t)."</th>";
								echo "<th>".number_format($cre)."</th>";
								echo "<th>".number_format($c)."</th>";
								echo "<th></th>";
							echo "</tr>";
						}
						if($other_nbr % 2 == 0){
							echo"<tr class='alt'>";
						}
						else{
							echo"<tr>";
						}
					}
							echo"<td>".dateEn($donnees['date'])."</td>";
							echo"<td>".$donnees['num_fact']."</td>";
							echo"<td>".$donnees['product_name']."</td>";
							echo"<td>".number_format($donnees['price'])."</td>";
							echo"<td>".$donnees['quantity']."</td>";
							echo"<td>".number_format($donnees['total'])."</td>";
							echo"<td>".number_format($donnees['credit'])."</td>";
							echo"<td>".number_format($donnees['cash'])."</td>";
							echo"<td>";
								$u = new User();
								$res = $u->is_username_exist($donnees['user']);
								if($res == true) {
									$u = new User();
									$user_infos = $u->infos_user($donnees['user']);
									$lastname = $user_infos[2];
									echo $lastname;
								}
								else {
									echo $donnees['user'];
								}
							echo "</td>";
							echo "<td>";
								if($donnees['type'] == "cash") {
									$open_page = "open_fact.php";
								}
								if($donnees['type'] == "insu") {
									$open_page = "open_fact_insu.php";
								}
								if($donnees['type'] == "rssb") {
									$open_page = "open_fact_rssb.php";
								}
								echo "<a href='".$open_page."?num_fact=".$donnees['num_fact']."' class='link'>Open invoice</a>";
							echo "</td>";
					echo "</tr>";
					$t += $donnees['total'];
					$cre += $donnees['credit'];
					$c += $donnees['cash'];
				}
			}
			$total_res = $today_nbr + $other_nbr;
			echo "<tr>";
				echo "<th colspan='5'>".$total_res." result(s)</th>";
				echo "<th>".number_format($t)."</th>";
				echo "<th>".number_format($cre)."</th>";
				echo "<th>".number_format($c)."</th>";
				echo "<th></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}

		// Afficher toutes les ventes
		public function sales_by_month($month, $year)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year."");
			$total_sales = 0;
			while($donnees = $reponse->fetch())
			{
				$total_sales += $donnees['total'];
			}
			$reponse->closeCursor();
			return $total_sales;
		}

		// Afficher toutes les ventes
		public function tous_by_type($type)
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM commande WHERE type = '".$type."' ORDER BY id DESC");
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice nr</th>";
					echo "<th>Product</th>";
					echo "<th>Qty</th>";
					echo "<th>Price</th>";
					echo "<th>Total</th>";
					if($type == "insu") {
						echo "<th>Credit</th>";
						echo "<th>Cash</th>";
					}
					echo "<th>Type</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			$i = 0;
			$t = 0;
			if($type == "insu") {
				$cre = 0;
				$c = 0;
			}
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
							echo"<td>".$donnees['num_fact']."</td>";
							echo"<td>".$donnees['product_name']."</td>";
							echo"<td>".$donnees['quantity']."</td>";
							echo"<td>".number_format($donnees['price'])."</td>";
							echo"<td>".number_format($donnees['total'])."</td>";
							if($type == "insu") {
								echo"<td>".number_format($donnees['credit'])."</td>";
								echo"<td>".number_format($donnees['cash'])."</td>";
							}
							echo"<td>";
								if($donnees['type'] == 'insu')
								{
									echo "Insurance";
								}
								if($donnees['type'] == 'cash')
								{
									echo "Cash 100%";
								}
							echo"</td>";
							echo"<td>";
								$u = new User();
								$res = $u->is_username_exist($donnees['user']);
								if($res == true) {
									$u = new User();
									$user_infos = $u->infos_user($donnees['user']);
									$lastname = $user_infos[2];
									echo $lastname;
								}
								else {
									echo $donnees['user'];
								}
							echo"</td>";
						echo"</tr>";
					echo"</form>";
					$t += $donnees['total'];
					if($type == "insu") {
						$cre += $donnees['credit'];
						$c += $donnees['cash'];
					}
				}
			}
			echo "<tr>";
				echo "<th colspan='5'>".$i." result(s)</th>";
				echo "<th>".number_format($t)."</th>";
				if($type == "insu") {
					echo "<th>".number_format($cre)."</th>";
					echo "<th>".number_format($c)."</th>";
				}
				echo "<th colspan='2'></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Afficher toutes les ventes
		public function search_sales($month, $year, $product, $user)
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			
			if($user != "All") {
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
			}

			echo "<p>";
			if($month != "All" AND $year == "All" AND $product == "All" AND $user != "All") {
				// echo "Month: ".mois($month)." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND user = '".$user."' ORDER BY id DESC");
			}
			if($month != "All" AND $year == "All" AND $product == "All" AND $user == "All") {
				// echo "Month: ".mois($month);
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." ORDER BY id DESC");
			}
			if($month != "All" AND $year != "All" AND $product == "All" AND $user == "All") {
				// echo "Month: ".mois($month)." ".$year;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." ORDER BY id DESC");
			}
			if($month != "All" AND $year != "All" AND $product == "All" AND $user != "All") {
				// echo "Month: ".mois($month)." ".$year." - <b>Cashier:</b> ".$lastname;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER BY id DESC");
			}
			if($month != "All" AND $year != "All" AND $product != "All" AND $user == "All") {
				// echo "Month: ".mois($month)." ".$year." - Product: ".$product;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' ORDER BY id DESC");
			}
			if($month != "All" AND $year != "All" AND $product != "All" AND $user != "All") {
				// echo "Month: ".mois($month)." ".$year." - Product: ".$product." - <b>Cashier:</b> ".$lastname;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			if($month != "All" AND $year == "All" AND $product != "All" AND $user == "All") {
				// echo "Month: ".mois($month)." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND product_name = '".$product."' ORDER BY id DESC");
			}
			if($month != "All" AND $year == "All" AND $product != "All" AND $user != "All") {
				// echo "Month: ".mois($month)." - Product: ".$product." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			if($month == "All" AND $year != "All" AND $product == "All" AND $user == "All") {
				// echo "Year: ".$year;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." ORDER BY id DESC");
			}
			if($month == "All" AND $year != "All" AND $product == "All" AND $user != "All") {
				// echo "Year: ".$year." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER BY id DESC");
			}
			if($month == "All" AND $year != "All" AND $product != "All" AND $user == "All") {
				// echo "Year: ".$year." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' ORDER BY id DESC");
			}
			if($month == "All" AND $year != "All" AND $product != "All" AND $user != "All") {
				// echo "Year: ".$year." - Product: ".$product." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			if($month == "All" AND $year == "All" AND $product != "All" AND $user == "All") {
				// echo "Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM commande WHERE product_name = '".$product."' ORDER BY id DESC");
			}
			if($month == "All" AND $year == "All" AND $product != "All" AND $user != "All") {
				// echo "Product: ".$product." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			if($month == "All" AND $year == "All" AND $product == "All" AND $user == "All") {
				// echo "Select: All";
				$reponse = $bdd->query("SELECT * FROM commande ORDER BY id DESC");
			}
			if($month == "All" AND $year == "All" AND $product == "All" AND $user != "All") {
				// echo "Select: All - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE user = '".$user."' ORDER BY id DESC");
			}
			echo "</p>";
			
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice nr</th>";
					echo "<th>Product</th>";
					echo "<th>Price</th>";
					echo "<th>Qty</th>";
					echo "<th>Total</th>";
					echo "<th>Credit</th>";
					echo "<th>Cash</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
				if($product != "All")	$total_pro_quantity = 0;
				$today_nbr = 0;
				$other_nbr = 0;
				$done = false;
			$t = 0;
			$cre = 0;
			$c = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees['date'] == dater())
				{
					echo"<tr class='today_move'>";
					$today_nbr++;
				} else {
					$other_nbr++;
					if($today_nbr > 0 && $done == false) {
						$done = true;
						echo "<tr>";
							echo "<th colspan='5'>Today: ".$today_nbr." result(s)</th>";
							echo "<th>".number_format($t)."</th>";
							echo "<th>".number_format($cre)."</th>";
							echo "<th>".number_format($c)."</th>";
							echo "<thy></th>";
						echo "</tr>";
					}
					if($other_nbr % 2 == 0){
						echo"<tr class='alt'>";
					}
					else{
						echo"<tr>";
					}
				}
									if($month != "All" OR $year != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
										echo dateEn($donnees['date']);
									echo "</td>";
									echo"<td>".$donnees['num_fact']."</td>";
									if($product != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
										echo $donnees['product_name'];
									echo "</td>";
									echo"<td>".number_format($donnees['price'])."</td>";
									echo "<td>";
										if($product != "All"){$total_pro_quantity += $donnees['quantity'];}
										echo $donnees['quantity'];
									echo "</td>";
									echo"<td>".number_format($donnees['total'])."</td>";
									echo"<td>".number_format($donnees['credit'])."</td>";
									echo"<td>".number_format($donnees['cash'])."</td>";
									if($user != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
										$u = new User();
										$res = $u->is_username_exist($donnees['user']);
										if($res == true) {
											$u = new User();
											$user_infos = $u->infos_user($donnees['user']);
											$lastname = $user_infos[2];
											echo $lastname;
										}
										else {
											echo $donnees['user'];
										}
									echo "</td>";
								echo"</tr>";
							$t = $t + $donnees['total'];
							$cre = $cre + $donnees['credit'];
							$c = $c + $donnees['cash'];
			}
			echo "<tr>";
				$total_res = $today_nbr + $other_nbr;
				if($product != "All") {
					echo "<th colspan='4'>Total: ".$total_res." result(s)</th>";
					echo "<th>".number_format($total_pro_quantity)."</th>";
				} else {
					echo "<th colspan='5'>Total: ".$total_res." result(s)</th>";
				}
				echo "<th>".number_format($t)."</th>";
				echo "<th>".number_format($cre)."</th>";
				echo "<th>".number_format($c)."</th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}

		// Afficher toutes les ventes
		public function search_sales_by_type($month, $year, $product, $user, $type)
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			
			if($month != "All" AND $year == "All" AND $product == "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Month: ".mois($month)." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND user = '".$user."' ORDER BY id DESC");
			}
			if($month != "All" AND $year == "All" AND $product == "All" AND $user == "All"){
				echo "Month: ".mois($month);
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." ORDER BY id DESC");
			}
			
			if($month != "All" AND $year != "All" AND $product == "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." ORDER BY id DESC");
			}
			if($month != "All" AND $year != "All" AND $product == "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Month: ".mois($month)." ".$year." - <b>Cashier:</b> ".$lastname;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER BY id DESC");
			}
			
			if($month != "All" AND $year != "All" AND $product != "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year." - Product: ".$product;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' ORDER BY id DESC");
			}
			if($month != "All" AND $year != "All" AND $product != "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Month: ".mois($month)." ".$year." - Product: ".$product." - <b>Cashier:</b> ".$lastname;
	$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			
			if($month != "All" AND $year == "All" AND $product != "All" AND $user == "All"){
				echo "Month: ".mois($month)." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND product_name = '".$product."' ORDER BY id DESC");
			}
			if($month != "All" AND $year == "All" AND $product != "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Month: ".mois($month)." - Product: ".$product." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%m') = ".$month." AND product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			
			if($month == "All" AND $year != "All" AND $product == "All" AND $user == "All"){
				echo "Year: ".$year;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." ORDER BY id DESC");
			}
			if($month == "All" AND $year != "All" AND $product == "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Year: ".$year." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER BY id DESC");
			}
			
			if($month == "All" AND $year != "All" AND $product != "All" AND $user == "All"){
				echo "Year: ".$year." - Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' ORDER BY id DESC");
			}
			if($month == "All" AND $year != "All" AND $product != "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Year: ".$year." - Product: ".$product." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE DATE_FORMAT(date, '%Y') = ".$year." AND product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			
			if($month == "All" AND $year == "All" AND $product != "All" AND $user == "All"){
				echo "Product: ".$product;
				$reponse = $bdd->query("SELECT * FROM commande WHERE product_name = '".$product."' ORDER BY id DESC");
			}
			if($month == "All" AND $year == "All" AND $product != "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Product: ".$product." - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE product_name = '".$product."' AND user = '".$user."' ORDER BY id DESC");
			}
			
			if($month == "All" AND $year == "All" AND $product == "All" AND $user == "All"){
				echo "Select: All";
				$reponse = $bdd->query("SELECT * FROM commande ORDER BY id DESC");
			}
			if($month == "All" AND $year == "All" AND $product == "All" AND $user != "All"){
				$u = new User();
				$infos_user = $u->infos_user($user);
				$lastname = $infos_user[2];
				echo "Select: All - <b>Cashier:</b> ".$lastname;
				$reponse = $bdd->query("SELECT * FROM commande WHERE user = '".$user."' ORDER BY id DESC");
			}
			
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice nr</th>";
					echo "<th>Product</th>";
					echo "<th>Price</th>";
					echo "<th>Qty</th>";
					echo "<th>Total</th>";
					if($type == "insu") {
						echo "<th>Credit</th>";
						echo "<th>Cash</th>";
					}
					echo "<th>Cashier</th>";
					echo "<th>Type</th>";
				echo "</tr>";
			$i = 0;
			$t = 0;
			if($type == "insu") {
				$cre = 0;
				$c = 0;
			}
			$q = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees['type'] == $type)
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
									if($month != "All" OR $year != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
										echo dateEn($donnees['date']);
									echo "</td>";
									echo"<td>".$donnees['num_fact']."</td>";
									if($product != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
										echo $donnees['product_name'];
									echo "</td>";
									echo"<td>".number_format($donnees['price'])."</td>";
									echo "<td>";
										if($product != "All"){$q += $donnees['quantity'];}
										echo $donnees['quantity'];
									echo "</td>";
									echo"<td>".number_format($donnees['total'])."</td>";
									if($type == "insu") {
										echo"<td>".number_format($donnees['credit'])."</td>";
										echo"<td>".number_format($donnees['cash'])."</td>";
									}
									if($user != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
										$u = new User();
										$res = $u->is_username_exist($donnees['user']);
										if($res == true) {
											$u = new User();
											$user_infos = $u->infos_user($donnees['user']);
											$lastname = $user_infos[2];
											echo $lastname;
										}
										else {
											echo $donnees['user'];
										}
									echo "</td>";
									echo"<td>";
										if($donnees['type'] == 'insu')
										{
											echo "Insurance";
										}
										if($donnees['type'] == 'non_insu')
										{
											echo "Cash 100%";
										}
									echo"</td>";
								echo"</tr>";
							echo"</form>";
							$t = $t + $donnees['total'];
							if($type == "insu") {
								$cre = $cre + $donnees['credit'];
								$c = $c + $donnees['cash'];
							}
				}
			}
			echo "<tr>";
				if($product == "All") {
					echo "<th colspan='5'>".$i." result(s)</th>";
				} else {
					echo "<th colspan='4'>".$i." result(s)</th>";
					echo "<th>".$q."</th>";
				}
				echo "<th>".number_format($t)."</th>";
				if($type == "insu") {
					echo "<th>".number_format($cre)."</th>";
					echo "<th>".number_format($c)."</th>";
				}
				echo "<th colspan='2'></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		public function print_commande_open($num_fact)
		{
			$fact = new Facture();
			$infos_fact = $fact->infos_fact($num_fact);
			$client = $infos_fact[1];
			$date = $infos_fact[2];
			$user = $infos_fact[3];
			$time = $infos_fact[4];
			$pay_mode = $infos_fact[5];
			echo "<p>";
						$name = $tin = $address = $tel = $email = null;
						$bdd = connexionDb();
						$rep = $bdd->query("SELECT * FROM company");
						while($data = $rep->fetch())
						{
							$name = $data["nom"];
							$tin = $data["tin"];
							$address = $data["address"];
							$tel = $data["tel"];
							$email = $data["email"];
						}
						$rep->closeCursor();
						echo "<span class='subtitle'>".$name."</span>";
						echo "<span class='right'><b>TIN : </b>".$tin."</span>";
			echo "</p>";
			echo "<hr />";
			echo "<p>";
				echo "<form action='' method='post'>";
					echo "<table>";
						echo "<tr>";
							echo "<td><b>Invoice nr</b></td><td>: ".$num_fact."</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td><b>Client</b></td><td>: ".$client."</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td><b>Date</b></td><td>: ".dateEn($date)." - ".$time." - </td><td><b>Payment method: </b></td><td>".$pay_mode."</td>";
						echo "</tr>";
					echo "</table>";
		echo "<hr>";
		echo "<div class='hidden'>";
			echo "<table class='hidden'>";
				echo "<tr>";
					echo "<td><input type='text' size='50' id='sel_pro' name='product_name' placeholder='Product name' required /></td>";
					echo "<td><input type='number' name='quantity' required placeholder='Quantity' /></td>";
					echo "<td><input type='submit' name='add' value='Add' class='btn' /></td>";
					echo "<td><span class='closeNew link'>Cancel</span></td>";
				echo "</tr>";
			echo "</table>";
		echo "</div>";
				echo "</form>";
				echo "</p>";
				if(isset($_POST["add"]))
				{
					$pro = new Product();
					$res = $pro->is_product_exist($_POST["product_name"]);
					if($res == false) {
						echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The product <b>".$_POST["product_name"]."</b> does NOT exist in the system !!!";
						echo"</div>";
					} else {
						if($_POST["quantity"] <= 0) {
							echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["quantity"]."</b> Invalid quantity !!!";
							echo"</div>";
						} else {
							$p = new Product();
							$infos_pro = $p->infosParNom($_POST["product_name"]);
							$product_code = $infos_pro[0];
							$product_unit = $infos_pro[1];
							$price_100 = $infos_pro[4];

							$she = new Shelf();
							$quantity = $she->get_product_quantity_shelf($product_code);
							if($quantity < $_POST["quantity"]) {
								echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["product_name"] ."</b>, Qty: <b>".number_format($_POST["quantity"])."</b> insufficient quantity !!! Rest: <b>".number_format($quantity)." ".$product_unit."</b>";
								echo"</div>";
							} else {
								$total = $_POST["quantity"] * $price_100;
								$bdd = connexionDb();
					$req = $bdd->prepare("INSERT INTO commande (num_fact, product_code, product_name, quantity, price, total, cash, user, date, status, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
								$req->execute(array($_GET["num_fact"], $product_code, $_POST["product_name"], $_POST["quantity"], $price_100, $total, $total, $_SESSION["username"], $date, true, "cash"));
								$req->closeCursor();
							}
						}
					}
				}
				
				// Delete a product in tempo
				if(isset($_POST["del_com"]))
				{
					echo "<form action='' method='POST' id='askMsg'>";
						echo "<img src='img/warning.png' class='small' /> Confirm ";
						echo "<input type='hidden' name='id_com' value=".$_POST["id_com"].">";
						echo "<input type='submit' name='confirm_del_com' value='Yes delete' class='small_btn'>";
						echo "<a href='' class='link'>Cancel</a>";
					echo "</form>";
				}
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Code</th>";
					echo "<th>Product</th>";
					echo "<th>Qty</th>";
					echo "<th>Price</th>";
					echo "<th>Total</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM commande WHERE num_fact = '".$num_fact."' ORDER BY id ASC");
			$total = 0;
			$n = 0;
			while($donnees = $reponse->fetch())
			{
					$n++;
					echo"<tr>";
						echo"<form action='' method='post'>";
							echo"<td>".$donnees['product_code']."</td>";
							echo"<td>".$donnees['product_name']."</td>";
							echo"<td>".number_format($donnees['quantity'])."</td>";
							echo"<td>".number_format($donnees['price'])."</td>";
							echo"<td>".number_format($donnees['total'])."</td>";
							echo"<td class='hidden'>";
								echo"<input type='hidden' name='id_com' value=".$donnees['id']." />";
								echo"<input type='submit' name='del_com' value='&times;' title='Delete' />";
							echo"</td>";
						echo"</form>";
					echo"</tr>";
					$total = $total + $donnees['total'];
			}
			$reponse->closeCursor();
			echo"<tr>";
				echo"<th colspan='4'></th>";
				echo"<th>".number_format($total)."</th>";
			echo"</tr>";
			echo "</table>";
			echo "</div>";
			echo "<p>";
				$u = new User();
				$user_infos = $u->infos_user($user);
				$lastname = $user_infos[2];
				echo "<b>Operator:</b> ".$lastname."<hr>";
				echo "Signature & Stamp";
			echo "</p>";
			echo "<div class='right hidden'>";
				echo "<div id='hidden_div'>";
					echo "<form action='invoices_cash.php' method='post' id='askMsg'>";
						echo "<img src='./img/warning.png' class='small'> Do you really want to delete this invoice?";
						echo "<input type='hidden' name='num_fact' value='".$num_fact."' />";
						echo "<input type='submit' name='del_fact' value='Yes delete' class='other_red_btn small_btn' />";
						echo " <span class='link' onclick='hide_div()'>Cancel</span>";
					echo "</form>";
				echo "</div>";
				echo "<p>";
					echo "<span onclick='show_div()' class='open_hiden_div_btn red_btn small_btn'><img src='./img/delete.png' class='small small_down'> Delete this invoice</span>";
				echo "</p>";
			echo "</div>";
			echo "<p class='hidden'>";
				echo "<a href='javascript:window.print()'><img src='img/print.jpg' class='print'></a>";
			echo "</p>";
		}
		
		public function print_commande_open_insu($num_fact)
		{
			$fact = new Facture_insurance();
			$infos_fact = $fact->infos_fact_insurance($num_fact);
			$date = $infos_fact[1];
			$user = $infos_fact[2];
			$status = $infos_fact[3];
			$insu = $infos_fact[4];
			$time = $infos_fact[5];
			$rate = $infos_fact[6];
			$adherent = $infos_fact[7];
			$card_number = $infos_fact[8];
			$pay_mode = $infos_fact[9];
			echo "<p class=''>";
						$name = $tin = $address = $tel = $email = null;
						$bdd = connexionDb();
						$rep = $bdd->query("SELECT * FROM company");
						while($data = $rep->fetch())
						{
							$name = $data["nom"];
							$tin = $data["tin"];
							$address = $data["address"];
							$tel = $data["tel"];
							$email = $data["email"];
						}
						$rep->closeCursor();
						echo "<span class='subtitle'>".$name."</span>";
						echo "<span class='right'><b>TIN : </b>".$tin."</span>";
			echo "</p>";
			echo "<hr />";
			echo "<p>";
					echo "<table>";
						echo "<tr>";
							echo "<tr>";
								echo "<td><b>Invoice nr</b></td><td>: ".$num_fact."</td>";
							echo "</tr>";
							echo "<td><b>Insurance</b></td>";
							echo "<td>: ".$insu. " (".$rate."%)";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td><b>Client</b></td><td>: ".$adherent."</td><td><b>Card number</b></td><td>: ".$card_number."</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td><b>Date</b></td><td>: ".dateEn($date)." - ".$time."</td><td><b>Pay. method</b></td><td>: ".$pay_mode."</td>";
							echo "<td></td>";
						echo "</tr>";
					echo "</table>";
					if($status == false AND nombre("product") > 0)
					{
	echo "<hr>";
	echo "<form action='' method='post'>";
		echo "<table class='hidden'>";
			echo "<tr>";
				echo "<td><input type='text' size='30' id='sel_pro' name='product_name' placeholder='Product name' required /></td>";
				echo "<td><input type='number' name='quantity' required placeholder='Quantity' /></td>";
				echo "<td><input type='submit' name='add' value='Add' class='btn' /></td>";
			echo "</tr>";
		echo "</table>";
	echo "</form>";
					}
				echo "</form>";
				
				if(isset($_POST["add"]))
				{
					$pro = new Product();
					$res = $pro->is_product_exist($_POST["product_name"]);
					if($res == false) {
						echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!!</b> The product <b>".$_POST["product_name"] ."</b> does NOT exist in the system !!!";
						echo"</div>";
					} else {
						if($_POST["quantity"] <= 0) {
							echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".$_POST["quantity"] ."</b> Invalid quantity !!!";
							echo"</div>";
						} else {
							$p = new Product();
							$infos_pro = $p->infosParNom($_POST["product_name"]);
							$product_code = $infos_pro[0];
							$price = $infos_pro[3];
							$product_unit = $infos_pro[1];

							$she = new Shelf();
							$quantity = $she->get_product_quantity_shelf($product_code);
							if($quantity < $_POST["quantity"]) {
								echo"<div id='askMsg'>";
				echo "<img src='img/warning.png' class='small' /> <b>Error !!! ".number_format($_POST["quantity"]) ."</b> insufficient quantity !!! Rest: <b>".number_format($quantity)." ".$product_unit."</b>";
								echo"</div>";
							} else {
								$total = $_POST["quantity"] * $price;
								$credit = ($total * $rate) / 100;
								$cash = $total - $credit;
								$bdd = connexionDb();
					$req = $bdd->prepare("INSERT INTO commande (num_fact, product_code, product_name, quantity, price, total, credit, cash, user, date, status, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
								$req->execute(array($_GET["num_fact"], $product_code, $_POST["product_name"], $_POST["quantity"], $price, $total, $credit, $cash, $_SESSION["username"], $date, $status, "insu"));
								$req->closeCursor();
							}
						}
					}
				}
				
				// Delete a product in tempo_insu
				if(isset($_POST["del_com"]))
				{
					echo "<form action='' method='POST' id='askMsg'>";
						echo "<img src='img/warning.png' class='small' /> Confirm ";
						echo "<input type='hidden' name='id_com' value=".$_POST["id_com"].">";
						echo "<input type='submit' name='confirm_del_com' value='Yes delete' class='small_btn'>";
						echo "<a href='' class='link'>Cancel</a>";
					echo "</form>";
				}
			echo "</p>";
			echo "<div id='display'>";
				echo "<table>";
					echo "<tr>";
						echo "<th>N°</th>";
						echo "<th>CODE</th>";
						echo "<th>Product</th>";
						echo "<th>Qty</th>";
						echo "<th>Price</th>";
						echo "<th>Total (100%)</th>";
						echo "<th>Insurance (".$rate."%)</th>";
						echo "<th>";
							$cash_rate = 100 - $rate;
							echo "Client (".$cash_rate."%)";
						echo "</th>";
					echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM commande WHERE num_fact = '".$num_fact."' ORDER BY id ASC");
			$total = 0;
			$cre = 0;
			$c = 0;
			$n = 0;
			while($donnees = $reponse->fetch())
			{
				$n++;
				echo"<tr>";
					echo"<form action='' method='post'>";
						echo"<td>".$n."</td>";
						echo"<td>".$donnees['product_code']."</td>";
						echo"<td>".$donnees['product_name']."</td>";
						echo"<td>".number_format($donnees['quantity'])."</td>";
						echo"<td>".number_format($donnees['price'])."</td>";
						echo"<td>".number_format($donnees['total'])."</td>";
						echo"<td>".number_format($donnees['credit'])."</td>";
						echo"<td>".number_format($donnees['cash'])."</td>";
						if($status == false) {
							echo"<td class='hidden'>";
								echo"<input type='hidden' name='id_com' value=".$donnees['id']." />";
								echo"<input type='submit' name='del_com' value='&times;' title='Delete' />";
							echo"</td>";
						}
					echo"</form>";
				echo"</tr>";
				$total = $total + $donnees['total'];
				$cre = $cre + $donnees['credit'];
				$c = $c + $donnees['cash'];
			}
			$reponse->closeCursor();
				echo "<tr>";
					echo "<td colspan='6'></td>";
					echo "<td><b>Total (100%)</b></td><td> : ".number_format($total)."</td>";
				echo "<tr>";
				echo "<tr>";
					echo "<td colspan='6'></td>";
					echo "<td><b>Insur. (".$rate."%)</b></td><td> : ".number_format($cre)."</td>";
				echo "<tr>";
				echo "<tr>";
					echo "<td colspan='6'></td>";
					echo "<td><b>Client (".$cash_rate."%)</b></td>";
					echo "<td> : ";
						$cash = $total - $cre;
						echo number_format($cash);
					echo "</td>";
				echo "<tr>";
			echo "</table>";
			echo "</div>";
			echo "<form action='' method='post' class='right hidden'>";
					if($status == true)
					{
						echo "<span class='greenText'>Paid</span> <img src='img/valid.png' class='medium pay_sign' />";
						echo "<input type='submit' class='small_btn' name='unpaid' value='Mark as unpaid'>";
					}
					else{
						echo "<span class='redText'>Waiting for payment ...</span> <img src='img/non_valid.png' class='medium pay_sign' />";
						echo "<input type='submit' class='small_btn' name='paid' value='Mark as paid'>";
					}
				echo "</form>";
			echo "<p>";
				$u = new User();
				$user_infos = $u->infos_user($user);
				$lastname = $user_infos[2];
				echo "<b>Operator:</b> ".$lastname."<hr>";
				echo "Signature & Stamp";
			echo "</p>";
			echo "<div class='right hidden'>";
				echo "<div id='hidden_div'>";
					echo "<form action='invoices_insu.php' method='post' id='askMsg'>";
						echo "<img src='./img/warning.png' class='small'> Do you really want to delete this invoice? ";
						echo "<input type='hidden' name='num_fact' value='".$num_fact."' />";
						echo "<input type='submit' name='del_fact' value='Yes delete' class='other_red_btn small_btn' />";
						echo " <span class='link' onclick='hide_div()'>Cancel</span>";
					echo "</form>";
				echo "</div>";
				echo "<p>";
					echo "<span onclick='show_div()' class='open_hiden_div_btn red_btn small_btn'><img src='./img/delete.png' class='small small_down'> Delete this invoice</span>";
				echo "</p>";
			echo "</div>";
			echo "<p class='hidden'>";
				echo "<a href='javascript:window.print()'><img src='img/print.jpg' class='print'></a>";
			echo "</p>";
		}
		
		// Verifier si le nom du product existe
		public function is_product_exist_except_all($nom_pro)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM commande");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["product"]) == strtolower($nom_pro))
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			if($nom_pro == "All"){
				$trouver = true;
			}
			return $trouver;
		}

		// Verifier si le nom du product existe
		public function is_product_exist_in_commande($nom_pro)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM commande");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["product_name"]) == strtolower($nom_pro))
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			if($nom_pro == "All"){
				$trouver = true;
			}
			return $trouver;
		}
	}
?>