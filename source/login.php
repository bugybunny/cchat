<?php
/**
 * Loggt zuerst den aktuell eingeloggten User aus.
 *
 * �berpr�ft ob ein User mit dem Namen $user und dem Passwort $password existiert
 * Ist dies der Fall, wird der Status user.logedin auf true gesetzt und die
 * Session-Variable name auf $name gesetzt. In $_SESSION['name'] ist immer der
 * Name des aktuell eingeloggten Users gespeichert und in $_SESSION['userid']
 * die dazugeh�rige Userid
 *
 * @param  string	$name 		Name den der User eingegeben hat
 * @param  string	$password 	Passwort, welches User eingegeben hat
 * @return int					Errorcode: Beschreibung der Codes unter http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function login($name, $password) {
	/*
	 * Sofern momentan ein User eingeloggt ist, wird er zuerst ausgeloggt. Damit ist es nicht m�glich,
	 * dass ein User bei mehreren Leuten gleichzeitig eingeloggt ist.
	 */
	if(isset($_SESSION['userid']) && isset($_SESSION['name'])) {
		require 'logout.php';
		logoutUser($_SESSION['userid'], $_SESSION['name']);
	}

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
			require_once 'actions.php';

			/* Username und UserID des aktuell eingeloggten Users */
			$_SESSION['name'] = $name;
			$_SESSION['userid'] = $user['id'];

			/* Userstatus auf logedin setzen */
			mysql_query("UPDATE user SET logedin = true, lastrefresh = now() WHERE user.id = {$_SESSION['userid']}");

			/* Neuen Action-Datensatz des Typs login einf�gen */
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
		if($name == "logout" && empty($password) && isset($_SESSION['userid'])) {
			require_once 'logout.php';
			logoutUser($_SESSION['userid']);
		} else if(!empty($name)) {
			return 201;
		}
	}
}