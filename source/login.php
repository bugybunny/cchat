<?php
function login($name, $password) {
	/* Username und Passwort überprüfen */
	if(!empty($name) && !empty($password)) {
		/* Datenbankabfrage machen */
		$name_login = mysql_real_escape_string($name);
		$result_login = mysql_query("SELECT id, salt FROM user WHERE name = '$name_login'");
		/* User wurde gefunden */
		if(mysql_num_rows($result_login)) {
			/* Passwort mit Verschlüsselung überprüfen */
			$user = mysql_fetch_assoc($result_login);
			$password_login = hash("sha256", $user['salt'] . hash("sha256", $user['salt'] . $password));
			$result_login = mysql_select("SELECT id FROM user WHERE id = '{$user['id']}' AND password = '$password_login'");
			return !!mysql_num_rows($result_login);
		}
	}
}
?>