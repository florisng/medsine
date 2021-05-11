<?php
	// Classe Product
	class Product
	{
		// Update product info
		public function modify_product_info($name, $unit, $price_100, $price_insu, $secu, $code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE product SET nom = :n, unit = :u, price_100 = :p1, insu_price = :p2, secu_shelf = :s WHERE code = :c");
			$reponse->execute(array(
				'n' => $name,
				'u' => $unit,
				'p1' => $price_100,
				'p2' => $price_insu,
				's' => $secu,
				'c' => $code
			));
			$reponse->closeCursor();
		}

		// Afficher tous les products
		public function tous_by_letter_product($l)
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th></th>";
					echo "<th>Code</th>";
					echo "<th>Product name</th>";
					echo "<th>Unit</th>";
					echo "<th>Insur. Price</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM all_products");
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
						echo"<td><u><b>".number_format($donnees['insu_price'])."</b></u></td>";
					echo"</tr>";
				}
			}
			$reponse->closeCursor();
			echo "<tr>";
				echo "<th colspan='5'>".number_format($i)." result(s)</th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}
		
		// Suppréssion d'un product
		public function delete_product($code_pro)
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM product WHERE code = '".$code_pro."'");
			
			$bdd = connexionDb();
			$bdd->query("DELETE FROM shelf WHERE product_code = '".$code_pro."'");
		}
		
		// Vérifier si le nom du product existe
		public function is_product_exist($nom_pro)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM product');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["nom"]) == strtolower($nom_pro))
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}

		// Vérifier si le nom du product existe
		public function is_product_exist_in_allProducts($nom_pro)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM all_products');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["nom"]) == strtolower($nom_pro))
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		public function is_product_exist_except($product_name, $old_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM product WHERE nom != '".$old_name."'");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["nom"] == $product_name)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Toutes les infos du product
		public function infosParNom($product_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM product");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["nom"]) == strtolower($product_name))
				{
					$trouver = true;
					$code = $donnees["code"];
					$unit = $donnees["unit"];
					$secu_shelf = $donnees["secu_shelf"];
					$insu_price = $donnees["insu_price"];
					$price_100 = $donnees["price_100"];
					$infos = array ($code, $unit, $secu_shelf, $insu_price, $price_100);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}

		// Toutes les infos du product
		public function infosParNom_allProducts($product_name)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM all_products");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if(strtolower($donnees["nom"]) == strtolower($product_name))
				{
					$trouver = true;
					$code = $donnees["code"];
					$unit = $donnees["unit"];
					$insu_price = $donnees["insu_price"];
					$infos = array ($code, $unit, $insu_price);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}

		// Informations du product par son code
		public function infosParCode($code)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM product");
			$trouver = false;
			$infos = null;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["code"] == $code)
				{
					$trouver = true;
					$nom = $donnees["nom"];
					$unit = $donnees["unit"];
					$secu_shelf = $donnees["secu_shelf"];
					$insu_price = $donnees["insu_price"];
					$price_100 = $donnees["price_100"];
					$infos = array ($nom, $unit, $secu_shelf, $insu_price, $price_100);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Generate product code
		public function product_code_generator()
		{
			do {
				$code_product = generateRandomCode();
			} while($this->is_code_product_exist($code_product) == true);
			return $code_product;
		}

		// Vérifier si le nom du product existe
		public function is_code_product_exist($code_pro)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM product');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["code"] == $code_pro)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Enregistrer un product
		public function add($code_product, $nom, $unit, $price_100, $insu_price, $secu_shelf)
		{
			$bdd = connexionDb();
			$req = $bdd->prepare('INSERT INTO product (code, nom, unit, price_100, insu_price, secu_shelf) VALUES (?, ?, ?, ?, ?, ?)');
			$req->execute(array($code_product, strtoupper($nom), $unit, $price_100, $insu_price, $secu_shelf));
			$req->closeCursor();
			return $code_product;
		}

		// Afficher tous les products
		public function tous()
		{
			echo "<div id='display'>";
			echo "<table>";
				echo "<tr>";
					echo "<th></th>";
					echo "<th>Code</th>";
					echo "<th>Product name</th>";
					echo "<th>Unit</th>";
					echo "<th>Insur. Price</th>";
				echo "</tr>";
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM all_products");
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
						echo"<td><u><b>".number_format($donnees['insu_price'])."</b></u></td>";
					echo"</tr>";
			}
			$reponse->closeCursor();
			echo "<tr>";
				echo "<th colspan='5'>".number_format($i)." result(s)</th>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
		}
	}
?>