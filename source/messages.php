<?php
/**
 * Speichert die Nachrichten in der Datenbank.
 * @param string	$data
 */
function message($data) {
	$codes["message"] = 10;
	$codes["login"]   = 20;
	$codes["logout"]  = 30;
	
	if(isset($_SESSION['userid'])) {
		foreach($data['messages'] as $message) {
			$message = mysql_real_escape_string($message);
			mysql_query("INSERT INTO action (typ, text, userid, time) VALUES ('{$codes['message']}', '$message', {$_SESSION['userid']}, ".floor(microtime(true) * 1000).")");
			echo mysql_error();
			
			// TODO Zeitabfrage anpassen, sobald bei $data eine Time mitgesendet wird 
			$result_message = mysql_query("SELECT name FROM action WHERE typ = '{$codes['message']}' AND time >= 1");
			if(!mysql_num_rows($result_message)) {
				$action = mysql_fetch_assoc($result_message);
				
				/*
				 * Da in action nur die UserID gespeichert ist, muss der Username über die Tabelle user geholt werden
				 * um den Sendernamen in $data_answer['messages][x]['sender'] zu setzen
				 */
				$result_user = mysql_query("SELECT name FROM user WHERE id = {$action['userid']}");
				// TODO $data_answer['messages'] setzen. Nachrichten, Login und Logout setzen
				if(!mysql_num_rows($result_user)) {
					
				}
				
			}
		}
	}
	/* Aktuell ist kein User eingeloggt. Deshalb können keine Nachrichten verschickt werden */
	else {
		echo "Bitte einloggen!";
	}
}
?>