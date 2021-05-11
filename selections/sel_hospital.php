<?php
    if(isset($_GET['query'])) {
        // Mot tape par l'utilisateur
        $q = htmlentities($_GET['query']);
 
        // Connexion e la base de donnees
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=pha', 'root', '');
        } catch(Exception $e) {
            exit('Impossible de se connecter ela base de donnees.');
        }
		
        // Requete SQL
		$requete = "SELECT nom FROM hospitals WHERE nom LIKE '%". $q ."%'";
		
        // Execution de la requete SQL
        $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		
        // On parcourt les resultats de la requete SQL
        while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
            // On ajoute les donnees dans un tableau
			$suggestions["suggestions"][] = $donnees["nom"];
        }
		
        // On renvoie le donnees au 'display'at JSON pour le plugin
		echo json_encode($suggestions);
    }
?>