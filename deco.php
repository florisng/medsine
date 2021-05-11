<?php
	session_start();

	// DB connexion
	function connexionDb()
	{
		try
		{
			$bdd = new PDO("mysql:host=localhost;dbname=pha", "root", "");
		}
		catch(Exception $e)
		{
			echo "<div id='Error'>";
				die('Oops !!! Error while connecting to database: ('.$e->getMessage().')');
			echo "</div>";
		}
		return $bdd;
	}

	// Change user status to false[disconnected]
	$bdd = connexionDb();
	$reponse = $bdd->prepare("UPDATE user SET user_status = :s WHERE username = :u");
	$reponse->execute(array(
		's' => false,
		'u' => $_SESSION["username"]
	));
	$reponse->closeCursor();
	
	session_unset();
	session_destroy();
	header('Location: index.php');
?>

