<?php
	define('MYSQL_SERVER', "localhost");
	define('MYSQL_LOGIN', "root");
	define('MYSQL_PASS', "gibbiX12345");
	define('MYSQL_DB', "cchat");
	define('DB_PREFIX', "cchat_");
	
	
	define('CCHAT_VERSION', "1.0 dev");
	
	// Werte für das Feld action.typ
	define('CODE_MESSAGE', 10);
	define('CODE_LOGIN', 20);
	define('CODE_LOGOUT', 30);
	define('CODE_ERROR', 40);
	
	// Geloggte Fehlermeldungen im Chat anzeigen
	define('DISPLAY_ERROR_MESSAGES', true);
	// loggt alle Fehler, Warnungen und Anmerkungen
	define('ERROR_LOGGING', E_ALL | E_STRICT);
	// loggt nur Fatale Fehler
	#define('ERROR_LOGGING', E_ERROR);
	
	define('TIMEZONE', "Europe/Zurich");