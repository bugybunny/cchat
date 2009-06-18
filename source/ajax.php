<?php
// +----------------------------------------------------------------------------------------------+
// | Projekt       cchat                                                                          |
// | Dateiname     ajax.php                                                                       |
// | Plattform     PHP 5.1 / Apache 2.0                                                           |
// |                                                                                              |
// | Autor         Marco Syfrig (syfm)                                                            |
// | Datum         2009-04-30                                                                     |
// |                                                                                              |
// | Beschreibung  Erh�lt von index.php ein JSON dekodiertes Array $data per POST, wertet es      |
// |               aus und gibt ein Array $data_answer zur�ck									  |
// |               Diese Datei und die hier aufgerufenen Funktionen machen Folgendes:		 	  |
// |				 - user.lastrefresh (=DB-Feld) aktualisieren und den User automatisch         |
// |				   ausloggen, wenn er nicht mehr erreichbar ist								  |
// |                 - Die neuen Nachrichten werden in die Datenbank geschrieben				  |
// |				 - Vom Benutzer noch ungelesene Nachrichten werden in $data_answer gespeichert|
// |                 - Login �berpr�fen (Name und Passwort) und den Userstatus auf eingeloggt	  |
// |			 	   setzen, sofern das Login stimmt											  |
// |				 - Neuen user Datensatz bei einer Registrierung in der Datenbank anlegen	  |
// | 																							  |
// |			  Die Beschreibung von $data, $data_answer und den Errorcodes ist unter 		  |
// |		      http://code.google.com/p/cchat/wiki/Datenaustausch zu finden.					  |
// |                                                                                              |
// |                                                                                              |
// |                                                                                              |
// |                                                                                              |
// |                                                                                              |
// +----------------------------------------------------------------------------------------------+
include 'config.inc.php';

session_start();
header('Content-type: text/json; charset=utf-8');

/* Datenbankverbindung herstellen */
mysql_connect($mysql_server, $mysql_login, $mysql_pass);
mysql_select_db($mysql_db);

/* Variablendeklaration */
$data = json_decode($_POST['data'], true);
/* Array, welches zur�ckgeschick wird an XXX.php/js*/
$data_answer = array();
$errorcode = 000;

/* Aktualisiert user.lastrefresh immer
 * Anhand von user.lastrefresh wird gepr�ft, ob der User seinen Browser geschlossen oder Verbindungsprobleme hat. 
 * Der user wird automatisch ausgeloggt, sofern sich user.lastrefresh seit mehr als 30 Sekunden nicht mehr aktualisiert hat. 
 *
 */
if(isset($_SESSION['name']) && isset($_SESSION['userid'])) {
	mysql_query("UPDATE user SET lastrefresh = now() WHERE user.id = {$_SESSION['userid']}");	
}

/* Registrierung */
if(isset($data['register'])) {
	require 'register.php';
	$data_answer['error'] = register($data['register']['name'], $data['register']['password'], $data['register']['email']);
}

/* Login:
 * �berpr�fen und Errorcode setzen
 */
if(isset($data['login'])) {
	require 'login.php';
	$errorcode = login($data['login']['name'], $data['login']['password']);
	if($errorcode != 000) {
		$data_answer['logedout'] = true;
		$data_answer['error'] = $errorcode;
	}
}

/*
 * Nachrichten:
 * Immer wenn eine Anfrage kommt (ajax.php aufgerufen wird) und der User eingeloggt ist, werden die neuen Nachrichten in der Datenbank, seit der letzten
 * Abfrage, zur�ckkgeschickt.
 * Wenn zus�tzlich neue Nachrichten vom User geschrieben wurden, werden sie in die Datenbank geschrieben.
 */
if(isset($_SESSION['name']) && isset($_SESSION['userid'])) {
	require_once 'actions.php';
	if(isset($data['messages'])) {
		$errorcode = insertmessages($data, $_SESSION['userid']);
		$data_answer['error'] = $errorcode;
	}
	/* Wenn $data['last'] nicht gesetzt ist, werden alle Nachrichten aus der DB geholt */
	if(!isset($data['last'])) {
		$data['last'] = 0;
	}
	$data_answer['messages'] = checkNewMessages($data['last']);
}

/**
 * User die sich neu eingeloggt bzw. ausgeloggt haben in $data['user'] speichern
 */
require_once 'actions.php';
$data_answer['user']['login']  = getNewUsers($data['last']);
$data_answer['user']['logout'] = getOldUsers($data['last']);


/* Antwort an XXX.php/js zur�ckschicken */
echo json_encode($data_answer); 
?>