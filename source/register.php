<?php
/**
 *
 */
function register($name, $password, $mail) {
	/* Registrierung */
	/* Neuer Benutzer mit Passwort in der Datenbank anlegen */
	/* �berpr�fen ob alle Felder ausgef�llt sind. Wird in der Eingabemaske schon gepr�ft, da die Daten k�nnen
	 jedoch ver�ndert werden k�nnen, wird es hier nochmals �berpr�ft*/
	if(!empty($name) && !empty($password) && !empty($mail)) {
		$name_register = mysql_real_escape_string($name);
		$email_register = mysql_real_escape_string($mail);
		$result_register = mysql_query("SELECT name FROM user WHERE name = '$name_register'");
		/* Der Username existiert noch nicht, deshalb liefert die MySQL-Abfrage kein Ergebnis. Der Account kann erstellt werden */
		if(!mysql_num_rows($result_register)) {
			$salt = rand(1, PHP_INT_MAX) . "cchatisttoll" . rand(1, PHP_INT_MAX);
			$password_register = hash("sha256", $salt . hash("sha256", $salt . $password));
			/* Neuen user Daten */
			mysql_query("INSERT INTO user (name, password, salt, mail, register, logedin, lastrefresh) VALUES ('$name_register', '$password_register', '$salt', '$email_register', now(), true, now())");
			echo mysql_error();
		}
	}
}
?>