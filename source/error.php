<?php
/**
 * Verwaltung von Fehlermeldungen (Errorhandling)
 *
 * @author Marco Syfrig
 */

/*
 * Setzt die Funktion, die aufgerufen wird, wenn ein Fehler auftritt
 * 
 * Um die Fehlermeldungen wieder standardmässig per echo auszugeben, muss der Funktionsaufruf set_error_handler auskommentiert werden
 * Wenn Fehler auftreten und set_error_handler("errorHandler") auskommentiert wird, funktioniert der Chat nicht mehr, da xhr.class.js ein JSON dekodiertes Array erwartet
 * und dieses Array per echo zurückgegeben wird. Fehlermeldungen die per echo ausgegeben werden "zerstören" die Struktur des Arrays und machen es unleserlich, sodass
 * neue Nachrichten nicht mehr angezeigt werden können
 */
set_error_handler("errorHandler");

/**
 * Fügt einen neuen Actiondatensatz des Typs error in die Datenbank ein
 * Wird nur eingefügt, wenn $errstr nicht leer ist (z.B. wird ein leerer mysql_error() nicht eingefügt)
 *
 * @param int		$errno 		Fehlernummer
 * @param string	$errstr		Text der Fehlermeldung
 * @param string	$errfile	Filename wo der Fehler auftrat
 * @param int		$errline	Zeilennummer wo der Fehler auftrat
 * @return boolean				TRUE wenn die Fehlermeldung eingefügt wurde, ansonsten FALSE
 */
function errorHandler($errno, $errstr, $errfile, $errline) {
	if(!empty($errstr)) {
		if($errno & ERROR_LOGGING) {
			$errormessage = "Fehler $errno: '$errstr' in $errfile auf Zeile $errline";
			$errormessage = mysql_real_escape_string($errormessage);
			/* Userid 1 = User "System", der die Ein- und Auslogg- und Fehlernachrichten ausgibt */
			mysql_query("INSERT INTO action (typ, text, userid, time) VALUES (".CODE_ERROR.", '{$errormessage}', 1, ".floor(microtime(true) * 1000).")");
			echo mysql_error();
			return true;
		}
		return true;
	}
}