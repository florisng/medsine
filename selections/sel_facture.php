<?php
    if(isset($_GET['query'])) {
        // Mot tape par l'utilisateur
        $q = htmlentities($_GET['query']);
 
        // Connexion to DB
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=pha', 'root', '');
        } catch(Exception $e) {
            exit('Impossible de se connecter a la base de donnees.');
        }
		
        // SQL request
		$requete = "SELECT numero FROM facture WHERE numero LIKE '%". $q ."%' LIMIT 10";
		
        // Execution of SQL request
        $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		
        // Resust
        while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
            // Add data in table
			$suggestions["suggestions"][] = $donnees["numero"];
        }
		
        // Echo data in JSON format
		echo json_encode($suggestions);
    }
?>