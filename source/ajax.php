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
// |               Diese Datei macht Folgendes:													  |
// |                 - Die neuen Nachrichten werden in die Datenbank geschrieben				  |
// |                 - Login überprüfen (Name und Passwort) und den Userstatus auf eingeloggt     |
// |				 - Neuen user Datensatz (Account) in der Datenbank anlegen, sofern der        |
// |                   Username noch nicht vorhanden ist 										  |
// |				 - 	                                    	  								  |
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
header('Content-type: text/json; charset=utf-8');

/* Datenbankverbindung herstellen */
mysql_connect($mysql_server, $mysql_login, $mysql_pass);
mysql_select_db($mysql_db);
/*
 * Variablendeklaration
 */
$data = json_decode($_POST['data'], true);
/* Array, welches zurückgeschick wird an script.js */
$data_answer = array();
$errorcode = 000;

/* Lastrefresh bei User aktualisieren */
if(isset($_SESSION['name']) && isset($_SESSION['userid'])) {
	mysql_query("UPDATE user SET lastrefresh = now() WHERE user.id = {$_SESSION['userid']}");
	
}

/* Registrierung */
if(isset($data['register'])) {
	require 'register.php';
	$data_answer['error'] = register($data['register']['name'], $data['register']['password'], $data['register']['email']);
}

/* Login:
 * Überprüfen und Errorcode setzen
 *
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
 * Immer wenn eine Anfrage kommt und der User eingeloggt ist, werden die neuen Nachrichten in der Datenbank seit der letzten
 * Abfrage zurückgeschickt.
 * Wenn zusätzlich neue Nachrichten vom User geschrieben wurden, werden sie in die Datenbank geschrieben.
 */
if(isset($_SESSION['name']) && isset($_SESSION['userid'])) {
	require_once 'actions.php';
	if(isset($data['messages']) && isset($_SESSION['userid'])) {
		$errorcode = insertmessages($data, $_SESSION['userid']);
		$data_answer['error'] = $errorcode;
	}
	if(!isset($data['last'])) {
		$data['last'] = 0;
	}
	$data_answer['messages'] = checkNewMessages($data['last']);
}

/* Antwort an index.php zurückschicken */
echo json_encode($data_answer); 

?>