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
			mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_MESSAGE.", '{$message}', {$userid}, ".floor(microtime(true) * 1000).")");
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
 * @return	Array[][]
 * 			Neue Nachrichten mit Sender, Nachrichtentext und Zeit der Nachricht.
 * 			Die Arraystruktur ist unter Answer beschrieben: http://code.google.com/p/cchat/wiki/Datenaustausch
 */
function checkNewMessages($time) {
	$newMessages = array();
	$result_message = mysql_query("SELECT u.name, a.text, a.time FROM action a, user u WHERE a.time > {$time} AND a.userid = u.id ORDER BY a.time LIMIT 30");
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
	mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_LOGIN.", '{$text}', {$userid}, ".floor(microtime(true) * 1000).")");
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
	$data_answer['message']['text'] =  mysql_error();
}

/**
 * Gibt zweidimensionales Array zurück. Eines mit Usernamen, die sich seit $time eingeloggt haben, und eines mit Usernamen, die sich seither ausgeloggt haben.
 *
 * @param	long	$time		Zeitpunkt der letzten Anfrage in Millisekunden
 * @return  array				Array mit den neu ein- und ausgeloggten Usern
 */
function getUsersLogin($time) {
	$users = array();
	$users["login"] = array();
	$users["logout"] = array();
	if($time == 0) {
		$query = mysql_query("SELECT name FROM user WHERE logedin = 1");
		echo mysql_error();
		while($action = mysql_fetch_assoc($query)) {
			$users["login"] = $action['name'];
		}
		return $users;
	} else {
		$query = mysql_query("SELECT u.name, a.typ FROM action a, user u WHERE (a.typ = ".CODE_LOGIN." OR a.typ = ".CODE_LOGOUT.") AND a.time > {$time} AND a.userid = u.id ORDER BY a.time");
		echo mysql_error();
		while($action = mysql_fetch_assoc($query)) {
			$user = $action["name"];
			if($action["type"] == CODE_LOGIN) {
				$position = array_search($name, $users["logout"]);
				if($position !== false) {
					unset($users["logout"][$position]);
				}
				$users["login"][] = $user;
			} else {
				$position = array_search($name, $users["login"]);
				if($position !== false) {
					unset($users["login"][$position]);
				}
				$users["logout"][] = $user;
			}
		}
		return $users;
	}
}