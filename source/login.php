<?php
/**
 * �berpr�ft ob ein User mit dem Namen $user und dem Passwort $password existiert
 * Ist dies der Fall, wird der Status user.logedin auf true gesetzt und die
 * Session-Variable name auf $name gesetzt. In $_SESSION['name'] ist immer der
 * Name des aktuell eingeloggten Users gespeichert und in $_SESSION['userid']
 * die dazugeh�rige Userid
 *
 * @param string 	$name 		Name den der User eingegeben hat
 * @param string	$password 	Passwort das der User eingegeben hat
 * @return boolean  $logedin    True wenn der User eingeloggt wurde, false wenn nicht
 */
function login($name, $password) {
	/* Username und Passwort �berpr�fen */
	/* Datenbankabfrage machen */
	$name_login = mysql_real_escape_string($name);
	$result_login = mysql_query("SELECT id, salt FROM user WHERE name = '$name_login'");
	/* User wurde gefunden */
	if(mysql_num_rows($result_login)) {
		/* Passwort mit Verschl�sselung �berpr�fen */
		$user = mysql_fetch_assoc($result_login);
		$password_login = hash("sha256", $user['salt'] . hash("sha256", $user['salt'] . $password));
		$result_login = mysql_query("SELECT id FROM user WHERE id = '{$user['id']}' AND password = '$password_login'");

		/* Passwort�berpr�fung */
		if(mysql_num_rows($result_login)) {
			/* Userstatus auf logedin setzen */
			mysql_query("UPDATE user SET logedin = true, lastrefresh = now() WHERE user.id = {$user['id']}");

			/* Username und ID des aktuell eingeloggten Users */
			$_SESSION['name'] = $name;
			$_SESSION['userid'] = $user['id'];
				
			return false;
		}
	}

	/* Wenn der User bereits eingeloggt ist und sich neu oder mit einem neuen Username einloggen will
	 * und die Kombination aus $name und $password nicht gefunden wird, wird er mit dem alten Username ausgeloggt
	 */
	else {
		$_SESSION['name'] = "Marco";
		$_SESSION['userid'] = 1;
		echo $_SESSION['name'];
		echo $_SESSION['userid'];
		if(isset($_SESSION['name']) && isset($_SESSION['userid'])) {
			echo "aasasas";
			mysql_query("UPDATE user SET logedin = false WHERE id = {$_SESSION['userid']}");
			session_destroy();
		}
		/* Der User hat sich augeloggt */
		return true;
	}
}