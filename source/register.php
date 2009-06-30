<?php
/**
 * Überprüfen korrekter Registrierungsdaten
 *
 * @author Marco Syfrig
 */

/**
 * Neuer Benutzer mit Passwort in der Datenbank anlegen
 * ÜberprÜfen ob alle Felder ausgefüllt sind. Wird in der Eingabemaske schon geprüft, da die Daten können
 * jedoch verändert werden können, wird es hier nochmals überprüft
 * @param string	$name 		Username
 * @param string	$password	Passwort des Users
 * @param string	$mail 		E-Mail des Users
 * @return int					Errorcode: Beschreibung der Codes unter http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function register($name, $password, $mail) {
	if(!empty($name) && !empty($password) && !empty($mail)) {

		/* Prüfung ob die Emailadresse gültig ist */
		if(preg_match("/^(?:[a-zA-Z0-9_'^&\/+-])+(?:\.(?:[a-zA-Z0-9_'^&\/+-])+)*@(?:(?:\[?(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))\.){3}(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\]?)|(?:[a-zA-Z0-9-]+\.)+(?:[a-zA-Z]){2,}\.?)$/", $mail) > 0) {
				
				
			$name_register = mysql_real_escape_string($name);
			$email_register = mysql_real_escape_string($mail);
			$result_register = mysql_query("SELECT name FROM ".DB_PREFIX."user WHERE name = '$name_register'") or trigger_error(mysql_error(), E_USER_ERROR);
			/*
			 * Der Username existiert noch nicht, deshalb liefert die MySQL-Abfrage kein Ergebnis.
			 * Der Account kann erstellt werden
			 */
			if(!mysql_num_rows($result_register)) {
				$salt = rand(1, PHP_INT_MAX) . "cchatisttoll" . rand(1, PHP_INT_MAX);
				$password_register = hash("sha256", $salt . hash("sha256", $salt . $password));
				mysql_query("INSERT INTO ".DB_PREFIX."user (name, password, salt, mail, register, logedin, lastrefresh) VALUES ('$name_register', '$password_register', '$salt', '$email_register', now(), true, now())") or trigger_error(mysql_error(), E_USER_ERROR);
					
				/* User automatisch einloggen nach der Registrierung */
				login($name_register, $password);
				return 000;
			}
			/* Errorcode: Benutzer bereits vorhanden */
			else {
				return 301;
			}
		}
		/* Errorcode: Emailadresse ist ungültig */
		else {
			return 302;
		}
	}
	/* Errorcode: Es sind nicht alle Felder ausgefüllt */
	else {
		return 303;
	}
}