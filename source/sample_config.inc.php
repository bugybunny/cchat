<?php
	// Datenbank
	define('MYSQL_SERVER', "localhost");
	define('MYSQL_LOGIN', "root");
	define('MYSQL_PASS', "");
	define('MYSQL_DB', "cchat");
	define('DB_PREFIX', "cchat_");
	
	// Zeitzone
	define('TIMEZONE', "Europe/Zurich");
	
	// Fehler-Logging
	define('DISPLAY_ERROR_MESSAGES', false);
	# loggt alle Fehler, Warnungen und Anmerkungen:
	define('ERROR_LOGGING', E_ALL | E_STRICT);
	# loggt nur Fatale Fehler:
	#define('ERROR_LOGGING', E_ERROR);
?>