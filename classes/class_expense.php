<?php
	// Classe Expense
	class Expense
	{
		// Update an expense
		public function update_expense($descri, $amount, $pay_date, $id_exp)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE expense SET descri = :d, amount = :a, pay_date = :d WHERE id = :i");
			$reponse->execute(array(
				'd' => $descri,
				'a' => $amount,
				'd' => $pay_date,
				'i' => $id_exp
			));
			$reponse->closeCursor();
		}
		
		public function add($descri, $amount, $pay_date)
		{
			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO expense (descri, amount, pay_date, user) VALUES (?, ?, ?, ?)");
			$req->execute(array($descri, $amount, $pay_date, $_SESSION["username"]));
			$req->closeCursor();
		}
		
		// Expense infos
		public function infos_expense($id_exp)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM expense");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["id"] == $id_exp)
				{
					$trouver = true;
					$descri = $donnees["descri"];
					$amount = $donnees["amount"];
					$pay_date = $donnees["pay_date"];
					$infos = array($descri, $amount, $pay_date);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Afficher tous les expenses
		public function tous()
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM expense ORDER BY id DESC');
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Description</th>";
					echo "<th>Amount</th>";
					echo "<th>User</th>";
				echo "</tr>";
			$i = 0;
			$t = 0;
			$infosDate = infosDate(dater());
			$current_month = $infosDate[1];
			$current_year = $infosDate[2];
			while($donnees = $reponse->fetch())
			{
				$infosDate = infosDate($donnees['pay_date']);
				$month = $infosDate[1];
				$year = $infosDate[2];
				if($current_month == $month AND $current_year == $year){
					echo"<form action='' method='post'>";
						$i++;
						if($i % 2 == 0){
							echo"<tr class='alt'>";
						}
						else{
							echo"<tr>";
						}
							echo"<td>".dateEn($donnees['pay_date'])."</td>";
							echo"<td>".$donnees['descri']."</td>";
							echo"<td>".number_format($donnees['amount'])."</td>";
							echo"<td>".$donnees['user']."</td>";
							echo"<td>";
								echo" <input type='hidden' name='id_exp' value='".$donnees['id']."' />";
								echo"<input type='submit' name='modify' value='' class='mod' title='Modify infos' />";
							echo"</td>";
							echo"<td> ========== </td>";
							echo"<td>";
								echo"<input type='submit' name='delete_expense' value='' class='del' title='Delete' />";
							echo"</td>";
						echo"</tr>";
					echo"</form>";
					$t += $donnees["amount"];
				}
			}
			echo "<tr>";
				echo "<th colspan='2'>".$i." result(s)</th>";
				echo "<th>".number_format($t)."</th>";
				echo "<th></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Afficher tous les expenses
		public function tous_by_period($month, $year)
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			
			echo "<p>";
			if($month != "All" AND $year != "All"){
				echo "Month: ".mois($month)." - Year: ".$year;
		$reponse = $bdd->query("SELECT * FROM expense WHERE DATE_FORMAT(pay_date, '%m') = ".$month." AND DATE_FORMAT(pay_date, '%Y') = ".$year." ORDER BY id DESC");
			}
			
			if($month != "All" AND $year == "All"){
				echo "Month: ".mois($month);
				$reponse = $bdd->query("SELECT * FROM expense WHERE DATE_FORMAT(pay_date, '%m') = ".$month." ORDER BY id DESC");
			}
			
			if($month == "All" AND $year != "All"){
				echo "Year: ".$year;
				$reponse = $bdd->query("SELECT * FROM expense WHERE DATE_FORMAT(pay_date, '%Y') = ".$year." ORDER BY id DESC");
			}
			
			if($month == "All" AND $year == "All"){
				$reponse = $bdd->query("SELECT * FROM expense ORDER BY id DESC");
			}
			echo "</p>";
			
			echo "<table>";
				echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Description</th>";
					echo "<th>Amount</th>";
					echo "<th>User</th>";
				echo "</tr>";
			$i = 0;
			$t = 0;
			while($donnees = $reponse->fetch())
			{
					echo"<form action='' method='post'>";
						$i++;
						if($i % 2 == 0){
							echo"<tr class='alt'>";
						}
						else{
							echo"<tr>";
						}
							echo"<td>".dateEn($donnees['pay_date'])."</td>";
							echo"<td>".$donnees['descri']."</td>";
							echo"<td>".number_format($donnees['amount'])."</td>";
							echo"<td>".$donnees['user']."</td>";
							echo"<td>";
								echo" <input type='hidden' name='id_exp' value='".$donnees['id']."' />";
								echo"<input type='submit' name='modify' value='' class='mod' title='Modify infos' />";
							echo "</td>";
							echo "<td> ========== </td>";
							echo "<td>";
								echo"<input type='submit' name='delete_expense' value='' class='del' title='Delete' />";
							echo"</td>";
						echo"</tr>";
					echo"</form>";
					$t += $donnees["amount"];
			}
			echo "<tr>";
				echo "<th colspan='2'>".$i." result(s)</th>";
				echo "<th>".number_format($t)."</th>";
				echo "<th></th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Suppression d'une dépense
		public function delete_expense($id_exp)
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM expense WHERE id = ".$id_exp."");
		}
	}
?>