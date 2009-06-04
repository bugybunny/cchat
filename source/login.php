<?php
/**
 * Loggt zuerst den aktuell eingeloggten User aus.
 *
 * Überprüft ob ein User mit dem Namen $user und dem Passwort $password existiert
 * Ist dies der Fall, wird der Status user.logedin auf true gesetzt und die
 * Session-Variable name auf $name gesetzt. In $_SESSION['name'] ist immer der
 * Name des aktuell eingeloggten Users gespeichert und in $_SESSION['userid']
 * die dazugehörige Userid
 *
 * @param string	$name 		Name den der User eingegeben hat
 * @param string	$password 	Passwort, welches User eingegeben hat
 * @return int					Errorcode: Beschreibung der Codes unter http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function login($name, $password) {
	/* User zuerst ausloggen */
	logoutUser();

	/* Username und Passwort überprüfen */
	/* Datenbankabfrage machen */
	$name_login = mysql_real_escape_string($name);
	$result_login = mysql_query("SELECT id, salt FROM user WHERE name = '$name_login'");
	/* User wurde gefunden */
	if(mysql_num_rows($result_login)) {
		/* Passwort mit Verschlüsselung überprüfen */
		$user = mysql_fetch_assoc($result_login);
		$password_login = hash("sha256", $user['salt'] . hash("sha256", $user['salt'] . $password));
		$result_login = mysql_query("SELECT id FROM user WHERE id = '{$user['id']}' AND password = '$password_login'");

		/* Passwortüberprüfung */
		if(mysql_num_rows($result_login)) {
			/* Userstatus auf logedin setzen */
			mysql_query("UPDATE user SET logedin = true, lastrefresh = now() WHERE user.id = {$user['id']}");

			/* Username und UserID des aktuell eingeloggten Users */
			$_SESSION['name'] = $name;
			$_SESSION['userid'] = $user['id'];
			return 000;
		}
		/* Errorcode: Passwort ist falsch */
		else {
			return 202;
		}
	}
	/* Errorcode: Es wurde kein User mit dem eingegeben Namen gefunden */
	else {
		// if(!empty($name) {
			return 201;
	//	} 
	}
}

/**
 * Sofern momentan ein User eingeloggt ist, wird er zuerst ausgeloggt. Damit ist es nicht möglich,
 * dass sich ein User bei mehreren Leuten gleichzeitig einloggen kann. *
 */
function logoutUser() {
	/* Aktuell eingeloggten User ausloggen */
	if(isset($_SESSION['name']) && isset($_SESSION['userid'])) {
		mysql_query("UPDATE user SET logedin = false WHERE id = {$_SESSION['userid']}");
		session_destroy();
	}
}