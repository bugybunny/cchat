<?php
// +----------------------------------------------------------------------------------------------+
// | Projekt       cchat																		  |
// | Dateiname     ajax.php																		  |
// | Plattform     PHP 5.1 / Apache 2.0															  |
// |																							  |
// | Autor         Marco Syfrig, Jannis Grimm													  |
// | Datum         2009-04-30																	  |
// |																							  |
// | Beschreibung  Erhält von index.php ein JSON dekodiertes Array $data per POST, wertet es	  |
// |               aus und gibt ein Array $data_answer zurück									  |
// |               Diese Datei und die hier aufgerufenen Funktionen machen Folgendes:			  |
// |				 - user.lastrefresh (=DB-Feld) aktualisieren und den User automatisch		  |
// |				   ausloggen, wenn er nicht mehr erreichbar ist								  |
// |                 - Die neuen Nachrichten werden in die Datenbank geschrieben				  |
// |				 - Vom Benutzer noch ungelesene Nachrichten werden in $data_answer gespeichert|
// |                 - Login überprüfen (Name und Passwort) und den Userstatus auf eingeloggt	  |
// |			 	   setzen, sofern das Login stimmt											  |
// |				 - Neuen user Datensatz bei einer Registrierung in der Datenbank anlegen	  |
// |																					 		  |
// |			  Die Beschreibung von $data, $data_answer und den Errorcodes ist unter			  |
// |		      http://code.google.com/p/cchat/wiki/Datenaustausch zu finden.					  |
// |																							  |
// +----------------------------------------------------------------------------------------------+

session_start();
header('Content-type: text/json; charset=utf-8');

require 'config.inc.php';
require 'php/constants.php';
require 'php/login.php';
require 'php/logout.php';
require 'php/actions.php';

/* Datenbankverbindung herstellen */
mysql_connect(MYSQL_SERVER, MYSQL_LOGIN, MYSQL_PASS);
mysql_select_db(MYSQL_DB);

include 'php/error.php';

/* Variablendeklaration und -initialisierung */
$data = get_magic_quotes_gpc() ? stripslashes($_POST['data']) : $_POST['data'];
$data = json_decode($data, true);
/* Array, welches zurückgeschick wird */
$data_answer = array();
/* Fehlercode zum senden */
$errorcode = 000;
/* Falls letzte Aktualisierung nicht definiert ist, wird 0 angenommen */
$data['last'] = isset($data['last']) ? $data['last'] : 0;

/* Registrierung */
if(isset($data['register'])) {
	require 'php/register.php';
	$errorcode = register($data['register']['name'], $data['register']['password'], $data['register']['email']);
}

/* Login:
 * Überprüfen und Errorcode setzen
 */
if(isset($data['login'])) {
	$errorcode = login($data['login']['name'], $data['login']['password']);
}

/*
 * Prüfen, ob der Benutzer noch angemeldet ist
 * evtl. hat er sich unterdessen an einem anderen Computer ausgeloggt und es liegen noch "Session-Leichen" da..
 */
if(userIsLoggedin()) {
	$offline = mysql_query("SELECT id FROM ".DB_PREFIX."user WHERE logedin = false AND id = {$_SESSION['userid']}") or trigger_error(mysql_error(), E_USER_ERROR);
	if(mysql_num_rows($offline)) {
		// User ist nicht mehr online
		unset($_SESSION['userid'], $_SESSION['name']);
	}
}

/*
 * Aktualisiert user.lastrefresh immer
 * Anhand von user.lastrefresh wird geprüft, ob der User seinen Browser geschlossen oder Verbindungsprobleme hat.
 * Der user wird automatisch ausgeloggt, sofern sich user.lastrefresh seit mehr als 30 Sekunden nicht mehr aktualisiert hat.
 */
if(userIsLoggedin()) {
	mysql_query("UPDATE ".DB_PREFIX."user SET lastrefresh = now() WHERE id = {$_SESSION['userid']}") or trigger_error(mysql_error(), E_USER_ERROR);

	/*
	 * Nachrichten:
	 * Immer wenn eine Anfrage kommt (ajax.php aufgerufen wird) und der User eingeloggt ist, werden die neuen Nachrichten in der Datenbank, seit der letzten
	 * Abfrage, zurückkgeschickt.
	 */
	$messages = checkNewMessages($data['last']);
	if(count($messages)) {
		$data_answer['messages'] = checkNewMessages($data['last']);
	}
}
/*
 * Wenn zusätzlich neue Nachrichten vom User geschrieben wurden, werden sie in die Datenbank geschrieben.
 */
if(isset($data['messages'])) {
	if(userIsLoggedin()) {
		insertmessages($data, $_SESSION['userid']);
	} else {
		/* User nicht eingeloggt: Aktion fehlgeschlagen */
		$errorcode = 101;
	}
}


/*
 * Alle user, die seit 30 Sekunden nicht mehr erreichbar sind, ausloggen
 */
checkForLogout(30);

/*
 * Setzen, ob der User gerade eingeloggt ist
 */
if(!userIsLoggedin()) {
	$data_answer['logedout'] = true;
}

/* Falls ein Fehler aufgetreten ist, senden */
if($errorcode != 000) {
	$data_answer['error'] = $errorcode;
}

/*
 * User setzen, die sich neu ein- oder ausgeloggt haben, falls es welche gibt
 */
$users = getUsersLogin($data['last']);
if(count($users['login'])) {
	$data_answer['user']['login'] = $users['login'];
}
if(count($users['logout'])) {
	$data_answer['user']['logout'] = $users['logout'];
}

/* Antwort als JSON zurückschicken */
echo json_encode($data_answer);
?>