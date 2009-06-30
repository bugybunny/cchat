<?php
/**
 * Ausloggen von User
 * 
 * @author Marco Syfrig
 */

/**
 * Loggt den User mit $userid aus und zerstört die Session.
 *
 * @param	int		$userid					Userid des auszuloggenden Users
 * @param	string	$username 				Username des auszuloggenden Users
 * @param	boolean	$deleteSessionVariables	TRUE wenn die Session Variablen gelöscht werden sollen, ansonsten false
 */
function logoutUser($userid, $username, $deleteSessionVariables) {
	if(isset($userid) && isset($username)) {
		mysql_query("UPDATE ".DB_PREFIX."user SET logedin = false WHERE id = {$userid}") or trigger_error(mysql_error(), E_USER_ERROR);
		insertLogout($username, $userid);
		
		if($deleteSessionVariables) {
			unset($_SESSION['userid'], $_SESSION['name']);
		}
	}
}

/**
 * Loggt alle nicht erreichbaren User automatisch ab $maxTime Sekunden aus.
 * Nicht erreichbare User bezeichnet User, die weder lesen noch schreiben und z.B. den Browser geschlossen haben
 *
 * @param	int	$maxTime	Anzahl Sekunden, ab wann der User ausgeloggt werden soll
 */
function checkForLogout($maxTime) {	
	date_default_timezone_set(TIMEZONE);
	$now = date("Y-m-d H:i:s", time() - $maxTime);
	
	/*
	 * Da immer noch eine Logoutnachricht geschrieben werden muss, wenn sich ein User ausloggt, werden zuerst alle betroffenen user Datensätze geholt und nicht gleich mit UPDATE angepasst.
	 */
	$usersToLogout = mysql_query("SELECT id, name FROM ".DB_PREFIX."user WHERE logedin = TRUE AND lastrefresh < '{$now}'") or trigger_error(mysql_error(), E_USER_ERROR);
	while($user = mysql_fetch_assoc($usersToLogout)) {
		logoutUser($user['id'], $user['name'], false);
	}
}