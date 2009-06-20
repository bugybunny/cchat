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
 * @param  string	$name 		Name den der User eingegeben hat
 * @param  string	$password 	Passwort, welches User eingegeben hat
 * @return int					Errorcode: Beschreibung der Codes unter http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function login($name, $password) {
	/* Username und Passwort überprüfen */
	$name_login = mysql_real_escape_string($name);
	$result_login = mysql_query("SELECT id, salt FROM user WHERE name = '$name_login'");

	echo "Der User mit dem Namen $name und $password will sich einloggen\n";

	/* User wurde gefunden */
	if(mysql_num_rows($result_login)) {

		/*
		 * Sofern momentan ein User eingeloggt ist, wird er zuerst ausgeloggt. Damit ist es nicht möglich,
		 * dass ein User bei mehreren Leuten gleichzeitig eingeloggt ist.
		 */
		if(userIsLoggedin()) {
			logoutUser($_SESSION['userid'], $_SESSION['name'], true);
		}

		/* Passwort mit Verschlüsselung überprüfen */
		$user = mysql_fetch_assoc($result_login);
		$password_login = hash("sha256", $user['salt'] . hash("sha256", $user['salt'] . $password));
		$result_login = mysql_query("SELECT id FROM user WHERE id = '{$user['id']}' AND password = '$password_login'");

		/* Passwortüberprüfung */
		if(mysql_num_rows($result_login)) {

			/* Username und UserID des aktuell eingeloggten Users */
			$_SESSION['name'] = $name;
			$_SESSION['userid'] = $user['id'];

			/* Userstatus auf logedin setzen */
			mysql_query("UPDATE user SET logedin = true WHERE id = {$_SESSION['userid']}");

			/* Neuen Action-Datensatz des Typs login einfügen */
			insertLogin($_SESSION['userid'], $_SESSION['name']);

			return 000;
		}
		/* Errorcode: Passwort ist falsch */
		else {
			return 202;
		}
	}
	/* Errorcode: Es wurde kein User mit dem eingegeben Namen gefunden */
	else {
		if(userIsLoggedin()) {
			logoutUser($_SESSION['userid'], $_SESSION['name'], true);
		}
		if($name == "logout" && empty($password)) {
			return 000;
		} else {
			return 201;
		}
	}
}
/**
 * Prüft ob momentan ein User eingeloggt ist
 *
 * @return boolean		TRUE wenn ein Benutzer eingeloggt ist, ansonsten FALSE
 */
function userIsLoggedin() {
	return (isset($_SESSION['name']) && isset($_SESSION['userid']));
}