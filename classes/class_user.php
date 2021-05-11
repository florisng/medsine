<?php
	// Classe User
	class User
	{
		// Update user's infos
		public function update_user($new_username, $password, $nom, $prenom, $type, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE user SET username = :us, password = :p, nom = :n, prenom = :pre, type = :t WHERE username = :u");
			$reponse->execute(array(
				'us' => $new_username,
				'p' => $password,
				'n' => $nom,
				'pre' => $prenom,
				't' => $type,
				'u' => $username
			));
			$reponse->closeCursor();
		}

		// Update user's infos
		public function update_user_noPwd($new_username, $nom, $prenom, $type, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE user SET username = :us, nom = :n, prenom = :pre, type = :t WHERE username = :u");
			$reponse->execute(array(
				'us' => $new_username,
				'n' => $nom,
				'pre' => $prenom,
				't' => $type,
				'u' => $username
			));
			$reponse->closeCursor();
		}

		// Update user status
		public function update_status($status, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE user SET user_status = :s WHERE username = :u");
			$reponse->execute(array(
				's' => $status,
				'u' => $username
			));
			$reponse->closeCursor();
		}

		// Save user's token
		public function save_token($token, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->prepare("UPDATE user SET token = :t WHERE username = :u");
			$reponse->execute(array(
				't' => $token,
				'u' => $username
			));
			$reponse->closeCursor();
		}
		
		// Afficher tous les utilisateurs
		public function tous(){
			echo "<div id='display'>";
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM user ORDER BY username ASC');
			echo "<table>";
				echo "<tr>";
					echo "<th>Name</th>";
					echo "<th>User name</th>";
					echo "<th>Type</th>";
					echo "<th>Status</th>";
				echo "</tr>";
			$i = 0;
			while($donnees = $reponse->fetch())
			{
				echo"<form action='' method='post'>";
				if($donnees['type'] != "Admin") {
					$i++;
					if($i % 2 == 0){
						echo"<tr class='alt'>";
					}
					else{
						echo"<tr>";
					}
						echo "<td>";
							if($donnees['username'] === $_SESSION["username"]) {
								echo "<span class='bold'>";
							} else {echo "<span>";}
								echo $donnees['prenom']." ".$donnees['nom'];
							echo "</span>";
						echo "</td>";
						echo"<td>".$donnees['username']."</td>";
						echo"<td>".$donnees['type']."</td>";
						echo"<td>";
							if($donnees['user_status'] == true) {
								echo "<img src='img/online.png' class='online' alt=''> ";
								echo "<span class='greenText'>Online</span>";
							} else {
								echo "<img src='img/offline.png' class='offline' alt=''> ";
								echo "<span class='redText'>Offline</span>";
							}
						echo"</td>";
						echo"<td>";
							echo"<input type='hidden' name='nom' value='".$donnees["nom"]."' />";
							echo"<input type='hidden' name='prenom' value='".$donnees["prenom"]."' />";
							echo"<input type='hidden' name='username' value='".$donnees["username"]."' />";
							echo"<input type='hidden' name='type' value='".$donnees["type"]."' />";
							echo"<input type='submit' name='modify_user' value='Update' class='small_btn' title='Modify infos' />";
							if($donnees["type"] != "Admin" AND $donnees["username"] != $_SESSION["username"])
							{
								echo " ========== ";
								echo" <input type='submit' name='delete_user' value='&times;' class='small_btn' title='Delete' />";
							}
						echo"</td>";
					echo"</tr>";
				}
				echo"</form>";
			}
			echo "</table>";
			$reponse->closeCursor();
			echo "</div>";
		}
		
		// Toutes les infos de l'utilisateur
		public function infos_user($username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM user");
			$trouver = false;
			$infos = array();
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["username"] == $username)
				{
					$trouver = true;
					$password = $donnees["password"];
					$nom = $donnees["nom"];
					$prenom = $donnees["prenom"];
					$type = $donnees["type"];
					$infos = array($password, $nom, $prenom, $type);
				}
			}
			$reponse->closeCursor();
			return $infos;
		}
		
		// Connexion de l'utilisateur
		public function is_user_exist($username, $password)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM user');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["username"] == $username AND $donnees["password"] == sha1($password))
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}

		// Connexion de l'utilisateur
		public function is_token_valid($token)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM user');
			$found = false;
			while($donnees = $reponse->fetch() AND $found == false)
			{
				if($donnees["token"] == $token)
				{
					$found = true;
				}
			}
			$reponse->closeCursor();
			return $found;
		}
		
		// Connexion de l'utilisateur
		public function is_username_exist($username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM user');
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["username"] == $username)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}

		// Count how many managers we have
		public function nbr_manager()
		{
			$bdd = connexionDb();
			$reponse = $bdd->query('SELECT * FROM user');
			$nbr = 0;
			while($donnees = $reponse->fetch())
			{
				if($donnees["type"] == "Manager")
				{
					$nbr++;
				}
			}
			$reponse->closeCursor();
			return $nbr;
		}
		
		// Connexion de l'utilisateur
		public function is_username_exist_except($new_username, $username)
		{
			$bdd = connexionDb();
			$reponse = $bdd->query("SELECT * FROM user WHERE username != '".$username."'");
			$trouver = false;
			while($donnees = $reponse->fetch() AND $trouver == false)
			{
				if($donnees["username"] == $new_username)
				{
					$trouver = true;
				}
			}
			$reponse->closeCursor();
			return $trouver;
		}
		
		// Suppr�ssion de l'utilisateur
		public function deleteUser($username)
		{
			$bdd = connexionDb();
			$bdd->query("DELETE FROM user WHERE username = '".$username."'");
		}
		
		// Enregistrer un utilisateur
		public function add($nom, $prenom, $username, $password, $type)
		{
			$bdd = connexionDb();
			$req = $bdd->prepare('INSERT INTO user (nom, prenom, username, password, type) VALUES (?, ?, ?, ?, ?)');
			$req->execute(array($nom, $prenom, $username, sha1($password), $type));
			$req->closeCursor();
		}
	}
?>