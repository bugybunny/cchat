<?php
// +----------------------------------------------------------------------------------------------+
// | Projekt       cchat                                                                          |
// | Dateiname     ajax.php                                                                       |
// | Plattform     PHP 5.1 / Apache 2.0                                                           |
// |                                                                                              |
// | Autor         Marco Syfrig (syfm)                                                            |
// | Datum         2009-04-30                                                                     |
// |                                                                                              |
// | Beschreibung  Erhält von index.php ein JSON dekodiertes Array $data per POST, wertet es      |
// |               aus und gibt ein Array $data_answer zurück									  |
// |               Diese Datei und die hier aufgerufenen Funktionen machen Folgendes:		 	  |
// |				 - user.lastrefresh (=DB-Feld) aktualisieren und den User automatisch         |
// |				   ausloggen, wenn er nicht mehr erreichbar ist								  |
// |                 - Die neuen Nachrichten werden in die Datenbank geschrieben				  |
// |				 - Vom Benutzer noch ungelesene Nachrichten werden in $data_answer gespeichert|
// |                 - Login überprüfen (Name und Passwort) und den Userstatus auf eingeloggt	  |
// |			 	   setzen, sofern das Login stimmt											  |
// |				 - Neuen user Datensatz bei einer Registrierung in der Datenbank anlegen	  |
// | 																							  |
// |			  Die Beschreibung von $data, $data_answer und den Errorcodes ist unter 		  |
// |		      http://code.google.com/p/cchat/wiki/Datenaustausch zu finden.					  |
// |                                                                                              |
// +----------------------------------------------------------------------------------------------+
require 'config.inc.php';
require 'login.php';
require 'logout.php';
require 'actions.php';

session_start();
header('Content-type: text/json; charset=utf-8');

/* Datenbankverbindung herstellen */
mysql_connect($mysql_server, $mysql_login, $mysql_pass);
mysql_select_db($mysql_db);

/* Variablendeklaration und -initialisierung */
$data = json_decode($_POST['data'], true);
/* Array, welches zurückgeschick wird */
$data_answer = array();
/* Falls letzte Aktualisierung nicht definiert ist, wird 0 angenommen */
$data['last'] = isset($data['last']) ? $data['last'] : 0;

/*
 * Aktualisiert user.lastrefresh immer
 * Anhand von user.lastrefresh wird geprüft, ob der User seinen Browser geschlossen oder Verbindungsprobleme hat.
 * Der user wird automatisch ausgeloggt, sofern sich user.lastrefresh seit mehr als 30 Sekunden nicht mehr aktualisiert hat.
 */
if(userIsLoggedin()) {
	mysql_query("UPDATE user SET lastrefresh = now() WHERE user.id = {$_SESSION['userid']}");
}

/* Registrierung */
if(isset($data['register'])) {
	require 'register.php';
	$data_answer['error'] = register($data['register']['name'], $data['register']['password'], $data['register']['email']);
}

/* Login:
 * Überprüfen und Errorcode setzen
 */
if(isset($data['login'])) {
	$data_answer['error'] = login($data['login']['name'], $data['login']['password']);
}

/*
 * Nachrichten:
 * Immer wenn eine Anfrage kommt (ajax.php aufgerufen wird) und der User eingeloggt ist, werden die neuen Nachrichten in der Datenbank, seit der letzten
 * Abfrage, zurückkgeschickt.
 */
if(userIsLoggedin()) {
	$data_answer['messages'] = checkNewMessages($data['last']);
}
/*
 * Wenn zusätzlich neue Nachrichten vom User geschrieben wurden, werden sie in die Datenbank geschrieben.
 */
if(isset($data['messages'])) {
	if(userIsLoggedin()) {
		insertmessages($data, $_SESSION['userid']);
	} else {
		/* User nicht eingeloggt: Aktion fehlgeschlagen */
		$data_answer['error'] = 101;
	}
}


/*
 * Alle user, die seit 30 Sekunden nicht mehr erreichbar sind, ausloggen
 */
checkForLogout(30);

/*
 * Setzen, ob der User gerade eingeloggt ist
 */
$data_answer['logedout'] = !userIsLoggedin();

/*
 * User setzen, die sich neu ein- oder ausgeloggt haben, falls es welche gibt
 */
$users = getUsersLogin($data['last']);
if(count($users['login']) != 0)
	$data_answer['user']['login'] = $users['login'];
if(count($users['logout']) != 0)
	$data_answer['user']['logout'] = $users['logout'];

/* Antwort als JSON zurückschicken */
echo json_encode($data_answer);
?>