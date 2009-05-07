<?php
// +----------------------------------------------------------------------------------------------+
// | Projekt       cchat                                                                          |
// | Dateiname     ajax.php                                                                       |
// | Plattform     PHP 5.1 / Apache 2.0                                                           |
// |                                                                                              |
// | Autor         Marco Syfrig (syfm)                                                            |
// | Datum         2009-04-30                                                                     |
// |                                                                                              |
// | Beschreibung  Erhält von index.php ein JSON dekodiertes Array $data per POST und wertet es   |
// |               aus. 																		  |
// |               Diese Datei macht Folgendes:													  |
// |                 - Die neuen Nachrichten werden in die Datenbank geschrieben				  |
// |                 - Login überprüfen (Name und Passwort) und den Userstatus auf eingeloggt     |
// |				 - Neuen user Datensatz (Account) in der Datenbank anlegen, sofern der        |
// |                   Username noch nicht vorhanden ist 
// |				 - 	                                    	  |
// |               Es wird ein neues Array $data_answer erstellt. NOCH ERWEITERN                  |
// |                                                                                              |
// |                                                                                              |
// |                                                                                              |
// |                                                                                              |
// | Version  Datum       Beschreibung                                                  Autor     |
// | -------  ----------  ------------                                                  -----     |
// | V1.00    2009-04-30  erstellt                                                      syfm      |
// |                                                                                              |
// +----------------------------------------------------------------------------------------------+
include 'config.inc.php';
session_start();
/* header('Content-type: text/json; charset=utf-8'); */
mysql_connect($mysql_server, $mysql_login, $mysql_pass);
mysql_select_db($mysql_db);

/* Variablen */
$data = json_decode($_POST['data'], true);
$array_id_login  = 0;
$array_id_logout = 0;

/* Nachrichten */
$data_answer['messages'][0]['sender']  = "Hans";
$data_answer['messages'][0]['message'] = "Hallo";
$data_answer['messages'][0]['time']    = floor(microtime(true) * 1000);
$data_answer['messages'][1]['sender']  = "Rolf";
$data_answer['messages'][1]['message'] = "Hallo Hans";
$data_answer['messages'][1]['time']    = floor(microtime(true) * 1000);

/* User die sich ein- oder ausgeloggt haben in der Zeit seit dem letzten Senden */
/* $data_answer['login'][0] = $data['login']['name'];
 $data_answer['logout'][0] = "Rolf";
 $data_answer['logedin'][0] = true;
 $data_answer['logedin'][1] = false; */

/* Login */
if(isset($data['login'])) {
	/* Username und Passwort überprüfen */
	if(!empty($data['login']['name']) && !empty($data['login']['password'])) {
		/* Datenbankabfrage machen */
		$name_login = mysql_real_escape_string($data['login']['name']);
		$result_login = mysql_query("SELECT id, salt FROM user WHERE name = '$name_login'");
		/* User wurde gefunden */
		if(mysql_num_rows($result_login)) {
			/* Passwort mit Verschlüsselung überprüfen */
			$user = mysql_fetch_assoc($result_login);
			$password = hash("sha256", $user['salt'] . hash("sha256", $user['salt'] . $data['login']['password']));
			$result_login = mysql_select("SELECT id FROM user WHERE id = '{$user['id']}' AND password = '$password'");
			if(mysql_num_rows($result_login)) {
				$data_answer['login'][$array_id_login] = $data['login']['name'];
				$array_id_login += 1;
			}
		}
	}
}

/* Registrierung */
if(isset($data['register'])) {
	/* Neuer Benutzer mit Passwort in der Datenbank anlegen */
	if(isset($data['register'])) {
		/* Überprüfen ob alle Felder ausgefüllt sind. Wird in der Eingabemaske schon geprüft, da die Daten können
		 jedoch verändert werden können, wird es hier nochmals überprüft*/
		if(!empty($data['register']['name']) && !empty($data['register']['password']) && !empty($data['register']['email'])) {
			$name_register = mysql_real_escape_string($data['register']['name']);
			$email_register = mysql_real_escape_string($data['register']['email']);
			$result_register = mysql_query("SELECT name FROM user WHERE name = '$name_register'");
			/* Der Username existiert noch nicht, deshalb liefert die MySQL-Abfrage kein Ergebnis. Der Account kann erstellt werden */
			if(!mysql_num_rows($result_register)) {
				$salt = rand(1, PHP_INT_MAX) . "cchatisttoll" . rand(1, PHP_INT_MAX);
				$password_register = hash("sha256", $salt . hash("sha256", $salt . $data['register']['password']));
				echo $password_register;
				mysql_query("INSERT INTO user (name,password,salt,mail, register) VALUES ('$name_register', '$password_register', '$salt', '$email_register', now())");
				echo mysql_error();
				$data_answer['login'][$array_id_login] = $data['register']['name'];
				$array_id_login += 1;
			}
		}
	}
}

echo json_encode($data_answer);
?>