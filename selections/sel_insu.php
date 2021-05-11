<?php
    if(isset($_GET['query'])) {
        // Mot tapé par l'utilisateur
        $q = htmlentities($_GET['query']);
 
        // Connexion à la base de données
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=pha', 'root', '');
        } catch(Exception $e) {
            exit('Impossible de se connecter à la base de données.');
        }
		
        // Requète SQL
		$requete = "SELECT insu_name FROM insu WHERE insu_name LIKE '%". $q ."%'";
		
        // Exécution de la requète SQL
        $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		
        // On parcourt les résultats de la requète SQL
        while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
            // On ajoute les données dans un tableau
			$suggestions["suggestions"][] = $donnees["insu_name"];
        }
		
        // On renvoie le données au 'display'at JSON pour le plugin
		echo json_encode($suggestions);
    }
?>