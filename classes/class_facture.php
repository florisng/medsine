<?php
	// Classe Facture
	class Facture
	{
		// Afficher toutes les factures
		public function tous()
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Client</th>";
					echo "<th>Insurance (%)</th>";
					echo "<th>Total</th>";
					echo "<th>Credit</th>";
					echo "<th>Cash</th>";
					echo "<th>Type</th>";
					echo "<th>Cashier</th>";
					echo "<th>Status</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM invoices ORDER BY date DESC');
			$today_nbr = 0;
			$other_nbr = 0;
			$total = 0;
			$credit = 0;
			$cash = 0;
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
								echo "<th colspan='4'>Today: ".$today_nbr." result(s)</th>";
								echo "<th>".number_format($total)."</th>";
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
							echo"<td>".$donnees['num_fact']."</td>";
							echo"<td>".$donnees['client']."</td>";
							echo"<td>".$donnees['insurance']." (".$donnees['rate']."%)</td>";
							echo"<td>";
								$c = new Commande();
								$t = $c->total_facture($donnees['num_fact']);
								echo number_format($t);
							echo "</td>";
							echo"<td>";
								$c = new Commande();
								$t_credit = $c->total_facture_credit($donnees['num_fact']);
								echo number_format($t_credit);
							echo "</td>";
							echo"<td>";
								$c = new Commande();
								$t_cash = $c->total_facture_cash($donnees['num_fact']);
								echo number_format($t_cash);
							echo "</td>";
							echo"<td>".$donnees['type']."</td>";
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
							echo"<td>";
								if($donnees["type"] == "100% Cash") {
									$url = "open_fact";
								} else {
									if($donnees["type"] == "Insurance") {
										$url = "open_fact_insu";
									} else {
										$url = "open_fact_rssb";
									}
								}
								echo "<a href='".$url.".php?num_fact=".$donnees['num_fact']."' class='link' title='Open the invoice'>Open</a>";
							echo "</td>";
						echo"</tr>";
						$total += $t;
						$credit += $t_credit;
						$cash += $t_cash;
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				$total_res = $today_nbr + $other_nbr;
				echo "<th colspan='4'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th>".number_format($credit)."</th>";
				echo "<th>".number_format($cash)."</th>";
				echo "<th colspan='3'></th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}

		// Afficher toutes les factures Cash
		public function tous_cash()
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Total</th>";
					echo "<th>Pay. method</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM facture ORDER BY num DESC');
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
								echo "<th colspan='2'>Today: ".$today_nbr." result(s)</th>";
								echo "<th>".number_format($total)."</th>";
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
							echo"<td>";
								$c = new Commande();
								$t = $c->total_facture($donnees['numero']);
								echo number_format($t);
							echo "</td>";
							echo"<td>".$donnees['pay_mode']."</td>";
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
								echo "<a href='open_fact.php?num_fact=".$donnees['numero']."' class='link' title='Open the invoice'>Open</a>";
							echo "</td>";
						echo"</tr>";
						$total += $t;
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				$total_res = $today_nbr + $other_nbr;
				echo "<th colspan='2'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th colspan='2'></th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}
		
		// Afficher toutes les ventes
		public function search_invoices_cash($month, $year, $pay_mode, $user)
		{
			echo "<div id='display'>";
			if($user != "All") {
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
			}
			
			$bdd = connexionDb();
			echo "<p>";
			if($month != "All" AND $year != "All" AND $pay_mode != "All" AND $user != "All"){
				echo "Month: ".mois($month)." ".$year." - Payment method: ".$pay_mode." - Cashier: ".$lastname;
	$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by num DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode != "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year." - Payment method: ".$pay_mode;
	$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' ORDER by num DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode == "All" AND $user != "All"){
				echo "Month: ".mois($month)." ".$year." - Cashier: ".$lastname;
	$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER by num DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode == "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year;
	$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." ORDER by num DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode != "All" AND $user != "All"){
				echo "Month: ".mois($month)." - Payment method: ".$pay_mode." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." AND pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by num DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode != "All" AND $user == "All"){
				echo "Month: ".mois($month)." - Payment method: ".$pay_mode;
				$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." AND pay_mode = '".$pay_mode."' ORDER by num DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode == "All" AND $user != "All"){
				echo "Month: ".mois($month)." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." AND user = '".$user."' ORDER by num DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode == "All" AND $user == "All"){
				echo "Month: ".mois($month);
				$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%m') = ".$month." ORDER by num DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode != "All" AND $user != "All"){
				echo "Year: ".$year." - Payment method: ".$pay_mode." - Cashier: ".$lastname;
	$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by num DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode != "All" AND $user == "All"){
				echo "Year: ".$year." - Payment method: ".$pay_mode;
	$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' ORDER by num DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode == "All" AND $user != "All"){
				echo "Year: ".$year." - Cashier: ".$lastname;
	$reponse = $bdd->query("SELECT * FROM facture DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER by num DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode == "All" AND $user == "All"){
				echo "Year: ".$year;
	$reponse = $bdd->query("SELECT * FROM facture WHERE DATE_FORMAT(date, '%Y') = ".$year." ORDER by num DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode != "All" AND $user != "All"){
				echo "Payment method: ".$pay_mode." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture WHERE pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by num DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode != "All" AND $user == "All"){
				echo "Payment method: ".$pay_mode;
				$reponse = $bdd->query("SELECT * FROM facture WHERE pay_mode = '".$pay_mode."' ORDER by num DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode == "All" AND $user != "All"){
				echo "Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture WHERE user = '".$user."' ORDER by num DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode == "All" AND $user == "All"){
				$reponse = $bdd->query("SELECT * FROM facture ORDER by num DESC");
			}
			echo "</p>";
			
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Total</th>";
					echo "<th>Pay. method</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			$today_nbr = 0;
			$other_nbr = 0;
			$total = 0;
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
								echo "<th colspan='2'>Today: ".$today_nbr." result(s)</th>";
								echo "<th>".number_format($total)."</th>";
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
							if($month != "All" OR $year != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
								echo dateEn($donnees['date']);
							echo "</td>";
							echo"<td>".$donnees['numero']."</td>";
							echo"<td>";
								$c = new Commande();
								$t = $c->total_facture($donnees['numero']);
								echo number_format($t);
							echo "</td>";
							echo"<td>".$donnees['pay_mode']."</td>";
							if($user != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
								$u = new User();
								$user_infos = $u->infos_user($donnees['user']);
								$lastname = $user_infos[2];
								echo $lastname;
							echo "</td>";
							echo"<td>";
								echo "<a href='open_fact.php?num_fact=".$donnees['numero']."' class='link' title='Open the invoice'>Open</a>";
							echo "</td>";
						echo"</tr>";
						$total += $t;
			}
			echo "<tr>";
				$total_res = $today_nbr + $other_nbr;
				echo "<th colspan='2'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th colspan='2'></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Verifier si le numero de facture existe
		public function is_num_fact_exist($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM facture');
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
			$reponse = $bdd->query("SELECT num, date FROM facture");
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
		
		// Informations de la facture
		public function infos_fact($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM facture");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["numero"] == $num_fact)
				{
					$trouver = true;
					$num = $donnees["num"];
					$client = $donnees["client"];
					$date = $donnees["date"];
					$user = $donnees["user"];
					$time = $donnees["time"];
					$pay_mode = $donnees["pay_mode"];
					$infos = array ($num, $client, $date, $user, $time, $pay_mode);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Enregistrer une facture
		public function add_facture($client, $pay_mode, $date, $time)
		{
			$infos = $this->newNumFact(dater());
			$numero_fact = $infos[0];
			$num = $infos[1];
			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO facture (numero, num, client, pay_mode, date, time, user) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($numero_fact, $num, $client, $pay_mode, $date, $time, $_SESSION["username"]));
			$req->closeCursor();

			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO invoices (num_fact, date, client, type, insurance, rate, user, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($numero_fact, $date, $client, "100% Cash", "None", 0, $_SESSION["username"], true));
			$req->closeCursor();

			return $numero_fact;
		}
	}
?>



