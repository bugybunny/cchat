<?php
/**
 * Speichert die Nachrichten in der Datenbank.
 * @param string	$data
 */
function insertmessages($data) {
	$codes["message"] = 10;
	$codes["login"]   = 20;
	$codes["logout"]  = 30;

	if(isset($_SESSION['userid'])) {
		foreach($data['messages'] as $message) {
			$message = mysql_real_escape_string($message);
			mysql_query("INSERT INTO action (typ, text, userid, time) VALUES ('{$codes['message']}', '$message', {$_SESSION['userid']}, ".floor(microtime(true) * 1000).")");
			echo mysql_error();
		}
	}
	/* Aktuell ist kein User eingeloggt. Deshalb können keine Nachrichten verschickt werden */
	else {
		echo "Bitte einloggen!";
	}
}

/**
 * Prüft ob seit der letzten Anfrage eine neue Nachricht / neue Nachrichten geschrieben wurde
 * @return Array[][] $newmessages
 * 			Neue Nachrichten mit Sender, Nachrichtentext und Zeit der Nachricht.
 * 			Die Arraystruktur ist unter Answer beschrieben: http://code.google.com/p/cchat/wiki/Datenaustausch
 *
 */
function checkNewMessages() {
	// TODO Zeitabfrage anpassen, sobald bei $data eine Time mitgesendet wird
	$result_message = mysql_query("SELECT u.name, a.text FROM action a, user u WHERE a.typ = {$codes['message']} AND a.time >= 1 AND a.id = u.id");
	echo mysql_error();
	if(!mysql_num_rows($result_message)) {
		// TODO Login und Logout setzen
		$action = mysql_fetch_assoc($result_message);
		foreach($action as $row) {
			$result[]['sender'] = $row['name'];
			$result[]['message'] = $row['text'];
			$result[]['time'] = floor(microtime() / 1000);
		}
		return $result;
	}

}

?>