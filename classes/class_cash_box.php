<?php
	// Classe Cash_box
	class Cash_box
	{
		// Truncate cash_box table
		public function reset_cash_box()
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE cash_box SET montant = :m");
			$reponse->execute(array(
				'm' => 0
			));
			$reponse->closeCursor();
		}
		
		// Recuperer la moment total en caisse
		public function total_cash_box()
		{
			$bdd = connexionDb();
			$rep = $bdd->query('SELECT * FROM cash_box');
			$money = 0;
			while($data = $rep->fetch())
			{
				$money = $data["montant"];
			}
			$rep->closeCursor();
			return $money;
		}
		
		function update_cash_box_sale($total)
		{
			$old_amount = $this->total_cash_box();
			$new_amount = $old_amount + $total;
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE cash_box SET montant = :m");
			$reponse->execute(array(
				'm' => $new_amount
			));
			$reponse->closeCursor();
		}
		
		function update_cash_box_expense($total)
		{
			$nbr = nombre("cash_box");
			if($nbr == 0){
				$bdd = connexionDb();
				$req = $bdd->prepare("INSERT INTO cash_box (montant) VALUES (?)");
				$req->execute(array(0));
				$req->closeCursor();
			}
			
			$old_amount = $this->total_cash_box();
			$new_amount = $old_amount - $total;
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE cash_box SET montant = :m");
			$reponse->execute(array(
				'm' => $new_amount
			));
			$reponse->closeCursor();
		}
	}
?>