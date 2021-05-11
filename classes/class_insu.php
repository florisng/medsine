<?php
	// Classe Insurance
	class Insurance
	{
		public function update_insu($new_insu_name, $add, $phone, $email, $insu_code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE insu SET insu_name = :n, addr = :a, phone = :p, email = :e WHERE code = :c");
			$reponse->execute(array(
				'n' => $new_insu_name,
				'a' => $add,
				'p' => $phone,
				'e' => $email,
				'c' => $insu_code
			));
			$reponse->closeCursor();
		}
		
		// Afficher toutes les insurances
		public function tous() {
			echo "<div id='display'>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM insu ORDER BY insu_name ASC');
			echo "<table>";
				echo "<tr>";
					echo "<th>Name</th>";
					echo "<th>Address</th>";
					echo "<th>Phone num</th>";
					echo "<th>Email</th>";
				echo "</tr>";
			$i = 0;
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
						echo"<td>".$donnees['insu_name']."</td>";
						echo"<td>".$donnees['addr']."</td>";
						echo"<td>".$donnees['phone']."</td>";
						echo"<td>".$donnees['email']."</td>";
						echo"<td>";
							echo "<input type='hidden' name='insu_name' value='".$donnees["insu_name"]."' />";
							echo "<input type='submit' name='sell' value='Select' title='Sell with this insurance' />";
						echo"</td>";
						if($_SESSION["type"] == "Manager" || $_SESSION["type"] == "Admin")
						{
							echo"<td>";
								echo "<input type='hidden' name='insu_code' value='".$donnees["code"]."' />";
								echo "<input type='submit' name='modify_insu' value='' class='mod' title='Modify infos' />";
							echo"</td>";
							echo"<td>";
								echo "<a href='?insu_id=".$donnees["code"]."#show' class='link'>Invoices</a>";
							echo"</td>";
							echo"<td> ========== </td>";
							echo"<td>";
								echo "<input type='submit' name='delete_insu' value='' class='del' title='Delete' />";
							echo"</td>";
						}				
					echo"</tr>";
				echo"</form>";
			}
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Toutes les infos de l'insurance
		public function infos_insu($insu_code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM insu");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["code"] == $insu_code)
				{
					$trouver = true;
					$name = $donnees["insu_name"];
					$add = $donnees["addr"];
					$phone = $donnees["phone"];
					$email = $donnees["email"];
					$infos = array($name, $add, $phone, $email);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Toutes les infos de l'insurance
		public function infos_insu_by_name($insu_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM insu");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["insu_name"] == $insu_name)
				{
					$trouver = true;
					$code = $donnees["code"];
					$add = $donnees["addr"];
					$phone = $donnees["phone"];
					$email = $donnees["email"];
					$infos = array($code, $add, $phone, $email);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Connexion de l'insurance
		public function is_insu_name_exist($insu_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM insu');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["insu_name"] == $insu_name)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}

		// Connexion de l'insurance
		public function is_insu_code_exist($insu_code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM insu');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["code"] == $insu_code)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Connexion de l'insurance
		public function is_insu_name_exist_except($insu_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM insu WHERE insu_name != '".$insu_name."'");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["insu_name"] == $insu_name)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Suppréssion de l'utilisateur
		public function del_insu($insu_code)
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM insu WHERE code = ".$insu_code."");
		}

		// Générer un code pour insurance
		public function insu_code_gene()
		{
			$bdd = connexionDb();
			$rep = $bdd->query("SELECT * FROM insu");
			$new_code = mt_rand(1000, 9999);
			while($data = $rep->fetch())
			{
				if($data["code"] == $new_code)
				{
					$new_code = mt_rand(1000, 9999);
				}
			}
			$rep->closeCursor();
			return $new_code;
		}
		
		// Enregistrer un utilisateur
		public function add($insu_name, $address, $phone, $email)
		{
			$insu_code = $this->insu_code_gene();
			$bdd = connexionDb();
			$req = $bdd->prepare('INSERT INTO insu (code, insu_name, addr, phone, email) VALUES (?, ?, ?, ?, ?)');
			$req->execute(array($insu_code, $insu_name, $address, $phone, $email));
			$req->closeCursor();
		}
	}
?>