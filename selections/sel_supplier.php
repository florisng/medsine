<?php
    if(isset($_GET['query'])) {
        // Mot tap� par l'utilisateur
        $q = htmlentities($_GET['query']);
 
        // Connexion � la base de donn�es
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=pha', 'root', '');
        } catch(Exception $e) {
            exit('Impossible de se connecter �la base de donn�es.');
        }
		
        // Requ�te SQL
		$requete = "SELECT nom FROM supplier WHERE nom LIKE '%". $q ."%'";
		
        // Ex�cution de la requ�te SQL
        $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		
        // On parcourt les r�sultats de la requ�te SQL
        while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
            // On ajoute les donn�es dans un tableau
			$suggestions["suggestions"][] = $donnees["nom"];
        }
		
        // On renvoie le donn�es au format JSON pour le plugin
		echo json_encode($suggestions);
    }
?>