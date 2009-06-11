<?php
define('CODE_MESSAGE', 10);
define('CODE_LOGIN', 20);
define('CODE_LOGOUT', 30);
/*
$codes['message'] = 10;
$codes['login']   = 20;
$codes['logout']  = 30; */

/**
 * Speichert die Nachrichten in der Datenbank.
 * @param array	 $data
 */
function insertmessages($data, $userid) {
	if(isset($userid)) {
		foreach($data['messages'] as $message) {
			$message = mysql_real_escape_string($message);
			mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_MESSAGE.", '{$message}', {$userid}, ".floor(microtime(true) * 1000).")");
			echo mysql_error();
		}
	}
	/* Aktuell ist kein User eingeloggt. Deshalb können keine Nachrichten verschickt werden */
	else {
		return 101;
	}
}

/**
 * Prüft ob seit der letzten Anfrage eine neue Nachricht / neue Nachrichten geschrieben wurde
 * @return Array[][] $newmessages
 * 			Neue Nachrichten mit Sender, Nachrichtentext und Zeit der Nachricht.
 * 			Die Arraystruktur ist unter Answer beschrieben: http://code.google.com/p/cchat/wiki/Datenaustausch
 *
 */
function checkNewMessages($time) {
	$result = array();
	$result_message = mysql_query("SELECT u.name, a.text, a.time FROM action a, user u WHERE a.typ = ".CODE_MESSAGE." AND a.time > {$time} AND a.userid = u.id");
	echo mysql_error();
	
	while($action = mysql_fetch_assoc($result_message)) {
		$message['sender'] = $action['name'];
		$message['message'] = $action['text'];
		$message['time'] = $action['time'];
		$result[] = $message;
	}
	return $result;
}

/**
 * Fügt einen neuen Actiondatensatz des Typs login in die Datenbank ein
 *
 * @param int		$userid 	Userid des Users, der sich eingeloggt hat
 * @param string	$username 	Name des Users, der sich eingeloggt hat
 */
function insertLogin($userid, $username) {
	$text = "User {$username} hat sich eingeloggt";
	$string = "INSERT INTO action (typ, text, userid, time) VALUES (".CODE_LOGIN.", '{$text}', {$userid}, ".floor(microtime(true) * 1000).")";
	mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_LOGIN.", '{$text}', {$userid}, ".floor(microtime(true) * 1000).")");
	echo mysql_error();
}

/**
 * Fügt einen neuen Actiondatensatz des Typs logout in die Datenbank ein
 *
 * @param int		$userid 	Userid des Users, der sich ausgeloggt hat
 * @param string	$username 	Name des Users, der sich ausgeloggt hat
 */
function insertLogout($userid, $username) {
	$text = "User {$username} hat sich ausgeloggt";
	mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_LOGOUT.", '{$text}', {$userid}, ".floor(microtime(true) * 1000).")");
	echo mysql_error();
}