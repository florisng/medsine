<?php
	// Classe Facture pour assurance
	class Facture_insurance
	{
		// Afficher toutes les factures pour ambulant
		public function tous_insu_by_insurance($insu_id)
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Adherent</th>";
					echo "<th>Card number</th>";
					echo "<th>Total</th>";
					echo "<th>Credit</th>";
					echo "<th>Client</th>";
					echo "<th>Insurance</th>";
					echo "<th>Rate (%)</th>";
					echo "<th>Paid</th>";
					echo "<th>Payment mode</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			
			// Today's invoice
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE insu_code = ".$insu_id." ORDER by date DESC");
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
								echo "<th colspan='4'>Today: ".$today_nbr." result(s)</th>";
								echo "<th>".number_format($total)."</th>";
								echo "<th>".number_format($total_credit)."</th>";
								echo "<th>".number_format($total_cash)."</th>";
								echo "<th colspan='5'></th>";
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
							echo"<td>".$donnees['adherent']."</td>";
							echo"<td>".$donnees['card_number']."</td>";
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
							echo"<td>".$donnees['insurance']."</td>";
							echo"<td>".number_format($donnees['rate'])."%</td>";
							echo"<td>";
								if($donnees['status'] == true)
								{
									echo "<img src='img/valid.png' class='small' /> ";
								}
								else{
									echo "<img src='img/non_valid.png' class='small' /> ";
								}
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
								echo "<a href='open_fact_insu.php?num_fact=".$donnees['numero']."' class='link' title='Open the invoice'>Open</a>";
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
				echo "<th colspan='4'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th>".number_format($total_credit)."</th>";
				echo "<th>".number_format($total_cash)."</th>";
				echo "<th colspan='5'></th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}

		// Afficher toutes les factures pour ambulant
		public function tous_insu()
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Adherent</th>";
					echo "<th>Card number</th>";
					echo "<th>Total</th>";
					echo "<th>Credit</th>";
					echo "<th>Client</th>";
					echo "<th>Insurance</th>";
					echo "<th>Rate (%)</th>";
					echo "<th>Paid</th>";
					echo "<th>Payment mode</th>";
					echo "<th>Cashier</th>";
				echo "</tr>";
			
			// Today's invoice
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM facture_insurance ORDER by date DESC');
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
								echo "<th colspan='4'>Today: ".$today_nbr." result(s)</th>";
								echo "<th>".number_format($total)."</th>";
								echo "<th>".number_format($total_credit)."</th>";
								echo "<th>".number_format($total_cash)."</th>";
								echo "<th colspan='5'></th>";
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
							echo"<td>".$donnees['adherent']."</td>";
							echo"<td>".$donnees['card_number']."</td>";
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
								$insu = new Insurance();
								$insu_infos = $insu->infos_insu($donnees['insurance']);
								$insu_name = $insu_infos[0];
								echo $insu_name;
							echo "</td>";
							echo"<td>".number_format($donnees['rate'])."%</td>";
							echo"<td>";
								if($donnees['status'] == true)
								{
									echo "<img src='img/valid.png' class='small' /> ";
								}
								else{
									echo "<img src='img/non_valid.png' class='small' /> ";
								}
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
								echo "<a href='open_fact_insu.php?num_fact=".$donnees['numero']."' class='link' title='Open the invoice'>Open</a>";
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
				echo "<th colspan='4'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th>".number_format($total_credit)."</th>";
				echo "<th>".number_format($total_cash)."</th>";
				echo "<th colspan='5'></th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}
		
		// Afficher toutes les ventes
		public function search_invoices_insurance($month, $year, $pay_mode, $status, $user)
		{
			echo "<div id='display'>";
			if($user != "All") {
				$u = new User();
				$res_user = $u->infos_user($user);
				$lastname = $res_user[2];
			}

			$status_val = "Unpaid";
			if($status == true){$status_val = "Paid";}
			
			$bdd = connexionDb();
			echo "<p>";
			if($month != "All" AND $year != "All" AND $pay_mode != "All" AND $status != "All" AND $user != "All"){
				echo "Month: ".mois($month)." ".$year." - Payment mode: ".$pay_mode." - Paid: ".$status_val." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode != "All" AND $status != "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year." - Payment mode: ".$pay_mode." - Paid: ".$status_val;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND status = ".$status." ORDER by date DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode != "All" AND $status == "All" AND $user != "All"){
				echo "Month: ".mois($month)." ".$year." - Payment mode: ".$pay_mode." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode != "All" AND $status == "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year." - Payment method: ".$pay_mode;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' ORDER by date DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode == "All" AND $status != "All" AND $user != "All"){
				echo "Month: ".mois($month)." ".$year." - Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode == "All" AND $status != "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year." - Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." ORDER by date DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode == "All" AND $status == "All" AND $user != "All"){
				echo "Month: ".mois($month)." ".$year." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year != "All" AND $pay_mode == "All" AND $status == "All" AND $user == "All"){
				echo "Month: ".mois($month)." ".$year;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND DATE_FORMAT(date, '%Y') = ".$year." ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode != "All" AND $status != "All" AND $user != "All"){
				echo "Month: ".mois($month)." - Payment method: ".$pay_mode." Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND pay_mode = '".$pay_mode."' AND status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode != "All" AND $status != "All" AND $user == "All"){
				echo "Month: ".mois($month)." - Payment method: ".$pay_mode." - Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND pay_mode = '".$pay_mode."' AND status = ".$status." ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode != "All" AND $status == "All" AND $user != "All"){
				echo "Month: ".mois($month)." - Payment method: ".$pay_mode." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode != "All" AND $status == "All" AND $user == "All"){
				echo "Month: ".mois($month)." - Payment method: ".$pay_mode;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND pay_mode = '".$pay_mode."' ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode == "All" AND $status != "All" AND $user != "All"){
				echo "Month: ".mois($month)." - Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode == "All" AND $status != "All" AND $user == "All"){
				echo "Month: ".mois($mois)." - Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND status = ".$status." ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode == "All" AND $status == "All" AND $user != "All"){
				echo "Month: ".mois($month)." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND user = '".$user."' ORDER by date DESC");
			}
			if($month != "All" AND $year == "All" AND $pay_mode == "All" AND $status == "All" AND $user == "All"){
				echo "Month: ".mois($month);
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode != "All" AND $status != "All" AND $user != "All"){
				echo "Year: ".$year." - Payment mode: ".$pay_mode." - Paid: ".$status_val." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode != "All" AND $status != "All" AND $user == "All"){
				echo "Year: ".$year." - Payment mode: ".$pay_mode." - Paid: ".$status_val;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND status = ".$status." ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode != "All" AND $status == "All" AND $user != "All"){
				echo "Year: ".$year." - Payment mode: ".$pay_mode." - Cashier: ".$lastname;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode != "All" AND $status == "All" AND $user == "All"){
				echo "Year: ".$year." - Payment method: ".$pay_mode;
		$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." AND pay_mode = '".$pay_mode."' ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode == "All" AND $status != "All" AND $user != "All"){
				echo "Year: ".$year." - Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode == "All" AND $status != "All" AND $user == "All"){
				echo "Year: ".$year." - Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." AND status = ".$status." ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode == "All" AND $status == "All" AND $user != "All"){
				echo "Year: ".$year." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." AND user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year != "All" AND $pay_mode == "All" AND $status == "All" AND $user == "All"){
				echo "Year: ".$year;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%Y') = ".$year." ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode != "All" AND $status != "All" AND $user != "All"){
				echo "Payment method: ".$pay_mode." Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE pay_mode = '".$pay_mode."' AND status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode != "All" AND $status != "All" AND $user == "All"){
				echo "Payment method: ".$pay_mode." - Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE pay_mode = '".$pay_mode."' AND status = ".$status." ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode != "All" AND $status == "All" AND $user != "All"){
				echo "Payment method: ".$pay_mode." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE DATE_FORMAT(date, '%m') = ".$month." AND pay_mode = '".$pay_mode."' AND user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode != "All" AND $status == "All" AND $user == "All"){
				echo "Payment method: ".$pay_mode;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE pay_mode = '".$pay_mode."' ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode == "All" AND $status != "All" AND $user != "All"){
				echo "Paid: ".$status_val." - Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE status = ".$status." AND user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode == "All" AND $status != "All" AND $user == "All"){
				echo "Paid: ".$status_val;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE status = ".$status." ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode == "All" AND $status == "All" AND $user != "All"){
				echo "Cashier: ".$lastname;
				$reponse = $bdd->query("SELECT * FROM facture_insurance WHERE user = '".$user."' ORDER by date DESC");
			}
			if($month == "All" AND $year == "All" AND $pay_mode == "All" AND $status == "All" AND $user == "All"){
				$reponse = $bdd->query("SELECT * FROM facture_insurance ORDER by date DESC");
			}
			echo "</p>";
			
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Invoice num</th>";
					echo "<th>Adherent</th>";
					echo "<th>Card number</th>";
					echo "<th>Total</th>";
					echo "<th>Credit</th>";
					echo "<th>Client</th>";
					echo "<th>Insurance</th>";
					echo "<th>Rate (%)</th>";
					echo "<th>Paid</th>";
					echo "<th>Payment mode</th>";
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
								echo "<th colspan='4'>Today: ".$today_nbr." result(s)</th>";
								echo "<th>".number_format($total)."</th>";
								echo "<th>".number_format($total_credit)."</th>";
								echo "<th>".number_format($total_cash)."</th>";
								echo "<th colspan='5'></th>";
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
						echo"<td>".$donnees['adherent']."</td>";
						echo"<td>".$donnees['card_number']."</td>";
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
							$insu = new Insurance();
							$insu_infos = $insu->infos_insu($donnees['insurance']);
							$insu_name = $insu_infos[0];
							echo $insu_name;
						echo "</td>";
						echo"<td>".number_format($donnees['rate'])."%</td>";
						if($status != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
							if($donnees['status'] == true)
							{
								echo "<img src='img/valid.png' class='small' /> ";
							}
							else{
								echo "<img src='img/non_valid.png' class='small' /> ";
							}
						echo "</td>";
						if($pay_mode != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
							echo $donnees['pay_mode'];
						echo "</td>";
						if($user != "All"){echo"<td class='keyword'>";}else{echo "<td>";}
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
							echo "<a href='open_fact_insu.php?num_fact=".$donnees['numero']."' class='link' title='Open the invoice'>Open</a>";
						echo "</td>";
					echo"</tr>";
					$total += $t;
					$total_credit += $t_credit;
					$total_cash += $t_cash;
			}
			echo "<tr>";
				$total_res = $today_nbr + $other_nbr;
				echo "<th colspan='4'>Total: ".$total_res." result(s)</th>";
				echo "<th>".number_format($total)."</th>";
				echo "<th>".number_format($total_credit)."</th>";
				echo "<th>".number_format($total_cash)."</th>";
				echo "<th colspan='5'></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Verifier si le numero de facture existe
		public function is_num_fact_exist($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM facture_insurance');
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
		public function newNumFact_insurance($date)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT num, date FROM facture_insurance");
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
			$numero = $num."/".$current_year."/insu";
			$infos = array ($numero, $num);
			return $infos;
		}
		
		// Informations de la facture
		public function infos_fact_insurance($num_fact)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM facture_insurance");
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
					$insu = $donnees["insurance"];
					$time = $donnees["time"];
					$rate = $donnees["rate"];
					$adherent = $donnees["adherent"];
					$card_number = $donnees["card_number"];
					$pay_mode = $donnees["pay_mode"];
					$infos = array ($num, $date, $user, $status, $insu, $time, $rate, $adherent, $card_number, $pay_mode);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Enregistrer une facture
		public function add_facture_insurance($insu_code, $insu_name, $adherent, $card_number, $pay_mode, $date, $time, $rate)
		{
			$infos = $this->newNumFact_insurance(dater());
			$numero_fact = $infos[0];
			$num = $infos[1];
			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO facture_insurance (numero, num, adherent, card_number, insu_code, insurance, rate, pay_mode, date, time, user, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($numero_fact, $num, $adherent, $card_number, $insu_code, $insu_name, $rate, $pay_mode, $date, $time, $_SESSION["username"], false));
			$req->closeCursor();

			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO invoices (num_fact, date, client, type, insurance, rate, user, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array($numero_fact, $date, $adherent, "Insurance", $insu_name, $rate, $_SESSION["username"], false));
			$req->closeCursor();

			return $numero_fact;
		}
	}
?>







