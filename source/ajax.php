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
// |               aus.                                                                           |
// |               Es wird ein neues Array $data_answer erstellt. NOCH ERWEITERN                  |
// |                                                                                              |
// | Version  Datum       Beschreibung                                                  Autor     |
// | -------  ----------  ------------                                                  -----     |
// | V1.00    2009-04-30  erstellt                                                      syfm      |
// |                                                                                              |
// +----------------------------------------------------------------------------------------------+
include 'config.inc.php';
session_start();
/* header('Content-type: text/json; charset=utf-8'); */
mysql_connect("localhost", "root", "gibbiX12345");
mysql_select_db("cchat");


$data = json_decode($_POST['data'], true);

/* Nachrichten */
$data_answer['messages'][0]['sender'] = "Hans";
$data_answer['messages'][0]['message'] = "Hallo";
$data_answer['messages'][0]['time'] = 	floor(microtime(true) * 1000);
$data_answer['messages'][1]['sender'] = "Rolf";
$data_answer['messages'][1]['message'] = "Hallo Hans";
$data_answer['messages'][1]['time'] = floor(microtime(true) * 1000);

/* User die sich ein- oder ausgeloggt haben in der Zeit seit dem letzten Senden */
$data_answer['login'][0] = $data['login']['name'];
$data_answer['logout'][0] = "Rolf";
$data_answer['logedin'][0] = true;
$data_answer['logedin'][1] = false;

/* Login */
if(isset($data['login'])) {
	/* Username und Passwort überprüfen */
	if(!empty($data['login']['name']) && !empty($data['login']['password'])) {
		/* Datenbankabfrage machen */
		$name = mysql_escape_string($data['login']['name']);
		$result = mysql_query("SELECT id, salt FROM user WHERE name = '$name'");
		/* User wurde gefunden */
		if(mysql_num_rows($result)) {
			/* Passwort überprüfen */
			$user = mysql_fetch_assoc($result);
			$password = hash("sha256", $user['salt'] . hash("sha256", $user['salt'] . $data['login']['password']));
			$result = mysql_select("SELECT id FROM user WHERE id = '{$user['id']}' AND password = '$password'");
			if(mysql_num_rows($result)) {
				$data_answer['login'][0] = $data['login']['name'];
			}
		}
	} else {
		
	}
}

/* Registrierung */
if(isset($data['register'])) {
	/* Neuer Benutzer mit Passwort in der Datenbank anlegen */



}
?>