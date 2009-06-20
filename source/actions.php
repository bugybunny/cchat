<?php
define('CODE_MESSAGE', 10);
define('CODE_LOGIN', 20);
define('CODE_LOGOUT', 30);

/**
 * Speichert die Nachrichten in der Datenbank
 *
 * @param	array	$data		Enthält die neu geschriebenen Nachrichten, die in die Datenbank geschrieben werden
 * @param	int		$userid		Userid des Users, der die Aktion ausgelöst hat
 * @return	int					Errorcode: Beschreibung der Codes unter http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function insertmessages($data, $userid) {
	if(isset($userid)) {
		foreach($data['messages'] as $message) {
			$message = mysql_real_escape_string($message);
			mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_MESSAGE.", '{$message}', {$userid}, ".floor(microtime(true) / 1000).")");
		}
	}
	/* Aktuell ist kein User eingeloggt. Deshalb können keine Nachrichten verschickt werden */
	else {
		return 101;
	}
}

/**
 * Prüft ob seit der letzten Anfrage eine neue Nachricht / neue Nachrichten geschrieben wurde
 *
 * @param	long		$time	Zeitpunkt der letzten Anfrage in Millisekunden
 * @return	Array[][]	$newmessages
 * 			Neue Nachrichten mit Sender, Nachrichtentext und Zeit der Nachricht.
 * 			Die Arraystruktur ist unter Answer beschrieben: http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function checkNewMessages($time) {
	$newMessages = array();
	$result_message = mysql_query("SELECT u.name, a.text, a.time FROM action a, user u WHERE a.typ = ".CODE_MESSAGE." AND a.time > {$time} AND a.userid = u.id");
	while($action = mysql_fetch_assoc($result_message)) {
		$message['sender']  = $action['name'];
		$message['message'] = $action['text'];
		$message['time'] 	= $action['time'];
		$newMessages[] 		= $message;
	}
	return $newMessages;
}

/**
 * Fügt einen neuen Actiondatensatz des Typs login in die Datenbank ein
 *
 * @param int		$userid 	Userid des Users, der sich eingeloggt hat
 * @param string	$username 	Name des Users, der sich eingeloggt hat
 */
function insertLogin($userid, $username) {
	$text = "User {$username} hat sich eingeloggt";
	mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_LOGIN.", '{$text}', {$userid}, ".floor(microtime(true) / 1000).")");
}

/**
 * Fügt einen neuen Actiondatensatz des Typs logout in die Datenbank ein
 *
 * @param int		$userid 	Userid des Users, der sich ausgeloggt hat
 * @param string	$username 	Name des Users, der sich ausgeloggt hat
 */
function insertLogout($userid, $username) {
	$text = "User {$username} hat sich ausgeloggt";
	mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_LOGOUT.", '{$text}', {$userid}, ".floor(microtime(true) / 1000).")");
	$data_answer['message']['text'] =  mysql_error();
}

/**
 * Gibt ein Array mit Usernamen zurück, die sich seit $time eingeloggt haben
 *
 * @param	long	$time		Zeitpunkt der letzten Anfrage in Millisekunden
 * @return  array	$newUsers	Array mit den neu eingeloggten Usern
 */
function getNewUsers($time) {
	$newUsers = array();
	$result_login = mysql_query("SELECT u.name, a.text, a.time FROM action a, user u WHERE a.typ = ".CODE_LOGIN." AND a.time > {$time} AND a.userid = u.id");
	$data_answer['message']['text'] =  mysql_error();

	while($action = mysql_fetch_assoc($result_login)) {
		$newUsers[] = $action['name'];
	}
	return $newUsers;
}

/**
 * Gibt ein Array mit Usernamen zurück, die sich seit $time ausgeloggt haben
 *
 * @param	long	$time		Zeitpunkt der letzten Anfrage in Millisekunden
 * @return  array	$oldUsers	Array mit den neu ausgeloggten Usern
 */
function getOldUsers($time) {
	$oldUsers = array();
	$result_logout = mysql_query("SELECT u.name, a.text, a.time FROM action a, user u WHERE a.typ = ".CODE_LOGOUT." AND a.time > {$time} AND a.userid = u.id");
	$data_answer['message']['text'] =  mysql_error();
	while($action = mysql_fetch_assoc($result_logout)) {
		$oldUsers[] = $action['name'];
	}
	return $oldUsers;
}