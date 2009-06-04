<?php
/**
 * Neuer Benutzer mit Passwort in der Datenbank anlegen
 * Überprüfen ob alle Felder ausgefüllt sind. Wird in der Eingabemaske schon geprüft, da die Daten können
 * jedoch verändert werden können, wird es hier nochmals überprüft 
 * @param string	$name 		Username
 * @param string	$password	Passwort des Users
 * @param string	$mail 		E-Mail des Users 
 * @return int					Errorcode: Beschreibung der Codes unter http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function register($name, $password, $mail) {
	$_SESSION['name'] = $name;	

	if(!empty($name) && !empty($password) && !empty($mail)) {
		$name_register = mysql_real_escape_string($name);
		$email_register = mysql_real_escape_string($mail);
		$result_register = mysql_query("SELECT name FROM user WHERE name = '$name_register'");
		/*
		 * Der Username existiert noch nicht, deshalb liefert die MySQL-Abfrage kein Ergebnis.
		 * Der Account kann erstellt werden
		 */
		if(!mysql_num_rows($result_register)) {
			$salt = rand(1, PHP_INT_MAX) . "cchatisttoll" . rand(1, PHP_INT_MAX);
			$password_register = hash("sha256", $salt . hash("sha256", $salt . $password));
			/* Neuen user Daten */
			mysql_query("INSERT INTO user (name, password, salt, mail, register, logedin, lastrefresh) VALUES ('$name_register', '$password_register', '$salt', '$email_register', now(), true, now())");
			
			/* User automatisch einloggen nach der Registrierung */
			require 'login.php';
			login($name_register, $password);
			
			echo mysql_error();
		} 
		/* Errorcode: Benutzer bereits vorhanden */
		else {
			return 301;
		}
	}
}