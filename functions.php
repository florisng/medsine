<?php
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

	function generateRandomString() {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 10; $i++) {
			$randomString .= $characters[mt_rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	// Random String Generator
	function generateRandomCode() {
		return mt_rand(11111, 99999);
	}
	

	// Change due date
	if(isset($_POST["save_due_date"])) {
		$bdd = connexionDb();
		$reponse = $bdd->prepare("UPDATE company SET exp_date = :d");
		$reponse->execute(array(
			'd' => $_POST["due_date"]
		));
		$reponse->closeCursor();
		echo"<div id='other_okMsg'>";
			echo "<img src='img/cool.png' class='small' /> Due date changed to <b>".dateEn($_POST["due_date"])."</b> <a href='' class='btn'>Got it</a>";
		echo"</div>";
	}

	session_start();

	$bdd = connexionDb();
	$rep = $bdd->query("SELECT * FROM company");
	$exp_date = date("Y-m-d");
	$status = false;
	while($data = $rep->fetch())
	{
		$exp_date = $data["exp_date"];
		$status = $data["status"];
	}
	$rep->closeCursor();
	$today = date("Y-m-d");
	if($today > $exp_date) {
		$bdd = connexionDb();
		$reponse = $bdd->prepare("UPDATE company SET status = :s WHERE id = :i");
		$reponse->execute(array(
			's' => true,
			'i' => 1
		));
		$reponse->closeCursor();
		session_unset();
		session_destroy();
		header('Location: expired.php');
	}

	if($status == true) {
		session_unset();
		session_destroy();
		header('Location: expired.php');
	}

		// Number of elements
		function nombre($tab)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT COUNT(*) AS nbr FROM $tab");
			$data = $reponse->fetch();
			$nbr = $data['nbr'];
			$reponse->closeCursor();
			return $nbr;
		}
		
		// Get current date
		function dater()
		{
			$date = date("Y-m-d");
			return $date;
		}

		// Get the current time
		function getCurrentTime()
		{
			date_default_timezone_set("Africa/Kigali");
			return date("h:i:s");
		}
		
		function dateEn($date){
			$infos_date = infosDate($date);
			$m = $infos_date[1];
			$o = mois($m);
			return strftime("%d-".$o."-%Y", strtotime($date));
		}
		
		function dateFr($date){
			$infos_date = infosDate($date);
			$m = $infos_date[1];
			$o = mois($m);
			return strftime("%d ".$o." %Y", strtotime($date));
		}
		
		function noel()
		{
			$res = false;
			if(date("m") == 12){
				if(date("d") <= 31 AND date("d") >= 1){
					$res = true;
				}
			}
			return $res;
		}
		
		function bonneAnnee(){
			$res = false;
			if(date("d") == 1 AND date("m") == 1){
				$res = true;
			}
			return $res;
		}
		
		// Recuperer le jour dans une date
		function infosDate($date)
		{
			$infos = null;
			$date = date_parse($date);
			$jour = $date['day'];
			$mois = $date['month'];
			$annee = $date['year'];
			
			$infos = array ($jour, $mois, $annee);
			return $infos;
		}
		
		// 'display'atage des mois
		function mois($mois)
		{
			$nom = null;
			switch ($mois) // on indique sur quelle variable on travaille
			{
				case 1:
				$nom = "Jan";
				break;
				case 2:
				$nom = "Feb";
				break;
				case 3:
				$nom = "Mar";
				break;
				case 4:
				$nom = "April";
				break;
				case 5:
				$nom = "May";
				break;
				case 6:
				$nom = "Jun";
				break;
				case 7:
				$nom = "Jul";
				break;
				case 8:
				$nom = "Aug";
				break;
				case 9:
				$nom = "Sept";
				break;
				case 10:
				$nom = "Oct";
				break;
				case 11:
				$nom = "Nov";
				break;
				case 12:
				$nom = "Dec";
				break;
				default:
				$nom = null;
			}
			return $nom;
		}
?>