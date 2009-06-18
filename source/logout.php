<?php
/**
 * Loggt den User mit $userid aus und zerstört die Session.
 *
 * @param	int	$userid	Userid des auszuloggenden Users
 */
function logoutUser($userid) {
	if(isset($userid)) {
		mysql_query("UPDATE user SET logedin = false WHERE id = {$userid}");
		session_destroy();
	}
}

/**
 * Loggt alle nicht erreichbaren User automatisch ab 30 Sekunden aus.
 * Nicht erreichbare User bezeichnet User, die weder lesen noch schreiben und z.B. den Browser geschlossen haben
 *
 */
function checkForLogout() {
	date_default_timezone_set("Europe/Zurich"); 
	$now = date("Y-m-d H:i:s", time() - 30);
	mysql_query("UPDATE user SET logedin = false WHERE logedin = TRUE AND lastrefresh < '{$now}'");
	echo mysql_error();
}