<?php
	// Classe Supplier
	class Supplier
	{	
		// Generer un code supplier
		public function supplier_code_gene()
		{
			$bdd = connexionDb();
			$rep = $bdd->query("SELECT * FROM supplier");
			$supplier_code = mt_rand(1000, 9999);
			while($data = $rep->fetch())
			{
				if($data["code"] == $supplier_code)
				{
					$supplier_code = mt_rand(1000, 9999);
				}
			}
			$rep->closeCursor();
			return $supplier_code;
		}
		
		// Enregistrer un supplier
		public function add($nom, $tin, $adresse, $tel)
		{
			$code_supplier = $this->supplier_code_gene();
			$bdd = connexionDb();
			$req = $bdd->prepare("INSERT INTO supplier (code, nom, tin, adresse, tel) VALUES (?, ?, ?, ?, ?)");
			$req->execute(array($code_supplier, $nom, $tin, $adresse, $tel));
			$req->closeCursor();
		}
		
		public function is_supplier_code_exist($code_supplier)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM supplier');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["code"] == $code_supplier)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		public function is_supplier_exist_except($supplier_name, $old_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM supplier WHERE nom != '".$old_name."'");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["nom"] == $supplier_name)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		public function is_supplier_exist($supplier_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM supplier');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["nom"]) == strtolower($supplier_name))
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Toutes les infos du supplier
		public function infosParNom($nom_supplier)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM supplier");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["nom"]) == strtolower($nom_supplier))
				{
					$trouver = true;
					$code = $donnees["code"];
					$tel = $donnees["tel"];
					$tin = $donnees["tin"];
					$adresse = $donnees["adresse"];
					$infos = array ($code, $tel, $tin, $adresse);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Toutes les infos du supplier
		public function infosParCode($code_supplier)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM supplier");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["code"] == $code_supplier)
				{
					$trouver = true;
					$nom = $donnees["nom"];
					$tel = $donnees["tel"];
					$tin = $donnees["tin"];
					$adresse = $donnees["adresse"];
					$infos = array ($nom, $tel, $tin, $adresse);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Afficher tous les suppliers
		public function tous_by_letter($l)
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM supplier ORDER BY nom DESC');
			echo "<table>";
				echo "<tr>";
					echo "<th>Code</th>";
					echo "<th>Name</th>";
					echo "<th>TIN</th>";
					echo "<th>Adresse</th>";
					echo "<th>Contact</th>";
				echo "</tr>";
			$i = 0;
			while($donnees = $reponse->fetch())
			{
				$string = $donnees["nom"];
				if(strtolower($string[0]) == $l)
				{
					echo"<form action='' method='post'>";
						$i++;
						if($i % 2 == 0){
							echo"<tr class='alt'>";
						}
						else{
							echo"<tr>";
						}
							echo"<td>".$donnees['code']."</td>";
							echo"<td>".$donnees['nom']."</td>";
							echo"<td>".$donnees['tin']."</td>";
							echo"<td>".$donnees['adresse']."</td>";
							echo"<td>".$donnees['tel']."</td>";
							echo"<td>";
								echo"<input type='hidden' name='supplier_name' value='".$donnees['nom']."' />";
								echo"<input type='hidden' name='supplier_code' value='".$donnees['code']."' />";
								echo"<input type='submit' name='modify' value='' class='mod' title='Modify' />";
							echo"</td>";
							echo"<td> ========== </td>";
							echo"<td>";
								echo"<input type='submit' name='delete_supplier' value='&times;' title='Delete' />";
							echo"</td>";
						echo"</tr>";
					echo"</form>";
				}
			}
			echo "<tr>";
				echo "<th colspan='5'>".$i." result(s)</th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Afficher tous les suppliers
		public function tous()
		{
			echo "<div id='display'>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM supplier ORDER BY nom DESC');
			echo "<table>";
				echo "<tr>";
					echo "<th>Code</th>";
					echo "<th>Name</th>";
					echo "<th>TIN</th>";
					echo "<th>Adresse</th>";
					echo "<th>Contact</th>";
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
						echo"<td>".$donnees['code']."</td>";
						echo"<td>".$donnees['nom']."</td>";
						echo"<td>".$donnees['tin']."</td>";
						echo"<td>".$donnees['adresse']."</td>";
						echo"<td>".$donnees['tel']."</td>";
						echo"<td>";
							echo"<input type='hidden' name='supplier_name' value='".$donnees['nom']."' />";
							echo"<input type='hidden' name='supplier_code' value='".$donnees['code']."' />";
							echo"<input type='submit' name='modify' value='' class='mod' title='Modify' />";
						echo"</td>";
						echo"<td> ========== </td>";
						echo"<td>";
							echo"<input type='submit' name='delete_supplier' value='&times;' title='Delete' />";
						echo"</td>";
					echo"</tr>";
				echo"</form>";
			}
			echo "<tr>";
				echo "<th colspan='5'>".$i." result(s)</th>";
			echo "</tr>";
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Suppression d'un supplier
		public function delete_supplier($supplier_code)
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM supplier WHERE code = ".$supplier_code."");
		}
	}
?>