<?php
/**
 * Loggt den User mit $userid aus und zerstört die Session.
 *
 * @param	int	$userid	Userid des auszuloggenden Users
 */
function logoutUser($userid, $username) {
	if(isset($userid) && isset($username)) {
		require_once 'actions.php';
		mysql_query("UPDATE user SET logedin = false WHERE id = {$userid}");
		$data_answer['message']['text'] =  mysql_error();
		insertLogout($userid, $username);
		unset($_SESSION['userid'], $_SESSION['name']);
	}
}

/**
 * Loggt alle nicht erreichbaren User automatisch ab $maxTime Sekunden aus.
 * Nicht erreichbare User bezeichnet User, die weder lesen noch schreiben und z.B. den Browser geschlossen haben
 * 
 * @param	int	$maxTime	Anzahl Sekunden, ab wann der User ausgeloggt werden soll
 */
function checkForLogout($maxTime) {
	date_default_timezone_set("Europe/Zurich");
	$now = date("Y-m-d H:i:s", time() - $maxTime);

	/*
	 * Da immer noch ein Logoutnachricht geschrieben werden muss, wenn sich ein User ausloggt, werden zuerst alle betroffenen user Datensätze geholt und nicht gleich mit UPDATE angepsasst.
	 */
	$usersToLogout = mysql_query("SELECT id, name FROM user WHERE logedin = TRUE AND lastrefresh < '{$now}'");
	while($user = mysql_fetch_assoc($usersToLogout)) {
		logoutUser($user['id'], $user['name']);
	}
}