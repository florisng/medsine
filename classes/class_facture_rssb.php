<?php
	// Classe Facture pour RSSB
	class Facture_rssb
	{
		// Afficher toutes les factures pour RSSB
		public function tous_rssb()
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Affiliate</th>";
					echo "<th>Beneficiary</th>";
					echo "<th>Relation</th>";
					echo "<th>Receiver</th>";
					echo "<th>Total</th>";
					echo "<th>RSSB (85%)</th>";
					echo "<th>Cash (15%)</th>";
					echo "<th>Paid</th>";
					echo "<th>Pay. method</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM facture_rssb ORDER BY num DESC');
			$today_nbr = 0;
			$other_nbr = 0;
			$total = 0;
			$total_credit = 0;
			$total_cash = 0;
			$done = false;
			$infosDate = infosDate(dater());
			$current_month = $infosDate[1];
			$current_year = $infosDate[2];
			while($donnees = $reponse->fetch())
			{
				$infosDate = infosDate($donnees['date']);
				$month = $infosDate[1];
				$year = $infosDate[2];
				if($current_month == $month AND $current_year == $year)
				{
					if($donnees['date'] == dater())
					{
						echo"<tr class='today_move'>";
						$today_nbr++;
					}
					else{
						$other_nbr++;
						if($today_nbr > 0 && $done == false) {
							$done = true;
							echo "<tr>";
								echo "<th colspan='6'>Today: ".$today_nbr." result(s)</th>";
								echo "<th>".number_format($total)."</th>";
								echo "<th>".number_format($total_credit)."</th>";
								echo "<th>".number_format($total_cash)."</th>";
								echo "<th colspan='3'></th>";
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
							echo"<td>".$donnees['numero']."</td>";
							echo"<td>".$donnees['affi_name']."</td>";
							echo"<td>".$donnees['bene_name']."</td>";
							echo"<td>".$donnees['relation']."</td>";
							echo"<td>".$donnees['receiver']."</td>";
							echo"<td>";
								$c = new Commande();
								$t = $c->total_facture($donnees['numero']);
								echo number_format($t);
							echo "</td>";
							echo"<td>";
								$c = new Commande();
								$t_credit = $c->total_facture_credit($donnees['numero']);
								echo number_format($t_credit);
							echo "</td>";
							echo"<td>";
								$c = new Commande();
								$t_cash = $c->total_facture_cash($donnees['numero']);
								echo number_format($t_cash);
							echo "</td>";
							echo"<td>";
								if($donnees['status'] == true)
								{
									echo "<img src='img/valid.png' class='small' /> ";
								}
								else{
									echo "<img src='img/non_valid.png' class='small' /> ";
								}
							echo "</td>";
							echo "<td>".$donnees['pay_mode']."</td>";
							echo"<td>";
								$u = new User();
								$res = $u->is_username_exist($donnees['user']);
								if($res == true){
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
								echo "<a href='open_fact_rssb.php?num_fact=".$donnees['numero']."' class='link' title='Open the invoice'>Open</a>";
							echo "</td>";
						echo"</tr>";
						$total += $t;
						$total_credit += $t_credit;
						$total_cash += $t_cash;
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				$total_res = $today_nbr + $other_nbr;
				echo "<th colspan='6'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th>".number_format($total_credit)."</th>";
				echo "<th>".number_format($total_cash)."</th>";
				echo "<th colspan='3'></th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}
		
		// Afficher toutes les ventes
		public function search_invoices_rssb($month, $year, $status, $user)
		{
			echo "<div id='display'>";
			
			$status_val = "Unpaid";
			if($status == "true"){$status_val = "Paid";}
			
			$bdd = connexionDb();
			echo "<p>";
			if($month != "All" AND $year != "All" AND $status != "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Month: ".mois($month)." ".$year." - Paid: ".$status_val." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." AND user = '".$user."' ORDER by num DESC");
			}
		
			if($month != "All" AND $year != "All" AND $status != "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year." - Paid: ".$status_val;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." ORDER by num DESC");
			}
			
			if($month != "All" AND $year != "All" AND $status == "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Month: ".mois($month)." ".$year." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER by num DESC");
			}
			if($month != "All" AND $year != "All" AND $status == "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." ORDER by num DESC");
			}
			
			if($month != "All" AND $year == "All" AND $status != "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Month: ".mois($month)." - Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." AND status = ".$status." AND user = '".$user."' ORDER by num DESC");
			}
			if($month != "All" AND $year == "All" AND $status != "All" AND $user == "All"){
				echo "Month: ".mois($month)." - Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." AND status = ".$status." ORDER by num DESC");
			}
			
			if($month != "All" AND $year == "All" AND $status == "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Month: ".mois($month)." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." AND user = '".$user."' ORDER by num DESC");
			}
			if($month != "All" AND $year == "All" AND $status == "All" AND $user == "All"){
				echo "Month: ".mois($month);
				$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%m') = ".$month." ORDER by num DESC");
			}

			if($month == "All" AND $year != "All" AND $status != "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Year: ".$year." - Paid: ".$status_val." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." AND user = '".$user."' ORDER by num DESC");
			}
		
			if($month == "All" AND $year != "All" AND $status != "All" AND $user == "All"){
				echo "Year: ".$year." - Paid: ".$status_val;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." ORDER by num DESC");
			}
			
			if($month == "All" AND $year != "All" AND $status == "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Year: ".$year." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER by num DESC");
			}
			if($month == "All" AND $year != "All" AND $status == "All" AND $user == "All"){
				echo "Year: ".$year;
		$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE DATE_FORMAT(date, '%Y') = ".$year." ORDER by num DESC");
			}
			
			if($month == "All" AND $year == "All" AND $status != "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE status = ".$status." AND user = '".$user."' ORDER by num DESC");
			}
			if($month == "All" AND $year == "All" AND $status != "All" AND $user == "All"){
				echo "Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE status = ".$status." ORDER by num DESC");
			}
			
			if($month == "All" AND $year == "All" AND $status == "All" AND $user != "All"){
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
				echo "Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_rssb WHERE user = '".$user."' ORDER by num DESC");
			}
			if($month == "All" AND $year == "All" AND $status == "All" AND $user == "All"){
				$reponse = $bdd->query("SELECT * FROM facture_rssb ORDER by num DESC");
			}
			echo "</p>";
			
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Affiliate</th>";
					echo "<th>Beneficiary</th>";
					echo "<th>Relation</th>";
					echo "<th>Receiver</th>";
					echo "<th>Total</th>";
					echo "<th>RSSB (85%)</th>";
					echo "<th>Cash (15%)</th>";
					echo "<th>Paid</th>";
					echo "<th>Pay. method</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			$today_nbr = 0;
			$other_nbr = 0;
			$total = 0;
			$total_credit = 0;
			$total_cash = 0;
			$done = false;
			while($donnees = $reponse->fetch())
			{
				if($donnees['date'] == dater())
				{
					echo"<tr class='today_move'>";
					$today_nbr++;
				}
				else{
					$other_nbr++;
					if($today_nbr > 0 && $done == false) {
						$done = true;
						echo "<tr>";
							echo "<th colspan='6'>Today: ".$today_nbr." result(s)</th>";
							echo "<th>".number_format($total)."</th>";
							echo "<th>".number_format($total_credit)."</th>";
							echo "<th>".number_format($total_cash)."</th>";
							echo "<th colspan='3'></th>";
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
					echo"<td>".$donnees['numero']."</td>";
					echo"<td>".$donnees['affi_name']."</td>";
					echo"<td>".$donnees['bene_name']."</td>";
					echo"<td>".$donnees['relation']."</td>";
					echo"<td>".$donnees['receiver']."</td>";
					echo"<td>";
						$c = new Commande();
						$t = $c->total_facture($donnees['numero']);
						echo number_format($t);
					echo "</td>";
					echo"<td>";
						$c = new Commande();
						$t_credit = $c->total_facture_credit($donnees['numero']);
						echo number_format($t_credit);
					echo "</td>";
					echo"<td>";
						$c = new Commande();
						$t_cash = $c->total_facture_cash($donnees['numero']);
						echo number_format($t_cash);
					echo "</td>";
					echo"<td>";
						$u = new User();
						$res = $u->is_username_exist($donnees['user']);
						if($res == true){
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
						if($donnees['status'] == true)
						{
							echo "<img src='img/valid.png' class='small' /> ";
						}
						else{
							echo "<img src='img/non_valid.png' class='small' /> ";
						}
					echo "</td>";
					echo "<td>".$donnees['pay_mode']."</td>";
					echo"<td>";
						echo "<a href='open_fact_rssb.php?num_fact=".$donnees['numero']."' class='link' title='Open the invoice'>Open</a>";
					echo "</td>";
				echo"</tr>";
				$total += $t;
				$total_credit += $t_credit;
				$total_cash += $t_cash;
			}
			echo "<tr>";
				$total_res = $today_nbr + $other_nbr;
				echo "<th colspan='6'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th>".number_format($total_credit)."</th>";
				echo "<th>".number_format($total_cash)."</th>";
				echo "<th colspan='3'></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Verifier si le numero de facture existe
		public function is_num_fact_exist($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM facture_rssb');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["numero"] == $num_fact)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Creer une nouvelle facture
		public function newNumFact($date)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT num, date FROM facture_rssb");
			$infosDate = infosDate($date);
			$current_year = $infosDate[2];
			$max = 0;
			$infos = null;
			$found = false;
			while($donnees = $reponse->fetch())
			{
				$infosDate = infosDate($donnees["date"]);
				$year = $infosDate[2];
				if($year == $current_year AND $donnees["num"] > $max)
				{
					$found = true;
					$max = $donnees["num"];
				}
			}
			$reponse->closeCursor();
			
			if($found == false)	$num = 0;
			
			$num = $max + 1;
			$numero = $num."/".$current_year;
			$infos = array ($numero, $num);
			return $infos;
		}

		// Creer une nouvelle facture
		public function newNumFact_rssb($date)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT num, date FROM facture_rssb");
			$infosDate = infosDate($date);
			$current_year = $infosDate[2];
			$max = 0;
			$infos = null;
			$found = false;
			while($donnees = $reponse->fetch())
			{
				$infosDate = infosDate($donnees["date"]);
				$year = $infosDate[2];
				if($year == $current_year AND $donnees["num"] > $max)
				{
					$found = true;
					$max = $donnees["num"];
				}
			}
			$reponse->closeCursor();
			
			if($found == false)	$num = 0;
			
			$num = $max + 1;
			$numero = $num."/".$current_year."/rssb";
			$infos = array ($numero, $num);
			return $infos;
		}
		
		// Informations de la facture
		public function infos_fact_rssb($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM facture_rssb");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["numero"] == $num_fact)
				{
					$trouver = true;
					$num = $donnees["num"];
					$date = $donnees["date"];
					$user = $donnees["user"];
					$status = $donnees["status"];
					$time = $donnees["time"];
					$card_number = $donnees["card_number"];
					$affi_name = $donnees["affi_name"];
					$affected = $donnees["affected"];
					$station = $donnees["station"];
					$bene_name = $donnees["bene_name"];
					$relation = $donnees["relation"];
					$sex = $donnees["sex"];
					$age = $donnees["age"];
					$receiver = $donnees["receiver"];
					$id_number = $donnees["id_number"];
					$place = $donnees["place"];
					$hospital = $donnees["hospital"];
					$doctor = $donnees["doctor"];
					$prescri = $donnees["prescri"];
					$pay_mode = $donnees["pay_mode"];
					$infos = array ($num, $card_number, $affi_name, $affected, $station, $bene_name, $relation, $sex, $age, $receiver, $id_number, $place, $date, $user, $status, $time, $hospital, $doctor, $prescri, $pay_mode);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}

		// Enregistrer une facture pour RSSB
		public function add_rssb($prescri, $card_number, $affi_name, $affected, $station, $bene_name, $relation, $sex, $age, $hospital, $doctor, $receiver, $id_number, $place, $pay_mode, $date, $time)
		{
			$infos = $this->newNumFact_rssb(dater());
			$numero_fact = $infos[0];
			$num = $infos[1];
			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO facture_rssb (numero, num, prescri, card_number, affi_name, affected, station, bene_name, relation, sex, age, hospital, doctor, receiver, id_number, place, pay_mode, date, time, user, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($numero_fact, $num, $prescri, $card_number, $affi_name, $affected, $station, $bene_name, $relation, $sex, $age, $hospital, $doctor, $receiver, $id_number, $place, $pay_mode, $date, $time, $_SESSION["username"], false));
			$req->closeCursor();

			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO invoices (num_fact, date, client, type, insurance, rate, user, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($numero_fact, $date, $affi_name, "RSSB", "RSSB", 85, $_SESSION["username"], false));
			$req->closeCursor();

			return $numero_fact;
		}
	}
?>




