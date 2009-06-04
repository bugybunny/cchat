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
/* header('Content-type: text/json; charset=utf-8'); */
mysql_connect($mysql_server, $mysql_login, $mysql_pass);
mysql_select_db($mysql_db);

/* Variablen */
$data = json_decode($_POST['data'], true);
$data_answer = array();
$errorcode = 000;

/* Login überprüfen und Errorcode setzen */
if(isset($data['login'])) {
	require 'login.php';
	$errorcode = login($data['login']['name'], $data['login']['password']);
	if($errorcode != 000) {
		$data_answer['logedout'] = true;
		$data_answer['error'] = $errorcode;
	}
}

/* Registrierung */
if(isset($data['register'])) {
	require 'register.php';
	$data_answer['error'] = register($data['register']['name'], $data['register']['password'], $data['register']['email']);
}

/*
 * Nachrichten:
 * Immer wenn eine Anfrage kommt und der User eingeloggt ist, werden die neuen Nachrichten in der Datenbank seit der letzten
 * Abfrage zurückgeschickt.
 * Wenn zusätzlich neue Nachrichten vom User geschrieben wurden, werden sie in die Datenbank geschrieben.
 */
if(isset($_SESSION['name']) && isset($_SESSION['userid'])) {
	require 'messages.php';
	if(isset($data['messages'])) {
		$errorcode = insertmessages($data);
		$data_answer['error'] = $errorcode;
	}
	// TODO Parameter anpassen, sobald er bei $data mitgesendet wird
	$data_answer['messages'] = checkNewMessages(1);
}

/* Antwort an index.php zurückschicken */
echo json_encode($data_answer);
?>